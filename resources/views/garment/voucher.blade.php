{{-- impresion para cuando la persona entrega el prestamo al cliente --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante de entrega de Dinero por el prendario</title>
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
    </style>
</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    

        <table width="90%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 50px; font-size: 22px"><small>COMPROBANTE DE ENTREGA <br> DE PRESTAMO</small> </h3>
                </td>
            </tr>
        </table>
        <hr>
        {{-- <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div> --}}
        <table width="90%" cellpadding="5" style="font-size: 20px">
            <tr>
                <th style="text-align: right; width: 10%">
                    CODIGO:
                </th>
                <td>
                    {{ $garment->code }}
                </td>
                <td rowspan="3" style="text-align: right">
                    {!! QrCode::size(120)->generate('Codigo: '.$garment->code.', Fecha de entrega de dinero al recibir la prenda: '.Carbon\Carbon::parse($garment->dateDelivered)->format('d/m/Y').', CI: '.$garment->people->ci.
                    ' Beneficiario: '.$garment->people->last_name1.' '.$garment->people->last_name2.' '.$garment->people->first_name.', Monto Prestado: '.number_format($garment->amountLoan, 2, ',', '.').', Monto de Interes por mes: '.number_format($garment->amountPorcentage, 2, ',', '.').
                    ', Atendido Por: '.$garment->agentDelivered->role->name
                    ); !!} <br>
                    {{-- {!!!!} --}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    F. ENTREGA:
                </th>
                <td>
                    {{Carbon\Carbon::parse($garment->dateDelivered)->format('d/m/Y')}}

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
        </table>
        <hr>
        <table width="90%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 0px; font-size: 20px"><small>DETALLE DE LA PRENDA</small> </h3>
                </td>
            </tr>
        </table>

        <table width="90%" cellpadding="5"  style="font-size: 18px">
            <tr>
                <th style="text-align: right; width: 10%">
                    ARTICULO:
                </th>
                <td>
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
        </table>
        <hr>
        <table width="90%" cellpadding="2" cellspacing="0" border="0" style="font-size: 15px">
            <tr style="text-align: center">
                <th class="border" style="width: 33%">
                    MONTO PRESTADO
                </th>

                <th class="border" style="width: 33%">
                    INTERES A PAGAR
                </th>  
            </tr>
            @php
                $total=0;
            @endphp
                <tr>
                    <td style="text-align: right">
                        <b>Bs.</b> {{number_format($garment->amountLoan, 2, ',', '.')}}
                    </td>
                    <td style="text-align: right">
                        <b>Bs.</b> {{number_format($garment->amountPorcentage, 2, ',', '.')}}
                    </td>
                  
                </tr>
            
        </table>
        
        {{-- <hr> --}}
        <table width="90%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 5px; font-size: 20px"><small>ENTREGADO POR</small> </h3>
                </td>
            </tr>
        </table>
        <table width="90%" cellpadding="2" cellspacing="0" border="0" style="font-size: 15px">
            <tr>
                <td style="text-align: right; width: 40%">
                    {{strtoupper($garment->agentDelivered->role->name)}}:
                </td>
                <td style="text-align: center; width: 60%">
                    {{strtoupper($garment->agentDelivered->name)}}
                </td>
            </tr>
        </table>
        <hr>
        <table width="90%" cellpadding="5" style="font-size: 12px">
            <tr>
                <th style="text-align: right; width: 10%">
                    FIRMA:
                </th>
                <td>
                    _____________________________________
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    NOMBRE:
                </th>
                <td>
                    _____________________________________
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    CI:
                </th>
                <td>
                    _____________________________________
                </td>
            </tr>
        </table>
        <br><br>
        <table width="90%" style="font-size: 12px">
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