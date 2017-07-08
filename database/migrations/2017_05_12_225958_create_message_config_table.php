<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->string('serverName');
            $table->string('mySQL');
            $table->string('serverType');
            $table->string('time');
            $table->string('languages');
            $table->string('defaultLanguage');
            $table->string('logExpiredMessages');

            $table->string('wm_enable');
            $table->string('wm_type');
            $table->string('wm_delay');
            $table->string('wm_flags');
            $table->string('wm_message');

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
        Schema::dropIfExists('message_configs');
    }
}
