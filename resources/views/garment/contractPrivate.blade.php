@extends('layouts.template-print-legal')

@section('page_title', 'Contrato privado')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
@endphp

@section('qr_code')
    <div id="qr_code" style="text-align: right">
        {{-- {!! QrCode::size(100)->generate(Request::url()); !!} --}}
        {!! QrCode::size(80)->generate('Contrato:'.$garment->id.', DEUDOR '.$garment->people->first_name.' '.$garment->people->last_name1.' '.$garment->people->last_name2.' con C.I. '.$garment->people->ci.', con un prestamo de $'.number_format($garment->amountLoan, 2, ',', '.').'. Santisima Trinidad, '.date('d', strtotime($garment->date)) .' de '. $months[intval(date('m', strtotime($garment->date)))] .' de '. date('Y', strtotime($garment->date)) ); !!}
        <strong >{{$garment->code}}</strong>
    
    </div>
@endsection
{{-- <div class="visible-print text-center">
    {!! QrCode::size(100)->generate(Request::url()); !!}
    <p>Scan me to return to the original page.</p>
</div> --}}

@section('content')
    <div class="content" style="text-align: justify">
        <h2 class="text-center" style="font-size: 18px">CONTRATO PRIVADO</h2>
        <p><em>Conste por el presente documento privado del préstamo que a solo reconocimiento de firmas tendrá calidad de instrumento público, que el señor <strong>CHRISTIAN MERCADO PERICON</strong></em><em>, con</em><em><strong> C.I.1919784 BE, </strong>, que para fines del contrato en adelante en adelante se denominará como el <strong>ACREEDOR</strong>, por una parte, y el (la) señor (a) <strong>{{$garment->people->first_name}} {{$garment->people->last_name1}} {{$garment->people->last_name2}} mayor de edad, habil por derecho con CI. {{ $garment->people->ci }} con domicilio <b>{{$garment->people->zone}} {{$garment->people->street}}, {{$garment->people->home}}</b> </strong> que para fines del presente contrato en adelante se denominara como <strong>EL DEUDOR</strong>, por la otra parte, suscriben el presente contrato al tenor de las siguiente cláusula: </em></p>
        <p><em><span style="text-decoration: underline;"><strong>PRIMERA.- EL DEUDOR</strong></span></em><em> declara ser legítimo propietario de un: 
        @foreach ($garment->garmentArticle as $item)
            <em><strong>{{$item->article}} </strong></em>
                {{-- @php
                    $ok = false;
                @endphp --}}
                @foreach ($item->garmentArticleDetail as $itm)
                    <strong>{{$itm->title}}: </strong>
                    @if (is_numeric($itm->value))
                        {{ number_format($itm->value,2, ',','.') }}
                    @else
                        {{$itm->value}}
                    @endif
                    , 
                @endforeach
                {{-- @if ($ok)
                    <strong>Cantidad de Gramo: </strong>{{ number_format($item->amountCant,2, ',','.') }}<br>
                    <strong>Monto Prestado por Gramo: </strong>{{ number_format($item->amountLoan,2, ',','.') }} <br>
                @endif --}}
                
                <strong>Monto Total Prestado por esta prenda: </strong>Bs. {{ number_format($item->amountSubTotal,2, ',','.') }} <br>


         
        @endforeach
        </p>
        
     
        <p><em><span style="text-decoration: underline;"><strong>SEGUNDA.- EL DEUDOR</strong></span></em><em> en la presente fecha, de su libre y espontanea libertad, por así convenir a sus interés, sin que medie presión violencia, dolor o vicio en el consentimiento, entrega todo lo anteriormente mensionado en la primer cláusula, en calidad de garantía prendaria, con opción a transferencia o venta definitiva, en favor de <strong>EL ACREEDOR</strong>, por la suma libremente convenida de <strong>$ {{NumerosEnLetras::convertir($garment->amountLoanDollar,'Dolares Americanos',true)}}</strong> cantidad de dinero que <strong>EL DEUDOR</strong> declara haber recibido a su entera y absoluta conformidad, sin lugar a reclamos posterior alguno de su parte. Así mismo garantiza la evicción y saneamiento de ley de lo otorgado en garantía.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>TERCERA.- EL DEUDOR</strong></span></em><em> se compromete a devolver la suma de la que ha sido objeto el préstamo en la segunda cláusula, mas lo correspondientes interés, a la tasa libremente acordada entre las partes, que se hubieran generado, en un plazo no mayor {{$garment->month==1?'30':'90'}} días ({{$garment->month==1?'1 mes':'3 meses'}}), y a su vez recoger su garantía prendaria. Caso contrario pasada las 48 horas de cumplido el plazo acordado, no existiendo rescisión ambas partes <strong>EL DEUDOR Y EL ACREEDOR</strong> declaran perfeccionada la venta adquiriendo <strong>EL ACREEDOR</strong> el derecho propietario definitivo frente a terceras personas, pudiendo éste vender lo trasferido, y vender, sin responsabilidad civil ni penal, por lo que <strong>EL DEUDOR</strong> desiste de cualquier acción judicial o policial dando el presente carácter de transacción con el sello de cosa juzgada.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CUARTA .- </strong></span></em><em>Ambas partes expresan conformidad con cada una de las cláusulas del presente documento y para tal efecto firman el mismo.   El vendedor, Sr. <strong>{{$garment->people->first_name}} {{$garment->people->last_name1}} {{$garment->people->last_name2}}</strong> sin embargo, queda obligado, en término de ley, a la evicción y saneamiento de este contrato, en concepto de hallarse el Vehículo vendio libre de toda carga, o gravámenes.</em></p>
        <p style="text-align: right;"> 
            <span>Santísima Trinidad</span>, {{ date('d', strtotime($garment->date)) }} de {{ $months[intval(date('m', strtotime($garment->date)))] }} de {{ date('Y', strtotime($garment->date)) }}
        </p>
     
        <br>
        <br>
        <br>
        <br>
        <table width="100%" style="text-align: center; margin-top: 1px;">
            <tr>
                <td style="width: 50%">
                    ....................................................... <br>
                    {{-- <em>{{ $garment->people->gender == 'masculino' ? 'Sr.' : 'Sra.' }} {{$garment->people->first_name}} {{$garment->people->last_name1}} {{$garment->people->last_name2}}</em><br> --}}
                    <em>{{  strtoupper($garment->people->first_name)}} {{strtoupper($garment->people->last_name1)}} {{strtoupper($garment->people->last_name2)}}</em><br>
                    <b>DEUDOR</b> <br>
                    <b>C.I. {{$garment->people->ci}}</b><br>
                    {{-- <b>DEUDOR</b> --}}
                </td>
                <td style="width: 50%">
                    ....................................................... <br>
                    <em>CHRISTIAN MERCADO PERICÓN</em> <br>
                    <b>ACREEDOR</b><br>
                    <b>C.I. 1919784 Beni</b><br>
                    {{-- <b>ACREEDOR</b> --}}
                </td>
                                
            </tr>
        </table>
        
       



    </div>
@endsection

@section('css')
    <style>
        .content {
            padding: 50px 34px;
            font-size: 15px;
        }
        .text-center{
            text-align: center;
        }
        .saltopagina{
            display: none;
        }
        @media print{
            .saltopagina{
                display: block;
                page-break-before: always;
            }
            .pt{
                height: 90px;
            }
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection