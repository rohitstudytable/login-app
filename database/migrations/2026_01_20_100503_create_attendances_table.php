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

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            | Calculated after clock-out
            */
            $table->enum('status', [
                'present',
                'half_day',
                'absent',
                'overtime',
                'paid_leave'
            ])->nullable();

            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */
            $table->string('location')->nullable();       // general location (optional)
            $table->string('in_location')->nullable();    // clock-in GPS
            $table->string('out_location')->nullable();   // clock-out GPS

            /*
            |--------------------------------------------------------------------------
            | TIME TRACKING
            |--------------------------------------------------------------------------
            */
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->integer('worked_minutes')->nullable();

            /*
            |--------------------------------------------------------------------------
            | OPTIONAL PHOTO
            |--------------------------------------------------------------------------
            */
            $table->string('photo')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | CONSTRAINTS
            |--------------------------------------------------------------------------
            */
            $table->unique(['intern_id', 'date']);

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