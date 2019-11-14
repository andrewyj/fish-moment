<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('用户id');
            $table->bigInteger('social_pool_id')->default(0)->comment('圈子id');
            $table->bigInteger('school_id')->default(0)->comment('学校id');
            $table->longText('content')->comment('内容');
            $table->tinyInteger('resource_type')->default(0)->comment('资源类型 0：图片 1：视频');
            $table->text('resource_urls')->default('')->comment('资源路径');
            $table->bigInteger('repost_count')->default(0)->comment('转发次数');
            $table->bigInteger('like_count')->default(0)->comment('喜欢次数');
            $table->bigInteger('dislike_count')->default(0)->comment('不喜欢次数');
            $table->bigInteger('comment_count')->default(0)->comment('评论次数');
            $table->tinyInteger('verify_status')->default(0)->comment('审核状态 0：待审核 1：审核中 2：审核通过');
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
        Schema::dropIfExists('user_posts');
    }
}
