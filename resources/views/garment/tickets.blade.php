{{-- impresion para cuando la persona entrega el prestamo al cliente --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tickets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            /* margin: 100px auto; */
            font-family: Arial, sans-serif;
            /* font-weight: 100; */
            max-width: 1000px;
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
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    

        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 50px; font-size: 18px"><small>TICKETS Nº {{$ticket->number}}</small> </h3>
                </td>
            </tr>
        </table>
        {{-- <hr> --}}
        {{-- <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div> --}}
        <table width="100%" cellpadding="5" style="font-size: 20px">
            <tr>
                <td style="text-align: center">
                    {!! QrCode::size(300)->generate($garment->code); !!}<br>
                    <br><br>

                    {!! DNS1D::getBarcodeSVG($garment->code, 'C128',2.7,120, true); !!} 
                    {{-- {!! DNS1D::getBarcodeHTML($garment->code, 'C128',3,120); !!}  --}}
                    {{-- {!! DNS1D::getBarcodeSVG($garment->code, 'CODABAR'); !!}  --}}


                    {{-- {!!   !!} --}}
                    {{-- {!!QrCode::geo(-14.830299, -64.974075);!!} --}}
                    {{-- {!!!!} --}}
                </td>
            </tr>

            


            {{-- <tr>
                <td style="text-align: center; font-size: 35px">
                    {{$garment->code}}
                </td>
            </tr> --}}

        </table>
        {{-- <hr> --}}
 
        <table width="100%" style="font-size: 12px">
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