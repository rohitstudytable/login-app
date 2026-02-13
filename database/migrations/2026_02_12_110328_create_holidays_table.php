<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('holidays', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Holiday name (Republic Day etc)
        $table->date('holiday_date')->unique();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('holidays');
}

};
