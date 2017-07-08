<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $guarded = ['id', 'server_id'];

    protected $table = 'status';

    public function server()
    {
        return $this->belongsTo('App\Server');
    }

    public function players()
    {
        return $this->belongsToMany('App\Player')->withTimestamps();
    }


    public function since() {
        $date = new Carbon($this->created_at);

        $diff = $date->diffForHumans();

        return $diff;
    }

    public function getMaxPlayers()
    {
        //(?:bots ()((.)*) max)
        preg_match('/(?:bots \()((.*))\/0 max\)/', $this->players, $matches);
        return $matches[2];
    }
}