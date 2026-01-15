<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_apis', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('woo')->nullable();
            $table->string('shopify')->nullable();
            $table->text('woo_details')->nullable();
            $table->text('shopify_details')->nullable();
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
        Schema::dropIfExists('seller_apis');
    }
}
