@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
            <td style="text-align: center;  width:70%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    EMPRESA "CAPRESI"<br>
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DETALLADO DE RECAUDACION DIARIA
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                        {{ date('d', strtotime($date)) }} de {{ $months[intval(date('m', strtotime($date)))] }} de {{ date('Y', strtotime($date)) }}
                    </small>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                   
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <br><br>
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
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

                <th style="text-align: center">N. TRANSACCION</th>
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
                    <td style="text-align: left">{{ $item->last_name1}} {{ $item->last_name2}} {{ $item->first_name}}</td>
                    <td style="text-align: left">{{ $item->name}}</td>
                    <td style="text-align: center">{{ $item->code}}</td>
                    <td style="text-align: center">{{date('d/m/Y', strtotime($item->dateDay))}}</td>
                    <td style="text-align: right">{{ number_format($item->amountTotal, 2, ',', '.') }}</td>
                    <td style="text-align: right">{{ $item->transaction}}</td>
                    <td style="text-align: center">{{date('d/m/Y', strtotime($item->loanDayAgent_fecha))}}</td>
                    <td style="text-align: right">{{ number_format($item->amount,2, ',', '.') }}</td>                              
                                                                            
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
            </tr>
        </tbody>       
       

    </table>
    <div class="row" style="font-size: 9pt">
        <p style="text-align: right">Total Detalle de Egreso: {{NumerosEnLetras::convertir(number_format($total,2),'Bolivianos',true)}}</p>
    </div>

    <br>
    <br><br>
    <br>
    <table width="100%">
        <tr>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Entregado Por</b><br>
                <b>{{ Auth::user()->name }}</b><br>
                <b>CI: {{ Auth::user()->ci }}</b>
            </td>
            <td style="text-align: center">
                {{-- ______________________
                <br>
                <b>Firma Responsable</b> --}}
            </td>
            <td style="text-align: center">
                ______________________
                <br>
                <b>Recibido Por</b><br>
                <b>........................</b><br>
                <b>CI: .................</b>
            </td>
        </tr>
    </table>

@endsection