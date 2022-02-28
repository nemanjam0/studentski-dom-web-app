<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('college_id')->nullable();
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('set null');
            $table->tinyInteger('year_of_study');
            $table->string('indeks',20);//zato sto neki indeksi mogu da sadrze slova npr (REr 3/19)
            $table->enum('finance_status', ['budget', 'self-financing']);
            $table->boolean('dorm_tenant')->default(0);
            $table->unsignedBigInteger('dorm_id')->nullable();
            $table->foreign('dorm_id')->references('id')->on('dorms')->onDelete('set null');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
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
        Schema::dropIfExists('students');
    }
}
