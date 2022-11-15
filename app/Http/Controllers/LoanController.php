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

    public function moneyDeliver($loan)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $agent = $this->agent($user->id);
            $loan = Loan::where('id', $loan)->first();
            $loan->update(['delivered'=>'Si', 'dateDelivered'=>Carbon::now()]);

            // return Carbon::now();



            $date = date("d-m-Y",strtotime(Carbon::now()."+ 1 days"));
            // return $date;
            for($i=1;$i<=$loan->day; $i++)
            {
                $fecha = Carbon::parse($date);
                $fecha = $fecha->format("l");
                if($fecha == 'Sunday')
                {
                    $date = date("Y-m-d", strtotime($date));
                    $date = date("d-m-Y",strtotime($date."+ 1 days"));
                }
                $date = date("Y-m-d", strtotime($date));
                LoanDay::create([
                    'loan_id' => $loan->id,
                    'number' => $i,
                    'debt' => $loan->amountDay,
                    'amount' => $loan->amountDay,

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
        $loan = Loan::where('id', $loan)->first();
        return view('loans.print.loanDaily', compact('loan'));
    }




    public function dailyMoney($loan)
    {
        $id = $loan;
        $loan = Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
            ->where('deleted_at', null)->where('id',$id)->first();

        $loanday = LoanDay::where('loan_id', $id)->where('deleted_at', null)->get();
        // return $loanday;
        
        $agent = LoanRoute::with(['agent'])->where('loan_id', $id)->where('status', 1)->where('deleted_at', null)->first();

        // return $agent;
        $register = Auth::user();
        // return $register->role->name;


        return view('loans.add-money', compact('loan', 'agent', 'loanday', 'register'));

    }

    public function dailyMoneyStore(Request $request)
    {
        DB::beginTransaction();
        try {
            LoanDayAgent::create([
                'loanDay_id' => $request->day_id,
                'amount' => $request->amount,
                'agent_id' => $request->agent_id,
                'agentType' => $this->agent($request->agent_id)->role
            ]);
            Loan::where('id', $request->loan_id)->decrement('debt', $request->amount);
            LoanDay::where('id', $request->day_id)->decrement('debt', $request->amount);
            DB::commit();
            return redirect()->route('loans-daily.money', ['loan' => $request->loan_id])->with(['message' => 'Prestamo aprobado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('loans-daily.money', ['loan' => $request->loan_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }        
    }


}
