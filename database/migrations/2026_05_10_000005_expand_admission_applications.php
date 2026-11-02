<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Expand admission_applications to capture the full official ADM-AF/01A form
 * (Sections A–C) plus supporting document uploads.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admission_applications', function (Blueprint $table) {
            // Section A
            $table->text('permanent_address')->nullable()->after('postal_address');

            // Section B — first degree detail
            $table->string('first_degree_field')->nullable()->after('first_degree_university');
            $table->string('first_degree_dates')->nullable()->after('first_degree_field');
            // Section B — second degree (optional)
            $table->string('second_degree_university')->nullable()->after('first_degree_year');
            $table->string('second_degree_field')->nullable()->after('second_degree_university');
            $table->string('second_degree_dates')->nullable()->after('second_degree_field');
            $table->string('second_degree_award')->nullable()->after('second_degree_dates');
            $table->year('second_degree_year')->nullable()->after('second_degree_award');
            // Section B — other background
            $table->text('other_qualifications')->nullable()->after('second_degree_year');
            $table->json('employment')->nullable()->after('research_experience');
            $table->string('languages')->nullable()->after('employment');

            // Section C — study details
            $table->string('field_of_study')->nullable()->after('programme_id');
            $table->string('research_institution')->nullable()->after('campus');
            $table->date('commencement_date')->nullable()->after('research_institution');
            $table->date('completion_date')->nullable()->after('commencement_date');
            $table->string('application_no')->nullable()->after('completion_date');

            // Supporting documents (stored paths)
            $table->string('passport_photo_path')->nullable();
            $table->string('application_receipt_path')->nullable();
            $table->string('academic_certificate_path')->nullable();
            $table->string('transcript_path')->nullable();
            $table->string('professional_certificate_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('admission_applications', function (Blueprint $table) {
            $table->dropColumn([
                'permanent_address',
                'first_degree_field', 'first_degree_dates',
                'second_degree_university', 'second_degree_field', 'second_degree_dates',
                'second_degree_award', 'second_degree_year',
                'other_qualifications', 'employment', 'languages',
                'field_of_study', 'research_institution', 'commencement_date', 'completion_date', 'application_no',
                'passport_photo_path', 'application_receipt_path', 'academic_certificate_path',
                'transcript_path', 'professional_certificate_path',
            ]);
        });
    }
};
