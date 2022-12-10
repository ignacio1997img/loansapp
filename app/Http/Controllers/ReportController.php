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

class ReportController extends Controller
{
    //:::::::::::::::Daily Collection::::::::::::::
    public function dailyCollection()
    {
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
    

    //::::::::::::: Daily List ::::::::::::::::
    public function dailyList()
    {
        // $user = User::where('role_id', '!=', 1)->where('role_id', '!=', 2)->where('role_id', '!=', 3)->orderBy('name', 'ASC')->get();
        $route = Route::where('status', 1)->where('deleted_at', null)->get();



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

            ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci')
            ->get();
        
        // return $data;
        dd($data);






        return view('print.dailyList.report', compact('route'));
    }

    public function dailyListList(Request $request)
    {
        
        $data = DB::table('loan_routes as lr')
            ->join('loans as l', 'l.id', 'lr.loan_id')
            ->join('loan_days as ld', 'ld.loan_id', 'l.id')
            ->join('people as p', 'p.id', 'l.people_id')


            ->where('l.deleted_at', null)
            ->where('ld.deleted_at', null)
            ->where('lr.deleted_at', null)

            ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci')
            ->get();
        
        // return $data;
        dd($data);



        // $article = Article::whereRaw($query_filter)->get();
        // $data = DB::table('loan_day_agents as lda')
        //             ->join('loan_days as ld', 'ld.id', 'lda.loanDay_id')
        //             ->join('loans as l', 'l.id', 'ld.loan_id')
        //             ->join('people as p', 'p.id', 'l.people_id')
        //             ->join('users as u', 'u.id', 'lda.agent_id')
        //             ->where('l.deleted_at', null)
        //             ->where('ld.deleted_at', null)
        //             ->where('lda.deleted_at', null)
        //             ->whereDate('lda.created_at', '>=', date('Y-m-d', strtotime($request->start)))
        //             ->whereDate('lda.created_at', '<=', date('Y-m-d', strtotime($request->finish)))
        //             // ->where('lda.agent_id', $request->agent_id)
        //             ->whereRaw($query_filter)
        //             ->select('p.first_name', 'p.last_name1', 'last_name2', 'p.ci', 'ld.date as dateDay', 'u.name', 'l.id as loan_id', 'l.code', 'l.amountTotal', 'lda.id as loanDayAgent_id',
        //                     'lda.created_at as loanDayAgent_fecha', 'lda.amount')
        //             ->orderBy('ci', 'ASC')
        //             ->get();
        // return $data->id;        
        if($request->print){
            $start = $request->start;
            $finish = $request->finish;
            return view('print.dailyCollection.print', compact('data', 'start', 'finish'));
        }else{
            return view('print.dailyCollection.list', compact('data'));
        }
    }
}
