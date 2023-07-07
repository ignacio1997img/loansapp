@extends('voyager::master')

@section('page_title', 'Viendo detalle de prenda')
@if (auth()->user()->hasPermission('read_garments'))

@section('page_header')
    <h1 id="titleHead" class="page-title">
        <i class="fa-solid fa-handshake"></i> Detalle de la Prenda
        <a href="{{ route('garments.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cod. Prenda</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->code}} </small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de Registro</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{ date('d-m-Y H:i', strtotime($garment->created_at)) }}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Feche Entrega del Dinero</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{ $garment->dateDelivered?date('d-m-Y H:i', strtotime($garment->dateDelivered)):'Sin Entregar' }}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
  


                        <div class="col-md-2">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Estado</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                @if ($garment->status == 'pendiente')
                                    <span class="label label-danger">Pendiente</span>
                                @endif
                                @if ($garment->status == 'aprobado')
                                    <span class="label label-dark">Aprobado</span>
                                @endif
                                @if ($garment->status == 'enpago')
                                    <span class="label label-primary">En Pago</span>
                                @endif
                                @if ($garment->status == 'finalizado')
                                    <span class="label label-success">Finalizado</span>
                                @endif
                             
                            </div>
                            <hr style="margin:0;">
                        </div>  
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Monto Prestado (Bs)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>Bs. {{$garment->amountLoan}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>     
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Monto Prestado ($)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>$. {{$garment->amountLoanDollar}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>    
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Interes Prestamos (%)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->porcentage}} %</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div> 
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Interes a Pagar (Bs.)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>Bs. {{$garment->amountPorcentage}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>          
                    </div>
                </div>
                <div class="panel panel-bordered">                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Articulo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->article}} </small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Modelo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->modelGarment}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Marca</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->brandGarment}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>

                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Categoría</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{ $garment->categoryGarment}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>


                         
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Detalle de la Prenda</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p style="padding: 0px">{!! $garment->articleDescription !!}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>             
                    </div>
                </div>
                <div class="panel panel-bordered">
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Ci/Pasaporte</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->people->ci??'SN'}} </small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        

                        <div class="col-md-5">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Nombre del Beneficiario</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->people->first_name.' '.$garment->people->last_name.' '.$garment->people->last_name1}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>  
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Género</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->people->gender?ucfirst( $garment->people->gender):'SN'}} </small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>


                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">F. Nacimiento</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">                               
                                <p><small>{{ date('d-m-Y H:i', strtotime($garment->people->birth_date)) }}</small></p>                                
                            </div>
                            <hr style="margin:0;">
                        </div>  
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Celular</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->people->cell_phone??'SN'}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>  
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Telefono</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->people->phone??'SN'}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>     
                    </div>
                </div>

                {{-- Para mostrar los meses --}}

                <div class="panel panel-bordered">       
                    <h4 id="titleHead">Tiempo de la Prenda</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="dataStyle" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 50px">Nº</th>
                                            <th class="text-center">Fecha Inicio</th>
                                            <th class="text-center">Fecha Fin</th>                    
                                            <th class="text-center">Monto</th>   
                                            <th class="text-center">Estado</th>            
                                            {{-- <th style="width: 50px" class="text-right">Acciones</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                            $x= 0;
                                        @endphp
                                        @forelse ($garment->months as $item)
                                            @php
                                                $i = $i+1;
                                                if ($item->status=='pendiente')
                                                {
                                                    $x = $x+1;
                                                }

                                            @endphp
                                            <tr>
                                                <td class="text-center"><small>{{ $i }}</small></td>
                                                <td class="text-center">{{ date("d-m-Y", strtotime($item->start)) }}</td>
                                                <td class="text-center">{{ date("d-m-Y", strtotime($item->finish)) }}</td>
                                                <td style="text-align: right"><small>Bs. {{ number_format($item->amount,2, ',','.') }}</small></td>

                                                <td class="text-center">
                                                    @if ($item->status == 'pendiente')
                                                        <label class="label label-danger">PENDIENTE</label>                            
                                                    @endif
                                                    @if ($item->status == 'pagado')
                                                        <label class="label label-success">PAGADO</label>                            
                                                    @endif
                                                    <br>
                                                    @if ($item->status=='pendiente')
                                                        @if ($x==1)
                                                            <a data-toggle="modal" data-target="#modal-addmoney" data-money="{{$item->amount}}" onclick="successItem('{{ route('garments-payment-month.add', ['month' => $item->id]) }}')" title="Pagar Mes"  class="btn btn-success">
                                                                <i class="fa-solid fa-money-bill"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </td>
                                                
                                            </tr>
                                        @empty
                                            <tr>
                                                <td style="text-align: center" valign="top" colspan="5" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                    </div>
                   
                </div>
                @if ($garment->status != 'pendiente')                    
                <div class="panel panel-bordered">         
                    <h4 id="titleHead">Transacciones / Pagos Realizados</h4>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="dataStyle" class="table-hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; width:12%">N&deg; Transacción</th>
                                            <th style="text-align: center">Monto</th>
                                            <th style="text-align: center">Fecha</th>
                                            <th style="text-align: center">Atendido Por</th>
                                            <th style="text-align: right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaction as $item)
                                            <tr>
                                                {{-- <td style="text-align: center">{{$item->id}}</td> --}}
                                                <td style="text-align: center">{{$item->transaction_id}}</td>
                                                <td style="text-align: center">
                                                    @if ($item->deleted_at)
                                                        <del>BS. {{$item->amount}} <br></del>
                                                        <label class="label label-danger">Anulado por {{$item->eliminado}}</label>
                                                    @else
                                                    BS. {{$item->amount}}
                                                    @endif
                                                </td>
                                                <td style="text-align: center">
                                                    {{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}
                                                </td>
                                                <td style="text-align: center">{{$item->agentType}} <br> {{$item->name}}</td>
                                                <td class="no-sort no-click bread-actions text-right">
                                                    @if(!$item->deleted_at)
                                                        <a onclick="printDailyMoney({{$item->garment}}, {{$item->transaction_id}})" title="Imprimir"  class="btn btn-danger">
                                                            <i class="glyphicon glyphicon-print"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach                                                                               
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <div id="popup-button">
        <div class="col-md-12" style="padding-top: 5px">
            <h4 class="text-muted">Desea imprimir el comprobante?</h4>
        </div>
        <div class="col-md-12 text-right">
            <button onclick="javascript:$('#popup-button').fadeOut('fast')" class="btn btn-default">Cerrar</button>
            <a id="btn-print" onclick="printDailyMoneys()"  title="Imprimir" class="btn btn-danger">Imprimir <i class="glyphicon glyphicon-print"></i></a>
            {{-- <button type="submit" id="btn-print" title="Imprimir" class="btn btn-danger" onclick="printDailyMoney()" class="btn btn-primary">Imprimir <i class="glyphicon glyphicon-print"></i></button> --}}

        </div>
    </div>

    <div class="modal modal-dark fade" data-backdrop="static" tabindex="-1" id="modal-addmoney" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa-solid fa-money-check-dollar"></i> Pagar Mes</h4>
                </div>
                <form action="#" id="success_form" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="text-center" style="text-transform:uppercase">
                        <i class="fa-solid fa-money-check-dollar" style="color: rgb(68, 68, 68); font-size: 5em;"></i>
                        <br>
                        
                        <p><b>Desea Pagar?</b></p>
                    </div>
                    <div class="text-center" style="text-transform:uppercase">
                        
                        <small style="font-size: 35px" id="money_text"></small>
                    </div>
                    <div class="text-center">
                        {{-- <div class="col-md-12">                            --}}                                
                            <div class="form-group">
                                <input type="radio" id="html" name="qr" value="Efectivo" checked>
                                <label for="html"><small style="font-size: 15px">Efectivo</small></label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" id="css" name="qr" value="Qr">
                                <label for="css"><small style="font-size: 15px">QR</small></label>
                            </div>
                        {{-- </div> --}}
                    </div>
                    <div>
                        <label class="checkbox-inline"><input type="checkbox" value="1" required>Confirmar Pago..!</label>
                    </div>

                    <input type="hidden" name="garment_id" value="{{$garment->id}}">
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-dark pull-right delete-confirm" value="Sí, pagar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>



@stop
@section('css')
<style>
    /* .alltables {
                width: 100%;
            }
            .alltables td{
                padding: 2px;
            }
            .box-section {
                margin-top: 1mm;
                border: 1px solid #000;
                padding: 8px;
            } */

        /* .form-group{
            margin-bottom: 10px !important;
        }
        .label-description{
            cursor: pointer;
        } */
        #popup-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            height: 100px;
            background-color: white;
            box-shadow: 5px 5px 15px grey;
            z-index: 1000;

            /* Mostrar/ocultar popup */
            /* @if(session('garment_id')) */
                animation: show-animation 1s;
            /* @else */
                right: -500px;
            /* @endif */
        }

        @keyframes show-animation {
            0% {
                right: -500px;
            }
            100% {
                right: 20px;
            }
        }
</style>
@stop

@section('javascript')
    <script>

        let garment_id=0;
        let transaction_id=0;
        $(document).ready(function(){

            @if(session('garment_id'))
                garment_id = "{{ session('garment_id') }}";
                // alert(garment_id)
                transaction_id = "{{ session('transaction_id') }}";
            @endif

                // Ocultar popup de impresión
            setTimeout(() => {
                $('#popup-button').fadeOut('fast');
            }, 8000);
        });
        function printDailyMoneys()
        {
            window.open("{{ url('admin/garments/payment/money/print') }}/"+garment_id+"/"+transaction_id, "Recibo", `width=700, height=700`)
        }
       
       function successItem(url){
            $('#success_form').attr('action', url);
        }

        $('#modal-addmoney').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) 
            var money = button.data('money')
            // alert(data)
            var modal = $(this)
            modal.find('.modal-body #money_text').text('Bs. '+money)
        });


        function printDailyMoney(garment_id, transaction_id)
            {
                // alert(transaction_id);
                window.open("{{ url('admin/garments/payment/money/print') }}/"+garment_id+"/"+transaction_id, "Recibo", `width=320, height=700`)
            }
            
    </script>
@stop
@endif
