<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_address', function (Blueprint $table) {
            $table->id('delivery_address_id');
            $table->string('user_id',11);
            $table->string('order_unique_id',25);
            $table->string('city',25);
            $table->string('state',25);
            $table->string('country',25)->nullable();
            $table->string('area',25)->nullable();
            $table->string('pincode',7);
            $table->string('phone',13);
            $table->string('nearby',7)->nullable();
            $table->string('lat',25);
            $table->string('lng',25);
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
        Schema::dropIfExists('delivery_address');
    }
}
