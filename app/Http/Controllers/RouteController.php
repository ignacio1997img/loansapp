<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\People;
use App\Models\RouteCollector;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('routes.browse');
    }



    public function list($search = null){
        $user = Auth::user();

        $paginate = request('paginate') ?? 10;
        $data = Route::where(function($query) use ($search){
                    $query->OrWhereRaw($search ? "id = '$search'" : 1)
                    ->OrWhereRaw($search ? "name like '%$search%'" : 1)
                    ->OrWhereRaw($search ? "description like '%$search%'" : 1);
                    })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
                    // $data = 1;
                    // dd($data->links());
        return view('routes.list', compact('data'));
    }


    public function indexCollector($route)
    {
        // return $route;
        $id = $route;
        $collector = User::where('role_id', 5)->get();
        return view('routes.collector.browse', compact('id', 'collector'));
    }

    public function listCollector($id, $search = null){
        $route = $id;
        $user = Auth::user();

        $paginate = request('paginate') ?? 10;

        // $data = User::with(['routeCollector' => function($q) use($route)
        //             {
        //                 $q->where('route_id', $route)->where('deleted_at', null);
        //             }])
        //             ->where(function($query) use ($search){
        //                 $query->OrWhereRaw($search ? "id = '$search'" : 1)
        //                 ->OrWhereRaw($search ? "name like '%$search%'" : 1);
        //                 })
        //             ->orderBy('id', 'DESC')->paginate($paginate);

        $data = RouteCollector::with(['collector' => function($q) use($search)
                    {
                        $q->where(function($query) use ($search){
                            $query->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "name like '%$search%'" : 1);
                            });
                    }])
                    ->where('route_id', $route)->where('deleted_at', null)
                    ->orderBy('id', 'DESC')->paginate($paginate);
                    // $data = 1;
                    // dd($data->links());
        return view('routes.collector.list', compact('data'));
    }
    public function storeCollector(Request $request, $route)
    {
        // $id= $route;
        // return $id;
        DB::beginTransaction();
        try {

            $ok = RouteCollector::where('route_id', $route)->where('user_id', $request->user_id)->where('deleted_at', null)->first();
            if($ok)
            {
                return redirect()->route('routes.collector.index', ['route'=>$route])->with(['message' => 'El cobrador ya existe.', 'alert-type' => 'error']);
            }
            RouteCollector::create([
                'route_id'=>$route,
                'user_id'=>$request->user_id,
                'observation'=>$request->observation,
                'register_userId'=>Auth::user()->id
            ]);

            DB::commit();
            return redirect()->route('routes.collector.index', ['route'=>$route])->with(['message' => 'Cobrador asignado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('routes.collector.index', ['route'=>$route])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function inhabilitarCollector($route, $collector)
    {
        $id= $route;
        DB::beginTransaction();
        try {
            RouteCollector::where('id', $collector)
                ->update([
                    'status'=>0,
                ]);
            DB::commit();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Inhabilitado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function habilitarCollector($route, $collector)
    {
        $id= $route;
        DB::beginTransaction();
        try {
            RouteCollector::where('id', $collector)
                ->update([
                    'status'=>1,
                ]);
            DB::commit();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Habilitado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
    public function deleteCollector($route, $collector)
    {
        $id= $route;
        DB::beginTransaction();
        try {
            RouteCollector::where('id', $collector)
                ->update([
                    'deleted_at'=>Carbon::now(),
                    'deleted_userId' => Auth::user()->id,
                    'deleted_agentType' => $this->agent(Auth::user()->id)->role
                ]);
            DB::commit();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('routes.collector.index', ['route'=>$id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
