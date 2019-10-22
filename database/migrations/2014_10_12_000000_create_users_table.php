<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone')->default("")->comment('手机号')->unique();
            $table->string('nickname')->default("")->comment("用户昵称");
            $table->string('avatar')->default("")->comment('头像地址');
            $table->string('oauth_id')->default("")->comment('第三方登录验证id')->unique();
            $table->string('unionid')->default("")->comment('unionid');
            $table->text('token')->comment('登录token');
            $table->string('password')->comment('密码');
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
