<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SSOAuthUsersTable extends Migration
{
    public function up()
    {
        Schema::create('sso_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar');
            $table->string('email');
            $table->json('known_logins')->nullable();
            $table->string('remote_token')->nullable();
            $table->string('store_number');
            $table->json('guards');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sso_users');
    }
}
