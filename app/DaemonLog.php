<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DaemonLog extends Model
{
    public function since() {
        $date = new Carbon($this->created_at);

        $diff = $date->diffForHumans();

        return $diff;
    }
}
