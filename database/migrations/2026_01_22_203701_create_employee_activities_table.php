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
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('activity_type_id')->constrained('activity_types')->onDelete('cascade');
            $table->text('description');
            $table->enum('status', ['pending', 'in_progress', 'finished', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->integer('time_spent_minutes')->nullable()->comment('Time spent on activity in minutes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_activities');
    }
};
