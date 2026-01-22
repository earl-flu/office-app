<?php

namespace Database\Seeders;

use App\Models\FacilityType;
use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $officeType = FacilityType::where('name', 'Office')->first();
        DB::table('offices')->insert([
            [
                'abbreviation' => 'PHO',
                'name' => 'Provincial Health Office',
                'facility_type_id' => $officeType->id,
                'created_at' => $now,
                'updated_at' => $now

            ],
        ]);
    }
}
