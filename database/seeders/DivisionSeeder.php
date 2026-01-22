<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $divisions = [
            [
                'name' => 'Health Services Division',
                'abbreviation' => 'HSD',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Administrative Division',
                'abbreviation' => 'AD',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Finance Division',
                'abbreviation' => 'FD',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Planning and Development Division',
                'abbreviation' => 'PDD',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Human Resource Division',
                'abbreviation' => 'HRD',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Division::insert($divisions);
    }
}
