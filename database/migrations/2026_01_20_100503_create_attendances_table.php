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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Reference to the intern
            $table->foreignId('intern_id')
                ->constrained()
                ->cascadeOnDelete();

            // Attendance status
            $table->enum('status', ['present', 'absent', 'half_day']);

            // Photo proof of attendance
            $table->string('photo')->nullable();
            $table->string('date')->nullable();


            // Exact submission timestamp
            $table->timestamps();

            // Prevent duplicate attendance per intern per day

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
