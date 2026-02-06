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
                'name' => 'Health System Support Division',
                'abbreviation' => 'HSSD',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Health System Delivery Division',
                'abbreviation' => 'HSDD',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Division::insert($divisions);
    }
}
