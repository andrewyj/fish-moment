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
            $table->bigInteger('social_pool_id')->nullable()->comment('圈子id');
            $table->longText('content')->comment('内容');
            $table->bigInteger('repost_times')->default(0)->comment('转发次数');
            $table->bigInteger('like_count')->default(0)->comment('喜欢次数');
            $table->bigInteger('unlike_count')->default(0)->comment('不喜欢次数');
            $table->bigInteger('comment_count')->default(0)->comment('评论次数');
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
