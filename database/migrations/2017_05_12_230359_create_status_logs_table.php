<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status', function (Blueprint $table) {
            $table->increments('id');

            $table->string('hostname');
            $table->string('version');
            $table->string('udpip');
            $table->string('os');
            $table->string('type');
            $table->string('map');
            $table->string('players');

            $table->integer('request_interval');

            $table->integer('server_id')->unsigned();
            $table->foreign('server_id')->references('id')->on('servers');

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
        Schema::dropIfExists('status');
    }
}
