<?php

namespace App\Http\Controllers;

use App\MessageConfig;
use Illuminate\Http\Request;

class MessagesConfigController extends Controller
{
    public function index() {
        return MessageConfig::all();
    }
}
