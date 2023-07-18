<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante de pago</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            /* margin: 100px auto; */
            font-family: Arial, sans-serif;
            /* font-weight: 100; */
            max-width: 1000px;
        }
        /* #watermark {
            position: absolute;
            opacity: 0.1;
            z-index:  -1000;
        }
        #watermark-stamp {
            position: absolute;
            z-index:  -1000;
        }
        #watermark img{
            position: relative;
            width: 300px;
            height: 300px;
            left: 205px;
        }
        #watermark-stamp img{
            position: relative;
            width: 4cm;
            height: 4cm;
            left: 50px;
            top: 70px;
        } */
        .show-print{
            display: none;
            padding-top: 15px
        }
        .btn-print{
            padding: 5px 10px
        }
        @media print{
            .hide-print, .btn-print{
                display: none
            }
            .show-print, .border-bottom{
                display: block
            }
            .border-bottom{
                border-bottom: 1px solid rgb(90, 90, 90);
                padding: 20px 0px;
            }
        }

        @media all {
        div.saltopagina{
            display: none;
        }
        }
        
        @media print{
        div.saltopagina{
            display:block;
            page-break-before:always;
        }
        }
    </style>
</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    

        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 50px; font-size: 30px"><small>COMPROBANTE DE PAGO</small> </h3>
                </td>
            </tr>
        </table>
        <hr>
        {{-- <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div> --}}
        <table width="100%" cellpadding="5" style="font-size: 18px">
            <tr>
                <th style="text-align: right; width: 10%">
                    CODIGO:
                </th>
                <td>
                    {{ $garment->code }}
                </td>
                <td rowspan="4" style="text-align: right">
                    {!! QrCode::size(120)->generate('Codigo: '.$garment->code.', Fecha de Pago: '.Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s').', CI: '.$garment->people->ci.
                    ', Beneficiario: '.$garment->people->last_name1.' '.$garment->people->last_name2.' '.$garment->people->first_name.', Monto Total Pagado: '.$loanDayAgent->SUM('amount').
                    ', Atendido Por: '.$loanDayAgent[0]->agent->name.', Codigo de Transaccion: '.$transaction->transaction
                    ); !!} <br>
                    {{-- {!!DNS2D::getBarcodeHTML('4445645656', 'DATAMATRIX');!!} --}}
                    {{-- {!!DNS1D::getBarcodeSVG('2', 'CODABAR' ,1,100);!!} --}}
                    {{-- {!! DNS1D::getBarcodeHTML('4445645656', 'C128C');!!} --}}
                    {{-- {!! DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T',3,33); !!} --}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    PAGO:
                </th>
                <td>
                    {{$transaction->type=='Efectivo'?'EFECTIVO':'QR'}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    FECHA:
                </th>
                <td>
                    {{Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s')}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    CI:
                </th>
                <td>
                    {{$garment->people->ci}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    BENEFICIARIO:
                </th>
                <td colspan="2">
                    {{$garment->people->last_name1}} {{$garment->people->last_name2}} {{$garment->people->first_name}}
                </td>
            </tr>
            {{-- <hr> --}}
            {{-- <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr> --}}
            {{-- <tr>
                <th style="text-align: right; width: 10%">
                    ARTICULO:
                </th>
                <td colspan="2">
                    {{$garment->article}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    MODELO:
                </th>
                <td colspan="2">
                    {{$garment->modelGarment}}
                </td>
            </tr>   
            <tr>
                <th style="text-align: right; width: 10%">
                    MARCA:
                </th>
                <td colspan="2">
                    {{$garment->brandGarment}}
                </td>
            </tr>  
            <tr>
                <th style="text-align: right; width: 10%">
                    DETALLE:
                </th>
                <td colspan="2">
                    {!! $garment->articleDescription !!}
                </td>
            </tr>   --}}
            
        </table>
        {{-- <hr>

        <table width="100%" cellpadding="5"  style="font-size: 18px">
            <tr>
                <th style="text-align: right; width: 10%">
                    ARTICULO:
                </th>
                <td colspan="2">
                    {{$garment->article}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    MODELO:
                </th>
                <td colspan="2">
                    {{$garment->modelGarment}}
                </td>
            </tr>   
            <tr>
                <th style="text-align: right; width: 10%">
                    MARCA:
                </th>
                <td colspan="2">
                    {{$garment->brandGarment}}
                </td>
            </tr>            
        </table> --}}
        <hr>
        {{-- <hr> --}}
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 0px; font-size: 20px"><small>DETALLE DEL PAGO</small> </h3>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="2" cellspacing="0" border="0" style="font-size: 18px">
            <tr style="text-align: center">
                <th class="border" style="width: 75%">
                    DETALLES
                </th>                
                <th class="border" style="width: 25%">
                    TOTAL
                </th>
            </tr>
            @php
                $total=0;
            @endphp
            @foreach ($loanDayAgent as $item)
                <tr>
                    <td style="text-align: center">
                        @if($item->garmentMonth_id)                        
                            {{Carbon\Carbon::parse($item->garmentMonth->start)->format('d/m/Y')}} - {{Carbon\Carbon::parse($item->garmentMonth->finish)->format('d/m/Y')}}
                        @else
                            Recojo del Artículo/Prenda
                        @endif
                    </td>                    
                    <td style="text-align: right">
                        {{number_format($item->amount, 2 , ',', '.')}}
                    </td>
                    @php
                        $total+=$item->amount;
                    @endphp
                </tr>
            @endforeach
            <tr>
                <th class="border" style="text-align: center; width: 75%">
                    TOTAL (BS)
                </th>
                <th class="border" style="text-align: right; width: 25%">
                    {{ number_format($total, 2 , ',', '.') }}
                </th>
            </tr>
        </table>
        {{-- <hr> --}}
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 5px; font-size: 20px"><small>ATENDIDO POR</small> </h3>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="2" cellspacing="0" border="0" style="font-size: 17px">
            <tr>
                <td style="text-align: right; width: 40%">
                    {{strtoupper($loanDayAgent[0]->agentType)}}:
                </td>
                <td style="text-align: center; width: 60%">
                    {{strtoupper($loanDayAgent[0]->agent->name)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: right; width: 40%">
                    COD TRANS:
                </td>
                <td style="text-align: center; width: 60%">
                    {{$transaction->transaction}}
                    {{-- {{str_pad($transaction_id, 15, '0', STR_PAD_LEFT);}} --}}
                </td>
            </tr>
        </table>
        <br>
        

        <hr>
        <table width="100%" cellpadding="5" style="font-size: 17px">
            
            <tr>
                <th style="text-align: right; width: 10%">
                    CI:
                </th>
                <td>
                    _______________________________________
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    NOMBRE:
                </th>
                <td>
                    _______________________________________
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    FIRMA:
                </th>
                <td>
                    _______________________________________
                </td>
            </tr>
            
        </table>
        {{-- <table width="100%" cellpadding="5">
            <tr>
                <th style="text-align: center">
                    {!! QrCode::size(120)->generate('Contrato:' ); !!} <br>
                </th>
            </tr>
        </table> --}}
        <br><br>
        <table width="100%" style="font-size: 15px">
            <tr style="text-align: center">
                <td>
                    <small><b>Impreso por: {{ Auth::user()->name }} <br> {{ date('d/m/Y H:i:s') }}</b></small>
                    <br>
                    <small><b>LOANSAPP V1</b></small>
                </td>
            </tr>
        </table>
        

    <style>
        .border{
            border: solid 1px black;
        }
    </style>
<script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>

    <script>
        $(function() {
            // alert(1);
            window.print();
        
        });
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                default:
                    break;
            }
        });
    </script>
</body>
</html>