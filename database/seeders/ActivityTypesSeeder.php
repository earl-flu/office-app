<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('activity_types')->insert([
            ['name' => 'Administrative', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IT Support Task', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Field Work', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Disaster Response', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Meeting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Programming', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Training & Development', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
