<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\People;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\LoanDay;
use App\Models\User;

class ReportManagerController extends Controller
{

    //$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$               PARA LA RECOLECCION DIARIA POR RANGO DE FECHA                   $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
    public function dailyCollection()
    {
        // return 1;
        $user = User::where('role_id', '!=', 1)->where('role_id', '!=', 2)->where('role_id', '!=', 3)->orderBy('name', 'ASC')->get();
        return view('report.manager.dailyCollection.report', compact('user'));
    }

    // VIEW LIST
    public function dailyCollectionList(Request $request)
    {
        $query_filter = 'lda.agent_id = '. $request->agent_id;
        if ($request->agent_id=='todo') {
            $query_filter = 1;
        }


        // $article = Article::whereRaw($query_filter)->get();
        $data = DB::table('loan_day_agents as lda')
                    ->join('loan_days as ld', 'ld.id', 'lda.loanDay_id')
                    ->join('loans as l', 'l.id', 'ld.loan_id')
                    ->join('people as p', 'p.id', 'l.people_id')
                    ->join('users as u', 'u.id', 'lda.agent_id')
                    ->join('transactions as t', 't.id', 'lda.transaction_id')

                    ->where('l.deleted_at', null)
                    ->where('ld.deleted_at', null)
                    ->where('lda.deleted_at', null)
                    ->whereDate('lda.created_at', '>=', date('Y-m-d', strtotime($request->start)))
                    ->whereDate('lda.created_at', '<=', date('Y-m-d', strtotime($request->finish)))
                    // ->where('lda.agent_id', $request->agent_id)
                    ->whereRaw($query_filter)
                    ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci', 'ld.date as dateDay', 'u.name', 'l.id as loan', 'l.code', 'l.amountTotal', 'lda.id as loanDayAgent_id',
                                DB::raw('SUM(lda.amount)as amount'), 't.transaction',
                            'lda.created_at as loanDayAgent_fecha')
                    ->groupBy('loan', 'transaction')
                    ->orderBy('lda.created_at', 'ASC')
                    ->get();
        // return $data->id;    
        $amountTotal = $data->SUM('amount');
        // dump($amountTotal);
        if($request->print){
            $start = $request->start;
            $finish = $request->finish;
            return view('report.manager.dailyCollection.print', compact('data', 'start', 'finish', 'amountTotal'));
        }else{
            return view('report.manager.dailyCollection.list', compact('data'));
        }
    }
}
