<?php

namespace Database\Seeders;

use App\Models\OfficeType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OfficeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $officeTypes = [
            ['name' => 'Office', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'RHU', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hospital', 'created_at' => $now, 'updated_at' => $now],
        ];

        OfficeType::insert($officeTypes);
    }
}
