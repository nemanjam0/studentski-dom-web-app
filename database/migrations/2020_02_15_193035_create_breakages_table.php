<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breakages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('description');
            $table->unsignedBigInteger('reported_by_id');
            $table->foreign('reported_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('dorm_id');//zbog toga sto ce u slucaju da studenta u medjuvremenu presele majstor ce dobiti pogresan info. gde je kvar
            $table->foreign('dorm_id')->references('id')->on('dorms')->onDelete('cascade');
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->text('answer')->nullable();
            $table->unsignedBigInteger('answered_by_id')->nullable();
            $table->foreign('answered_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['unanswered','answered','solved','notsolved']);
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
        Schema::dropIfExists('breakages');
    }
}
