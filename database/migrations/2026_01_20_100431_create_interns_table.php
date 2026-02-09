<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

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
            $table->string('intern_code');
            $table->string('contact')->nullable();

            // Updated role types ONLY
            $table->enum('role', ['intern', 'employee', 'admin'])
                  ->default('intern');

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
