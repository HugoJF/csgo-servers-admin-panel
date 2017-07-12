<?php

namespace App\Http\Controllers;

use App\DaemonLog;
use Illuminate\Http\Request;

class DaemonController extends Controller
{
    public function logs() {
        return view('logs', [
            'logs' => DaemonLog::orderBy('created_at', 'desc')->take(15)->get()
        ]);
    }
}
