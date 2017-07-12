<?php

namespace App\Http\Controllers;

use App\DaemonLog;
use Illuminate\Http\Request;

class DaemonController extends Controller
{
    public function logs() {
        return view('daemon-logs', [
            'logs' => DaemonLog::all()
        ]);
    }
}
