<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment

            // intern_id (indexed + foreign key)
            $table->unsignedBigInteger('intern_id');
            $table->index('intern_id');

            // status enum
            $table->enum('status', ['present', 'absent', 'half_day']);

            // photo nullable
            $table->string('photo')->nullable();

            // date indexed
            $table->date('date');
            $table->index('date');

            // timestamps nullable
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // foreign key
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
