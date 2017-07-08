<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    public function index($id = null) {
        return view('servers', [
            'servers' => Server::all()
        ]);
    }

    public function logs($id) {
        return $id;
    }

    public function manage($id) {
        $server = Server::find($id);

        return view('servers-manage', [
            'server' => $server,
            'players' => $server->status()->latest()->first()->players()->get(),
            'stats' => $server->stats()->latest()->limit(10)->get(),
            'status' => $server->status()->latest()->limit(10)->get(),
        ]);
    }

    public function stats($id) {
        return $id;
    }
}
