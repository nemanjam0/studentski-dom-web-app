<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDormInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dorm_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_template_id');
            $table->foreign('invoice_template_id')->references('id')->on('invoice_templates')->onDelete('cascade');
            $table->unsignedBigInteger('dorm_id');
            $table->foreign('dorm_id')->references('id')->on('dorms')->onDelete('cascade');
            $table->enum('students_finance_status', ['budget', 'self-financing','all_types']);
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
        Schema::dropIfExists('dorm_invoices');
    }
}
