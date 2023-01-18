@extends('voyager::master')

@section('page_title', 'Ver Caja')


@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i> Viendo Caja 
        <a href="{{ route('cashiers.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-print"></span> Impresión <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('print.open', ['cashier' => $cashier->id]) }}" target="_blank">Apertura</a></li>
                @if ($cashier->status == 'cerrada')
                <li><a href="{{ route('print.close', ['cashier' => $cashier->id]) }}" target="_blank">Cierre</a></li>
                @endif
                {{-- <li><a href="{{ route('print.payments', ['cashier' => $cashier->id]) }}" target="_blank">Pagos</a></li> --}}
            </ul>
        </div>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Descripción</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->title }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cajero</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->user->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->observations ?? 'Ninguna' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop

@section('javascript')
    <script>
       
    </script>
@stop
