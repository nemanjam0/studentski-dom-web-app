<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dorm_id');
            $table->foreign('dorm_id')->references('id')->on('dorms')->onDelete('cascade');
            $table->string('room_number',20);//u paviljonu 4 postoje blokovi(1A 3A itd.) zbog toga je string,a ne int
            $table->tinyInteger('room_capacity');//koliko studenta moze da stane u sobu
            $table->tinyInteger('floor');//onako
            $table->mediumText('description');
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
        Schema::dropIfExists('rooms');
    }
}
