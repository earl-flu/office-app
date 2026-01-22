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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->text('task_description');
            $table->foreignId('assigned_by_employee_id')->nullable()->constrained('employees')->onDelete('set null'); // Employee who gave the order
            $table->foreignId('assigned_to_user_id')->constrained('users')->onDelete('cascade'); // User/Employee who performs the task
            $table->integer('time_spent_minutes')->nullable(); // Time spent in minutes
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
