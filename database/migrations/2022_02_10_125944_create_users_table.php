<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email',255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');
            $table->string('company_name')->nullable();
            $table->string('seller_type')->nullable();
            $table->string('permission')->nullable();
            $table->unsignedBigInteger('privileges_group')->nullable();
            $table->rememberToken();
            $table->string('mobile_no')->nullable();
            $table->string('mobile_verifyed_at')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('profile_img')->nullable();
            $table->string('theme')->default("light-mode");
            $table->boolean('order_process_status')->default(0);
            $table->boolean('order_auto_payment')->default(0);
            $table->boolean('shipping_from_us')->default(0);
            $table->string('language')->default("en");
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
        Schema::dropIfExists('users');
    }
}
