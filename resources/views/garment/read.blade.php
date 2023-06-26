@extends('voyager::master')

@section('page_title', 'Viendo detalle de prenda')
@if (auth()->user()->hasPermission('read_garments'))

@section('page_header')
    <h1 class="page-title">
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
                                @if ($garment->status == 'poraprobar')
                                    <span class="label label-dark">Por Aprobar</span>
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
                                <p><small>{{$garment->people->cell_phone}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>  
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Telefono</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p><small>{{$garment->people->phone}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>        
                       
                          
                         
                  
                        
                     
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>



@stop
@section('css')
<style>
    .alltables {
                width: 100%;
            }
            .alltables td{
                padding: 2px;
            }
            .box-section {
                margin-top: 1mm;
                border: 1px solid #000;
                padding: 8px;
            }
</style>
@stop

@section('javascript')
    <script>
       

            
    </script>
@stop
@endif
