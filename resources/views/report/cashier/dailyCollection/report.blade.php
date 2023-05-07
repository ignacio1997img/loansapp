@extends('voyager::master')

@section('page_title', 'Reporte Recaudacion Diaria')
@if(auth()->user()->hasPermission('browse_printloanCollection'))


@section('page_header')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-calendar"></i> Reporte Diario Recaudado
                            </h1>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('print-loanCollection.list') }}" method="post">

                                @csrf
                                <input type="hidden" name="print">
                                
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="date" name="date" class="form-control">
                                        <small>Fecha</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="agent_id" class="form-control select2" required>
                                            <option value=""disabled selected>--- Seleccione una opcion ---</option>
                                            @foreach ($user as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach                                             
                                        </select>
                                        <small>Persona</small>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div id="div-results" style="min-height: 100px">
                
            </div>
        </div>
    </div>
@stop

@section('css')
    
@stop

@section('javascript')
    <script>

        $(document).ready(function() {

            $('#form-search').on('submit', function(e){
                e.preventDefault();
                $('#div-results').empty();
                // $('#div-results').loading({message: 'Cargando...'});
                // alert(1)
                var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
                $('#div-results').html(loader);
                $.post($('#form-search').attr('action'), $('#form-search').serialize(), function(res){
                    $('#div-results').html(res);
                })
                .fail(function() {
                    toastr.error('Ocurrió un error!', 'Oops!');
                })
                .always(function() {
                    $('#div-results').loading('toggle');
                    $('html, body').animate({
                        scrollTop: $("#div-results").offset().top - 70
                    }, 500);
                });
            });
        });

        function report_print(){
            $('#form-search').attr('target', '_blank');
            $('#form-search input[name="print"]').val(1);
            window.form_search.submit();
            $('#form-search').removeAttr('target');
            $('#form-search input[name="print"]').val('');
        }
        function report_excel()
        {
            // $('#form-search').attr('target', '_blank');
            $('#form-search input[name="print"]').val(2);
            window.form_search.submit();
             $('#form-search').removeAttr('target');
            $('#form-search input[name="print"]').val('');
        }
    </script>
@stop
@else
    @section('content')
        {{-- @include('errors.403') --}}
        <h1>403</h1>
    @stop
@endif