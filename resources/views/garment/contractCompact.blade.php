@extends('layouts.template-print-legal')

@section('page_title', 'Contrato con Pacto Rescate')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
@endphp

@section('qr_code')
    <div id="qr_code" style="text-align: right">
        {{-- {!! QrCode::size(100)->generate(Request::url()); !!} --}}
        {!! QrCode::size(80)->generate('Contrato:'.$garment->id.', DEUDOR '.$garment->people->first_name.' '.$garment->people->last_name1.' '.$garment->people->last_name2.' con C.I. '.$garment->people->ci.', con un prestamo de $'.number_format($garment->amountLoan, 2, ',', '.').'. Santisima Trinidad, '.date('d', strtotime($garment->date)) .' de '. $months[intval(date('m', strtotime($garment->date)))] .' de '. date('Y', strtotime($garment->date)) ); !!} <br>
        <strong >{{$garment->code}}</strong>
    </div>
@endsection
{{-- <div class="visible-print text-center">
    {!! QrCode::size(100)->generate(Request::url()); !!}
    <p>Scan me to return to the original page.</p>
</div> --}}

@section('content')
    <div class="content" style="text-align: justify">
        <h2 class="text-center" style="font-size: 18px">SEÑOR NOTARIO DE FE PÚBLICA</h2>
        <p><em>En los registros de escritura que corren a su digno cargo, sírvase insertar una de transferencia con pacto de rescate de un VEHICULO de acuerdo a las siguientes cláusulas: </em></p>
        <p><em><span style="text-decoration: underline;"><strong>PRIMERA.-</strong></span></em><em> Dirá ud. Que yo <strong>{{$garment->people->first_name}} {{$garment->people->last_name1}} {{$garment->people->last_name2}} con CI. {{ $garment->people->ci }}con domicilio <b>{{$garment->people->zone}} {{$garment->people->street}}, {{$garment->people->home}}</b> </strong> declaro ser legítimo propietario de un VEHICULO Marca: {{$garment->brandGarment}}, Modelo: {{$garment->modelGarment}}.</em> {!! $garment->articleDescription !!}</p>
        <p><em><span style="text-decoration: underline;"><strong>SEGUNDA.-</strong></span></em><em> Al presente, en pleno uso de mis derecho y por convenir asi a mis intereses, el referido inmueble, doy y transfiero en calidad de compra-venta con pacto de rescate, de acuerdo a lo prescrito en el Art. 641 de Código civil, en favor del Sr. <strong>CHRISTIAN MERCADO PERICON</strong></em><em>, con</em><em><strong> C.I.1919784 BE, </strong> por la suma libremente convenida de <strong>Bs. {{NumerosEnLetras::convertir($garment->amountLoanDollar,'Dolares',true)}}</strong> valor que declaro haber recibido en moneda de un curso legal y corriente, a tiempo de suscribir la presente munuta, a mi plena satisfacción.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>TERCERA .- </strong></span></em><em>Consecuentemente, en virtud de lo expuesto en la clausula anterior, el vendedor, Sr. <strong>{{$garment->people->first_name}} {{$garment->people->last_name1}} {{$garment->people->last_name2}}</strong>, se reserva el derecho de rescate o de retraer a su dominio, VEHICULO vendido, previo abono de su precio legítimo, dentro del plazo de <strong>{{$garment->month}} Mes</strong> a contar de la suscripción de la escritura pública de la transferencia, aclarándose del mismo modo que, si el vendedor no comunica al comprador Sr. <strong>CHRISTIAN MERCADO PERICON</strong>, su declaración de rescate con la protesta de reembolsar los gasto efectuado y que deben ser objeto de comprobación y liquidación, dentro del témino fijado en la presnete cláusula, caducara ese su derecho, conforme a los prescrito por el Art. 664 del señalado Código Civil, convirtiendo al comprador en irrevocable propietario con todos los derechos otorgados por ley.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CUARTA .- </strong></span></em><em>El vendedor, Sr. <strong>{{$garment->people->first_name}} {{$garment->people->last_name1}} {{$garment->people->last_name2}}</strong> sin embargo, queda obligado, en término de ley, a la evicción y saneamiento de este contrato, en concepto de hallarse el Vehículo vendio libre de toda carga, o gravámenes.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>QUINTA .- </strong></span></em><em>La presente Minuta a solo reconocimientode firma tendrá valor de Documento Privado, en tanto no llegue a protocolizarse.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>SEXTA .- </strong></span></em><em> Yo <strong>{{$garment->people->first_name}} {{$garment->people->last_name1}} {{$garment->people->last_name2}}</strong> como vendedor, por una parte y <strong>CHRISTIAN MERCADO PERICON</strong> como comprador, en conformidad con la cláusulas suscritas en la presente minuta, firmamos el presente documento. <br>Ud. Señor Notario se servirá agregar las demas cláusulas y seguridad y estilo, para mayor validez del protocolo.</em></p>
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
                    <b>VENDEDOR</b> <br>
                    <b>C.I. {{$garment->people->ci}}</b><br>
                    {{-- <b>DEUDOR</b> --}}
                </td>
                <td style="width: 50%">
                    ....................................................... <br>
                    <em>CHRISTIAN MERCADO PERICÓN</em> <br>
                    <b>COMPRADOR</b><br>
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