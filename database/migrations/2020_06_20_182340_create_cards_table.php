<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',20);
            $table->unsignedInteger('breakfasts_per_month')->nullable();//ako ne moze da uzima dorucak setuje se na 0,ako ima dorucka koliko ima dana u mesecu onda je NULL
            $table->unsignedInteger('lunches_per_month')->nullable();
            $table->unsignedInteger('dinners_per_month')->nullable();;

            $table->unsignedInteger('breakfasts_per_day')->nullable();//ako je NULL to znaci da moze neograniceno dorucka da uzme u toku dana
            $table->unsignedInteger('lunches_per_day')->nullable();
            $table->unsignedInteger('dinners_per_day')->nullable();

            $table->unsignedInteger('validity_days');//koliko dana traje kartica
            $table->string('description',100);
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
        Schema::dropIfExists('cards');
    }
}
