<?php

namespace Database\Seeders;

use App\Models\Sex;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $sexes = [
            [
                'description' => 'Female',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'description' => 'Male',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Sex::insert($sexes);
    }
}
