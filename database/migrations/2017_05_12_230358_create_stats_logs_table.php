<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->increments('id');

            $table->float('netin');
            $table->float('netout');
            $table->integer('uptime');
            $table->integer('maps');
            $table->float('fps');
            $table->integer('players');
            $table->float('svms');
            $table->float('svms_stdv');
            $table->float('var');

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
        Schema::dropIfExists('stats');
    }
}
