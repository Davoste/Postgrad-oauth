<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_referees', function (Blueprint $table) {
            $table->text('known_for')->nullable()->after('email');
            $table->json('ratings')->nullable()->after('known_for');
            $table->string('designation')->nullable()->after('ratings');
            $table->text('referee_address')->nullable()->after('designation');
            $table->string('referee_contact')->nullable()->after('referee_address');
        });
    }

    public function down(): void
    {
        Schema::table('application_referees', function (Blueprint $table) {
            $table->dropColumn([
                'known_for',
                'ratings',
                'designation',
                'referee_address',
                'referee_contact',
            ]);
        });
    }
};