@extends('voyager::master')

@section('content')
    <div class="page-content">
        @include('voyager::alerts')
        {{-- Vista de cajero(a) --}}
        @if (auth()->user()->hasRole('cajeros'))
            @php
                $cashier = \App\Models\Cashier::with(['movements' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'vault_details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }])
                // }, 'client'])
                ->where('user_id', Auth::user()->id)
                ->where('status', '<>', 'cerrada')
                ->where('deleted_at', NULL)->first();
                // dd($cashier);
            @endphp
            @if ($cashier)
            
                @if ($cashier->status == 'abierta' || $cashier->status == 'apertura pendiente')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-bordered">
                                <div class="panel-body">
                                    @php
                                        $cashier_in = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('amount');
                                        $cashier_balance = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('balance');
                                        // dd($cashier_in);
                                        // $cashier_out = $cashier->movements->where('type', 'egreso')->where('deleted_at', NULL)->sum('amount');
                                        // $payments = $cashier->payments->where('deleted_at', NULL)->sum('amount');
                                        

                                        // $amount = $cashier->client->where('deleted_at', null)->sum('amount');
                                        // $amount = \App\Models\Adition::where('deleted_at', null)->where('cashier_id', $cashier->id)->sum('cant');

                                        // dd($amount);
                                        $amount =2;
                                        $movements = $cashier_in + $amount;
                                        // $total = $movements - $payments;
                                        $total = $movements;


                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2 id="h2"><i class="fa-solid fa-wallet"></i> {{ $cashier->title }}</h2>
                                        </div>
                                        @if ($cashier->status == 'abierta')
                                            <div class="col-md-6 text-right">
                                                {{-- <button type="button" data-toggle="modal" data-target="#transfer-modal" class="btn btn-success">Transferir <i class="voyager-forward"></i></button> --}}
                                                <a href="{{ route('cashiers.close', ['cashier' => $cashier->id]) }}" class="btn btn-danger">Cerrar <i class="voyager-lock"></i></a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($cashier->status == 'abierta')

                        @php
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
                                // dd($loans);
                        @endphp
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 50px">
                                                <table width="100%" cellpadding="20">
                                                    <tr>
                                                        <td><small>Dinero Asignado a Caja</small></td>
                                                        <td class="text-right"><h3>{{ number_format($cashier_in, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Dinero disponible en Caja</small></td>
                                                        <td class="text-right"><h3>{{ number_format($cashier_balance, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                </table>
                                                <hr>
                                                <table width="100%" cellpadding="20">
                                                    <tr>
                                                        <td><small>Pagos Cobrados</small></td>
                                                        <td class="text-right"><h3>{{ number_format($transTotal, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Prestamos Entregados</small></td>
                                                        <td class="text-right"><h3>{{ number_format($loanTotal, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                </table>
                                            
                                            </div>
                                            <div class="col-md-6">
                                                <canvas id="myChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <h3 id="h3">Prestamos Entregados <label class="label label-danger">Egreso</label></h3>
                                        <table id="dataStyle" class="table table-bordered table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Fecha de Entrega</th>
                                                    <th>Nombre Cliente</th>                    
                                                    <th>Tipo de Préstamos</th>                    
                                                    <th>Monto Prestado</th>                    
                                                    {{-- <th>Interés a Cobrar</th>   
                                                    <th>Total a Pagar</th>  --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $amountLoanTotal = 0;
                                                @endphp
                                                @forelse ($loans as $item)
                                                    <tr>
                                                        <td>{{ $item->id }}</td>
                                                        <td>{{ date("d-m-Y", strtotime($item->dateDelivered)) }}</td>
                                                        <td>{{$item->people->first_name}} {{$item->people->last_name1}} {{$item->people->last_name2}}</td>
                                                        <td>{{$item->typeLoan}}</td>
                                                        <td style="text-align: right"> <small>Bs.</small> {{$item->amountLoan}}</td>
                                                        {{-- <td style="text-align: right"> <small>Bs.</small> {{$item->amountPorcentage}}</td>
                                                        <td style="text-align: right"> <small>Bs.</small> {{$item->amountTotal}}</td> --}}        
                                                    </tr>
                                                    @php
                                                        $amountLoanTotal+= $item->amountLoan;
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td style="text-align: center" valign="top" colspan="5" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                                                    </tr>
                                                @endforelse
                                                @if ($amountLoanTotal != 0)
                                                    <tr>
                                                        <td colspan="4">Total</td>
                                                        <td style="text-align: right"> <small>Bs.</small> {{$amountLoanTotal}}</td>     
                                                    </tr>
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php

                            
                                // dd($trans);
                        @endphp

                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <h3 id="h3">Cobros Realizados <label class="label label-success">Ingreso</label></h3>
                                        <table id="dataStyle" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width:12%">N&deg; Transacción</th>                                                    
                                                    <th style="text-align: center">Fecha</th>
                                                    <th style="text-align: center">Atendido Por</th>
                                                    <th style="text-align: center">Monto</th>
                                                    {{-- <th style="text-align: right">Acciones</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 1;
                                                    $total_movements = 0;
                                                @endphp
                                                @forelse ($trans as $item)
                                                    <tr>
                                                        <td style="text-align: center">{{$item->transaction}}</td>
                                                        <td style="text-align: center">
                                                            {{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}
                                                        </td>
                                                        <td style="text-align: center">{{$item->agentType}} <br> {{$item->name}}</td>
                                                        <td style="text-align: right">BS. {{$item->amount}}</td>
                                                    </tr>
                                                    @php
                                                        $total_movements+= $item->amount;
                                                    @endphp
                                                 @empty
                                                    <tr>
                                                        <td style="text-align: center" valign="top" colspan="4" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                                                    </tr>
                                                @endforelse
                                                @if ($total_movements != 0)
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        <td style="text-align: right"> <small>Bs.</small> {{$total_movements}}</td>     
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>                    
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 50px">
                                                <table class="table table-hover" id="dataStyle">
                                                    <thead>
                                                        <tr>
                                                            <th>Corte</th>
                                                            <th>Cantidad</th>
                                                            <th>Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $cash = ['200', '100', '50', '20', '10', '5', '2', '1', '0.5', '0.2', '0.1'];
                                                        $total = 0;
                                                    @endphp
                                                    <tbody>
                                                        @foreach ($cash as $item)
                                                        <tr>
                                                            <td><h4 style="margin: 0px"><img src=" {{ url('images/cash/'.$item.'.jpg') }} " alt="{{ $item }} Bs." width="70px"> {{ $item }} Bs. </h4></td>
                                                            <td>
                                                                @php
                                                                    $details = $cashier->vault_details->cash->where('cash_value', $item)->first();
                                                                @endphp
                                                                {{ $details ? $details->quantity : 0 }}
                                                            </td>
                                                            <td>
                                                                {{ $details ? number_format($details->quantity * $item, 2, ',', '.') : 0 }}
                                                                <input type="hidden" name="cash_value[]" value="{{ $item }}">
                                                                <input type="hidden" name="quantity[]" value="{{ $details ? $details->quantity : 0 }}">
                                                            </td>
                                                            @php
                                                            if($details){
                                                                $total += $details->quantity * $item;
                                                            }
                                                            @endphp
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <br>
                                                <div class="alert alert-info">
                                                    <strong>Información:</strong>
                                                    <p>Si la cantidad de de cortes de billetes coincide con la cantidad entregada por parte del administrador(a) de vóbeda, acepta la apertura de caja, caso contrario puedes rechazar la apertura.</p>
                                                </div>
                                                <br>
                                                <h2 id="h3" class="text-right">Total en caja: Bs. {{ number_format($total, 2, ',', '.') }} </h2>
                                                <br>
                                                <div class="text-right">
                                                    <button type="button" data-toggle="modal" data-target="#refuse_cashier-modal" class="btn btn-danger">Rechazar apertura <i class="voyager-x"></i></button>
                                                    <button type="button" data-toggle="modal" data-target="#open_cashier-modal" class="btn btn-success">Aceptar apertura <i class="voyager-key"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Aceptar apertura de caja --}}
                        <form action="{{ route('cashiers.change.status', ['cashier' => $cashier->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="status" value="abierta">
                            <div class="modal fade" tabindex="-1" id="open_cashier-modal" role="dialog">
                                <div class="modal-dialog modal-success">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><i class="fa-solid fa-wallet"></i> Aceptar apertura de caja</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted"></p>
                                            <small>Esta a punto de aceptar que posee todos los cortes de billetes descritos en la lista, ¿Desea continuar?</small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Si, aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- Rechazar apertura de caja --}}
                        <form action="{{ route('cashiers.change.status', ['cashier' => $cashier->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="status" value="cerrada">
                            <div class="modal modal-danger fade" tabindex="-1" id="refuse_cashier-modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><i class="fa-solid fa-wallet"></i> Rechazar apertura de caja</h4>
                                        </div>
                                        <div class="modal-body">
                                            <small>Esta a punto de rechazar la apertura de caja, ¿Desea continuar?</small>
                                            <p class="text-muted"></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Si, rechazar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-bordered">
                                <div class="panel-body text-center">
                                    <h2>Tienes una caja esperando por confimación de cierre</h2>
                                    <button type="button" data-toggle="modal" data-target="#cashier-revert-modal" class="btn btn-success"><i class="voyager-key"></i> Reabrir caja</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('cashiers.close.revert', ['cashier' => $cashier->id]) }}" method="post">
                        @csrf
                        <div class="modal fade" tabindex="-1" id="cashier-revert-modal" role="dialog">
                            <div class="modal-dialog modal-success">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="voyager-key"></i> Reabrir Caja</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted">Si reabre la caja deberá realizar el arqueo nuevamente, ¿Desea continuar?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success">Si, reabrir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                @endif
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <h1 class="text-center">No tienes caja abierta</h1>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
    @if (auth()->user()->hasRole('cajeros') && $cashier)
        @if ($cashier->status == 'abierta')
            <script>
                $(document).ready(function(){
                    const data = {
                        labels: [
                            // 'Dinero Asignado a Caja',
                            'Dinero Disponible en Caja',
                            'Pagos Cobrados',
                            'Prestamos Entregados'
                        ],
                        datasets: [{
                            label: 'My First Dataset',
                            data: ["{{ $cashier_balance }}", "{{$transTotal}}", "{{$loanTotal}}"],
                            backgroundColor: [
                            // 'rgb(54, 162, 257)',
                            'rgb(54, 162, 117)',
                            'rgb(12, 55, 101)',
                            'red'
                            ],
                            hoverOffset: 4
                        }]
                    };
                    const config = {
                        type: 'pie',
                        data: data,
                    };
                    var myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );

                    // Si retorna las opciones para generar recibo de traspaso a caja
                   
                });


            </script>
        @endif
    @endif

@stop
