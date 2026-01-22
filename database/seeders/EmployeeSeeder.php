<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Division;
use App\Models\Program;
use App\Models\Facility;
use App\Models\Office;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Get sample divisions, programs, and facilities
        $division = Division::first();
        $program = Program::first();
        $office = Office::first();

        $employees = [
            [
                'employee_id' => 'IT0001',
                'first_name' => 'Earl John',
                'middle_name' => 'Budy',
                'last_name' => 'Sarmiento',
                'suffix' => null,
                'position' => 'Computer Programmer II',
                'salary_per_day' => null,
                'salary_grade' => 15,
                'salary_per_month' => null,
                'sex' => 'Male',
                'birthday' => '1995-11-09',
                'date_employed' => '2023-07-16',
                'division_id' => $division?->id,
                'program_id' => $program?->id,
                'office_id' => $office?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'EMP-002',
                'first_name' => 'Maria',
                'middle_name' => 'Santos',
                'last_name' => 'Garcia',
                'suffix' => null,
                'position' => 'Nurse',
                'salary_per_day' => 1200.00,
                'salary_grade' => 15,
                'salary_per_month' => 36000.00,
                'sex' => 'Female',
                'birthday' => '1990-08-20',
                'date_employed' => '2015-03-10',
                'division_id' => $division?->id,
                'program_id' => $program?->id,
                'office_id' => $office?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'EMP-003',
                'first_name' => 'Jose',
                'middle_name' => 'Rizal',
                'last_name' => 'Reyes',
                'suffix' => 'Jr.',
                'position' => 'Administrative Officer',
                'salary_per_day' => 1000.00,
                'salary_grade' => 12,
                'salary_per_month' => 30000.00,
                'sex' => 'Male',
                'birthday' => '1988-12-10',
                'date_employed' => '2012-06-01',
                'division_id' => $division?->id,
                'program_id' => $program?->id,
                'office_id' => $office?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'EMP-004',
                'first_name' => 'Ana',
                'middle_name' => 'Luna',
                'last_name' => 'Torres',
                'suffix' => null,
                'position' => 'Midwife',
                'salary_per_day' => 1100.00,
                'salary_grade' => 14,
                'salary_per_month' => 33000.00,
                'sex' => 'Female',
                'birthday' => '1992-03-25',
                'date_employed' => '2018-09-15',
                'division_id' => $division?->id,
                'program_id' => $program?->id,
                'office_id' => $office?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'EMP-005',
                'first_name' => 'Carlos',
                'middle_name' => null,
                'last_name' => 'Villanueva',
                'suffix' => null,
                'position' => 'Medical Technologist',
                'salary_per_day' => 1300.00,
                'salary_grade' => 16,
                'salary_per_month' => 39000.00,
                'sex' => 'Male',
                'birthday' => '1987-07-18',
                'date_employed' => '2013-11-20',
                'division_id' => $division?->id,
                'program_id' => $program?->id,
                'office_id' => $office?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Employee::insert($employees);
    }
}
