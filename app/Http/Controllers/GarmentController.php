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
use Illuminate\Support\Carbon;

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
            case 'porentregar':
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
                    ->where('deleted_at', NULL)->where('status', 'aprobado')->orderBy('id', 'DESC')->paginate($paginate);
                    // dump($data);
                return view('garment.list', compact('data', 'cashier_id'));
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


    public function rechazar($id)
    {
        // return $id;
        try {
            $ok= Garment::where('id', $id)->where('deleted_at', null)->where('status', '!=', 'entregado')->first();
            if(!$ok)
            {
                return redirect()->route('garments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
            Garment::where('id', $id)->update([
                'status' => 'rechazado',
            ]);

            return redirect()->route('garments.index')->with(['message' => 'Rechazado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('garments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function destroy($id)
    {
        try {
            $ok= Garment::where('id', $id)->where('deleted_at', null)->where('status', '!=', 'entregado')->first();
            if(!$ok)
            {
                return redirect()->route('garments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
            Garment::where('id', $id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role
            ]);
            return redirect()->route('garments.index')->with(['message' => 'Anulado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('garments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    //Para aprobar un prestamo el gerente
    public function successLoan($id)
    {
        // return $id;
        DB::beginTransaction();
        try {
            $ok = Garment::with(['people'])
                ->where('id', $id)->first();

            // Http::get('https://api.whatsapp.capresi.net/?number=591'.$ok->people->cell_phone.'&message=Hola *'.$ok->people->first_name.' '.$ok->people->last_name1.' '.$ok->people->last_name2.'*.%0A%0A*SU SOLICITUD DE PRESTAMO HA SIDO APROBADA EXITOSAMENTE*%0A%0APase por favor por las oficinas para entregarle su solicitud de prestamos%0A%0AGracias🤝');
            
            // return $loan;
            $ok= Garment::where('id', $id)->where('deleted_at', null)->where('status', 'pendiente')->first();
            if(!$ok)
            {
                return redirect()->route('garments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }

            Garment::where('id', $id)->update([
                'status' => 'aprobado',
                'success_userId' => Auth::user()->id,
                'success_agentType' => $this->agent(Auth::user()->id)->role
            ]);


            DB::commit();
            return redirect()->route('garments.index')->with(['message' => 'Prestamo aprobado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('garments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    // funcion para entregar dinero al beneficiario
    public function moneyDeliver(Request $request, $loan)
    {
        return $loan;
        DB::beginTransaction();
        try {
            $loan = Loan::where('id', $loan)->first();

            if($loan->status== 'entregado')
            {
                return redirect()->route('loans.index')->with(['message' => 'El Prestamo ya fue entregado', 'alert-type' => 'error']);
            }
            // return $loan;
            $loan->update(['cashier_id'=>$request->cashier_id,'delivered_userId'=>Auth::user()->id, 'delivered_agentType' => $this->agent(Auth::user()->id)->role, 'status'=>'entregado', 'delivered'=>'Si', 'dateDelivered'=>Carbon::now()]);

            $movement = CashierMovement::where('cashier_id', $request->cashier_id)->where('deleted_at', null)->get();
            $countM = $movement->count();

            $amountLoan = $loan->amountLoan;

            foreach($movement as $item)
            {
                if($item->balance > 0 && $amountLoan > 0)
                {
                    if($item->balance >= $amountLoan)
                    {
                        $item->decrement('balance', $amountLoan);
                        $amountLoan = 0;
                    }
                    else
                    {
                        $amountLoan = $amountLoan - $item->balance;
                        $item->decrement('balance', $item->balance);
                    }
                }
            }       
            // $movement = CashierMovement::where('cashier_id', $request->cashier_id)->where('deleted_at', null)->first();
            // $movement->decrement('balance', $loan->amountLoan);

            $user = Auth::user();
            $agent = $this->agent($user->id);


            $date = date("d-m-Y",strtotime(date('y-m-d h:i:s')."+ 1 days"));
            $date = Carbon::parse($request->fechass);
            $date = date("Y-m-d", strtotime($date));
            $date = date("d-m-Y",strtotime($date."+ 1 days"));

            // return $loan;
         
            if($loan->typeLoan == 'diario')
            {
                // return 2;
                for($i=1;$i<=$loan->day; $i++)
                {
                    $fecha = Carbon::parse($date);
                    $fecha = $fecha->format("l");
                    // return $fecha;
                    if($fecha == 'Sunday' )
                    {
                        $date = date("Y-m-d", strtotime($date));
                        $date = date("d-m-Y",strtotime($date."+ 1 days"));
                    }
                    $date = date("Y-m-d", strtotime($date));
                    LoanDay::create([
                        'loan_id' => $loan->id,
                        'number' => $i,
                        'debt' => $loan->amountTotal/$loan->day,
                        'amount' => $loan->amountTotal/$loan->day,

                        'register_userId' => $agent->id,
                        'register_agentType' => $agent->role,

                        'date' => $date
                    ]);
                    $date = date("d-m-Y",strtotime($date."+ 1 days"));

                }
            }
            else
            {
                $loanDay = $loan->day;
                $amount = $loan->amountTotal;
                $amountDay = $amount/$loanDay;

                $aux = intval($amountDay);

                $dayT = $aux*($loanDay);
                // return $aux;


                $firstAux = $amount - $dayT;
                // return $first;
                $first = $aux + $firstAux;
                // return $first;
                // return $dayT+$firstAux;

                if($amount != ($dayT+$firstAux))
                {
                    DB::rollBack();
                    return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error en la distribucion.', 'alert-type' => 'error']);
                }


                for($i=1;$i<=$loanDay; $i++)
                {
                    $fecha = Carbon::parse($date);
                    $fecha = $fecha->format("l");
                    // return $fecha;
                    if($fecha == 'Sunday' )
                    {
                        $date = date("Y-m-d", strtotime($date));
                        $date = date("d-m-Y",strtotime($date."+ 1 days"));
                    }
                    $date = date("Y-m-d", strtotime($date));
                    if($i==1)
                    {
                        $debA = $first;
                    }
                    else
                    {
                        $debA = $aux;
                    }

                    LoanDay::create([
                        'loan_id' => $loan->id,
                        'number' => $i,
                        'debt' => $debA,
                        'amount' => $debA,

                        'register_userId' => $agent->id,
                        'register_agentType' => $agent->role,

                        'date' => $date
                    ]);
                    $date = date("d-m-Y",strtotime($date."+ 1 days"));

                }
            }


            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Dinero entregado exitosamente.', 'alert-type' => 'success', 'loan_id' => $loan->id,]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }

    }



}
