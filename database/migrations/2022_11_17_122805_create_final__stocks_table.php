<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final__stocks', function (Blueprint $table) {
            $table->id();
            $table->string('stock_ins_list_id')->nullable();
            $table->string('stock');
            $table->string('display')->default(false);
            $table->string('selected_stock');
            $table->string('delivered_stock')->nullable();
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
        Schema::dropIfExists('final__stocks');
    }
}
