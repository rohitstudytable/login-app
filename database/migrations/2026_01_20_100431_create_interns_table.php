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
        Schema::create('interns', function (Blueprint $table) {
            $table->id();

            // Basic details
            $table->string('name');
            $table->string('random_id');
            $table->string('email')->unique();

            // Authentication
            $table->string('password')->nullable();
            $table->string('plain_password', 20)->nullable();
            $table->string('intern_code')->unique();

            // Contact & role
            $table->string('contact')->nullable();
            $table->enum('role', ['intern', 'employee', 'admin'])->default('intern');

            // Personal details
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('nationality')->nullable();

            // Address details
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pin', 10)->nullable();

            // Work profile
            $table->string('designation')->nullable();

            // ✅ Leave / Holiday management
            $table->integer('total_holidays')->default(10); // yearly leave balance

            // Profile image
            $table->string('img')->nullable(); // store image path

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interns');
    }
};