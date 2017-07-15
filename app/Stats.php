<?php

namespace App;

use App\Http\Traits\SinceTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    use SinceTime;

    protected $guarded = ['id', 'server_id', 'status_id'];

    public function server()
    {
        return $this->belongsTo('App\Server');
    }

    public function getNetInAttribute($value) {
        return round($value / 1024 / 1024 * 8, 2) . ' Mbps';
    }

    public function getNetOutAttribute($value) {
        return round($value / 1024 / 1024 * 8, 2) . ' Mbps';
    }

    public function getUptimeAttribute($value) {
        return floor($value / 60) . 'h' . $value % 60 . 'min';
    }

    public function getSvmsAttribute($value) {
        return $value . ' ms';
    }

    public function getSvmsStdvAttribute($value) {
        return $value . ' ms';
    }

    public function getVarAttribute($value) {
        return $value . ' ms';
    }
}