<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('user_id');
            $table->string('supplier_id');
            $table->text('original_order');
            $table->text('products');
            $table->string('sub_total');
            $table->string('shipping_fee');
            $table->string('total');
            $table->string('status');
            $table->string('payment');
            $table->string('remaining');
            $table->string('paid')->default(0);
            $table->string('return')->default(0);
            $table->string('receiver_admin')->nullable();
            $table->string('warehouse_id')->nullable();
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
        Schema::dropIfExists('company_orders');
    }
}
