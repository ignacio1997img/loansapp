<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\People;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Loan;
use App\Models\LoanDay;
use App\Models\LoanRoute;
use App\Models\LoanRequirement;
use App\Models\User;
use Psy\CodeCleaner\ReturnTypePass;
use Psy\TabCompletion\Matcher\FunctionsMatcher;
use TCG\Voyager\Models\Role;
use App\Models\Route;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

use App\Http\Controllers\FileController;
use App\Models\LoanDayAgent;
use Illuminate\Support\Composer;
use PhpParser\Node\Stmt\TryCatch;

use function PHPUnit\Framework\returnSelf;

class LoanController extends Controller
{
    public function index()
    {
//         $search='ignaci';

//         $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
//             ->where(function($query) use ($search){
//                 if($search){
//                     $query->OrwhereHas('people', function($query) use($search){
//                         $query->whereRaw("(first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
//                     });
//                 }
//             })
//             ->where('deleted_at', NULL)->orderBy('id', 'DESC')->get();

// return $data;


        $collector = User::with(['role' => function($q)
            {
                $q->where('name','cobrador');
            }])
            ->get();

        return view('loans.browse', compact('collector'));
    }

    public function list($search = null){
        $user = Auth::user();
        $paginate = request('paginate') ?? 10;

        $data = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
            ->where(function($query) use ($search){
                if($search){
                    $query->OrwhereHas('people', function($query) use($search){
                        $query->whereRaw("(first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                    })
                    ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1);
                }
            })
            ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);

        return view('loans.list', compact('data'));
    }

    public function create()
    {
        $people = People::where('deleted_at', null)->where('status',1)->where('token','!=', null)->get();

        $routes = Route::where('deleted_at', null)->where('status', 1)->orderBy('name')->get();

        return view('loans.add', compact('people', 'routes'));
    }
    public function ajaxNotPeople($id)
    {
        return People::where('id', '!=', $id)->where('deleted_at', null)->where('status',1)->where('token','!=', null)->get();

    }

    public function createDaily($id)
    {
        // $loan_id = $id;
        $requirement = LoanRequirement::where('loan_id', $id)->first();

        $ok = LoanRequirement::where('loan_id', $id)
            ->where('ci','!=', null)
            ->where('luz','!=', null)
            ->where('croquis','!=', null)
            ->where('business','!=', null)
            ->select('*')
            ->first();
        // return $ok;
        // return $requirement;
        return view('requirement.daily.add', compact('requirement', 'ok'));        
    }

    public function storeRequirement(Request $request, $loan)
    {
        DB::beginTransaction();
        try {
            $imageObj = new FileController;
            $ok = LoanRequirement::where('loan_id', $loan)->first();
            $file = $request->file('ci');
            if($file)
            {                 
                if($file->getClientOriginalExtension()=='pdf')
                {
                    $ci = $imageObj->file($file, $loan, "Loan/requirement/daily/ci");
                }
                else
                {
                    $ci = $imageObj->image($file, $loan, "Loan/requirement/daily/ci");
                }                
                $ok->update(['ci' => $ci]);
            }

            $file = $request->file('luz');
            if($file)
            {               
                if($file->getClientOriginalExtension()=='pdf')
                {
                    $luz = $imageObj->file($file, $loan, "Loan/requirement/daily/luz");                    
                }
                else
                {
                    $luz = $imageObj->image($file, $loan, "Loan/requirement/daily/luz");
                }
                $ok->update(['luz' => $luz]);
            }

            $file = $request->file('croquis');
            if($file)
            {         
                if($file->getClientOriginalExtension()=='pdf')
                {
                    $croquis = $imageObj->file($file, $loan, "Loan/requirement/daily/croquis");
                }
                else
                {
                    $croquis = $imageObj->image($file, $loan, "Loan/requirement/daily/croquis");
                }

                $ok->update(['croquis' => $croquis]);
            }

            $file = $request->file('business');
            if($file)
            {                        
                if($file->getClientOriginalExtension()=='pdf')
                {
                    $business = $imageObj->file($file, $loan, "Loan/requirement/daily/business");
                }
                else
                {
                    $business = $imageObj->image($file, $loan, "Loan/requirement/daily/business");
                }

                $ok->update(['business' => $business]);
            }
            DB::commit();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Requisitos registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        
    }

    public function deleteRequirement($loan, $col)
    {
        DB::beginTransaction();
        try 
        {
            $requirement = LoanRequirement::where('loan_id', $loan)->first();

            if($col==0)
            {
                $requirement->update(['ci'=>null]);
            }
            if($col==1)
            {
                $requirement->update(['luz'=>null]);
            }
            if($col==2)
            {
                $requirement->update(['croquis'=>null]);
            }
            if($col==3)
            {
                $requirement->update(['business'=>null]);
            }
            DB::commit();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Requisitos eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function successRequirement($loan)
    {

        $url="http://api.trabajostop.com/?number=59163286317&message=hola"; //a qui pones tu url externa
        echo "<a href='$url'>caca</a>";

        DB::beginTransaction();
        try {
            LoanRequirement::where('loan_id',$loan)
                ->update(['status'=>1,
                        'success_userId' => Auth::user()->id,
                        'success_agentType' => $this->agent(Auth::user()->id)->role
                        ]);

            
            DB::commit();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Requisitos aprobado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans-requirement-daily.create', ['loan'=>$loan])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        $user = Auth::user();
        $agent = $this->agent($user->id);

        try {
            $loan = Loan::create([
                        'people_id' => $request->people_id,
                        'guarantor_id' => $request->guarantor_id?$request->guarantor_id:null,
                        'date' => $request->date,
                        'day' => $request->day,
                        'observation' => $request->observation,

                        'typeLoan' => 'Diario',

                        'porcentage' => $request->porcentage,
                        'amountLoan' =>  $request->amountLoan,
                        'amountPorcentage' => $request->amountPorcentage,
                        
                        'debt' => $request->amountTotal,
                        'amountTotal' => $request->amountTotal,

                        'register_userId' => $agent->id,
                        'register_agentType' => $agent->role,
                        'status' => 2
            ]);
            LoanRoute::create([
                'loan_id' => $loan->id,

                'route_id' => $request->route_id,

                'observation' => 'Primer ruta',

                'register_userId' => $agent->id,
                'register_agentType' => $agent->role
            ]);

            LoanRequirement::create([
                'loan_id' => $loan->id,

                'register_userId' => $agent->id,
                'register_agentType' => $agent->role
            ]);

            // return 


            // $date = date("d-m-Y",strtotime($request->date."+ 1 days"));
            // for($i=1;$i<=$request->day; $i++)
            // {
            //     $fecha = Carbon::parse($date);
            //     $fecha = $fecha->format("l");
            //     if($fecha == 'Sunday')
            //     {
            //         $date = date("Y-m-d", strtotime($date));
            //         $date = date("d-m-Y",strtotime($date."+ 1 days"));
            //     }
            //     $date = date("Y-m-d", strtotime($date));
            //     LoanDay::create([
            //         'loan_id' => $loan->id,
            //         'number' => $i,
            //         'debt' => $request->amountDay,
            //         'amount' => $request->amountDay,

            //         'register_userId' => $agent->id,
            //         'register_agentType' => $agent->role,

            //         'date' => $date
            //     ]);
            //     $date = date("d-m-Y",strtotime($date."+ 1 days"));

            // }
            // return 1;

            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Prestamos registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function show($id)
    {
        // $loan = Loan::with(['loanDay', 'loanAgent'])
        //     ->where('deleted_at', null)->get();
        
        // return $loan;

        
        return 1;
        return view('loans.read');
    }

    public function printCalendar($id)
    {
        $loan = Loan::with(['people', 'loanDay'])
            ->where('deleted_at', null)->where('id', $id)->first();
        // return $loan;
        return view('loans.print-calendar', compact('loan'));
    }

    public function destroy($id)
    {
        try {
            Loan::where('id', $id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role
            ]);

            LoanDay::where('loan_id', $id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role
            ]);

            LoanRoute::where('loan_id', $id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role
            ]);
            
            LoanRequirement::where('loan_id', $id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_userId' => Auth::user()->id,
                'deleted_agentType' => $this->agent(Auth::user()->id)->role
            ]);

            return redirect()->route('loans.index')->with(['message' => 'Anulado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function updateAgent(Request $request, $loan)
    {
        // return $request;
        DB::beginTransaction();
        try {

            // $agent = LoanAgent::where('loan_id', $loan)
            //             ->where('deleted_at', null)
            //             ->first();
            // $agent->update([
            //     'status' => 0
            // ]);

            // LoanAgent::create([
            //     'loan_id' => $loan,

            //     'agent_id' => $request->loan_id,
            //     'agentType' => $this->agent($request->loan_id)->role,

            //     'observation' => $request->observation,

            //     'register_userId' => Auth::user()->id,
            //     'register_agentType' => $this->agent(Auth::user()->id)->role
            // ]);

            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Cabrador Cambiado.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }    

    public function successLoan($loan)
    {
        DB::beginTransaction();
        try {
            $ok = Loan::where('id', $loan)->first();
            Http::get('http://api.trabajostop.com/?number=591'.$ok->people->cell_phone.'&message=hola *'.$ok->people->first_name.' '.$ok->people->last_name1.' '.$ok->people->last_name2.'*.%0A%0A*SU SOLICITUD DE PRESTAMO HA SIDO APROBADA EXITOSAMENTE*%0A%0APase por favor por las oficinas para entregarle su solicitud de prestamos%0A%0AGracias🤝😊');
            // return $loan;
            Loan::where('id', $loan)->update([
                'status' => 1,
                'success_userId' => Auth::user()->id,
                'success_agentType' => $this->agent(Auth::user()->id)->role
            ]);
        


            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Prestamo aprobado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    // funcion para entregar dinero al beneficiario
    public function moneyDeliver($loan)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $agent = $this->agent($user->id);
            $loan = Loan::where('id', $loan)->first();
            $loan->update(['delivered'=>'Si', 'dateDelivered'=>Carbon::now()]);

            // return $loan;



            // $date = date("d-m-Y",strtotime(Carbon::now()."+ 1 days"));
            $date = date("d-m-Y",strtotime(date('y-m-d h:i:s')."+ 1 days"));

         
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


            DB::commit();
            return redirect()->route('loans.index')->with(['message' => 'Dinero entregado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }

    }

    public function printContracDaily($loan)
    {
        // return 1;
        $loan = Loan::where('id', $loan)->first();
        return view('loans.print.loanDaily', compact('loan'));
    }




    // para ver el prestamos y poder abonar o pagar el dinero
    public function dailyMoney($loan)
    {
        $id = $loan;
        $loan = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people', 'guarantor'])
            ->where('deleted_at', null)->where('id',$id)->first();

        $loanday = LoanDay::where('loan_id', $id)->where('deleted_at', null)->orderBy('number', 'ASC')->get();
        // return $loanday;
        
        $route = LoanRoute::with(['route'])->where('loan_id', $id)->where('status', 1)->where('deleted_at', null)->first();

        // return $id;
        $register = Auth::user();
        // return $register->role->name;
        $date = date('Y-m-d');
        // return $loanday;


        return view('loans.add-money', compact('loan', 'route', 'loanday', 'register', 'date'));

    }
// funcion para guardar el dinero diario en ncada prestamos
    public function dailyMoneyStore(Request $request)
    {
        $code = Transaction::all()->count();

        $loan =Loan::where('id', $request->loan_id)->first();
        if($request->amount > $loan->debt)
        {
            return redirect()->route('loans-daily.money', ['loan' => $request->loan_id])->with(['message' => 'Monto Incorrecto.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {
            $transaction = Transaction::create(['transaction'=>$code+1]);
            $loan->update(['transaction_id'=>$transaction->transaction]);
            $amount = $request->amount;
            
            $ok = LoanDay::where('loan_id', $request->loan_id)->where('date', $request->date)->where('debt', '>', 0)->first();
            // return $request;
            if($ok)
            {
                // return "fecha actual";
                $debt = $ok->debt;
                if($amount > $debt)
                {
                    $amount = $amount-$debt;
                }
                else
                {                    
                    $debt = $amount;
                    $amount = 0;
                }
                LoanDay::where('id', $ok->id)->decrement('debt', $debt);

                LoanDayAgent::create([
                    'loanDay_id' => $ok->id,
                    'transaction_id'=>$transaction->id,
                    'amount' => $debt,
                    'agent_id' => $request->agent_id,
                    'agentType' => $this->agent($request->agent_id)->role
                ]);
                Loan::where('id', $request->loan_id)->decrement('debt', $debt);

            }

            if($amount>0)
            {
                // return $amount;
                $day = LoanDay::where('loan_id', $request->loan_id)->where('debt', '>', 0)->orderBy('number', 'ASC')->get();
                // return $day;
                foreach($day as $item)
                {
                    $debt = $item->debt;
                    if($amount > $debt)
                    {
                        $amount = $amount-$debt;
                    }
                    else
                    {                    
                        $debt = $amount;
                        $amount = 0;
                    }

                    LoanDay::where('id', $item->id)->decrement('debt', $debt);

                    LoanDayAgent::create([
                        'loanDay_id' => $item->id,
                        'transaction_id'=>$transaction->id,
                        'amount' => $debt,
                        'agent_id' => $request->agent_id,
                        'agentType' => $this->agent($request->agent_id)->role
                    ]);
                    Loan::where('id', $request->loan_id)->decrement('debt', $debt);
                    if($amount<=1)
                    {
                        break;
                    }

                }

            }
            // return 1;



            // LoanDayAgent::create([
            //     'loanDay_id' => $request->day_id,
            //     'amount' => $request->amount,
            //     'agent_id' => $request->agent_id,
            //     'agentType' => $this->agent($request->agent_id)->role
            // ]);
            // Loan::where('id', $request->loan_id)->decrement('debt', $request->amount);
            // LoanDay::where('id', $request->day_id)->decrement('debt', $request->amount);
            DB::commit();
            return redirect()->route('loans-daily.money', ['loan' => $request->loan_id])->with(['message' => 'Prestamo aprobado exitosamente.', 'alert-type' => 'success', 'loan_id' => $loan->id, 'transaction_id'=>$transaction->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans-daily.money', ['loan' => $request->loan_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }        
    }

    public function printDailyMoney($loan_id, $transaction_id)
    {
        $transaction_id =$transaction_id;
        $loan = Loan::where('id', $loan_id)->first();

        $loanDayAgent = DB::table('loan_days as ld')
            ->join('loan_day_agents as la', 'la.loanDay_id', 'ld.id')
            ->join('users as u', 'u.id', 'la.agent_id')
            ->join('transactions as t', 't.id', 'la.transaction_id')
            ->where('ld.loan_id', $loan_id)
            ->where('t.id', $transaction_id)
            ->select('ld.id as loanDay', 'ld.date', 'la.amount', 'u.name', 'la.agentType', 'la.id as loanAgent')
            ->get();

        // return $loanDayAgent;

        
        return view('loansPrint.print-dailyMoneyCash', compact('loan', 'transaction_id', 'loanDayAgent'));
    }


}
