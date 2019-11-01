<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_collections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment("搜索内容");
            $table->string('keywords')->comment("关键字")->default("");
            $table->string('from')->comment("从哪边进行搜索")->default("");
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
        Schema::dropIfExists('search_collections');
    }
}
