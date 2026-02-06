<?php

namespace Database\Seeders;

use App\Models\OfficeType;
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
        $officeType = OfficeType::where('name', 'Office')->first();
        DB::table('offices')->insert([
            [
                'abbreviation' => 'PHO',
                'name' => 'Provincial Health Office',
                'office_type_id' => $officeType->id,
                'created_at' => $now,
                'updated_at' => $now

            ],
        ]);
    }
}
