<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cierre de caja</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            max-width: 740px;
        }
        #watermark {
            position: absolute;
            opacity: 0.1;
            z-index:  -1000;
        }
        #watermark img{
            position: relative;
            width: 300px;
            height: 300px;
            left: 205px;
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
    @for ($i = 0; $i < 2; $i++)
    <div style="height: 45vh" @if ($i == 1) class="show-print" @else class="border-bottom" @endif>
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h3 style="margin-bottom: 0px; margin-top: 5px">CAJAS - LOANSAPP<br> <small>CIERRE DE CAJA</small> </h3>
                </td>
            </tr>
        </table>
        <hr style="margin: 0px">
        <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div>
        <table width="100%" cellpadding="5" style="font-size: 12px">
            <tr>
                <td><b>FECHA</b></td>
                <td style="border: 1px solid #ddd">
                    @php
                        $dias = ['', 'Lunes', 'Martes', 'Mi??rcoles', 'Jueves', 'Viernes', 'S??bado', 'Domingo'];
                    @endphp
                    {{ $dias[date('N', strtotime($cashier->created_at))].', '.date('d', strtotime($cashier->created_at)).' de '.date('m', strtotime($cashier->created_at)).' de '.date('Y', strtotime($cashier->created_at)) }}
                </td>
                <td><b>HORA</b></td>
                <td style="border: 1px solid #ddd">
                    {{ date('H:i:s', strtotime($cashier->created_at)) }}
                </td>
            </tr>
            <tr>
                <td><b>CAJERO(A)</b></td>
                <td style="border: 1px solid #ddd">{{ $cashier->user->name }}</td>
                {{-- <td><b>CI</b></td>
                <td style="border: 1px solid #ddd">{{ $cashier->user->ci }}</td> --}}
            </tr>
        </table>
        <table width="100%" cellpadding="10" style="font-size: 12px">
            <tr>
                <td width="70%">
                    <table width="100%" cellpadding="3">
                        <tr>
                            <td colspan="4" style="text-align: center"><b><small>DETALLE</small></b></td>
                        </tr>
                        @php
                            $amount_open = 0;
                            $amount_in = 0;
                            $amount_out = 0;
                            $amount_transfers = 0;
                            $amount_payments = 0;
                            $amount_close = 0;

                            // Recorer movimientos de caja
                            foreach($cashier->movements as $movement){
                                if($movement->type == 'ingreso' && $movement->description == 'Monto de apertura de caja.'){
                                    $amount_open += $movement->amount;
                                }
                                if($movement->type == 'ingreso' && $movement->description != 'Monto de apertura de caja.'){
                                    $amount_in += $movement->amount;
                                }
                                if($movement->type == 'egreso'){
                                    $amount_out += $movement->amount;
                                }

                                if($movement->type == 'ingreso' && $movement->cashier_id_from){
                                    $amount_transfers += $movement->amount;
                                }
                            }

                            // Recorer pagos realizados
                            // foreach($cashier->payments as $payment){
                            //     $amount_payments += $payment->amount;
                            // }
                            $amount_payments += 0;


                            // Recorer arqueo de caja
                            foreach($cashier->details as $detail){
                                $amount_close += $detail->cash_value * $detail->quantity;
                            }




                            $loans = \App\Models\Loan::with(['loanDay', 'loanRoute', 'loanRequirement', 'people'])
                                        ->where('deleted_at', null)->where('status', 'entregado')->where('cashier_id', $cashier->id)->get();
                            $loanTotal = 0;
                            foreach ($loans as $item) {
                                $loanTotal+= $item->amountLoan;
                            }

                            $trans = \DB::table('loans as l')
                                    ->join('loan_days as ld', 'ld.loan_id', 'l.id')
                                    ->join('loan_day_agents as lda', 'lda.loanDay_id', 'ld.id')
                                    ->join('transactions as t', 't.id', 'lda.transaction_id')
                                    ->join('users as u', 'u.id', 'lda.agent_id')
                                    ->join('people as p', 'p.id', 'l.people_id')
                                    ->where('lda.status', 1)
                                    ->where('lda.deleted_at', null)
                                    ->where('lda.cashier_id', $cashier->id)

                                    ->where('ld.deleted_at', null)
                                    ->where('ld.status', 1)

                                    ->where('l.deleted_at', null)

                                    ->select('l.id as loan', DB::raw('SUM(lda.amount)as amount'), 'u.name', 'lda.agentType', 'p.id as people', 'lda.transaction_id', 't.transaction', 't.created_at')
                                    ->groupBy('loan', 'transaction')
                                    ->orderBy('transaction', 'ASC')
                                    ->get();
                            $transTotal = 0;
                            foreach ($trans as $item) {
                                $transTotal+= $item->amount;
                            }

                        @endphp
                        <tr>
                            <td width="120px"><b>DINERO ASIGNADO A CAJA</b></td>
                            <td style="border: 1px solid #ddd" colspan="3">{{ number_format($amount_open, 2, ',', '.') }}</td>
                        </tr>
                        {{-- <tr>
                            <td colspan="2" style="text-align: center"><b><small>MOVIMIENTOS</small></b></td>
                        </tr> --}}
                        <tr>
                            <td><b>PAGOS RECIBIDOS TOTAL</b></td>
                            <td style="border: 1px solid #ddd" colspan="3">{{ number_format($transTotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><b>PRESTAMO ENTREGADOS</b></td>
                            <td style="border: 1px solid #ddd" colspan="3">{{ number_format($loanTotal, 2, ',', '.') }}</td>
                        </tr>
                        {{-- <tr>
                            <td><b>TRASPASOS TOTAL</b></td>
                            <td style="border: 1px solid #ddd" colspan="3">{{ number_format($amount_transfers, 2, ',', '.') }}</td>
                        </tr> --}}
                        <tr>
                            <td colspan="4" style="text-align: center"><b><small>RECIBIDO</small></b></td>
                        </tr>
                        @php
                            $total_cashier = $amount_open + $amount_in - $amount_out - $amount_payments;
                        @endphp
                        <tr>
                            <tr>
                                <td><b>SALDO CAJA</b></td>
                                <td style="border: 1px solid #ddd">{{ number_format($cashier->movements[0]->balance, 2, ',', '.') }}</td>
                            {{-- </tr>
                            <tr> --}}
                                {{-- <td style="text-align: right; width: 120px"><b>SALDO ARQUEO</b></td>
                                <td style="border: 1px solid #ddd">{{ number_format($amount_close, 2, ',', '.') }}</td> --}}
                            </tr>
                        </tr>
                        {{-- @php
                            $amount_diff = $total_cashier - $amount_close;
                        @endphp
                        <tr>
                            <tr>
                                <td><b>MONTO FALTANTE</b></td>
                                <td style="border: 1px solid #ddd">{{ number_format( $amount_diff > 0 ? abs($amount_diff) : 0, 2, ',', '.') }}</td>
                       
                                <td style="text-align: right; width: 120px"><b>MONTO SOBRANTE</b></td>
                                <td style="border: 1px solid #ddd">{{ number_format($amount_diff < 0 ? abs($amount_diff) : 0, 2, ',', '.') }}</td>
                            </tr>
                        </tr> --}}
                    </table>
                    <br>
                    <b>CORTES DE BILLETES</b>
                    <div style="display: flex; border: 1px solid #ddd; padding: 10px 0px; margin-top: 10px">
                        @foreach ($cashier->details as $cash)
                            <div style="width: 33%; text-aign: center">
                                <div style="text-align: center">{{ $cash->cash_value >= 1 ? intval($cash->cash_value) : number_format($cash->cash_value, 2, '.', '') }}</div>
                                <div style="text-align: center">&darr;</div>
                                <div style="text-align: center"><b>{{ intval($cash->quantity) }}</b></div>
                            </div>
                        @endforeach
                    </div>
                </td>
                <td width="30%">
                    <div>
                        <br>
                        <br>
                        <br>
                        <p style="text-align: center">.............................................. <br> <small>{{ strtoupper($cashier->user->name) }}</small> <br> <small>{{ $cashier->user->ci }}</small> <br> <b>{{ strtoupper($cashier->user->role->display_name) }}</b> </p>
                    </div>
                    <br>
                    <div>
                        <br>
                        <p style="text-align: center">.............................................. <br> <small>{{ strtoupper(Auth::user()->name) }}</small> <br> <small>{{ Auth::user()->ci }}</small> <br> <b>{{ strtoupper(Auth::user()->role->display_name) }}</b> </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @endfor

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