@extends('voyager::master')

@section('page_title', 'Viendo Prendario')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-4" style="padding: 0px">
                            <h1 id="titleHead" class="page-title">
                                <i class="fa-solid fa-handshake"></i> Prendario
                            </h1>
                        </div>
                        <div class="col-md-8 text-right" style="padding: 0px">
                            <h1 id="titleHead" class="page-title money">
                                <i class="fa-solid fa-dollar-sign"></i> 0
                            </h1>
                            @if (auth()->user()->hasPermission('add_pawn'))
                                <a href="{{ route('pawn.create') }}" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Crear</span>
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
                            <div class="col-sm-7">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            
                            <div class="col-sm-2 text-right">
                                <select name="status" class="form-control select2" id="select-status">
                                    <option value="todo" selected>Todos</option>
                                    <option value="enpago">En Pago</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="porentregar">Por Entregar Prestamo</option>
                                    <option value="concluida">Prenda Concluida</option>
                                    <option value="recogido">Prenda Recogidas</option>
                                    <option value="rechazado">Rechazado</option>
                                    <option value="eliminado">Eliminado</option>
                                </select>
                            </div>

                            <div class="col-sm-3 text-right">
                                <input type="text" id="input-search" placeholder="Buscar..." class="form-control">
                                {{-- <a href="#more-options" class="btn btn-link" data-toggle="collapse"> <i class="fa-solid fa-gear"></i> Más opciones</a> --}}
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
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';   
        $(document).ready(function(){
            list();
           
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('#select-paginate').change(function(){
                countPage = $(this).val();
                list();
            });
        });

        function list(page = 1){
            let type =$("#select-status").val();
            let url = "{{ url('admin/garments/ajax/list')}}/";
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}/${type}/${search}?paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(result){
                $("#div-results").html(result);
            }});

        }
    </script>
@stop