<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Vault;
use App\Models\VaultClosure;
use App\Models\VaultClosureDetail;
use App\Models\Cashier;
use App\Models\VaultDetail;
use App\Models\VaultDetailCash;
use App\Models\CashierMovement;

class CashierController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $vault = Vault::where('deleted_at', null)->first();
        // return $vault;
        // $cashier ='';
        // if($vault)
        // {
        //     $cashier = Cashier::where('deleted_at', null)->where('vault_id', $vault->id)->get();

        // }
        $cashier = Cashier::where('deleted_at', null)->get();

        // return $cashier;
        return view('cashier.browse', compact('cashier', 'vault'));
    }


    public function create()
    {
        $user = Auth::user();
        $vault = Vault::first();
        return view('cashier.add' , compact('vault'));
    }

    public function store(Request $request)
    {
        // return $request;
        $cashier = Cashier::where('user_id', $request->user_id)->where('status', '!=', 'cerrada')->where('deleted_at', NULL)->first();


        // dd($cashier);

        if(!$cashier){
            if($request->amount == null)
            {
                return redirect()->route('cashiers.create')->with(['message' => 'Sin monto asignado a la caja.', 'alert-type' => 'warning']);
            }
            DB::beginTransaction();
            try {
                // return $request;
                $cashier = Cashier::create([
                    'vault_id' => $request->vault_id,
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'observations' => $request->observations,
                    'status' => 'apertura pendiente'
                ]);
                // return $request->amount;

                if($request->amount){
                    CashierMovement::create([
                        'user_id' => Auth::user()->id,
                        'cashier_id' => $cashier->id,
                        'amount' => $request->amount,
                        'description' => 'Monto de apertura de caja.',
                        'type' => 'ingreso'
                    ]);

                    // Registrar detalle de bóveda
                    $cashier = Cashier::with('user')->where('id', $cashier->id)->first();
                    $detail = VaultDetail::create([
                        'user_id' => Auth::user()->id,
                        'vault_id' => $request->vault_id,
                        'cashier_id' => $cashier->id,
                        'description' => 'Traspaso a '.$cashier->title,
                        'type' => 'egreso',
                        'status' => 'aprobado'
                    ]);

                    for ($i=0; $i < count($request->cash_value); $i++) { 
                        // if($request->quantity[$i]){
                            VaultDetailCash::create([
                                'vault_detail_id' => $detail->id,
                                'cash_value' => $request->cash_value[$i],
                                'quantity' => $request->quantity[$i],
                            ]);
                        // }
                    }
                }

                DB::commit();
    
                return redirect()->route('cashiers.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success', 'id_cashier_open' => $cashier->id]);
            } catch (\Throwable $th) {
                DB::rollback();
                return $th;
                //throw $th;
                return redirect()->route('cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
        }else{
            return redirect()->route('cashiers.index')->with(['message' => 'El usuario seleccionado tiene una caja que no ha sido cerrada.', 'alert-type' => 'warning']);
        }
    }


    //para que el cajero acepte el monto de dinero y abilite la caja
    public function change_status($id, Request $request){
        // return $id;
        DB::beginTransaction();
        try {
            if($request->status == 'abierta'){
                $message = 'Caja aceptada exitosamente.';
                Cashier::where('id', $id)->update([
                    'status' => $request->status
                ]);
            }else{
                $cashier = Cashier::with(['vault_details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }])->where('id', $id)->first();

                $message = 'Caja rechazada exitosamente.';
                Cashier::where('id', $id)->update([
                    'status' => 'Rechazada',
                    'deleted_at' => Carbon::now()
                ]);

                $vault_detail = VaultDetail::create([
                    'user_id' => Auth::user()->id,
                    'vault_id' => $cashier->vault_details->vault_id,
                    'cashier_id' => $cashier->id,
                    'description' => 'Rechazo de apertura de caja de '.$cashier->title.'.',
                    'type' => 'ingreso',
                    'status' => 'aprobado'
                ]);
                foreach ($cashier->vault_details->cash as $item) {
                    if($item->quantity > 0){
                        VaultDetailCash::create([
                            'vaults_detail_id' => $vault_detail->id,
                            'cash_value' => $item->cash_value,
                            'quantity' => $item->quantity
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('voyager.dashboard')->with(['message' => $message, 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function close($id){
        // return 234234;
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('id', $id)->where('deleted_at', NULL)->first();
        return view('cashier.close', compact('cashier'));
    }
}
