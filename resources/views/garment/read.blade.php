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
                    @if ($garment->status == 'recogido')
                        <div class="alert alert-success">
                            <strong>Aviso:</strong>
                            <p>Este Artículo / Producto ya se encuentra pagado y recogido.</p>
                        </div>
                    @endif
                    

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
                                @if ($garment->status == 'entregado')
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
                                <p><small>Bs. {{ number_format($garment->amountLoan,2, ',','.') }}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>     
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Monto Prestado ($)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>$ {{ number_format($garment->amountLoanDollar,2, ',','.') }}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>    
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Interes Prestamos (%)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{ number_format($garment->porcentage,2, ',','.') }} %</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div> 
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Interes a Pagar (Bs.)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>Bs. {{ number_format($garment->amountPorcentage,2, ',','.') }}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>    
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tipo de Contrato</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->type =='compacto'?'Minuta de Compacto de Rescate':'Contrato Privado'}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>     
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cantidad de Mes de Espera</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small> {{$garment->month==1?'1 mes':'3 meses'}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>     
                        {{-- <input type="text" name="amount[]" value="" class="form-control"> --}}
                    </div>
                </div>
                
                <div class="panel panel-bordered">            
                    {{-- <hr style="border: 5px solid #22a7f0; border-radius: 10px;">     --}}
                    <div class="row">
                        @forelse ($garment->garmentArticle as $item)
                            <div class="col-md-12">
                                <hr style="border: 5px solid #22a7f0; border-radius: 10px;"> 
                                <h2 id="titleHead" style="text-align: center">{{$item->article}}</h2>
                            </div>
                            
                            @foreach ($item->garmentArticleDetail as $itm)
                                <div class="col-md-3">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">{{$itm->title}}</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p><small>{{$itm->value}}</small></p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                            @endforeach
                            <div class="col-md-3">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Sub Total
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p><small>{{ number_format($item->amountSubTotal,2, ',','.') }}</small></p>
                                </div>
                                <hr style="margin:0;">
                            </div> 
                            
                        @empty
                        {{-- <tr>
                            <td style="text-align: center" valign="top" colspan="5" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                        </tr> --}}
                        @endforelse
                        
                    
                        

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
                            {{-- @if ($garment->status == 'entregado' && $garment->deleted_at == null)
                                <a data-toggle="modal" data-target="#modal_finish" data-money="" title="Pagar"  class="btn btn-success">
                                    <i class="fa-solid fa-money-bill"></i> Recoger Prenda
                                </a>
                            @endif --}}
                            
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


    {{-- Para finalizar el hopedaje --}}
    <form lass="form-submit" id="menu-form" action="{{route('garments-payment-month-add.all', ['garment_id'=>$garment->id])}}" method="post">
        @csrf
        <div class="modal fade" id="modal_finish" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-success">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-hourglass-end"></i> Recoger Prenda</h4>
                    </div>
                    <div class="modal-body">
                        {{-- <input type="hidden" name="room_id" id="room_id"> --}}
                        <div class="form-group">
                            <div class="table-responsive">
                                <table id="dataStyle" class="tables tablesMenu table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px">N&deg;</th>
                                            <th style="text-align: center">Fecha Inicio</th>  
                                            <th style="text-align: center">Fecha Fin</th>  
                                            <th style="text-align: center; width: 120px">Monto</th>  
                                        </tr>
                                    </thead>
                                    <tbody id="table-bodyFinish">
                                        <tr id="tr-emptyMenuFinish">
                                            <td colspan="4" style="height: 30px">
                                                <h4 class="text-center text-muted" style="margin-top: 5px">
                                                    <i class="fa-solid fa-list" style="font-size: 20px"></i> <br>
                                                    Lista de meses con deuda vacía
                                                </h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tr>
                                        <td colspan="3" style="text-align: right">
                                            Total <small>Bs.</small>
                                        </td>
                                        <td style="text-align: right">
                                            <small><b id="label-totalDetailFinish" class="label-totalDetailFinish">0.00</b></small>
                                            <input type="hidden" id="subTotalDetalle" name="subTotalDetalle">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>



                        <div class="col-md-12">                            
                            
                            <div class="form-group">
                                <input type="radio" id="html" name="qr" value="0" checked>
                                <label for="html"><small style="font-size: 15px">Efectivo</small></label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" id="css" name="qr" value="1">
                                <label for="css"><small style="font-size: 15px">QR</small></label><br>
                            </div>
                        </div>
                    </div>
                    
                    

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-success btn-submit" value="Finalizar">
                    </div>
                </div>
            </div>
        </div>
    </form>

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
                    <button type="submit" id="btn_submit" class="btn btn-dark pull-right delete-confirm">Sí, pagar</button>

                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>



@stop
@section('css')
<style>
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

            $('#success_form').submit(function(e){
                    $('#btn_submit').text('Pagando...');
                    $('#btn_submit').attr('disabled', true);

            });
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

        $('#modal_finish').on('show.bs.modal', function (event)
        {
            var button = $(event.relatedTarget);

            var id = '{{$garment->id}}';

            $.get('{{route('garments-list-month-debt.all')}}/'+id, function (data) {
                // alert(data.months[0].id);
                
                detailTotal=0;
                for (i = 0; i < data.months.length; i++) {
                    if(i==0)
                    {
                        $('#table-bodyFinish').empty();
                    }
                    if(i+1 <= data.month)
                    {
                        $('#table-bodyFinish').append(`   
                            <tr class="tr-item">
                                <td class="td-itemMenu">
                                    ${i+1}
                                    <input type="hidden" min="0" name="months[]" value="${data.months[i].id}" class="form-control">
                                </td>
                                <td style="text-align: center">
                                    <small>${moment(data.months[i].start).format('DD-MM-YYYY')}</small>
                                </td>
                                <td style="text-align: center">
                                    <small>${moment(data.months[i].finish).format('DD-MM-YYYY')}</small>
                                </td>
                                <td style="text-align: right">
                                    <small>${data.months[i].amount}</small>
                                    <input type="hidden" id="select-cant-${data.months[i].id}" onkeyup="getTotal()" onchange="getTotal()" min="0" name="amount[]" style="text-align: right" value="${data.months[i].amount}" class="form-control label-subtotal">
                                </td>

                            </tr>`
                        );
                    }
                    else
                    {
                        $('#table-bodyFinish').append(`   
                            <tr class="tr-item">
                                <td class="td-itemMenu">
                                    ${i+1}
                                    <input type="hidden" min="0" name="months[]" value="${data.months[i].id}" class="form-control">
                                </td>
                                <td style="text-align: center">
                                    <small>${moment(data.months[i].start).format('DD-MM-YYYY')}</small>
                                </td>
                                <td style="text-align: center">
                                    <small>${moment(data.months[i].finish).format('DD-MM-YYYY')}</small>
                                </td>
                                <td style="text-align: right">
                                
                                    <input type="number" id="select-cant-${data.months[i].id}" onkeyup="getTotal()" onchange="getTotal()" min="0" name="amount[]" style="text-align: right" value="${data.months[i].amount}" class="form-control label-subtotal" required>
                                </td>

                            </tr>`
                        );
                    }                    
                    detailTotal = parseFloat(detailTotal) + parseFloat(data.months[i].amount);

                }     
                $('#table-bodyFinish').append(`   
                    <tr class="tr-item">
                        <td colspan="3" style="text-align: center">
                            <small>Monto prestado por la prenda</small>                            
                        </td>
                        <td style="text-align: right">
                            <small>${data.amountLoan}</small>
                            <input type="hidden" min="0" id="amountLoan" name="amountLoan" style="text-align: right" value="${data.amountLoan}" class="form-control label-subtotal">
                        </td>

                    </tr>`
                );  
                detailTotal = parseFloat(detailTotal) + parseFloat(data.amountLoan);


                $('#label-totalDetailFinish').text(detailTotal);
                $('#subTotalDetalle').val(detailTotal);

            });


            // alert(parseFloat(totalArticle+totalMenu+TotalHosp))
            
            
            
        })

        function getSubtotal(id){
                // let price = $(`#select-price-${id}`).val() ? parseFloat($(`#select-price-${id}`).val()) : 0;
                // let quantity = $(`#select-cant-${id}`).val() ? parseFloat($(`#select-cant-${id}`).val()) : 0;
                // $(`#label-subtotal-${id}`).text((price * quantity).toFixed(2));
                getTotal();
        }

        function getTotal(){
                let total = 0;
                $(".label-subtotal").each(function(index) {
                    total += $(this).val()?parseFloat($(this).val()):0;
                });
                $('#label-totalDetailFinish').text(total.toFixed(2));
                $('#subTotalDetalle').val(total.toFixed(2));
        }
            
    </script>
@stop
@endif
