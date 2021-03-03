<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Files extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('D1')->nullable;
            $table->string('D2')->nullable;
            $table->string('D3')->nullable;
            $table->string('D4')->nullable;
            $table->string('D5')->nullable;
            $table->string('D6')->nullable;
            $table->string('D7')->nullable;
            $table->string('D8')->nullable;
            $table->string('D9')->nullable;
            $table->string('departure1');
            $table->string('arrival1');
            $table->string('seat1');
            $table->string('date1');
            $table->string('name1');
            $table->string('nationality1');
            $table->string('age1');
            $table->string('passport1');
            $table->string('others1');
            $table->string('accom1');
            $table->string('list1');
            $table->string('F1')->nullable;
            $table->string('F2')->nullable;
            $table->string('F3')->nullable;
            $table->string('F4')->nullable;
            $table->string('F5')->nullable;
            $table->string('F6')->nullable;
            $table->string('F7')->nullable;
            $table->string('F8')->nullable;
            $table->string('F9')->nullable;
            $table->string('G1')->nullable;
            $table->string('others2');
            $table->string('passenger1');
            $table->string('officer1');
            $table->string('tor8_filePDF');
            $table->string('tor8_fileDOCX');
            $table->string('tor8_fileJPEG');
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
        Schema::dropIfExists('files');
    
    }
}
