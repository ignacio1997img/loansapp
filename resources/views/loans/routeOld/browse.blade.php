@extends('voyager::master')

@section('page_title', 'Viendo Prestamos')

{{-- @if (auth()->user()->hasPermission('browse_loans')) --}}

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 id="titleHead" class="page-title">
                                <i class="fa-solid fa-route"></i> {{$route->where('status', 1)->first()->route->name}} <label class="label label-dark">{{$loan->code}}</label>
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            {{-- @if ( !auth()->user()->hasRole('admin') && $vault) --}}
                                <a href="{{ route('cashiers.create') }}" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Crear</span>
                                </a>
                            {{-- @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">                        
                        <div class="table-responsive">     
                                <table id="dataStyle" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">N.</th>
                                            <th style="text-align: center">Rutas</th>
                                            <th style="text-align: center">Descripción</th>
                                            <th style="text-align: center">Estado</th>
                                            <th style="text-align: right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cashier as $item)
                                            <tr>
                                                <td>{{$item->id}}</td>
                                                <td style="width: 200pt; text-align: center">{{strtoupper($item->user->name)}}</td>
                                                <td style="text-align: center">{{strtoupper($item->title)}}</td>
                                                <td style="text-align: center">{{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}.</small></td>
                                                <td style="text-align: center">@if($item->closed_at){{date('d/m/Y H:i:s', strtotime($item->close_at))}}<br><small>{{\Carbon\Carbon::parse($item->close_at)->diffForHumans()}}.@endif </small></td>
                                
                                                <td style="text-align: right">

                                                    @if ($item->status == 'abierta')
                                                        <a href="{{route('cashiers.amount', ['cashier'=>$item->id])}}" title="Editar" class="btn btn-sm btn-success">
                                                            <i class="voyager-dollar"></i> <span class="hidden-xs hidden-sm">Abonar Dinero</span>
                                                        </a>
                                                    @endif
                                                    @if (auth()->user()->hasPermission('read_cashiers'))
                                                        <a href="{{route('cashiers.show', ['cashier'=>$item->id])}}" title="Editar" class="btn btn-sm btn-warning">
                                                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                        </a>
                                                    @endif
                                                    
                                                    @if ($item->status == 'abierta' || $item->status == 'apertura pendiente')
                                                        
                                                        <a href="#" title="Imprimir" class="btn btn-dark" onclick="openWindow({{$item->id}})">
                                                            <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir apertura</span>
                                                        </a>

                                                    @endif
                                                    @if ($item->status == 'cerrada')
                                                        
                                                        <a href="#" title="Imprimir" class="btn btn-dark" onclick="closeWindow({{$item->id}})">
                                                            <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir cierre</span>
                                                        </a>

                                                    @endif

                                                    @if ($item->status == "cierre pendiente")
                                                        <a href="{{route('cashiers.confirm_close',['cashier' => $item->id])}}" title="Ver" class="btn btn-sm btn-primary pull-right">
                                                            <i class="voyager-lock"></i> <span class="hidden-xs hidden-sm">Confirmar Cierre de Caja</span>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" style="text-align: center">Sin Datos</td>
                                            </tr>
                                        @endforelse                                   
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


@stop


@section('css')
    <style>

    
    
    
    </style>
@stop

@section('javascript')


@stop
{{-- @endif --}}