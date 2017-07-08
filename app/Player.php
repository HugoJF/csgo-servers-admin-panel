<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public function nameHistory()
    {
        return $this->hasMany('App\NameHistory');
    }

    public function status()
    {
        return $this->belongsToMany('App\Status')->withTimestamps();
    }

}
