<?php

namespace App\Http\Controllers;

use App\Server;
use App\Stats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Khill\Lavacharts;

class StatsController extends Controller
{
    public function index()
    {
        return Stats::all();
    }
}
