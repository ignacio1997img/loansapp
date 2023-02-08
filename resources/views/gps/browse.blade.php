@extends('voyager::master')

@section('page_title', 'Viendo Caja')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-regular fa-money-bill-1"></i> Cajeros
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if ( !auth()->user()->hasRole('admin') && $vault)
                                <a href="{{ route('cashiers.create') }}" class="btn btn-success">
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
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">                        
                        <div class="table-responsive">
                            <main role="main" class="container-fluid">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <h1>
                                            Mostrar ubicación en mapa
                                        </h1>
                                        <p id="estado"></p>
                                        <a href="https://parzibyte.me/blog">By Parzibyte</a>
                                    </div>
                                    <div class="col-12">
                                        <div id="mapa" style="min-height: 500; height: 600px;"></div>
                                    </div>
                                </div>
                            </main>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


@stop

@section('css')
<link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ url('css/ol.css') }}">
<style>


</style>
@stop

@section('javascript')
    <script src="{{ url('js/gps/ol.js') }}"></script>
    <script src="{{ url('js/gps/script.js') }}"></script>
    {{-- <script src="{{ url('js/main.js') }}"></script> --}}
    <script>

    
    </script>
@stop
