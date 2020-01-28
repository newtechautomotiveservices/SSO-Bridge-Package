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
            $table->integer('remote_id');
            $table->string('token');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('store_number')->default("");
            $table->longText('roles');
            $table->longText('permissions');
            $table->longText('stores');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sso_users');
    }
}
