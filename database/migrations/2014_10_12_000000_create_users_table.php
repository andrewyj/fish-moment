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
            $table->text('token')->nullable()->comment('登录token');
            $table->string('password')->default('')->comment('密码');
            $table->tinyInteger('sex')->default(0)->comment('性别 0：未知 1：男 2：女');
            $table->tinyInteger('age')->default(18)->comment('年龄');
            $table->string('identifier')->nullable()->comment('学号');
            $table->string('longitude')->nullable()->comment('经度');
            $table->string('latitude')->nullable()->comment('纬度');
            $table->string('integral')->nullable()->comment('积分');
            $table->bigInteger('school_id')->nullable()->comment('所属院校id');
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
