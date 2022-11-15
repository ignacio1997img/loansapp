<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\People;

class MessageController extends Controller
{
    public function verification($id)
    {
        $ok = People::where('id', $id)->first();
        // return $id;
        if($ok->token)
        {
            return view('message.verification-success');
        }
        $ok->update(['token'=>1]);

        return view('message.verification');
    }
}
