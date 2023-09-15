<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     *product_id int [pk]
      restaurent_id int [ref: > restaurent.restaurent_id]
      product_name varchar
      product_image varchar
      product_desciption varchar
      product_buying_price varchar
      product_selling_price varchar
      product_status varchar
      product_quantity varchar
      product_rating varchar
      product_rating_count varchar
      product_sell_count int
      product_type varchar
      product_meta_data varchar
      created_at timestamp
     * 
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('restaurant_id',11);
            $table->string('product_name',100);
            $table->string('product_image',50)->nullable();
            $table->string('product_desciption', 500)->nullable();
            $table->string('product_buying_price', 10)->nullable();
            $table->string('product_selling_price', 10);
            $table->string('product_status', 10)->nullable();
            $table->string('product_quantity', 5)->nullable();
            $table->string('product_rating',10)->nullable();
            $table->string('product_rating_count',10)->nullable();
            $table->string('product_sell_count', 10)->nullable();
            $table->string('product_type', 55)->nullable();
            $table->string('product_meta_data', 255)->nullable();
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
        Schema::dropIfExists('products');
    }
}
