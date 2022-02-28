<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cardholder_id');
            $table->foreign('cardholder_id')->references('id')->on('users')->onDelete('cascade');
           // $table->unsignedInteger('money'); ipak cemo u transakcijama da cuvamo
            $table->unsignedBigInteger('issuer_id');
            $table->foreign('issuer_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE student_cards AUTO_INCREMENT = 100000;');//da id kartice bude sestocifrene(lepse je kada je svima id kartice iste duzine kada imamo sestocifren broj mozemo 900000 kartica da napravimo sa istom duzinom) kako ne bi morali da pravimo posebnu kolonu card_number
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_cards');
    }
}
