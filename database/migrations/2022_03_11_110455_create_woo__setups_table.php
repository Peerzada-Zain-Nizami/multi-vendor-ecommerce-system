<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWooSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woo__setups', function (Blueprint $table) {
            $table->id();
            $table->text('user_id');
            $table->text('tax_class_name');
            $table->text('tax_class_rates');
            $table->text('shipping_zone_ids');
            $table->text('shipping_method_ids');
            $table->text('shipping_class_ids');
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
        Schema::dropIfExists('woo__setups');
    }
}
