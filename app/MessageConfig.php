<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageConfig extends Model
{
    protected $guarded = ['id'];

    public function servers() {
        return $this->hasMany('App\Server');
    }

    public function messages() {
        return $this->hasMany('App\Message');
    }
}
