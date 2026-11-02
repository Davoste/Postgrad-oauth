<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_referees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admission_applications')->cascadeOnDelete();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->text('recommendation')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_referees');
    }
};