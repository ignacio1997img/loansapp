@extends('voyager::master')

@section('page_title', 'Viendo Prestamos')

{{-- @if (auth()->user()->hasPermission('browse_loans')) --}}

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-4" style="padding: 0px">
                            <h1 id="titleHead" class="page-title">
                                <i class="fa-solid fa-hand-holding-dollar"></i> Prestamos
                            </h1>

                            
                        </div>
                        <div class="col-md-8 text-right" style="padding: 0px">
                            <h1 id="titleHead" class="page-title money">
                                <i class="fa-solid fa-dollar-sign"></i> {{$balance}}
                            </h1>
                            @if (auth()->user()->hasPermission('add_loans'))
                                <a href="{{ route('loans.create') }}" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Prestamo Diario</span>
                                </a>
                            @endif

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            {{-- <div class="col-sm-2">
                                <input type="text" id="input-search" class="form-control">
                            </div> --}}
                            <div class="col-sm-4" style="margin-bottom: 0px">
                                <input type="text" id="input-search" class="form-control" placeholder="Ingrese busqueda..."> <br>
                            </div>
                            <div class="col-md-12 text-right">
                                @if (!auth()->user()->hasRole('cobrador'))
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="todo">Todos</label>
                                @endif
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="entregado" checked>En Pagos</label>
                                
                                
                                @if (!auth()->user()->hasRole('cobrador'))
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="aprobado">Por Entregar</label>
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="verificado">Por Aprobar</label>
                                @endif
                                
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="pendiente">Pendientes</label>
                             

                                @if (!auth()->user()->hasRole('cobrador'))
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="pagado">Pagados</label>
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="rechazado">Rechazados</label>
                                @endif
                            </div>
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>



 
@stop

@section('css')
    <style>

    
    
    
    </style>
@stop

@section('javascript')


@stop
{{-- @endif --}}