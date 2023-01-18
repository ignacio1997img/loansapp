<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Loan;
use App\Models\LoanDay;
use App\Models\LoanDayAgent;
use App\Models\User;
use App\Models\Route;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //:::::::::::::::Daily Collection::::::::::::::
    public function dailyCollection()
    {
        // return 1;
        $user = User::where('role_id', '!=', 1)->where('role_id', '!=', 2)->where('role_id', '!=', 3)->orderBy('name', 'ASC')->get();
        return view('print.dailyCollection.report', compact('user'));
    }

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
                    ->where('l.deleted_at', null)
                    ->where('ld.deleted_at', null)
                    ->where('lda.deleted_at', null)
                    ->whereDate('lda.created_at', '>=', date('Y-m-d', strtotime($request->start)))
                    ->whereDate('lda.created_at', '<=', date('Y-m-d', strtotime($request->finish)))
                    // ->where('lda.agent_id', $request->agent_id)
                    ->whereRaw($query_filter)
                    ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci', 'ld.date as dateDay', 'u.name', 'l.id as loan_id', 'l.code', 'l.amountTotal', 'lda.id as loanDayAgent_id',
                            'lda.created_at as loanDayAgent_fecha', 'lda.amount')
                    ->orderBy('ci', 'ASC')
                    ->get();
        // return $data->id;        
        if($request->print){
            $start = $request->start;
            $finish = $request->finish;
            return view('print.dailyCollection.print', compact('data', 'start', 'finish'));
        }else{
            return view('print.dailyCollection.list', compact('data'));
        }
    }


    //:::::::::::::::Daily Collection::::::::::::::
    public function loanListLate()
    {
        // return 1;
        // $user = User::where('role_id', '!=', 1)->where('role_id', '!=', 2)->where('role_id', '!=', 3)->orderBy('name', 'ASC')->get();
        return view('print.dailyDebtor.report');
    }

    public function loanListLateList(Request $request)
    {
        $data = DB::table('loan_routes as lr')
            ->join('loans as l', 'l.id', 'lr.loan_id')
            ->join('loan_days as ld', 'ld.loan_id', 'l.id')
            ->join('people as p', 'p.id', 'l.people_id')


            ->where('l.deleted_at', null)
            ->where('ld.deleted_at', null)
            ->where('lr.deleted_at', null)

            ->where('l.debt', '>', 0)

            ->where('ld.debt', '>', 0)
            ->where('ld.late', 1)

            ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci', 'l.code', 'l.dateDelivered', 'p.cell_phone', 'p.street', 'p.home', 'p.zone',
                'l.day', 'l.amountTotal', 'l.amountLoan', 'l.amountPorcentage', 'l.date',
                DB::raw("SUM(ld.late) as diasAtrasado"), DB::raw("SUM(ld.debt) as montoAtrasado")
            )
            ->groupBy('l.id','p.id')
            ->get();
        // return $data->id;        
        if($request->print){
            $start = $request->start;
            $finish = $request->finish;
            return view('print.dailyDebtor.print', compact('data'));
        }else{
            return view('print.dailyDebtor.list', compact('data'));
        }
    }



    

    //::::::::::::: Daily List ::::::::::::::::
    public function dailyList()
    {
        // return 1;
        // $user = User::where('role_id', '!=', 1)->where('role_id', '!=', 2)->where('role_id', '!=', 3)->orderBy('name', 'ASC')->get();
        $route = Route::where('status', 1)->where('deleted_at', null)->get();

        
        return view('print.dailyList.report', compact('route'));
    }

    public function dailyListList(Request $request)
    {
        // return $request->route_id;
        $query_filter = 'lr.route_id = '.$request->route_id;

        $message = Route::where('id', $request->route_id)->select('name')->first()->name;
        if($request->route_id  == 'todo')
        {
            $query_filter = 1;
            $message = 'Todas Las Rutas';
        }


        $data = DB::table('loan_routes as lr')
            ->join('loans as l', 'l.id', 'lr.loan_id')
            ->join('people as p', 'p.id', 'l.people_id')
            ->join('routes as r', 'r.id', 'lr.route_id')



            ->where('l.deleted_at', null)
            ->where('lr.deleted_at', null)

            ->where('l.debt', '!=', 0)
            ->where('l.status', 'entregado')
            ->whereRaw($query_filter)

            ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci', 'l.code', 'l.dateDelivered', 'p.cell_phone', 'p.street', 'p.home', 'p.zone',
                'l.day', 'l.amountTotal', 'l.amountLoan', 'l.amountPorcentage', 'l.date', 'l.id as loan_id', 'r.name as ruta'
            )
            ->get();
        
            
        if($request->print){
            return view('print.dailyList.print', compact('data', 'message'));
        }else{
            return view('print.dailyList.list', compact('data'));
        }
    }


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
        
        return view('print.dailyCollectionCashier.report', compact('route', 'user'));
    }

    public function loanCollectionList(Request $request)
    {


        // $article = Article::whereRaw($query_filter)->get();
        $data = DB::table('loan_day_agents as lda')
                    ->join('loan_days as ld', 'ld.id', 'lda.loanDay_id')
                    ->join('loans as l', 'l.id', 'ld.loan_id')
                    ->join('people as p', 'p.id', 'l.people_id')
                    ->join('users as u', 'u.id', 'lda.agent_id')
                    ->where('l.deleted_at', null)
                    ->where('ld.deleted_at', null)
                    ->where('lda.deleted_at', null)
                    ->whereDate('lda.created_at', date('Y-m-d', strtotime($request->date)))
                    // ->whereDate('lda.created_at', '<=', date('Y-m-d', strtotime($request->finish)))
                    ->where('lda.agent_id', $request->agent_id)
                    // ->whereRaw($query_filter)
                    ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci', 'ld.date as dateDay', 'u.name', 'l.id as loan_id', 'l.code', 'l.amountTotal', 'lda.id as loanDayAgent_id',
                            'lda.created_at as loanDayAgent_fecha', 'lda.amount')
                    ->orderBy('ci', 'ASC')
                    ->get();
        // return $data->id;        
        if($request->print){
            $date = $request->date;
            return view('print.dailyCollectionCashier.print', compact('data', 'date'));
        }else{
            return view('print.dailyCollectionCashier.list', compact('data'));
        }
        
    }







}
