<?php

namespace Database\Seeders;

use App\Models\Suffix;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuffixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $suffixes = [
            [
                'name' => 'Jr',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sr',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'III',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'IV',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'V',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Suffix::insert($suffixes);
    }
}
