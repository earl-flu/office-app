<?php

namespace Database\Seeders;

use App\Models\ActivityStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivityStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $mfos = [
            [
                'description' => 'Pending',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'description' => 'In Progress',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'description' => 'Finished',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'description' => 'Cancelled',
                'created_at' => $now,
                'updated_at' => $now,
            ],


        ];

        ActivityStatus::insert($mfos);
    }
}
