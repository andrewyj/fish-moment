<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostHeartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_hearts', function (Blueprint $table) {
            $table->bigInteger('user_id')->comment('用户id');
            $table->bigInteger('post_id')->comment('文章id');
            $table->tinyInteger('type')->default(1)->comment('类型：0：不喜欢 1：喜欢');
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
        Schema::dropIfExists('post_unlikers');
    }
}
