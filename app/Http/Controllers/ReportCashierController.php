<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\People;
use App\Models\Loan;
use App\Models\LoanDay;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class ReportCashierController extends Controller
{
    
    //:::::::::::: PARA RECAUDACION DIARIA DE LOS CAJEROS Y COBRADORES EN MOTOS::::::::    
    public function loanCollection()
    {        
        $route = Route::where('status', 1)->where('deleted_at', null)->get();
        // $query_filter = "";
        // if(auth()->user()->hashRole('admin'))
        // {
        //     $query_filter
        // }
        $user = User::where('id', Auth::user()->id)->get();
        
        return view('report.cashier.dailyCollection.report', compact('route', 'user'));
    }

    public function loanCollectionList(Request $request)
    {

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
                    ->whereDate('lda.created_at', date('Y-m-d', strtotime($request->date)))
                    // ->whereDate('lda.created_at', '<=', date('Y-m-d', strtotime($request->finish)))
                    ->where('lda.agent_id', $request->agent_id)
                    // ->whereRaw($query_filter)
                    ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci', 'ld.date as dateDay', 'u.name',
                            'l.id as loan', 'l.code', 'l.amountTotal', 'lda.id as loanDayAgent_id', DB::raw('SUM(lda.amount)as amount'),
                            'lda.created_at as loanDayAgent_fecha', 't.transaction')
                    ->groupBy('loan', 'transaction')
                    ->orderBy('lda.created_at', 'ASC')
                    ->get();


                   
        // return $data->id;        
        if($request->print){
            $date = $request->date;
            return view('report.cashier.dailyCollection.print', compact('data', 'date'));
        }else{
            return view('report.cashier.dailyCollection.list', compact('data'));
        }
        
    }


    // para obtener los prestamos entregados del dia o una fecha en especifica
    public function loanDelivered()
    {   
        $user = User::where('id', Auth::user()->id)->get();
        
        return view('report.cashier.loanDelivered.report', compact('user'));
    }


    public function loanDeliveredList(Request $request)
    {
        // dump($request);
        $data = Loan::with(['people', 'agentDelivered'])->where('deleted_at', null)->where('status', 'entregado')
            ->where('delivered_userId', $request->agent_id)
            ->whereDate('dateDelivered', date('Y-m-d', strtotime($request->date)))
            ->get();
        // dump($data);

        if($request->print){
            $date = $request->date;
            return view('report.cashier.loanDelivered.print', compact('data', 'date'));
        }else{
            return view('report.cashier.loanDelivered.list', compact('data'));
        }        
    }
}
