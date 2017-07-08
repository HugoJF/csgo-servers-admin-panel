<?php

namespace App\Http\Controllers;

use Invisnik\LaravelSteamAuth\SteamAuth;
use App\User;
use Auth;

class AuthController extends Controller
{
    /**
     * @var SteamAuth
     */
    private $steam;

    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    public function login()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();
            if (!is_null($info) && $info->steamID64 == '76561198033283983') {
                $user = User::where('steamid', $info->steamID64)->first();
                if (is_null($user)) {
                    $user = User::create([
                        'name' => $info->personaname,
                        'steamid' => $info->steamID64
                    ]);
                }
                Auth::login($user, true);
                return redirect('/'); // redirect to site
            }
        }
        return $this->steam->redirect(); // redirect to Steam login page
    }

    public function blank() {
        return 'TODO';
    }
}