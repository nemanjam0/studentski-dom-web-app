<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('invoice_template_id');//realno nam nije tolko potrebno posto cemo i onako da naziv i iznos cuvati u ovoj tabeli
            $table->foreign('invoice_template_id')->references('id')->on('invoice_templates')->onDelete('cascade');                                             //ako npr. dodje do promene a korisnik nije platio prethodni racun(kako se npr. ne bi povecalo iznos dospelih racuna,ako dodje do promene u medjuvremenu)
            $table->string('name',20);
            $table->unsignedInteger('amount_of_money');
            $table->date('date_of_issue');
            $table->date('due_date');                                                           
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
        Schema::dropIfExists('user_bills');
    }
}
