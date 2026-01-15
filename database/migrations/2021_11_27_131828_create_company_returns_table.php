<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_returns', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('user_id');
            $table->string('supplier_id');
            $table->text('products');
            $table->string('total');
            $table->string('status');
            $table->string('type');
            $table->string('payment');
            $table->string('remaining');
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
        Schema::dropIfExists('company_returns');
    }
}
