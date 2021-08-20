<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->bigInteger('no_hp')->unique();
            $table->string('no_kendaraan')->unique();
            $table->string('foto_stnk');
            $table->string('foto_ktp');
            $table->string('foto_formal');
            $table->bigInteger('total_trans');
            $table->bigInteger('saldo');
            $table->double('rating');
            $table->boolean('status');
            $table->string('fcm_token')->nullable();
            $table->string('api_token')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
