<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('reg_no')->unique();
            $table->string('program');
            $table->year('admission_year');
            $table->timestamps();
        });

        Schema::create('supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('department');
            $table->integer('max_capacity')->default(5);
            $table->timestamps();
        });
    }
};