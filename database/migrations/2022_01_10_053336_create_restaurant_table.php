<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantTable extends Migration
{
    /**
     * Run the migrations.
     *Table restaurent {
      restaurent_id int [pk]
      restaurent_name varchar
      restaurent_address varchar
      restaurent_image varchar
      restaurent_meta_deta varchar
      created_at timestamp
  
}
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant', function (Blueprint $table) {
            $table->id('restaurent_id');
            $table->string('restaurent_name',100);
            $table->string('restaurent_address');
            $table->string('restaurent_image')->nullable();
            $table->string('restaurent_meta_deta');
            $table->string('restaurent_added_by');


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
        Schema::dropIfExists('restaurant');
    }
}
