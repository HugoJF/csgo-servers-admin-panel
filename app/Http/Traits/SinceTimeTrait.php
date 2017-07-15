<?php
namespace App\Http\Traits;

use Carbon\Carbon;

trait SinceTime {
    public function since($column = 'created_at') {
        $date = new Carbon($this->$column);

        $diff = $date->diffForHumans();

        return $diff;
    }
}