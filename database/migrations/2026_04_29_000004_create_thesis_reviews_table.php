<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('thesis_reviews')) {
            Schema::create('thesis_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('thesis_id')->constrained('theses')->cascadeOnDelete();
                $table->foreignId('submission_id')->nullable()->constrained('thesis_submissions')->nullOnDelete();
                $table->foreignId('reviewer_id')->constrained('users');
                $table->string('review_type'); // panel_review, supervisor_review, admin_review
                $table->string('stage'); // concept_development, proposal_development, etc.
                $table->integer('rating')->nullable(); // 1-5 scale
                $table->text('strengths')->nullable();
                $table->text('weaknesses')->nullable();
                $table->text('recommendations');
                $table->string('decision'); // approved, rejected, revision_required, conditional_approval
                $table->json('conditions')->nullable(); // For conditional approvals
                $table->boolean('requires_meeting')->default(false);
                $table->timestamp('reviewed_at');
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('thesis_reviews');
    }
};