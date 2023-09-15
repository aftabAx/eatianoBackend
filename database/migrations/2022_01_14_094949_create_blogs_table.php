<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id('blog_id');
            $table->string('user_id',11);
            $table->string('blog_heading',250);
            $table->string('blog_subheading',250)->nullable();
            $table->string('blog_details',2000);
            $table->string('blog_main_image')->nullable();
            $table->string('blog_meta_data')->nullable();
            $table->string('blog_likes',250);
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
        Schema::dropIfExists('blogs');
    }
}
