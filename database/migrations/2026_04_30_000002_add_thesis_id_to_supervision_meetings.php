<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('supervision_meetings', function (Blueprint $table) {
            if (!Schema::hasColumn('supervision_meetings', 'thesis_id')) {
                $table->foreignId('thesis_id')->nullable()->after('id')
                      ->constrained('theses')->nullOnDelete();
            }
        });
    }

    public function down(): void {
        Schema::table('supervision_meetings', function (Blueprint $table) {
            $table->dropForeign(['thesis_id']);
            $table->dropColumn('thesis_id');
        });
    }
};
