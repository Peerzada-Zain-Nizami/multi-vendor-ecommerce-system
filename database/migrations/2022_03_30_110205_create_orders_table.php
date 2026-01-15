<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->string('user_id');
            $table->string('order_id');
            $table->string('shipping_id')->nullable();
            $table->string('order_warehouse_id')->nullable();
            $table->string('receiver_wadmin')->nullable();
            $table->string('admin_id');
            $table->string('company_name')->nullable();
            $table->text('shipping_address');
            $table->string('platform');
            $table->string('shipping_fee')->default(0);
            $table->string('shipping_group')->nullable();
            $table->text('product');
            $table->string('sub_total');
            $table->string('remaining')->default(0);
            $table->string('total');
            $table->string('order_status')->nullable();
            $table->string('api_status')->nullable();
            $table->string('refund_status')->nullable();
            $table->string('payment')->default('Unpaid');
            $table->string('return_payment')->default(0);
            $table->string('paid')->default(0);
            $table->string('delivery_status')->nullable();
            $table->string('picked_status')->nullable();
            $table->string('refund_stock_status')->nullable();
            $table->boolean('is_confirm')->default(false);
            $table->string('status')->default('New Order');
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
        Schema::dropIfExists('orders');
    }
}
