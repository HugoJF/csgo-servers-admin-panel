<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index() {
        return view('status', [
           'status' => Status::orderBy('created_at', 'DESC')->paginate(15),
        ]);
    }
}
