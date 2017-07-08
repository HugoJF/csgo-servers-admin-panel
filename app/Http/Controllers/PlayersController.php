<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Http\Request;

class PlayersController extends Controller
{
    public function index()
    {
        return view('players', [
            'players' => Player::all(),
        ]);
    }

    public function nameHistory($id)
    {
        return view('players-name-history', [
            'player' => Player::with('nameHistory')->find($id),
        ]);
    }
}
