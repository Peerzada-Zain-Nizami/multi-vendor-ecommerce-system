<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWooShippingCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woo_shipping_costs', function (Blueprint $table) {
            $table->id();
            $table->string('shipping_zone_id');
            $table->string('shipping_method_id');
            $table->string('shipping_class_id');
            $table->string('shipping_cost');
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
        Schema::dropIfExists('woo_shipping_costs');
    }
}
