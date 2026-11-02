<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Applications — JOOUST/AA/BPS/ADM-AF/01A
        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no', 25)->unique();
            // Personal
            $table->string('surname');
            $table->string('first_name');
            $table->string('other_names')->nullable();
            $table->date('dob');
            $table->enum('sex', ['male', 'female']);
            $table->string('nationality')->default('Kenyan');
            $table->string('id_no')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->text('postal_address');
            $table->string('marital_status')->nullable();
            // Academic background
            $table->string('first_degree_university')->nullable();
            $table->string('first_degree_award')->nullable();
            $table->string('first_degree_class')->nullable();
            $table->year('first_degree_year')->nullable();
            $table->text('research_experience')->nullable();
            // Programme applied for
            $table->foreignId('programme_id')->constrained();
            $table->enum('mode', ['full_time', 'part_time', 'school_based', 'weekend_intensive']);
            $table->string('campus')->default('Main Campus (Bondo)');
            $table->string('finance_source')->nullable();
            $table->json('referees')->nullable();
            $table->json('documents')->nullable();
            // Review — Section D
            $table->enum('hod_decision', ['pending', 'recommended', 'rejected'])->default('pending');
            $table->text('hod_comment')->nullable();
            $table->foreignId('hod_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('hod_reviewed_at')->nullable();
            $table->enum('director_decision', ['pending', 'admitted', 'rejected'])->default('pending');
            $table->text('director_comment')->nullable();
            $table->foreignId('director_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('director_reviewed_at')->nullable();
            // Status machine
            $table->enum('status', [
                'submitted', 'under_review', 'offer_made', 'accepted', 'declined', 'rejected',
            ])->default('submitted');
            $table->string('tracking_token', 40)->unique();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });

        // Acceptance of offer — ADM-AF/01
        Schema::create('acceptance_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admission_applications')->cascadeOnDelete();
            $table->enum('decision', ['accept', 'decline']);
            $table->text('decline_reason')->nullable();
            $table->string('next_of_kin_name')->nullable();
            $table->string('next_of_kin_phone')->nullable();
            $table->string('county')->nullable();
            $table->timestamp('signed_at')->useCurrent();
            $table->timestamps();
        });

        // Link the base students table to admissions (additive, nullable)
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('application_id')->nullable()->after('user_id')->constrained('admission_applications')->nullOnDelete();
            $table->foreignId('programme_id')->nullable()->after('application_id')->constrained()->nullOnDelete();
            $table->enum('mode', ['full_time', 'part_time', 'school_based', 'weekend_intensive'])->default('full_time')->after('program');
            $table->enum('status', ['active', 'deferred', 'suspended', 'graduated', 'withdrawn'])->default('active')->after('mode');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('application_id');
            $table->dropConstrainedForeignId('programme_id');
            $table->dropColumn(['mode', 'status']);
        });
        Schema::dropIfExists('acceptance_forms');
        Schema::dropIfExists('admission_applications');
    }
};
