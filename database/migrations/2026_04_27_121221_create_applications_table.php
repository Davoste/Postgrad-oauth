<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('program');
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->decimal('reviewer_score', 3, 2)->nullable();
            $table->json('documents')->nullable(); // file paths/IDs
            $table->text('decision_notes')->nullable();
            $table->timestamps();
        });
    }
};