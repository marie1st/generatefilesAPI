<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Fits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fits', function (Blueprint $table) {
            $table->id();
            $table->string('name1');
            $table->string('hnumber');
            $table->string('date1');
            $table->string('bday');
            $table->string('agerange');
            $table->string('roomno');
            $table->string('gendertype');
            $table->string('name2');
            $table->string('date2');
            $table->string('timerange');
            $table->string('name3');
            $table->string('D1')->nullable;
            $table->string('D2')->nullable;
            $table->string('D3')->nullable;
            $table->string('D4')->nullable;
            $table->string('D5')->nullable;
            $table->string('D6')->nullable;
            $table->string('E1')->nullable;
            $table->string('E2')->nullable;
            $table->string('E3')->nullable;
            $table->string('E4')->nullable;
            $table->string('E5')->nullable;
            $table->string('E6')->nullable;
            $table->string('E7')->nullable;
            $table->string('E8')->nullable;
            $table->string('E9')->nullable;
            $table->string('name4');
            $table->string('licensem');
            $table->string('phone1');
            $table->string('name5');
            $table->string('name6');
            $table->string('date3');
            $table->string('name7');
            $table->string('passport_no');
            $table->string('relationship1');
            $table->string('language1');
            $table->string('witness1');
            $table->string('witness2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fits');
    }
}
