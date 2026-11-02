<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supervision_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supervisor_id')->constrained('supervisors')->cascadeOnDelete();
            $table->date('meeting_date');
            $table->text('agenda')->nullable();
            $table->text('minutes')->nullable();
            $table->boolean('signed_student')->default(false);
            $table->boolean('signed_supervisor')->default(false);
            $table->string('student_sign_ip')->nullable();
            $table->string('supervisor_sign_ip')->nullable();
            $table->timestamps();
        });
    }
};