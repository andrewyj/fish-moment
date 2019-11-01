<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostUnlikersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_unlikers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('')->comment('分类名称');
            $table->tinyInteger('sort')->default(0)->comment('排序值');
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
