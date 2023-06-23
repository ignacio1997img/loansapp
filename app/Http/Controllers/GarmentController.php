<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Garment;
use App\Models\Cashier;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FileController;
use App\Models\GarmentsDoc;
use App\Models\GarmentsImage;

class GarmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {   

        $user = Auth::user();

        $cashier = Cashier::with(['movements' => function($q){
                $q->where('deleted_at', NULL);
            }])
            ->where('user_id', Auth::user()->id)
            ->where('status', 'abierta')
            ->where('deleted_at', NULL)->first();
        // return $cashier;

        $balance = 0;
        if($cashier)
        {
            $cashier_id = $cashier->id;
            $balance = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('balance');
        }
        else
        {
            $cashier_id = 0;
        }
        return view('garment.browse', compact('cashier','balance', 'cashier_id'));
    }

    public function create()
    {
        // return 1;
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('user_id', Auth::user()->id)
        ->where('status', 'abierta')
        ->where('deleted_at', NULL)->first();

        return view('garment.add', compact('cashier'));
    }

    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $imageObj = new FileController;
            $user = Auth::user();
            $agent = $this->agent($user->id);

            
            $garment = Garment::create([
                'people_id'=> $request->people_id,
                'article_id'=>$request->article_id,
                'articleDescription'=>$request->articleDescription,
                'type'=>$request->type,
                'month'=>$request->month,
                'monthCant'=>1,
                'amountLoan'=>$request->amountLoan,
                'priceDollar'=>$request->priceDollar,
                'amountLoanDollar'=>$request->amountLoanDollar,
                'porcentage'=>$request->porcentage,
                'amountPorcentage'=>$request->amountPorcentage,
                'observation'=>$request->observation,
                'cashierRegister_id'=>$request->cashierRegister_id
            ]);

            $file = $request->file('fileCi');
            if($file)
            {                 
                $fileCi = $imageObj->file($file, $garment->id, "Garment/ci"); 
                $garment->update(['fileCi' => $fileCi]);
            }


            $file = $request->file('filePrenda');
            if ($file) {
                for ($i=0; $i < count($file); $i++) { 
                    $filePrenda = $imageObj->image($file[$i], $garment->id, "Garment/filePrenda"); 
                    GarmentsImage::create([
                        'garment_id'=>$garment->id,
                        'image' => $filePrenda,
                        'register_userId' => $agent->id,
                        'register_agentType' => $agent->role,
                    ]);
                }
            }

            $file = $request->file('docPrenda');
            if ($file) {
                for ($i=0; $i < count($file); $i++) { 
                    $docPrenda = $imageObj->image($file[$i], $garment->id, "Garment/docPrenda"); 
                    GarmentsImage::create([
                        'garment_id'=>$garment->id,
                        'image' => $docPrenda,
                        'register_userId' => $agent->id,
                        'register_agentType' => $agent->role,
                    ]);
                }
            }


            return 1;
            DB::commit();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
    }
}
