<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Covids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covids', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('date1');
            $table->string('name1');
            $table->string('license_no');
            $table->string('name2');
            $table->string('date2');
            $table->string('name3');
            $table->string('date3');
            $table->string('name4');
            $table->string('name5');
            $table->string('address1');
            $table->string('covid_filePDF');
            $table->string('covid_fileDOCX');
            $table->string('covid_fileJPEG');
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
        
        Schema::dropIfExists('covids');
    }
}
