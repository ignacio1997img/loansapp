<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function verification()
    {
        return view('message.verification');
    }
}
