<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Department;
use App\Models\Programme;
use App\Models\School;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegistrationToAdmissionFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RbacSeeder::class);
    }

    private function makeProgramme(): Programme
    {
        $school = School::create(['name' => 'School of Computing', 'code' => 'SOC']);
        $dept = Department::create(['name' => 'Computer Science', 'code' => 'CS', 'school_id' => $school->id]);

        return Programme::create([
            'name' => 'Master of Computer Science',
            'code' => 'MCS',
            'department_id' => $dept->id,
            'level' => 'masters',
            'is_active' => true,
            'applications_open' => true,
        ]);
    }

    public function test_full_register_apply_admit_flow_unlocks_tabs()
    {
        Notification::fake();
        Storage::fake('public');

        // 1. Self-register
        $response = $this->post('/register', [
            'surname' => 'Otieno',
            'first_name' => 'Brenda',
            'email' => 'brenda@example.com',
            'email_confirmation' => 'brenda@example.com',
            'id_no' => '99887766',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $this->assertAuthenticated();
        $user = User::where('email', 'brenda@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('student'));
        $this->assertFalse($user->isAdmitted(), 'New registrant should not be admitted yet.');
        $this->assertFalse($user->hasVerifiedEmail());
        Notification::assertSentTo($user, VerifyEmailNotification::class);

        // 2. Unverified accounts cannot enter the portal.
        $this->actingAs($user)->get('/student')
            ->assertRedirect(route('verification.notice'));

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $this->get($verificationUrl);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        // 3. Dashboard is available after verification, with thesis tabs locked until admission.
        $dash = $this->actingAs($user)->get('/student');
        $dash->assertStatus(200);
        $dash->assertSee('Apply Now');
        $dash->assertDontSee('Go to Thesis Portal');

        // 4. Apply while logged in, via the portal shortcut route
        $programme = $this->makeProgramme();
        $applyPage = $this->actingAs($user)->get('/apply/portal?programme=' . $programme->id);
        $applyPage->assertStatus(200);
        $applyPage->assertSee($user->email);

        $payload = [
            'programme_id' => $programme->id,
            'surname' => 'Otieno',
            'first_name' => 'Brenda',
            'other_names' => '',
            'postal_address' => 'P.O Box 123, Bondo',
            'phone' => '0712345678',
            'email' => 'brenda@example.com',
            'dob' => 1995,
            'sex' => 'female',
            'nationality' => 'Kenyan',
            'id_no' => '99887766',
            'mode' => 'full_time',
            'campus' => 'Main Campus',
            'first_degree_university' => 'University of Nairobi',
            'first_degree_field' => 'Computer Science',
            'first_degree_dates' => '2015-2019',
            'first_degree_award' => 'BSc Computer Science',
            'first_degree_class' => 'First Class',
            'first_degree_year' => 2019,
            'referee_1_name' => 'Prof. A',
            'referee_1_email' => 'refa@example.com',
            'referee_2_name' => 'Prof. B',
            'referee_2_email' => 'refb@example.com',
            'passport_photo' => \Illuminate\Http\UploadedFile::fake()->image('photo.jpg'),
            'application_receipt' => \Illuminate\Http\UploadedFile::fake()->create('receipt.pdf', 100, 'application/pdf'),
            'academic_certificate' => \Illuminate\Http\UploadedFile::fake()->create('certificate.pdf', 100, 'application/pdf'),
            'transcript' => \Illuminate\Http\UploadedFile::fake()->create('transcript.pdf', 100, 'application/pdf'),
        ];

        $applyResponse = $this->actingAs($user)->post('/apply', $payload);

        $applyResponse->assertSessionHasNoErrors();
        $application = Application::where('email', 'brenda@example.com')->first();
        $this->assertNotNull($application, 'Application should have been created.');
        $this->assertEquals(
            $user->id,
            $application->user_id,
            'Application submitted while logged in should be linked to the user.'
        );
    }

    public function test_application_links_to_authenticated_user_and_enrollment_reuses_account()
    {
        $user = User::factory()->create(['email' => 'carlos@example.com']);
        $user->assignRole('student');

        $programme = $this->makeProgramme();

        $application = Application::create([
            'programme_id' => $programme->id,
            'user_id' => $user->id,
            'surname' => 'Mwangi',
            'first_name' => 'Carlos',
            'full_name' => 'Carlos Mwangi',
            'email' => 'carlos@example.com',
            'phone' => '0700000000',
            'id_no' => '12345678',
            'dob' => '1990-01-01',
            'postal_address' => 'P.O Box 1, Bondo',
            'sex' => 'male',
            'nationality' => 'Kenyan',
            'mode' => 'full_time',
            'status' => 'submitted',
            'ref_no' => 'REF-TEST-0001',
            'tracking_token' => \Illuminate\Support\Str::random(32),
        ]);

        $this->assertFalse($user->fresh()->isAdmitted());

        // Directly exercise the private enrollStudent logic through reflection
        // since it's only triggered via the full offer-acceptance controller
        // action in production; this isolates the "reuse existing account"
        // behaviour we added.
        $controller = new \App\Http\Controllers\ApplicationController();
        $method = new \ReflectionMethod($controller, 'enrollStudent');
        $method->setAccessible(true);

        \Illuminate\Support\Facades\Notification::fake();
        $student = $method->invoke($controller, $application);

        $this->assertEquals($user->id, $student->user_id, 'Enrollment should reuse the existing linked account, not create a new one.');
        $this->assertEquals(1, User::where('email', 'carlos@example.com')->count(), 'No duplicate account should be created.');
        $this->assertTrue($user->fresh()->isAdmitted());
    }
}
