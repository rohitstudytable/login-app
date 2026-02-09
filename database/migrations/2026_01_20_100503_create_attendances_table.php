<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {

            // Primary key
            $table->id();

            // Intern reference
            $table->unsignedBigInteger('intern_id');

            // Attendance date
            $table->date('date')->index();

            // Status
            $table->enum('status', [
                'present',
                'absent',
                'half_day',
                'paid_leave'
            ]);

            // Location
            $table->string('location');

            // Time tracking
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();

            // Optional photo
            $table->string('photo')->nullable();

            // Timestamps
            $table->timestamps();

            // One intern → one attendance per day
            $table->unique(['intern_id', 'date']);

            // Foreign key
            $table->foreign('intern_id')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
