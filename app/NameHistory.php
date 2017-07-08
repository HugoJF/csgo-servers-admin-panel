<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NameHistory extends Model
{
    protected $table = 'players_name_history';

    public function players()
    {
        return $this->belongsTo('App\Player');
    }
}
