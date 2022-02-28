<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardTypeIdToStudentCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('card_type_id')->after('cardholder_id');
            $table->foreign('card_type_id')->references('id')->on('cards')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_cards', function (Blueprint $table) {
            $table->dropForeign('student_cards_card_type_id_foreign');
            $table->dropColumn('card_type_id');
        });
    }
}
