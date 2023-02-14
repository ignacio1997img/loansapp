@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="CAPRESI" width="70px"></td>
            <td style="text-align: center;  width:50%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    EMPRESA "CAPRESI"<br>
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DETALLADO DE RECAUDACION POR PERIODO
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                    @if ($start == $finish)
                        {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }}
                    @else
                        {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }} Al {{ date('d', strtotime($finish)) }} de {{ $months[intval(date('m', strtotime($finish)))] }} de {{ date('Y', strtotime($finish)) }}
                    @endif
                </small>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    <div id="qr_code">
                        @if ($start != $finish)
                            {!! QrCode::size(80)->generate('Total Cobrado: Bs'.number_format($amountTotal,2, ',', '.').', Recaudado en Fecha '.date('d', strtotime($start)).' de '.strtoupper($months[intval(date('m', strtotime($start)))] ).' de '.date('Y', strtotime($start)).' al '.date('d', strtotime($finish)).' de '.strtoupper($months[intval(date('m', strtotime($finish)))] ).' de '.date('Y', strtotime($finish))); !!}
                        @else
                            {!! QrCode::size(80)->generate('Total Cobrado: Bs'.number_format($amountTotal,2, ',', '.').', Recaudado en Fecha '.date('d', strtotime($start)).' de '.strtoupper($months[intval(date('m', strtotime($start)))] ).' de '.date('Y', strtotime($start))); !!}
                        @endif
                    </div>
                    <small style="font-size: 8px; font-weight: 100">Impreso por: {{ Auth::user()->name }} {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 9px" border="1" cellspacing="0" cellpadding="3">
        <thead>
            <tr>
                <th rowspan="2" style="width:5px">N&deg;</th>
                <th rowspan="2" style="text-align: center">CI</th>
                <th rowspan="2" style="text-align: center">CLIENTE</th>
                <th rowspan="4" style="text-align: center">ATENDIDO POR</th>
                <th colspan="3" style="text-align: center">DETALLE DEL PRESTAMOS</th>
                <th colspan="3" style="text-align: center">DETALLE DE PAGO</th>
            </tr>
            <tr>
                <th style="text-align: center">CODIGO PRESTAMO</th>
                <th style="text-align: center">FECHA DE PRESTAMO</th>
                <th style="text-align: center">TOTAL DEL PRESTAMO</th>

                <th style="text-align: center">ID PAGO</th>
                <th style="text-align: center">FECHA DE PAGO</th>
                <th style="text-align: center">TOTAL PAGADO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $total = 0;
            @endphp
            @forelse ($data as $item)
                <tr>
                    <td>{{ $count }}</td>
                    <td style="text-align: right">{{ $item->ci }}</td>
                    <td style="text-align: right">{{ $item->last_name1}} {{ $item->last_name2}} {{ $item->first_name}}</td>
                    <td style="text-align: right">{{ $item->name}}</td>
                    <td style="text-align: right">{{ $item->code}}</td>
                    <td style="text-align: center">{{date('d/m/Y', strtotime($item->dateDay))}}</td>
                    <td style="text-align: right">{{ number_format($item->amountTotal,2) }}</td>
                    <td style="text-align: right">{{ $item->loanDayAgent_id}}</td>
                    <td style="text-align: center">{{date('d/m/Y', strtotime($item->loanDayAgent_fecha))}}</td>
                    <td style="text-align: right">{{ number_format($item->amount,2) }}</td>                              
                                                                            
                </tr>
                @php
                    $count++;                 
                    $total+= $item->amount;                    
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="10">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <th colspan="9" style="text-align: left">Total</th>
                <td style="text-align: right"><strong>Bs. {{ number_format($total,2) }}</strong></td>
            </tr>
        </tbody>
       
    </table>

@endsection
@section('css')
    <style>
        table, th, td {
            border-collapse: collapse;
        }
          
    </style>
@stop
