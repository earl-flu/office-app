<?php

namespace Database\Seeders;

use App\Models\FacilityType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FacilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $facilityTypes = [
            ['name' => 'Office', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'RHU', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hospital', 'created_at' => $now, 'updated_at' => $now],
        ];

        FacilityType::insert($facilityTypes);
    }
}
