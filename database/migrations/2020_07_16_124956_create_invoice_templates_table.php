<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',20);
            $table->enum('recurring', ['no', 'template','daily','monthly','weekly','yearly']);
            $table->unsignedInteger('day_of_the_week');//koristi se za weekly kako bi se znalo kog dana npr poneljkom utorokm
            $table->unsignedInteger('day');//koristi se za monthly kako bi se znalo kog dana u mesecu treba da se pokrene
            $table->unsignedInteger('month');//koristi se za yearly
            $table->unsignedInteger('amount_of_money');
            $table->unsignedInteger('days_to_pay');//koliko dana korisnik ima da plati racun od kad mu se uruci
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
        Schema::dropIfExists('invoice_templates');
    }
}
