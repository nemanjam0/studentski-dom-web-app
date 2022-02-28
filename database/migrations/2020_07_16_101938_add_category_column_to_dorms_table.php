<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryColumnToDormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dorms', function (Blueprint $table) {
            $table->unsignedInteger('category')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dorms', function (Blueprint $table) {
            $table->dropIndex('dorms_category_index');
            $table->dropColumn('category');
        });
    }
}
