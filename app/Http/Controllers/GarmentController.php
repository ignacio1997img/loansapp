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
use App\Models\BrandGarment;
use App\Models\GarmentsDoc;
use App\Models\GarmentsImage;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Carbon;
use App\Models\CashierMovement;
use App\Models\CategoryGarment;
use App\Models\GarmentsArticle;
use App\Models\GarmentsArticlesDetail;
use App\Models\GarmentsMonth;
use App\Models\GarmentsMonthAgent;
use App\Models\Jewel;
use App\Models\ModelGarment;
use App\Models\Quilate;
use App\Models\Ticket;
use App\Models\Transaction;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;
use ReturnTypeWillChange;
use Illuminate\Support\Facades\Http;
use Psy\TabCompletion\Matcher\FunctionsMatcher;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use function PHPUnit\Framework\returnSelf;

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
        $type = $type;
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
                return view('garment.list', compact('data', 'cashier_id', 'type'));
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
                return view('garment.list', compact('data', 'cashier_id', 'type'));
                break;

            case 'enpago':
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
                    ->where('deleted_at', NULL)->where('status', 'entregado')->orderBy('id', 'DESC')->paginate($paginate);
                    // dump($data);
                return view('garment.list', compact('data', 'cashier_id', 'type'));
                break;

            case 'recogido':
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
                    ->where('deleted_at', NULL)->where('status', 'recogido')->orderBy('id', 'DESC')->paginate($paginate);
                    // dump($data);
                return view('garment.list', compact('data', 'cashier_id', 'type'));
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

        $category = CategoryGarment::with('article')->where('deleted_at', null)->get();

        $article = Article::where('deleted_at', null)->get();

        $modelo = ModelGarment::where('deleted_at', null)->get();
        $marca = BrandGarment::where('deleted_at', null)->get();
        $typeMetal = Jewel::with(['quilate'=>function($q){$q->where('deleted_at', null)->where('status', 1);}])->where('deleted_at', null)->get();
        $quilate = Quilate::where('status', 1)->where('deleted_at', null)->get();
        // return $joya;

        return view('garment.add', compact('cashier', 'category', 'article', 'modelo', 'marca', 'typeMetal', 'quilate'));
    }

    public function ajaxCategory()
    {
        $q = request('q');
        $data = CategoryGarment::whereRaw($q ? '(name like "%'.$q.'%" )' : 1)
            ->where('status', 1)
            ->where('deleted_at', null)->get();

        return response()->json($data);
    }

    public function ajaxQuilate($id)
    {
        return Quilate::where('deleted_at', null)->where('jewel_id', $id)->get();
    }

    public function show($id)
    {
        // return $id;
        $garment = Garment::with(['people', 'months', 'garmentArticle', 'garmentArticle.garmentArticleDetail'])->where('id', $id)->first();

        $transaction = DB::table('garments as g')
            ->join('garments_month_agents as gma', 'gma.garment_id', 'g.id')
            ->join('transactions as t', 't.id', 'gma.transaction_id')
            ->join('users as u', 'u.id', 'gma.agent_id')
            ->where('g.id', $garment->id)
            ->select('t.id as transaction_id', 'g.id as garment', 'u.name', 'gma.agentType', 't.created_at', DB::raw('SUM(gma.amount)as amount'), 't.deleted_at')
            ->orderBy('t.created_at', 'ASC')
            ->groupBy('t.id')
            ->get();

        // return $transaction;
        // return $garment;


        return view('garment.read', compact('garment', 'transaction'));
    }

    public function store(Request $request)
    {
        // return $request['value1'];
        // return $request;
        if($request->amountTotal<=0)
        {
            return redirect()->route('garments.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        
        DB::beginTransaction();
        try {
            $imageObj = new FileController;
            $user = Auth::user();
            $agent = $this->agent($user->id);

            // $article = Article::with(['model', 'category', 'marca'])->where('id', $request->article_id)->where('deleted_at', null)->first();
            // return $article;


            $auxTotal = 0;

            
            $garment = Garment::create([
                'people_id'=> $request->people_id,

                'type'=>$request->type,
                'month'=>$request->month,
                'monthCant'=>1,

                'amountLoan'=>$request->amountTotal,
                'amountTotal'=>$request->amountTotal+$request->amountPorcentage,

                'priceDollar'=>$request->priceDollar,
                'amountLoanDollar'=>$request->amountLoanDollar,
                'porcentage'=>$request->porcentage,
                'amountPorcentage'=>$request->amountPorcentage,


                // 'observation'=>$request->observation,
                'cashierRegister_id'=>$request->cashierRegister_id,
                'status'=>'pendiente',
                'date'=>date('Y-m-d'),

                'register_userId'=>$user->id,
                'register_agentType' =>$user->role->name
            ]);

            $garment->update(['code'=>'P-'.str_pad($garment->id, 5, "0", STR_PAD_LEFT)]);

            // if()

            // return $request;
            for ($i=0; $i < count($request->category); $i++) { 
                $category = $request->category[$i];

                for($a=0; $a<count($request['article'.$category]); $a++)
                {
                    $getCategory = CategoryGarment::where('id', $category)->first();
                    // return $request['subtotal'.$category][0];
                    $article = GarmentsArticle::create([
                        'garment_id'=>$garment->id,
                        'category_id'=>$getCategory->id,
                        'category'=>$getCategory->name,
                        'amountSubTotal'=>$request['subtotal'.$category][$a],
    
                    ]);

                    // return $request['subtotal'.$category][0];

                    $getArticle = Article::where('id', $request['article'.$category][$a])->first();

                    GarmentsArticlesDetail::create([
                        'garmentArticle_id'=>$article->id,
                        'value' => $getArticle->name,
                        'title' => 'Detalle',
                        // 'foreign_id' => $request['value'.$request->group[$i]][$x]?$request['name'.$request->group[$i]][$x]:null,
                        'foreign_id' => $request['article'.$category][$a],
                        'typeForeign' => 'articles',
                    ]);


                    $kilate = Quilate::where('id', $request['kilate'.$category][$a])->first();

                    GarmentsArticlesDetail::create([
                        'garmentArticle_id'=>$article->id,
                        'value' => $kilate->name,
                        'title' => 'Kilate',

                        'foreign_id' => $request['kilate'.$category][$a],
                        'typeForeign' => 'Kilate',
                    ]);


                    GarmentsArticlesDetail::create([
                        'garmentArticle_id'=>$article->id,
                        'value' => $request['pesobruto'.$category][$a],
                        'title' => 'Peso Bruto',
                    ]);

                    GarmentsArticlesDetail::create([
                        'garmentArticle_id'=>$article->id,
                        'value' => $request['pesopiedra'.$category][$a],
                        'title' => 'Peso Piedra',
                    ]);
                    GarmentsArticlesDetail::create([
                        'garmentArticle_id'=>$article->id,
                        'value' => $request['pesoneto'.$category][$a],
                        'title' => 'Peso Neto',
                    ]);
                }



                // for($x = 0; $x < $countDetail; $x++)
                // {

                //     $auxName = $request['name'.$request->group[$i]][$x];
                //     // return $auxName;
                //     if($request['value'.$request->group[$i]][$x] == 'modelo_list')
                //     {
                //         $auxName = ModelGarment::where('id', $request['name'.$request->group[$i]][$x])->first()->name;
                //     }
                //     if($request['value'.$request->group[$i]][$x] == 'marca_list')
                //     {
                //         $auxName = BrandGarment::where('id', $request['name'.$request->group[$i]][$x])->first()->name;
                //     }

                //     if($request['value'.$request->group[$i]][$x] == 'joya_list')
                //     {
                //         $auxName = Jewel::where('id', $request['name'.$request->group[$i]][$x])->first()->name;
                //     }

                //     if($request['value'.$request->group[$i]][$x] == 'quilate')
                //     {
                //         $auxName = Quilate::where('id', $request['name'.$request->group[$i]][$x])->first()->name;
                //     }

                //     GarmentsArticlesDetail::create([
                //         'garmentArticle_id'=>$article->id,
                //         'articleDeveloper_id' => $request['developer'.$request->group[$i]][$x],
                //         'value' => $auxName,
                //         'title' => $request['title'.$request->group[$i]][$x],

                //         'foreign_id' => $request['value'.$request->group[$i]][$x]?$request['name'.$request->group[$i]][$x]:null,
                //         'typeForeign' => $request['value'.$request->group[$i]][$x],


                //     ]);
                // }
            }

            // for ($i=0; $i < count($request->group); $i++) { 

            //     $art = Article::where('id', $request->article[$i])->first();
            //     // return $art;
            //     $article = GarmentsArticle::create([
            //         'garment_id'=>$garment->id,
            //         'article_id'=>$art->id,
            //         'article'=>$art->name,
            //         'amountLoan'=>$request['amountLoan'.$request->group[$i]],
            //         'amountCant'=>$request['price'.$request->group[$i]],
            //         'amountSubTotal'=>$request['subAmountLoan'.$request->group[$i]],

            //     ]);

            //     $auxTotal = $auxTotal + $request['subAmountLoan'.$request->group[$i]];

            //     $countDetail = count($request['name'.$request->group[$i]]);

            //     // return $request['name'.$request->group[$i]][0];
            //     // return $request['value1'.$request->group[$i]][0];
            //     // return $request['developer'.$request->group[$i][$x]];

            //     for($x = 0; $x < $countDetail; $x++)
            //     {

            //         $auxName = $request['name'.$request->group[$i]][$x];
            //         // return $auxName;
            //         if($request['value'.$request->group[$i]][$x] == 'modelo_list')
            //         {
            //             $auxName = ModelGarment::where('id', $request['name'.$request->group[$i]][$x])->first()->name;
            //         }
            //         if($request['value'.$request->group[$i]][$x] == 'marca_list')
            //         {
            //             $auxName = BrandGarment::where('id', $request['name'.$request->group[$i]][$x])->first()->name;
            //         }

            //         if($request['value'.$request->group[$i]][$x] == 'joya_list')
            //         {
            //             $auxName = Jewel::where('id', $request['name'.$request->group[$i]][$x])->first()->name;
            //         }

            //         if($request['value'.$request->group[$i]][$x] == 'quilate')
            //         {
            //             $auxName = Quilate::where('id', $request['name'.$request->group[$i]][$x])->first()->name;
            //         }

            //         GarmentsArticlesDetail::create([
            //             'garmentArticle_id'=>$article->id,
            //             'articleDeveloper_id' => $request['developer'.$request->group[$i]][$x],
            //             'value' => $auxName,
            //             'title' => $request['title'.$request->group[$i]][$x],

            //             'foreign_id' => $request['value'.$request->group[$i]][$x]?$request['name'.$request->group[$i]][$x]:null,
            //             'typeForeign' => $request['value'.$request->group[$i]][$x],


            //         ]);
            //     }
            // }

            // return $request;
            DB::commit();
            return redirect()->route('garments.index')->with(['message' => 'Registrado exitosamente exitosamente.', 'alert-type' => 'success']);            
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
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

            Http::get('https://api.whatsapp.capresi.net/?number=591'.$ok->people->cell_phone.'&message=Hola *'.$ok->people->first_name.' '.$ok->people->last_name1.' '.$ok->people->last_name2.'*.%0A%0A*SU SOLICITUD DE PRESTAMO HA SIDO APROBADA EXITOSAMENTE*%0A%0APase por favor por las oficinas para entregarle su solicitud de prestamos%0A%0AGracias🤝');



            DB::commit();
            return redirect()->route('garments.index')->with(['message' => 'Prestamo aprobado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('garments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    // funcion para entregar dinero al beneficiario
    public function moneyDeliver(Request $request, $garment)
    {
        // return $garment;
        DB::beginTransaction();
        try {
            $garment = Garment::where('id', $garment)->where('status', 'aprobado')->where('deleted_at', null)->first();

            if(!$garment)
            {
                return redirect()->route('garments.index')->with(['message' => 'El Prestamo ya fue entregado', 'alert-type' => 'error']);
            }
            // return $loan;
            $garment->update(['cashier_id'=>$request->cashier_id,'delivered_userId'=>Auth::user()->id, 'delivered_agentType' => $this->agent(Auth::user()->id)->role, 'status'=>'entregado', 'delivered'=>'Si', 'dateDelivered'=>Carbon::now()]);

            $movement = CashierMovement::where('cashier_id', $request->cashier_id)->where('deleted_at', null)->get();
            // return $movement;
            $countM = $movement->count();

            $balance = $movement->sum('balance');
            if($garment->amountLoan > $balance)
            {
                return redirect()->route('garments.index')->with(['message' => 'Error', 'alert-type' => 'error']);
            }
            // return $balance;

            $amountLoan = $garment->amountLoan;

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

            $user = Auth::user();
            $agent = $this->agent($user->id);

            //Para saber cuantos dias quedan
            $date = date("Y-m-d",strtotime($request->fechass));
            // $date = date("Y-m-d",strtotime(date("2023-01-29")));



            $diaInicio = date("d",strtotime($date));

            $mesInicio = date("Y-m",strtotime($date));
            $mesFin = date("Y-m-d",strtotime($mesInicio."+ 1 month"));
            // return $mesInicio;


            $anioActual = date("Y",strtotime($date));
            $mesActual = date("m",strtotime($date));
            $cantidadDias = cal_days_in_month(CAL_GREGORIAN, $mesActual, $anioActual);

            $anioSig = date("Y",strtotime($mesFin));
            $mesSig = date("m",strtotime($mesFin));
            $cantidadDiasFin = cal_days_in_month(CAL_GREGORIAN, $mesSig, $anioSig);

            if($diaInicio <= $cantidadDiasFin)
            {
                $fechaFin = $anioSig.'-'.$mesSig.'-'.$diaInicio;
            }
            else
            {
                $fechaFin = $anioSig.'-'.$mesSig.'-'.$cantidadDiasFin;
            }

            if($diaInicio == 31 && $cantidadDiasFin == 31)
            {
                $fechaFin = $anioSig.'-'.$mesSig.'-30';
            }

            GarmentsMonth::create([
                'garment_id'=>$garment->id,
                'start'=>$date,
                'finish'=>$fechaFin,
                'amount'=>$garment->amountPorcentage,
                'status'=>'pendiente'
            ]);

            DB::commit();
            return redirect()->route('garments.index')->with(['message' => 'Dinero entregado exitosamente.', 'alert-type' => 'success', 'garment_id' => $garment->id,]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('garments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }

    }
    public function printContracDaily($garment)
    {
        // return 1;
        // return $garment;
        $garment = Garment::with(['people', 'garmentArticle', 'garmentArticle.garmentArticleDetail'])->where('id', $garment)->first();
        if($garment->type == 'compacto')
        {

            return view('garment.contractCompact', compact('garment'));
        }
        else
        {
            return view('garment.contractPrivate', compact('garment'));
        }
      
    }


    // Funcion  para imprimir el comprobante de pago al momento que se le entrega el prestamo al cliente o beneficiario
    public function printLoanVoucher($garment_id)
    {
        $garment = Garment::with(['people'])
        ->where('id', $garment_id)->first();
        // return $garment;
        return view('garment.voucher', compact('garment')) ;
    }

    public function printGarmentTickets($garment_id)
    {
        $garment = Garment::with(['people'])
        ->where('id', $garment_id)->first();

        $ticket = Ticket::create(['foreing'=>$garment->id,'type'=>'garment', 'registerUser_id'=>Auth::user()->id]);
        $ticket->update(['number'=>str_pad($ticket->id, 6, "0", STR_PAD_LEFT)]);
        // return $garment;
        return view('garment.tickets', compact('garment', 'ticket')) ;
    }



    // Para Pagar cada mes de atrazo
    public function paymentMonth(Request $request, $month)
    {
        DB::beginTransaction();
        try {

            $user = Auth::user();
            $garment = Garment::where('id', $request->garment_id)->where('status', 'entregado')->where('deleted_at', null)->first();
            
            $month = GarmentsMonth::where('garment_id', $request->garment_id)->where('id', $month)->where('status', 'pendiente')->first();

            if(!$month)
            {
                return redirect()->route('garments.show', ['garment' => $request->garment_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }

            $cashier = Cashier::with(['movements' => function($q){
                $q->where('deleted_at', NULL);
                }])
                ->where('user_id', $user->id)
                ->where('status', 'abierta')
                ->where('deleted_at', NULL)->first();

            if(!$cashier)
            {
                return redirect()->route('garments.show', ['garment' => $request->garment_id])->with(['message' => 'Error, La caja no se encuentra abierta.', 'alert-type' => 'error']);
            }

            if($request->qr!='Qr')
            {
                CashierMovement::where('cashier_id', $cashier->id)->where('deleted_at', null)->first()->increment('balance', $month->amount);
            }            
            $code = Transaction::all()->max('id');
            $code = $code?$code:0;
            $transaction = Transaction::create(['type'=>$request->qr, 'transaction'=>$code+1]);


            GarmentsMonthAgent::create([
                'garmentMonth_id' => $month->id,
                'cashier_id' => $cashier->id, 
                'type'=>$request->qr,
                'transaction_id'=>$transaction->id,
                'amount' => $month->amount,
                'agent_id' => $user->id,
                'agentType' => $this->agent($user->id)->role,
                'typeGarment'=>'month',
                'garment_id'=>$garment->id
            ]);

            $month->update(['status'=>'pagado']);
            $monthCant = GarmentsMonth::where('garment_id', $garment->id)->where('deleted_at', null)->where('status', 'pendiente')->get();

            $garment->update(['monthCant'=>$monthCant->count()]);


            $loanDayAgent = GarmentsMonthAgent::with(['transaction', 'garment', 'garmentMonth', 'agent'])
            ->where('garment_id', $garment->id)
            ->where('transaction_id', $transaction->id)
            ->get();
        
            
            $cadena = '';
            
            $cant = count($loanDayAgent);
            $i=1;
            foreach($loanDayAgent as $item)
            {
                $cadena=$cadena.'    '.Carbon::parse($item->garmentMonth->start)->format('d/m/Y').' - '.Carbon::parse($item->garmentMonth->finish)->format('d/m/Y').'     '.$item->amount.($i!=$cant?'%0A':'');
                $i++;
            }


            Http::get('https://api.whatsapp.capresi.net/?number=59167285914&message=
            *COMPROBANTE DE PAGO*
    
    CODIGO: '.$garment->code.'
    FECHA: '.Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s').'
    CI: '.$garment->people->ci.'
    BENEFICIARIO: '.$garment->people->last_name1.' '.$garment->people->last_name2.' '.$garment->people->first_name.'
    
                  *DETALLE DEL PAGO*
    *DETALLES*                                | *TOTAL*
    ____________________________________%0A'.
        $cadena.'
    ____________________________________
    TOTAL (BS)                              | '.number_format($loanDayAgent->SUM('amount'),2).'
                
                    *ATENDIDO POR*
    '.strtoupper($loanDayAgent[0]->agentType).':        '.strtoupper($loanDayAgent[0]->agent->name).'
    COD TRANS:      '.$transaction->id.'
    
                
    LOANSAPP V1');


            // return $month;

            // return 1;
            DB::commit();
            return redirect()->route('garments.show', ['garment' => $request->garment_id])->with(['message' => 'Mes Pagado exitosamente.', 'alert-type' => 'success', 'garment_id' => $garment->id, 'transaction_id'=>$transaction->id]);

        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('garments.show', ['garment' => $request->garment_id])->with(['message' => 'Error, La caja no se encuentra abierta.', 'alert-type' => 'error']);
        }
    }


    // public function paymentMonthAll()
    public function paymentMonthAll(Request $request, $garment_id)
    {
        // return $request;    
        // return $garment_id;
        DB::beginTransaction();
        try {

            $user = Auth::user();
            $garment = Garment::where('id', $garment_id)->where('status', 'entregado')->where('deleted_at', null)->first();
            // return $garment;

            $cantMonth = count($request->months);
            for($i=0; $i<$cantMonth;$i++)
            {
                $month = GarmentsMonth::where('garment_id', $garment_id)->where('id', $request->months[$i])->where('status', 'pendiente')->first();
                // return $month;
                if(!$month)
                {
                    return redirect()->route('garments.show', ['garment' => $garment_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
                }
            }


            $cashier = Cashier::with(['movements' => function($q){
                $q->where('deleted_at', NULL);
                }])
                ->where('user_id', $user->id)
                ->where('status', 'abierta')
                ->where('deleted_at', NULL)->first();

            if(!$cashier)
            {
                return redirect()->route('garments.show', ['garment' => $garment_id])->with(['message' => 'Error, La caja no se encuentra abierta.', 'alert-type' => 'error']);
            }
            // return $request;


            $monto =0;
            $code = Transaction::all()->max('id');
            $code = $code?$code:0;
            $transaction = Transaction::create(['type'=>$request->qr, 'transaction'=>$code+1]);

            for($i=0; $i<$cantMonth;$i++)
            {
                $monto = $monto + $request->amount[$i];
                
                
                if($request->qr!='Qr')
                {
                    CashierMovement::where('cashier_id', $cashier->id)->where('deleted_at', null)->first()->increment('balance', $request->amount[$i]);
                }

                $month = GarmentsMonth::where('garment_id', $garment_id)->where('id', $request->months[$i])->where('status', 'pendiente')->first();


                GarmentsMonthAgent::create([
                    'garmentMonth_id' => $request->months[$i],
                    'cashier_id' => $cashier->id, 
                    'type'=>$request->qr,
                    'transaction_id'=>$transaction->id,
                    'amount' => $request->amount[$i],
                    'agent_id' => $user->id,
                    'agentType' => $this->agent($user->id)->role,
                    'typeGarment'=>'month',
                    'garment_id'=>$garment->id
                ]);

                $month->update(['status'=>'pagado']);
                
            }

            if($request->qr!='Qr')
            {
                CashierMovement::where('cashier_id', $cashier->id)->where('deleted_at', null)->first()->increment('balance', $request->amountLoan);
            }

            GarmentsMonthAgent::create([
                // 'garmentMonth_id' => $request->months[$i],
                'cashier_id' => $cashier->id, 
                'type'=>$request->qr,
                'transaction_id'=>$transaction->id,
                'amount' => $request->amountLoan,
                'agent_id' => $user->id,
                'agentType' => $this->agent($user->id)->role,
                'typeGarment'=>'garment',
                'garment_id'=>$garment->id
            ]);

            $monthCant = GarmentsMonth::where('garment_id', $garment->id)->where('deleted_at', null)->where('status', 'pendiente')->get();
            // return $monthCant;

            $garment->update(['monthCant'=>$monthCant->count(), 'status'=>'recogido']);
            


            $loanDayAgent = GarmentsMonthAgent::with(['transaction', 'garment', 'garmentMonth', 'agent'])
            ->where('garment_id', $garment->id)
            ->where('transaction_id', $transaction->id)
            ->get();
            // return 1;
            // return $loanDayAgent;
        
            
            $cadena = '';
            
            $cant = count($loanDayAgent);
            $i=1;
            foreach($loanDayAgent as $item)
            {
                if($item->garmentMonth_id)
                {
                    $cadena=$cadena.'    '.Carbon::parse($item->garmentMonth->start)->format('d/m/Y').' - '.Carbon::parse($item->garmentMonth->finish)->format('d/m/Y').'     '.$item->amount.($i!=$cant?'%0A':'');
                }
                else
                {
                    $cadena=$cadena.'    Recojo del Artículo/Prenda   '.$item->amount.($i!=$cant?'%0A':'');
                }         
                $i++;
            }


            Http::get('https://api.whatsapp.capresi.net/?number=59167285914&message=
            *COMPROBANTE DE PAGO*
    
    CODIGO: '.$garment->code.'
    FECHA: '.Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s').'
    CI: '.$garment->people->ci.'
    BENEFICIARIO: '.$garment->people->last_name1.' '.$garment->people->last_name2.' '.$garment->people->first_name.'
    
                  *DETALLE DEL PAGO*
    *DETALLES*                                | *TOTAL*
    ____________________________________%0A'.
        $cadena.'
    ____________________________________
    TOTAL (BS)                              | '.number_format($loanDayAgent->SUM('amount'),2).'
                
                    *ATENDIDO POR*
    '.strtoupper($loanDayAgent[0]->agentType).':        '.strtoupper($loanDayAgent[0]->agent->name).'
    COD TRANS:      '.$transaction->id.'
    
                
    LOANSAPP V1');


            // return $month;

            // return 1;
            DB::commit();
            return redirect()->route('garments.show', ['garment' => $garment_id])->with(['message' => 'Pagado exitosamente.', 'alert-type' => 'success', 'garment_id' => $garment->id, 'transaction_id'=>$transaction->id]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('garments.show', ['garment' => $garment_id])->with(['message' => 'Error, La caja no se encuentra abierta.', 'alert-type' => 'error']);
        }
    }


    public function printDailyMoney($garment_id, $transaction_id)
    {
        $garment = Garment::where('id', $garment_id)->first();
        $loanDayAgent = GarmentsMonthAgent::with(['transaction', 'garment', 'garmentMonth', 'agent'])
            ->where('garment_id', $garment_id)
            ->where('transaction_id', $transaction_id)
            ->get();
        
        $transaction = Transaction::find($transaction_id);
        
        return view('garment.transaction.print-dailyMoneyCash', compact('garment', 'transaction', 'loanDayAgent'));
    }

    public function ajaxListMonthDebt($garment_id)
    {
        $garment = Garment::with(['months'=>function($q){
                $q->where('status', 'pendiente');
            }])
            ->where('id', $garment_id)->first();
        return $garment;
    }



}
