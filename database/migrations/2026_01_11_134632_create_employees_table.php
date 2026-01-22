<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('position')->nullable();
            $table->decimal('salary_per_day', 10, 2)->nullable();
            $table->integer('salary_grade')->nullable();
            $table->decimal('salary_per_month', 10, 2)->nullable();
            $table->enum('sex', ['Male', 'Female', 'Other']);
            $table->date('birthday')->nullable();
            $table->date('date_employed')->nullable();

            $table->foreignId('division_id')->nullable()->constrained('divisions');
            $table->foreignId('program_id')->nullable()->constrained('programs');
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
