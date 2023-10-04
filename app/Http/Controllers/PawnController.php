<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

// Models
use App\Models\ItemFeature;
use App\Models\PawnRegister;
use App\Models\PawnRegisterDetail;
use App\Models\PawnRegisterDetailFeature;
use App\Models\PawnRegisterPayment;

class PawnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->custom_authorize('browse_pawn');
        return view('pawn.browse');
    }

    public function list(){
        $this->custom_authorize('browse_pawn');
        $paginate = request('paginate') ?? 10;
        $search = request('search') ?? null;
        $status = request('status') ?? null;
        $data = PawnRegister::with(['person', 'user', 'details.type.category', 'details.features_list.feature', 'payments'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query
                            ->OrwhereHas('person', function($query) use($search){
                                $query->whereRaw("(first_name like '%$search%' or last_name1 like '%$search%' or last_name2 like '%$search%' or ci like '%$search%' or phone like '%$search%' or CONCAT(first_name, ' ', last_name1, ' ', last_name2) like '%$search%')");
                            })
                            ->OrWhereHas('details.type', function($query) use($search){
                                $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                            })
                            ->OrWhereHas('details.type.category', function($query) use($search){
                                $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                            })
                            ->OrWhereHas('details.features_list', function($query) use($search){
                                $query->whereRaw($search ? 'value like "%'.$search.'%"' : 1);
                            });
                        }
                    })
                    ->whereRaw($status ? " status = '$status'" : 1)
                    ->orderBy('id', 'desc')
                    ->paginate($paginate);
        return view('pawn.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->custom_authorize('add_pawn');
        $features = ItemFeature::all();
        return view('pawn.edit-add', compact('features'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            // Registrar empeño
            $pawn_register = PawnRegister::create([
                'user_id' => Auth::user()->id,
                'person_id' => $request->people_id,
                'date' => $request->date,
                'date_limit' => $request->date_limit,
                'interest_rate' => $request->interest_rate,
                'observations' => $request->observations,
            ]);

            // Registrar items del empeño
            for ($i=0; $i < count($request->item_type_id); $i++) { 
                $detail = PawnRegisterDetail::create([
                    'pawn_register_id' => $pawn_register->id,
                    'item_type_id' => $request->item_type_id[$i],
                    'price' => $request->price[$i],
                    'quantity' => $request->quantity[$i],
                    'observations' => $request->observation[$i]
                ]);

                // Registrar características de cada item
                if (isset($request->{'features_'.$i})) {
                    for ($j=0; $j < count($request->{'features_'.$i}); $j++) { 
                        PawnRegisterDetailFeature::create([
                            'pawn_register_detail_id' => $detail->id,
                            'item_feature_id' => is_numeric($request->{'features_'.$i}[$j]) ? $request->{'features_'.$i}[$j] : ItemFeature::create(['name' => ucfirst($request->{'features_'.$i}[$j])])->id,
                            'value' => ucfirst($request->{'features_value_'.$i}[$j])
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('pawn.index')->with(['message' => 'Registrado exitosamente', 'alert-type' => 'success', 'pawn_register_id' => $pawn_register->id]);            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return redirect()->route('pawn.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->custom_authorize('read_pawn');
        $pawn = PawnRegister::find($id);
        return view('pawn.read', compact('pawn'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function print($id){
        $pawn = PawnRegister::find($id);
        return view('pawn.print.contract', compact('pawn'));
    }

    public function payment_store(Request $request){
        try {
            PawnRegisterPayment::create([
                'pawn_register_id' => $request->id,
                'user_id' => Auth::user()->id,
                'date' => $request->date,
                'amount' => $request->amount,
                'observations' => $request->observations
            ]);

            // Verificar si ya se terminó de pagar
            $pawn = PawnRegister::find($request->id);
            $total_payment = $pawn->payments->sum('amount');
            $total = 0;
            // Obtener el dinero prestado
            foreach ($pawn->details as $detail) {
                $total += $detail->price * $detail->quantity;
            }
            $interest_rate = $total * ($pawn->interest_rate /100);

            // Si los pagos son iguales a la deuda total
            if ($total_payment >= ($total + $interest_rate)) {
                $pawn->status = 'pagado';
                $pawn->save();
            }

            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 1]);
        }
    }
}
