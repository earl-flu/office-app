<?php

namespace Database\Seeders;

use App\Models\Mfo;
use App\Models\MfoCategory;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MfoCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $mfoCategories = [
            [
                'name' => 'OPERATIONS',
                'percentage' => 45,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'SUPPORT TO OPERATIONS',
                'percentage' => 40,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'GENERAL ADMINISTRATIVE AND AND SUPPOT SERVICES',
                'percentage' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],


        ];

        MfoCategory::insert($mfoCategories);
    }
}
