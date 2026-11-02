<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')->constrained('theses')->cascadeOnDelete();
            $table->foreignId('supervisor_id')->constrained('supervisors');
            $table->enum('report_type', ['quarterly', 'biannual']);
            $table->date('period_start');
            $table->date('period_end');
            $table->longText('content');                          // Narrative progress summary
            $table->tinyInteger('student_progress_rating');       // 1–5 scale
            $table->text('challenges')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('student_acknowledged_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('progress_reports');
    }
};
