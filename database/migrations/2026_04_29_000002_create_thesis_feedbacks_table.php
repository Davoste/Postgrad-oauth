<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('thesis_feedbacks')) {
            Schema::create('thesis_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('thesis_id')->constrained('theses')->cascadeOnDelete();
                $table->foreignId('submission_id')->nullable()->constrained('thesis_submissions')->nullOnDelete();
                $table->foreignId('reviewer_id')->constrained('users'); // supervisor, panel member, admin
                $table->string('stage'); // concept_development, proposal_development, etc.
                $table->text('comments');
                $table->string('decision')->nullable(); // approved, rejected, revision_required
                $table->json('action_items')->nullable(); // List of required actions
                $table->date('deadline')->nullable(); // For revisions
                $table->boolean('is_final')->default(false); // Whether this is the final feedback for the stage
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('thesis_feedbacks');
    }
};