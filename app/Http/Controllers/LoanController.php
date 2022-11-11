<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\People;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Loan;
use App\Models\LoanDay;
use App\Models\LoanAgent;
use App\Models\LoanRequirement;
use App\Models\User;
use Psy\CodeCleaner\ReturnTypePass;
use Psy\TabCompletion\Matcher\FunctionsMatcher;
use TCG\Voyager\Models\Role;
use App\Models\Route;

use App\Http\Controllers\FileController;
use App\Models\LoanDayAgent;
use Illuminate\Support\Composer;

use function PHPUnit\Framework\returnSelf;

class LoanController extends Controller
{
    public function index()
    {
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

        $data = Loan::with(['loanDay', 'loanAgent', 'loanRequirement', 'people' => function($query) use ($search){
            if($search){
                // OrWhereRaw($search ? "id = '$search'" : 1)
                $query->OrWhereRaw($search ? "first_name like '%$search%'" : 1)
                ->OrWhereRaw($search ? "last_name like '%$search%'" : 1);
                // ->OrWhereRaw($search ? "CONCAT(first_name, ' ', last_name) like '%$search%'" : 1)
                // ->OrWhereRaw($search ? "ci like '%$search%'" : 1)
                // ->OrWhereRaw($search ? "nua_cua like '%$search%'" : 1)
                // ->OrWhereRaw($search ? "phone like '%$search%'" : 1);
            }
        }])
        ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        // $data = LOa::where(function($query) use ($search){
        //             $query->OrWhereRaw($search ? "id = '$search'" : 1)
        //             ->OrWhereRaw($search ? "first_name like '%$search%'" : 1)
        //             ->OrWhereRaw($search ? "last_name like '%$search%'" : 1)
        //             ->OrWhereRaw($search ? "CONCAT(first_name, ' ', last_name) like '%$search%'" : 1)
        //             ->OrWhereRaw($search ? "ci like '%$search%'" : 1);
        //             // ->OrWhereRaw($search ? "phone like '%$search%'" : 1);
        //             })
        //             ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
                    // $data = 1;
                    // dd($data->links());
        return view('loans.list', compact('data'));
    }

    public function create()
    {
        $people = People::where('deleted_at', null)->where('status',1)->get();
        // $collector = User::with(['role' => function($q)
        //     {
        //         $q->where('name','cobrador');
        //     }])
        //     ->get();

        $routes = Route::where('deleted_at', null)->where('status', 1)->orderBy('name')->get();

        // return $collector;

        return view('loans.add', compact('people', 'routes'));
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
                $ci = $imageObj->image($file, $loan, "Loan/requirement/daily/ci");
                $ok->update(['ci' => $ci]);
            }

            $file = $request->file('luz');
            if($file)
            {                        
                $luz = $imageObj->image($file, $loan, "Loan/requirement/daily/luz");
                $ok->update(['luz' => $luz]);
            }

            $file = $request->file('croquis');
            if($file)
            {                        
                $croquis = $imageObj->image($file, $loan, "Loan/requirement/daily/croquis");
                $ok->update(['croquis' => $croquis]);
            }

            $file = $request->file('business');
            if($file)
            {                        
                $business = $imageObj->image($file, $loan, "Loan/requirement/daily/business");
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
            LoanAgent::create([
                'loan_id' => $loan->id,

                'agent_id' => $request->loan_id,
                'agentType' => $this->agent($request->loan_id)->role,

                'observation' => 'Primer cobrador del prestamo asignado',

                'register_userId' => Auth::user()->id,
                'register_agentType' => $this->agent(Auth::user()->id)->role
            ]);

            LoanRequirement::create([
                'loan_id' => $loan->id,

                'register_userId' => Auth::user()->id,
                'register_agentType' => $this->agent(Auth::user()->id)->role
            ]);



            $date = date("d-m-Y",strtotime($request->date."+ 1 days"));
            for($i=1;$i<=$request->day; $i++)
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
                    'debt' => $request->amountDay,
                    'amount' => $request->amountDay,

                    'register_userId' => $agent->id,
                    'register_agentType' => $agent->role,

                    'date' => $date
                ]);
                $date = date("d-m-Y",strtotime($date."+ 1 days"));

            }
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
        $loan = Loan::with(['loanDay', 'loanAgent'])
            ->where('deleted_at', null)->get();
        
        return $loan;

        
        return 1;
        return view('loans.read');
    }

    public function printCalendar($id)
    {
        $loan = Loan::with(['people', 'loanDay', 'loanAgent'])
            ->where('deleted_at', null)->where('id', $id)->first();
        // return $loan;
        return view('loans.print-calendar', compact('loan'));
    }

    public function destroy($id)
    {
        // return $id;
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

            LoanAgent::where('loan_id', $id)->update([
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

            $agent = LoanAgent::where('loan_id', $loan)
                        ->where('deleted_at', null)
                        ->first();
            $agent->update([
                'status' => 0
            ]);

            LoanAgent::create([
                'loan_id' => $loan,

                'agent_id' => $request->loan_id,
                'agentType' => $this->agent($request->loan_id)->role,

                'observation' => $request->observation,

                'register_userId' => Auth::user()->id,
                'register_agentType' => $this->agent(Auth::user()->id)->role
            ]);

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




    public function dailyMoney($loan)
    {
        // return $loan;
        $id = $loan;
        $loan = Loan::with(['loanDay', 'loanAgent', 'loanRequirement', 'people'])
            ->where('deleted_at', null)->where('id',$id)->first();

        $loanday = LoanDay::where('loan_id', $id)->where('deleted_at', null)->get();
        // return $loanday;
        
        $agent = LoanAgent::with(['agent'])->where('loan_id', $id)->where('status', 1)->where('deleted_at', null)->first();

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
