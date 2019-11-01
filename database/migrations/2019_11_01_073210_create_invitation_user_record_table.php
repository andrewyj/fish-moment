<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationUserRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitation_user_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('invited_user_id')->comment('被邀请人id');
            $table->bigInteger('user_id')->comment('邀请人id');
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
        Schema::dropIfExists('invitation_user_record');
    }
}
