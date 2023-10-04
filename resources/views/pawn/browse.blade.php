@extends('voyager::master')

@section('page_title', 'Viendo Registros de Empeño')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-4">
                            <h1 id="titleHead" class="page-title">
                                <i class="fa-solid fa-handshake"></i> Registros de Empeño
                            </h1>
                        </div>
                        <div class="col-md-8 text-right">
                            <br>
                            {{-- <h1 id="titleHead" class="page-title money">
                                <i class="fa-solid fa-dollar-sign"></i> 0
                            </h1> --}}
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
                            
                            <div class="col-sm-2" style="padding-right: 0px">
                                <select name="status" class="form-control select2" id="select-status">
                                    <option value="" selected>Todos</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="por aprobar">Por aprobar</option>
                                    <option value="aprobado">Aprobado</option>
                                    <option value="pagado">Pagado</option>
                                    <option value="concluido">Concluido</option>
                                    <option value="rechazado">Rechazado</option>
                                    <option value="anulado">Anulado</option>
                                </select>
                            </div>

                            <div class="col-sm-3" style="padding-left: 0px">
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
    {{-- Create type items modal --}}
    <form action="{{ route('pawn.payment') }}" id="form-payment" class="form-submit" method="POST">
        @csrf
        <div class="modal modal-primary fade" tabindex="-1" id="payment-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-dollar"></i> Registrar pago</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="date">Fecha de pago</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Monto</label>
                            <input type="number" name="amount" min="0.1" step="0.1" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark btn-submit" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        @media (max-width: 767px) {
            .table-responsive .dropdown-menu {
                position: static !important;
            }
        }
        @media (min-width: 768px) {
            .table-responsive {
                overflow: visible;
            }
        }
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

            $('#select-status').change(function(){
                list();
            });

            $('#select-paginate').change(function(){
                countPage = $(this).val();
                list();
            });

            $('.form-submit').submit(function(e){
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    if(res.success){
                        list();
                        $('#payment-modal').modal('hide');
                        toastr.success('Registro exitoso', 'Bien hecho');
                    }else{
                        toastr.error('Ocurrió un error', 'Error');
                    }
                    $('.form-submit .btn-submit').removeAttr('disabled');
                });
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});
            let status =$("#select-status").val();
            let url = "{{ route('pawn.list')}}";
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}?paginate=${countPage}&page=${page}&status=${status}&search=${search}`,
                type: 'get',
                success: function(result){
                $("#div-results").html(result);
                $('#div-results').loading('toggle');
            }});
        }
    </script>
@stop