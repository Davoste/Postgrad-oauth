<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('thesis_milestones')) {
            Schema::create('thesis_milestones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('thesis_id')->constrained('theses')->cascadeOnDelete();
                $table->string('stage'); // concept_development, proposal_development, etc.
                $table->string('milestone_name');
                $table->text('description')->nullable();
                $table->date('target_date');
                $table->date('completed_date')->nullable();
                $table->string('status')->default('pending'); // pending, in_progress, completed, overdue
                $table->decimal('progress_percentage', 5, 2)->default(0); // 0-100
                $table->json('checklist_items')->nullable(); // Array of checklist items
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('thesis_milestones');
    }
};