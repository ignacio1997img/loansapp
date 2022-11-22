<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boleta de pago</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            max-width: 300px;
        }
        #watermark {
            position: absolute;
            opacity: 0.1;
            z-index:  -1000;
        }
        #watermark-stamp {
            position: absolute;
            /* opacity: 0.9; */
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
        }
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
    {{-- <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div> --}}
    
    <div >
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 50px; font-size: 15px"><small>COMPROBANTE DE PAGO <br>CAPRESI</small> </h3>
                </td>
            </tr>
        </table>
        <hr>
        {{-- <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div> --}}
        <table width="100%" cellpadding="5" style="font-size: 10px">
            <tr>
                <th style="text-align: right; width: 10%">
                    FECHA:
                </th>
                <td>
                    {{ date('d/m/Y H:i:s') }}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    BENEFICIARIO:
                </th>
                <td>
                    {{$loan->people->last_name1}} {{$loan->people->last_name2}} {{$loan->people->first_name}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    CI:
                </th>
                <td>
                    {{$loan->people->ci}}
                </td>
            </tr>
        </table>
        {{-- <hr> --}}
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 0px; font-size: 12px"><small>DETALLE DEL PAGO</small> </h3>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="2" cellspacing="0" border="0" style="font-size: 12px">
            <tr style="text-align: center">
                <th class="border" style="width: 50%">
                    FECHA
                </th>
                <th class="border" style="width: 50%">
                    TOTAL
                </th>
            </tr>
            @php
                $total=0;
            @endphp
            @foreach ($loanDayAgent as $item)
                <tr>
                    <td style="text-align: left">
                        {{Carbon\Carbon::parse($item->date)->format('d/m/Y')}}
                    </td>
                    <td style="text-align: right">
                        {{$item->amount}}
                    </td>
                    @php
                        $total+=$item->amount;
                    @endphp
                </tr>
            @endforeach
            <tr>
                <th class="border" style="text-align: center; width: 50%">
                    TOTAL (BS)
                </th>
                <th class="border" style="text-align: right; width: 50%">
                    {{$total}}
                </th>
            </tr>
        </table>
        {{-- <hr> --}}
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 5px; font-size: 12px"><small>ATENDIDO POR</small> </h3>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="2" cellspacing="0" border="0" style="font-size: 12px">
            <tr>
                <td style="text-align: right">
                    {{strtoupper($loanDayAgent[0]->agentType)}}:
                </td>
                <td style="text-align: center">
                    {{strtoupper($loanDayAgent[0]->name)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    COD TRANS:
                </td>
                <td style="text-align: center">
                    {{str_pad($transaction_id, 15, '0', STR_PAD_LEFT);}}
                </td>
            </tr>
        </table>
        <hr>
        <table width="100%" cellpadding="5" style="font-size: 10px">
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
        <table width="100%" style="font-size: 8px">
            <tr style="text-align: center">
                <td>
                    <small><b>SISTEMA LOANSAPP V1</b></small>
                </td>
            </tr>
        </table>

    </div>
    <style>
        .border{
            border: solid 1px black;
        }
    </style>
    <script>
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