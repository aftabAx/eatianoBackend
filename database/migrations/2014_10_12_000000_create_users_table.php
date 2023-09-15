<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 55);
            $table->string('email', 55)->unique();
            $table->string('phone', 15)->unique();
            $table->string('role', 15);
            $table->string('refer_id', 20)->unique();
            $table->string('refarel_id', 20);
            $table->string('o_auth_id', 55);
            $table->string('fb_id', 55);
            $table->string('country', 55);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
