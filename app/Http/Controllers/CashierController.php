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
use App\Models\CashierDetail;
use Psy\CodeCleaner\ReturnTypePass;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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
                        'balance' => $request->amount,
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
                // return $th;
                //throw $th;
                return redirect()->route('cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
        }else{
            return redirect()->route('cashiers.index')->with(['message' => 'El usuario seleccionado tiene una caja que no ha sido cerrada.', 'alert-type' => 'warning']);
        }
    }

    public function show($id)
    {
        $cashier = Cashier::where('id', $id)->first();
        // return $id;
        return view('cashier.read' , compact('cashier'));
    }

    //para abrir la vista de abonar dinero a una caja que este en estado ABIERTA
    public function amount($id)
    {
        // return $id;
        $cashier = Cashier::findOrFail($id);
        // return $cashier;
        if($cashier->status == 'abierta'){
            return view('cashier.add-amount', compact('id'));
        }else{
            return redirect()->route('voyager.cashiers.index')->with(['message' => 'La caja seleccionada ya no se encuentra abierta.', 'alert-type' => 'warning']);
        }
    }

    //para guardar el dinero abonado a la caja ABIERTA
    public function amount_store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {

            $cashier = Cashier::with('user')->where('id', $request->cashier_id)->where('status', 'abierta')->first();
            if(!$cashier){
                return redirect()->route($request->redirect ?? 'voyager.cashiers.index')->with(['message' => 'La caja seleccionada ya no se encuentra abierta.', 'alert-type' => 'warning']);
            }

            
            // Registrar traspaso a la caja
            $movement = CashierMovement::create([
                'user_id' => Auth::user()->id,
                'cashier_id' => $request->cashier_id,
                // 'cashier_id_from' => $request->id ?? NULL,
                'balance' => $request->amount,
                'amount' => $request->amount,
                'description' => $request->description,
                'type' => 'ingreso'
            ]);

            // CashierMovement::create([
            //     'user_id' => Auth::user()->id,
            //     'cashier_id' => $cashier->id,
            //     'balance' => $request->amount,
            //     'amount' => $request->amount,
            //     'description' => 'Monto de apertura de caja.',
            //     'type' => 'ingreso'
            // ]);

            $id_transfer = $movement->id;

            // En caso de ser un trapaso entre cajas
            if($request->id){
                return 00;
                CashierMovement::create([
                    'user_id' => Auth::user()->id,
                    'cashier_id' => $request->id,
                    'cashier_id_to' => $request->cashier_id,
                    'amount' => $request->amount,
                    'description' => 'Traspaso a '.$cashier->title,
                    'type' => 'egreso'
                ]);
            }else{
                if($request->amount){
                    // Registrar detalle de bóveda
                    $detail = VaultDetail::create([
                        'user_id' => Auth::user()->id,
                        'vault_id' => $request->vault_id,
                        'cashier_id' => $request->cashier_id,
                        'description' => 'Traspaso a '.$cashier->title,
                        'type' => 'egreso',
                        'status' => 'aprobado'
                    ]);
                    // return $detail;
                    for ($i=0; $i < count($request->cash_value); $i++) { 
                        VaultDetailCash::create([
                            'vaults_detail_id' => $detail->id,
                            'cash_value' => $request->cash_value[$i],
                            'quantity' => $request->quantity[$i],
                        ]);
                    }
                }
            }
            // return $id_transfer;    

            DB::commit();
            return redirect()->route('cashiers.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success', 'id_transfer' => $id_transfer]);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }




    //*** Para que los cajeros Acepte o rechase el dinero dado por Boveda o gerente
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
                // return $vault_detail;
                foreach ($cashier->vault_details->cash as $item) {
                    if($item->quantity > 0){
                        VaultDetailCash::create([
                            'vault_detail_id' => $vault_detail->id,
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

    //***para cerrar la caja el cajero vista 
    public function close($id){
        // return $id;
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('id', $id)->where('deleted_at', NULL)->first();
        // return $cashier;
        return view('cashier.close', compact('cashier'));
    }

    public function close_store($id, Request $request){
        // dd($request);
        // return $request;
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            if($cashier->status != 'cierre pendiente'){
                $cashier->closed_at = Carbon::now();
                $cashier->status = 'cierre pendiente';
                $cashier->save();

                for ($i=0; $i < count($request->cash_value); $i++) { 
                    // if($request->quantity[$i]){
                        CashierDetail::create([
                            'cashier_id' => $id,
                            'cash_value' => $request->cash_value[$i],
                            'quantity' => $request->quantity[$i],
                        ]);
                    // }
                }
            }

            DB::commit();
            return redirect()->route('voyager.dashboard')->with(['message' => 'Caja cerrada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd(0);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function close_revert($id, Request $request){
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            if($cashier->status == 'cierre pendiente'){
                $cashier->closed_at = NULL;
                $cashier->status = 'abierta';
                $cashier->save();

                CashierDetail::where('cashier_id', $id)->update([
                    'deleted_at' => Carbon::now()
                ]);

                DB::commit();
                return redirect()->route('voyager.dashboard')->with(['message' => 'Caja reabierta exitosamente.', 'alert-type' => 'success']);
            }

            return redirect()->route('voyager.dashboard')->with(['message' => 'Lo siento, su caja ya fué cerrada.', 'alert-type' => 'warning']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function confirm_close($id)
    {
        $cashier = Cashier::with(['details' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->first();
        if($cashier->status == 'cierre pendiente'){
            return view('cashier.confirm_close', compact('cashier'));
        }else{
            return redirect()->route('cashiers.index')->with(['message' => 'La caja ya no está abierta.', 'alert-type' => 'warning']);
        }
    }

    public function confirm_close_store($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            $cashier->status = 'cerrada';
            $cashier->save();
            
            $detail = VaultDetail::create([
                'user_id' => Auth::user()->id,
                'cashier_id' => $id,
                'vault_id' => $request->vault_id,
                'description' => 'Devolución de la caja '.$cashier->title,
                'type' => 'ingreso',
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

            DB::commit();
            return redirect()->route('cashiers.index')->with(['message' => 'Caja cerrada exitosamente.', 'alert-type' => 'success', 'id_cashier_close' => $id]);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }






    public function print_open($id){
        $cashier = Cashier::with(['user', 'vault_details' => function($q){
            $q->where('deleted_at', NULL);
        }, 'vault_details.cash' => function($q){
            $q->where('deleted_at', NULL);
        }, 'movements' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->first();
        // dd($cashier);
        $view = view('cashier.print-open', compact('cashier'));
        return $view;
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML($view);
        // return $pdf->download();
    }

    public function print_close($id){
        $cashier = Cashier::with(['user',
        'movements' => function($q){
            $q->where('deleted_at', NULL);
        }, 'details' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->first();


        
        $view = view('cashier.print-close', compact('cashier'));
        return $view;
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML($view);
        // return $pdf->download();
    }

}
