@extends('voyager::master')

@section('page_title', 'Ver Caja')


@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i> Viendo Caja 
        <a href="{{ route('cashiers.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-print"></span> Impresión <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('print.open', ['cashier' => $cashier->id]) }}" target="_blank">Apertura</a></li>
                @if ($cashier->status == 'cerrada')
                <li><a href="{{ route('print.close', ['cashier' => $cashier->id]) }}" target="_blank">Cierre</a></li>
                @endif
                {{-- <li><a href="{{ route('print.payments', ['cashier' => $cashier->id]) }}" target="_blank">Pagos</a></li> --}}
            </ul>
        </div>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Descripción</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->title }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cajero</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->user->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->observations ?? 'Ninguna' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h4>Prestamos Entregados</h4>

                            <table id="dataStyle" class="table table-bordered table-bordered">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Codigo</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Fecha Entrega</th>
                                        <th>Nombre Completo</th>
                                        <th style="text-align: right">Monto Prestado</th>
                                        <th style="text-align: right">Interes a Cobrar</th>
                                        <th style="text-align: right">Total</th>
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                        $loans=0;
                                        $interes =0;
                                        $total = 0;
                                    @endphp
                                    @foreach ($loan as $item)
                                        <tr>
                                            <td>{{ $cont }}

                                            </td>
                                            <td>
                                                {{ $item->code }}
                                                @if ($item->deleted_at)
                                                    <label class="label label-danger">Anulado</label>
                                                @endif
                                            </td>
                                            <td>{{ $item->date}}</td>
                                            <td>{{ $item->dateDelivered}}</td>
                                            <td>
                                                <p>{{ $item->people->first_name}} {{ $item->people->last_name}}</p>
                                                <p>{{ $item->people->ci}}</p>
                                            </td>
                                            <td style="text-align: right">
                                                @if ($item->deleted_at)
                                                    <del>{{ number_format($item->amountLoan, 2, ',', '.') }}</del>
                                                @else
                                                    {{ number_format($item->amountLoan, 2, ',', '.') }}
                                                @endif
                                            </td>
                                            <td style="text-align: right">
                                                @if ($item->deleted_at)
                                                    <del>{{ number_format($item->amountPorcentage, 2, ',', '.') }}</del>
                                                @else
                                                    {{ number_format($item->amountPorcentage, 2, ',', '.') }}
                                                @endif
                                            </td>
                                            <td style="text-align: right">
                                                @if ($item->deleted_at)
                                                    <del>{{ number_format($item->amountTotal, 2, ',', '.') }}</del>
                                                @else
                                                    {{ number_format($item->amountTotal, 2, ',', '.') }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if (!$item->deleted_at)
                                                    {{-- <button type="button" onclick="print_recipe({{ $payment->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Imprimir</button> --}}
                                                    <button type="button" data-toggle="modal" data-target="#delete_payment-modal" data-id="{{ $item->id }}" class="btn btn-danger btn-delete"><i class="voyager-trash"></i> Anular</button>
                                                @else
                                                    <label class="label label-danger">Prestamo eliminado</label>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                            if (!$item->deleted_at) {
                                                $interes = $interes + $item->amountPorcentage;
                                                $loans = $loans + $item->amountLoan;
                                                $total = $total + $item->amountTotal;
                                            }
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="5" style="text-align: right"><b>TOTAL</b></td>
                                        <td style="text-align: right"><b>{{ number_format($loans, 2, ',', '.') }}</b></td>
                                        <td style="text-align: right"><b>{{ number_format($interes, 2, ',', '.') }}</b></td>
                                        <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <form id="form-delete" action="{{ route('cashiers-loan.delete') }}" method="POST">
        @csrf
        <div class="modal modal-danger fade" tabindex="-1" id="delete_payment-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular el siguiente prestamos?</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="cashier_id" value="{{ $cashier->id }}">
                        <input type="hidden" name="loan_id" id="loan_id">

                        <div class="form-group">
                            <label for="observation">Motivo</label>
                            <textarea name="observations" class="form-control" rows="5" placeholder="Describa el motivo de la anulación del pago" required></textarea>
                        </div>
                        <label class="checkbox-inline"><input type="checkbox" value="1" required>Confirmar anulación</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Sí, ¡anúlalo!">
                    </div>
                </div>
            </div>
        </div>
    </form>


@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.btn-delete').click(function(){
                let loan_id = $(this).data('id');
                alert(loan_id)
                $(`#form-delete input[name="loan_id"]`).val(loan_id);
            });
        });

        // function print_recipe(id){
        //     window.open("{{ url('admin/planillas/pagos/print') }}/"+id, "Recibo", `width=700, height=500`)
        // }

        // function print_recipe_delete(id){
        //     window.open("{{ url('admin/planillas/pagos/delete/print') }}/"+id, "Recibo", `width=700, height=500`)
        // }

        // function print_transfer(id){
        //     window.open("{{ url('admin/cashiers/print/transfer/') }}/"+id, "Comprobante de transferencia", `width=700, height=500`)
        // }
    </script>
@stop
