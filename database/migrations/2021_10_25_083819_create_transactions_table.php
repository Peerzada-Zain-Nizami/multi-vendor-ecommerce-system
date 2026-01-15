<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id');
            $table->unsignedBigInteger('user_id');
            $table->string('cash_in')->nullable();
            $table->string('cash_out')->nullable();
            $table->string('previous_balance');
            $table->string('type');
            $table->string('status');
            $table->string('transfar_from')->nullable();
            $table->string('transfar_to')->nullable();
            $table->string('method')->nullable();
            $table->string('method_trs_id')->nullable();
            $table->string('note')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('iban_no')->nullable();
            $table->string('paypal_email')->nullable();
            $table->string('deposit_amount')->nullable();
            $table->string('withdraw_amount')->nullable();
            $table->string('fees')->nullable();
            $table->string('exchange_rate')->nullable();
            $table->string('total_recive')->nullable();
            $table->string('attach')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
