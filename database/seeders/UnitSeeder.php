<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $programs = [
            [
                'name' => 'Health Information Systems',
                'abbreviation' => 'HIS',
                'unit_head_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Supply Chain Management',
                'abbreviation' => 'SCM',
                'unit_head_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Unit::insert($programs);
    }
}
