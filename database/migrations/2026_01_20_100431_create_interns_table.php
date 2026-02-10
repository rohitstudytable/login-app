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

            $table->string('name');

            $table->string('random_id');

            $table->string('email')->unique();

            // 🔐 Password fields
            $table->string('password')->nullable();
            $table->string('plain_password', 20)->nullable();

            $table->string('intern_code')->unique();

            $table->string('contact')->nullable();

            $table->enum('role', [
                'intern',
                'employee',
                'admin'
            ])->default('intern');

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
