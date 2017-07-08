<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function messageConfig() {
        return $this->belongsTo('App\MessageConfig');
    }
}
