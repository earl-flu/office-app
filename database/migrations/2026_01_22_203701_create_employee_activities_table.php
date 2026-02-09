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
        Schema::create('employee_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained('employees');
            $table->foreignId('assigned_by_id')
                ->constrained('employees')
                ->comment('task from this employee');
            $table->date('activity_date')->comment('Date when the activity happened');
            $table->foreignId('activity_type_id')->constrained('activity_types');
            $table->foreignId('mfo_id')
                ->constrained('mfos');
            $table->text('description');
            $table->foreignId('activity_status_id')->constrained('activity_statuses');
            $table->text('remarks')->nullable();
            $table->integer('time_spent_minutes')->nullable()->comment('Time spent on activity in minutes');
            $table->timestamps();
        });
    }

    /**F
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_activities');
    }
};
