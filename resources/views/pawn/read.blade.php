@extends('voyager::master')

@section('page_title', 'Ver Empeño de Artículo')

@section('page_header')
    <h1 class="page-title">
        <i class="fa fa-handshake"></i> Viendo Empeño de Artículo &nbsp;
        {{-- @if (auth()->user()->hasPermission('edit_assets'))
        <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-info">
            <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">Editar</span>
        </a>&nbsp;
        @endif --}}
        <a href="{{ route('pawn.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <a href="{{ route('pawn.print', $pawn->id) }}" class="btn btn-danger" target="_blank">
            <span class="fa fa-print"></span>&nbsp;
            Imprimir
        </a>
    </h1>
@stop

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
@endphp

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Código</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ str_pad($pawn->id, 5, "0", STR_PAD_LEFT) }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Beneficiario</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $pawn->person->first_name }} {{ $pawn->person->last_name1 }} {{ $pawn->person->last_name2 }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de prestamo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d', strtotime($pawn->date)) }} de {{ $months[intval(date('m', strtotime($pawn->date)))] }} de {{ date('Y', strtotime($pawn->date)) }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha límite de devolución</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d', strtotime($pawn->date_limit)) }} de {{ $months[intval(date('m', strtotime($pawn->date_limit)))] }} de {{ date('Y', strtotime($pawn->date_limit)) }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="panel-title">Detalle de artículos</h3>
                                    </div>
                                    <div class="col-md-4 text-right" style="padding-top: 20px">
                                    </div>
                                </div>
                            </div>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Tipo de artículo</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Observaciones</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                        $total = 0;
                                    @endphp
                                    @forelse ($pawn->details as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->type->name }}</td>
                                            <td>{{ ($item->quantity - intval($item->quantity))*100 ? $item->quantity : intval($item->quantity) }}{{ $item->type->unit }}</td>
                                            <td>{{ $item->price }}</td>
                                            <td>{{ $item->observations }}</td>
                                            <td class="text-right">{{ number_format($item->quantity * $item->price, 2, ',', '') }}</td>
                                            @php
                                                $total += $item->quantity * $item->price;
                                            @endphp
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="6">No hay datos disponible</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-right" colspan="5"><b>TOTAL</b></td>
                                        <td class="text-right"><b style="font-size: 15px">Bs. {{ number_format($total, 2, ',', '') }}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="panel-title">Detalle de pagos</h3>
                                    </div>
                                    <div class="col-md-4 text-right" style="padding-top: 20px">
                                    </div>
                                </div>
                            </div>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Observaciones</th>
                                        <th>Registrado por</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($pawn->payments as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ date('d', strtotime($item->date)) }} de {{ $months[intval(date('m', strtotime($item->date)))] }} de {{ date('Y', strtotime($item->date)) }}</td>
                                            <td>{{ $item->amount }}</td>
                                            <td>{{ $item->observations }}</td>
                                            <td>{{ $item->user->name }}</td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                <a href="#" title="Imprimir" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
                                                </a>
                                            </th>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="5">No hay datos disponible</td>
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
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        moment.locale('es');
        $(document).ready(function () {
            
        });
    </script>
@stop
