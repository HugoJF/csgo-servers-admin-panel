<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    protected $guarded = ['id', 'server_id', 'status_id'];

    public function server()
    {
        return $this->belongsTo('App\Server');
    }


    public function since() {
        $date = new Carbon($this->created_at);

        $diff = $date->diffForHumans();

        return $diff;
    }
}