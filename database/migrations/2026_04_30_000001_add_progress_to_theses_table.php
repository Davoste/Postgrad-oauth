<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('theses', function (Blueprint $table) {
            if (!Schema::hasColumn('theses', 'progress_percentage')) {
                $table->decimal('progress_percentage', 5, 2)->default(0)->after('state');
            }
        });
    }

    public function down(): void {
        Schema::table('theses', function (Blueprint $table) {
            $table->dropColumn('progress_percentage');
        });
    }
};
