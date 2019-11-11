<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->default('')->comment('标题');
            $table->string('introduction')->default('')->comment('简介');
            $table->string('code')->comment('banner 码，用于约定banner出现位置');
            $table->string('picture_url')->default('')->comment('图片地址');
            $table->string('url')->default('')->comment('图片链接');
            $table->tinyInteger('disabled')->default(0)->comment('是否禁用 1：是 0：否');
            $table->tinyInteger('link_type')->default(0)->comment('链接类型 0：内链 1：外链');
            $table->tinyInteger('sort')->default(0)->comment('排序值');
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
        Schema::dropIfExists('banners');
    }
}
