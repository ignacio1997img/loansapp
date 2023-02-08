@extends('voyager::master')

@section('page_title', 'Viendo Caja')

@section('page_header')
    
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
