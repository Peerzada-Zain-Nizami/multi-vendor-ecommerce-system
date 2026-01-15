<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockInsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_ins_list', function (Blueprint $table) {
            $table->id();
            $table->string('stock_ins_id');
            $table->string('invoice_no');
            $table->string('stock');
            $table->string('reserved')->default(0);
            $table->string('available');
            $table->string('product_id');
            $table->string('warehouse_id');
            $table->string('sold')->default(0);
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
        Schema::dropIfExists('stock_ins_list');
    }
}
