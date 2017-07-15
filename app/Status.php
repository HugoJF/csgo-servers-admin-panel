<?php

namespace App;

use App\Http\Traits\SinceTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use SinceTime;

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

    public function getMaxPlayers()
    {
        //(?:bots ()((.)*) max)
        preg_match('/(?:bots \()((.*))\/0 max\)/', $this->players, $matches);
        return $matches[2];
    }
}