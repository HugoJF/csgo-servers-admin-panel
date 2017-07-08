<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $guarded = ['id', 'message_config_id'];

    public function stats()
    {
        return $this->hasMany('App\Stats', 'server_id');
    }

    public function messageConfigs()
    {
        return $this->belongsTo('App\MessageConfig');
    }

    public function status()
    {
        return $this->hasMany('App\Status');
    }

    public function check()
    {
        return [
            'online' => true,
            'status' => true,
            'message' => 'Online',
        ];
    }

}
