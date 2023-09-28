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
        $data = PawnRegister::with(['person', 'user', 'details.type.category', 'details.features_list.feature'])->paginate($paginate);
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
                            'item_feature_id' => $request->{'features_'.$i}[$j],
                            'value' => $request->{'features_value_'.$i}[$j]
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('pawn.index')->with(['message' => 'Registrado exitosamente', 'alert-type' => 'success', 'pawn_register_id' => $pawn_register->id]);            
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
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
        //
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
}
