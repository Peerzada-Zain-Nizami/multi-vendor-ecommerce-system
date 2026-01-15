<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drop_shippings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('product_id');
            $table->string('product_name');
            $table->string('category');
            $table->string('short_description');
            $table->text('brief_description');
            $table->string('featured_image')->nullable();
            $table->text('product_images')->nullable();
            $table->string('status');
            $table->string('selling_price')->nullable();
            $table->string('discount')->nullable();
            $table->string('fee')->nullable();
            $table->string('tags')->nullable();
            $table->text('platforms')->nullable();
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
        Schema::dropIfExists('drop_shippings');
    }
}
