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
            $table->bigInteger('school_id')->nullable()->comment('所属院校id');
            $table->string('name');
            $table->string('phone')->default("")->comment('手机号')->unique();
            $table->string('nickname')->default("")->comment("用户昵称");
            $table->string('avatar')->default("")->comment('头像地址');
            $table->string('introduction')->default('')->comment('简介');
            $table->string('oauth_id')->default("")->comment('第三方登录验证id')->unique();
            $table->string('unionid')->default("")->comment('unionid');
            $table->text('token')->nullable()->comment('登录token');
            $table->string('password')->default('')->comment('密码');
            $table->tinyInteger('gender')->default(0)->comment('性别 0：未知 1：男 2：女');
            $table->tinyInteger('age')->default(18)->comment('年龄');
            $table->string('identifier')->default('')->comment('学号');
            $table->string('longitude')->default('')->comment('经度');
            $table->string('latitude')->default('')->comment('纬度');
            $table->string('photos')->default('')->comment('图片集');
            $table->bigInteger('integral')->default(0)->comment('积分');
            $table->bigInteger('invitation_count')->default(0)->comment('邀请人数');
            $table->bigInteger('follow')->default(0)->comment('已关注人数');
            $table->bigInteger('follower')->default(0)->comment('被关注人数');
            $table->softDeletes();
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
