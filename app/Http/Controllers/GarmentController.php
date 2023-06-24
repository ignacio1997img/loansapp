<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Garment;
use App\Models\Cashier;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FileController;
use App\Models\Article;
use App\Models\GarmentsDoc;
use App\Models\GarmentsImage;
use PhpParser\Node\Expr\FuncCall;

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

    public function list($cashier_id, $type, $search = null){
        $user = Auth::user();
        $paginate = request('paginate') ?? 10;
        switch($type)
        {
            case 'pendiente':
                // dump(1);
                $data = Garment::with(['people'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('people', function($query) use($search){
                                $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                            })
                            // ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                        }
                    })
                    ->where('deleted_at', NULL)->where('status', 'pendiente')->orderBy('id', 'DESC')->paginate($paginate);
                    // dump($data);
                return view('garment.list', compact('data', 'cashier_id'));
                break;
            case 'entregado':
                    $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                        ->where(function($query) use ($search){
                            if($search){
                                $query->OrwhereHas('people', function($query) use($search){
                                    $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                                })
                                ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                                ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                            }
                        })
                        ->where('deleted_at', NULL)->where('status', 'entregado')->where('debt', '!=', 0)->orderBy('date', 'DESC')->paginate($paginate);
                    return view('loans.list', compact('data', 'cashier_id'));
                    break;
            case 'verificado':
                $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('people', function($query) use($search){
                                $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                            })
                            ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                        }
                    })
                    ->where('deleted_at', NULL)->where('status', 'verificado')->orderBy('date', 'DESC')->paginate($paginate);
                return view('loans.list', compact('data', 'cashier_id'));
                break;
            case 'aprobado':
                    $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                        ->where(function($query) use ($search){
                            if($search){
                                $query->OrwhereHas('people', function($query) use($search){
                                    $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                                })
                                ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                                ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                            }
                        })
                        ->where('deleted_at', NULL)->where('status', 'aprobado')->orderBy('date', 'DESC')->paginate($paginate);
                    return view('loans.list', compact('data', 'cashier_id'));
                    break;
            case 'pagado':
                $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('people', function($query) use($search){
                                $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                            })
                            ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                        }
                    })
                    ->where('deleted_at', NULL)->where('debt', 0)->orderBy('date', 'DESC')->paginate($paginate);
                return view('loans.list', compact('data', 'cashier_id'));
                break;
            case 'rechazado':
                    $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                        ->where(function($query) use ($search){
                            if($search){
                                $query->OrwhereHas('people', function($query) use($search){
                                    $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                                })
                                ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                                ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                            }
                        })
                        ->where('deleted_at', NULL)->where('status', 'rechazado')->orderBy('date', 'DESC')->paginate($paginate);
                    return view('loans.list', compact('data', 'cashier_id'));
                    break;
            case 'todo':
                    $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                        ->where(function($query) use ($search){
                            if($search){
                                $query->OrwhereHas('people', function($query) use($search){
                                    $query->whereRaw("(ci like '%$search%' or first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                                })
                                ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                                ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                            }
                        })
                        ->where('deleted_at', NULL)->orderBy('date', 'DESC')->paginate($paginate);
                    return view('loans.list', compact('data', 'cashier_id'));
                    break;

        }
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

    public function show($id)
    {
        // return $id;
        $garment = Garment::with(['people', 'doc', 'image'])->where('id', $id)->first();
        // return $garment;


        return view('garment.read', compact('garment'));
    }

    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $imageObj = new FileController;
            $user = Auth::user();
            $agent = $this->agent($user->id);

            $article = Article::with(['model', 'category', 'marca'])->where('deleted_at', null)->first();
            // return $article;

            
            $garment = Garment::create([
                'people_id'=> $request->people_id,
                'article_id'=>$request->article_id,
                'categoryGarment_id'=>$article->categoryGarment_id,
                'brandGarment_id'=>$article->brandGarment_id,
                'modelGarment_id'=>$article->modelGarment_id,

                'article'=>$article->name,
                'categoryGarment' =>$article->category->name,
                'brandGarment' =>$article->marca->name,
                'modelGarment' =>$article->model->name,

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
                'cashierRegister_id'=>$request->cashierRegister_id,
                'status'=>'pendiente',
                'date'=>date('Y-m-d'),

                'register_userId'=>$user->id,
                'register_agentType' =>$user->role->name
            ]);

            $garment->update(['code'=>'P-'.str_pad($garment->id, 5, "0", STR_PAD_LEFT)]);

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
                        'register_agentType' => $agent->role->name
                    ]);
                }
            }


            // return 1;
            DB::commit();
            return redirect()->route('garments.index')->with(['message' => 'Registrado exitosamente exitosamente.', 'alert-type' => 'success']);            
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('garments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
