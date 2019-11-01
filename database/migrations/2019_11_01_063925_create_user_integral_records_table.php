<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserIntegralRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_integral_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('用户id');
            $table->bigInteger('value')->comment('积分改变值');
            $table->tinyInteger('from')->default(0)->comment('积分来源 0：签到，1：发帖 2：拉新用户');
            $table->tinyInteger('type')->default(0)->comment('记录类型 0：减少 1：添加');
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
        Schema::dropIfExists('user_integral_records');
    }
}
