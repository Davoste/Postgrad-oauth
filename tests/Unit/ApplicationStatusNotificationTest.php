<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Notifications\ApplicationStatusNotification;
use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
use PHPUnit\Framework\TestCase;

class ApplicationStatusNotificationTest extends TestCase
{
    public function test_enrolled_notification_includes_admission_letter_and_credentials(): void
    {
        $application = new class extends Application {
            public $first_name = 'Jane';
            public $surname = 'Doe';
            public $email = 'jane@example.com';
            public $ref_no = 'BPS/AF/2026/0001';
            public $tracking_token = 'token';
            public $programme;

            public function __construct()
            {
                $this->programme = (object) ['name' => 'MSc Computer Science'];
            }
        };

        $application->setRelation('programme', $application->programme);

        $container = new Container;
        $container->instance(UrlGenerator::class, new LaravelUrlGenerator(new RouteCollection, Request::create('/')));
        $container->alias(UrlGenerator::class, 'url');
        Container::setInstance($container);

        $notification = new ApplicationStatusNotification($application, 'enrolled', [
            'adm_no' => 'ADM-2026-001',
            'password' => 'TempPass123',
        ]);

        $mail = $notification->toMail(new class {
            public $email = 'jane@example.com';
        });

        $content = implode("\n", array_merge($mail->introLines, $mail->outroLines));

        $this->assertStringContainsString('Admission Letter', $content);
        $this->assertStringContainsString('ADM-2026-001', $content);
        $this->assertStringContainsString('TempPass123', $content);
    }
}
