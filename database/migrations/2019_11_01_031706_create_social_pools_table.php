<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialPoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_pools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('创建者ID');
            $table->bigInteger('school_id')->nullable()->comment('所属学校');
            $table->string('avatar')->default('')->comment('圈子头像');
            $table->string('name')->comment('圈子名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('introduction')->nullable()->comment('简介');
            $table->bigInteger('user_count')->default(0)->comment('加入用户数');
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
        Schema::dropIfExists('social_pools');
    }
}
