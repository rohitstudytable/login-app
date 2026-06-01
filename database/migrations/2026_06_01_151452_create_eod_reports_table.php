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
        Schema::create('eod_reports', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('intern_id');

            $table->date('report_date');

            $table->text('tasks_completed');

            $table->text('challenges_faced')->nullable();

            $table->text('plan_for_tomorrow')->nullable();

            $table->timestamps();

            $table->foreign('intern_id')
                ->references('id')
                ->on('interns')
                ->onDelete('cascade');

            $table->index('intern_id');
            $table->index('report_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eod_reports');
    }
};