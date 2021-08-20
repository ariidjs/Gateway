<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name_store');
            $table->string('name_user');
            $table->string('phoneNumber')->unique();
            $table->string('image_ktp');
            $table->string('saldo');
            $table->string('email')->unique();
            $table->integer('status_store');
            $table->string('image_store');
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('status_delete');
            $table->string('token_fcm');
            $table->string('api_token');
            $table->string('password');
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
        Schema::dropIfExists('stores');
    }
}
