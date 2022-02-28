<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_card_id');
            $table->foreign('student_card_id')->references('id')->on('student_cards')->onDelete('cascade');
            $table->smallInteger('breakfasts_change');
            $table->smallInteger('lunches_change');
            $table->smallInteger('dinners_change');
            $table->smallInteger('money_change');
            $table->unsignedBigInteger('executed_by_user_id')->nullable();
            $table->foreign('executed_by_user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('card_transactions');
    }
}
