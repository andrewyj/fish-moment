<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentHeartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_hearts', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('comment_id');
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
        Schema::dropIfExists('comment_likers');
    }
}
