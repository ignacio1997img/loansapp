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
                    REPORTE DETALLADO DE DEUDORES ATRAZADOS
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                        {{ date('d') }} de {{ $months[intval(date('m'))] }} de {{ date('Y') }}
                   
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
                <th rowspan="2" style="text-align: center">CODIGO</th>
                <th rowspan="2" style="text-align: center">CI</th>
                <th rowspan="2" style="text-align: center">CLIENTE</th>
                <th rowspan="2" style="text-align: center">CELULAR</th>
                <th rowspan="2" style="text-align: center">DIRECCION</th>
                <th rowspan="2" style="text-align: center">PAGO DIARIO</th>
                <th rowspan="2" style="text-align: center">TOTAL DIAS A PAGAR</th>
                <th rowspan="2" style="text-align: center">MONTO PRESTADO</th>
                <th rowspan="2" style="text-align: center">INTERES A PAGAR</th>
                <th rowspan="2" style="text-align: center">TOTAL A PAGAR</th>
                <th colspan="2" style="text-align: center">RETRAZO</th>
                <th rowspan="2" style="text-align: center">RECIBIDO</th>
            </tr>
            <tr>
                <th style="text-align: center">DIAS</th>
                <th style="text-align: center">TOTAL A PAGAR</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @forelse ($data as $item)
                @php
                    $atras = Illuminate\Support\Facades\DB::table('loans as l')
                                ->join('loan_days as ld', 'ld.loan_id', 'l.id')
                                ->join('people as p', 'p.id', 'l.people_id')


                                ->where('l.deleted_at', null)
                                ->where('ld.deleted_at', null)

                                ->where('l.debt', '>', 0)

                                ->where('ld.debt', '>', 0)
                                ->where('ld.late', 1)
                                ->where('l.id', $item->loan_id)

                                ->select(
                                    DB::raw("SUM(ld.late) as diasAtrasado"), DB::raw("SUM(ld.debt) as montoAtrasado")
                                )
                                ->first();
                @endphp
                <tr style="text-align: center">
                    <td>{{ $count }}</td>
                    <td style="text-align: center">{{ $item->code}}</td>
                    <td style="text-align: center">{{ $item->ci }}</td>
                    <td style="text-align: left">{{ $item->last_name1}} {{ $item->last_name2}} {{ $item->first_name}}</td>
                    <td style="text-align: center">{{ $item->cell_phone}}</td>
                    <td style="text-align: left">{{ $item->street}} <br>
                        {{ $item->home}} <br>
                        {{ $item->zone}}
                    </td>
                    <td style="text-align: right"><b>Bs. {{ number_format($item->amountTotal/$item->day,2) }}</b></td>
                    <td style="text-align: right">{{ $item->day }}</td>
                    <td style="text-align: right">{{ number_format($item->amountLoan,2) }}</td>
                    <td style="text-align: right">{{ number_format($item->amountPorcentage,2) }}</td>
                    <td style="text-align: right">{{ number_format($item->amountTotal,2) }}</td>
                    <td @if($atras->diasAtrasado) style="text-align: right; background-color: #ff7979" @else style="text-align: right" @endif>{{$atras->diasAtrasado?$atras->diasAtrasado:''}}</td>
                    <td @if($atras->montoAtrasado) style="text-align: right; background-color: #ff7979" @else style="text-align: right" @endif>{{$atras->montoAtrasado?number_format($atras->montoAtrasado,2):'' }}</td>      
                    <td></td>
                </tr>
                @php
                    $count++;
                    
                @endphp
                
            @empty
                <tr style="text-align: center">
                    <td colspan="13">No se encontraron registros.</td>
                </tr>
            @endforelse
        </tbody>
       
    </table>

@endsection