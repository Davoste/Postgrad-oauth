<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 12)->unique();
            $table->foreignId('dean_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 12)->unique();
            $table->foreignId('hod_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 30)->unique();
            $table->enum('level', ['pgd', 'masters', 'phd'])->default('masters');
            $table->json('modes')->nullable();
            $table->unsignedTinyInteger('duration_years')->default(2);
            $table->decimal('tuition_per_year', 10, 2)->default(0);
            $table->text('overview')->nullable();
            $table->text('career_prospects')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('applications_open')->default(true);
            $table->timestamps();
        });

        Schema::create('programme_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->cascadeOnDelete();
            $table->string('requirement');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_requirements');
        Schema::dropIfExists('programmes');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('schools');
    }
};
