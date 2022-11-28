<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\LoanDay;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    // para poner en retrazado de forma automatica
    public function late()
    {
        $date = date("Y-m-d");
        // return $date;
        $data = LoanDay::where('deleted_at', null)
            ->where('deleted_at', null)
            ->where('debt', '>', 0)
            ->where('late', 0)
            ->where('date', '<', $date)
            ->get();
        foreach($data as $item)
        {
            $item->update(['late'=>1]);
        }
        return true;
    }

    public function notificationLate()
    {
        
    }
}
