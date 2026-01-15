<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('balance')->default('eyJpdiI6Im1wenVGL1NhbDJ1bHRSN0Q1L01EK1E9PSIsInZhbHVlIjoiREZ1N1ZRalZHcmFoVkJZc2pCM0R1UT09IiwibWFjIjoiNjhkYjkzYjYxMDFmMGVhN2ZmZTYyYjkyMTBlMGVmYTliODkzOGU5NTU1NWYzMGVlNDRhYjU4MjA1NjJlMjlhOSIsInRhZyI6IiJ9');
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
        Schema::dropIfExists('wallets');
    }
}
