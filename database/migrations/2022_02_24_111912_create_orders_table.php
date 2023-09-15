<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *    order_id int [pk]
          user_id int [ref: > users.user_id]
          total_amount varchar
          buying_amount varchar
          transaction_id varchar
          transaction_type varchar
          marchent_name varchar
          coupon_id int [ref: > coupon.coupon_id]
          order_date timestamp
          expected_d_date timestamp
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('user_id');
            $table->string('restaurant_id',11);
            $table->string('transaction_id',25);
            $table->string('total_amount',11);
            $table->string('buying_amount',11)->nullable();
            $table->string('transaction_amount',11);
            $table->string('marchent_name',55);
            $table->string('discount',11)->nullable();
            $table->string('delivery_charge',11)->nullable();
            $table->string('order_status',25)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
