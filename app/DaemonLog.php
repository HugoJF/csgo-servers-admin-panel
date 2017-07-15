<?php

namespace App;

use App\Http\Traits\SinceTime;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DaemonLog extends Model
{
    use SinceTime;
}
