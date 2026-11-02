<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixSupervisorIdNullableInThesesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE theses MODIFY supervisor_id BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE theses MODIFY supervisor_id BIGINT UNSIGNED NOT NULL');
    }
}
