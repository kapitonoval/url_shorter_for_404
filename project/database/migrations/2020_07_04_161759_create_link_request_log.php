<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkRequestLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_request_log', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id();
            $table->integer('link_id');
            $table->string('user_ip', 15)->comment('ip4');
            $table->string('user_agent',200);
            $table->string('referrer',200);
            $table->timestamps();

            $table->index([ 'created_at']);
            $table->index([ 'user_ip']);
            $table->index([ 'user_agent']);
            $table->index([ 'referrer']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_request_log');
    }
}
