<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_video', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('person_id');
            $table->foreign('person_id')->references('id')->on('people');

            $table->foreignId('video_id');
            $table->foreign('video_id')->references('id')->on('videos');

            $table->tinyInteger('role')->comment('1 - director, 2 - screenwriter, 4 - actor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_video');
    }
}
