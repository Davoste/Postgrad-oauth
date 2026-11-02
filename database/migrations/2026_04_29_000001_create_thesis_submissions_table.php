<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('thesis_submissions')) {
            Schema::create('thesis_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('thesis_id')->constrained('theses')->cascadeOnDelete();
                $table->string('stage'); // concept_development, proposal_development, etc.
                $table->string('submission_type'); // concept, proposal, ethics_form, thesis_document, etc.
                $table->text('title')->nullable();
                $table->longText('description')->nullable();
                $table->json('documents')->nullable(); // Store file paths/URLs
                $table->string('status')->default('submitted'); // submitted, under_review, approved, rejected, revision_required
                $table->timestamp('submitted_at');
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('thesis_submissions');
    }
};