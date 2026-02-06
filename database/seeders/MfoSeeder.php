<?php

namespace Database\Seeders;

use App\Models\Mfo;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $mfos = [
            [
                'code' => 'MFO 1',
                'description' => 'Provision of Technical Assistance (TA)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'MFO 2',
                'description' => 'Monitoring and Evaluation of Health Programs',
                'created_at' => $now,
                'updated_at' => $now,
            ],


        ];

        Mfo::insert($mfos);
    }
}
