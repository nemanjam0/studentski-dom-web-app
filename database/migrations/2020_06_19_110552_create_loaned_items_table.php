<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loaned_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('borrower_id');
            $table->foreign('borrower_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('borrowed_by_id');
            $table->foreign('borrowed_by_id')->references('id')->on('users');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->smallInteger('quantity');
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
        Schema::dropIfExists('loaned_items');
    }
}
