<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $programs = [
            [
                'name' => 'Maternal and Child Health Program',
                'abbreviation' => 'MCHP',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Family Planning Program',
                'abbreviation' => 'FPP',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Tuberculosis Control Program',
                'abbreviation' => 'TCP',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Immunization Program',
                'abbreviation' => 'IP',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Environmental Health Program',
                'abbreviation' => 'EHP',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Nutrition Program',
                'abbreviation' => 'NP',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Health Promotion Program',
                'abbreviation' => 'HPP',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Program::insert($programs);
    }
}
