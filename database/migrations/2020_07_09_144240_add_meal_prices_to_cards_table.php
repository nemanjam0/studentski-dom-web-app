<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMealPricesToCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->unsignedInteger('breakfast_price')->after('dinners_per_day');
            $table->unsignedInteger('lunch_price')->after('breakfast_price');
            $table->unsignedInteger('dinner_price')->after('lunch_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('breakfast_price');
            $table->dropColumn('lunch_price');
            $table->dropColumn('dinner_price');
        });
    }
}
