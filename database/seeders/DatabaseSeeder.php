<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Roles & permissions first
        $this->call(RbacSeeder::class);

        // Demo users (creates admin/hod/dean/finance/... accounts + sample fees)
        $this->call(SampleUserSeeder::class);

        // Academic structure + programmes + a demo application (needs hod/dean users)
        $this->call(AcademicStructureSeeder::class);
    }
}
