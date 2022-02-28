<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvernightStayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overnight_stay', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('person_name',50);
            $table->string('id_number',20);//realno moze i neki int zato sto su dokumenta
            $table->unsignedBigInteger('host');
            $table->foreign('host')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('dorm_id');//ako se student preseli da se zna gde osoba noci
            $table->foreign('dorm_id')->references('id')->on('dorms')->onDelete('cascade');
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedBigInteger('allowed_by');
            $table->foreign('allowed_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('overnight_stay');
    }
}
