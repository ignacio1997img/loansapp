@extends('voyager::master')

@section('page_title', 'hola')

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
          
            <div class="col-md-12">
                
                <div class="panel panel-bordered">
                    <div class="row">
                        <br>
                        <div class="col-xs-4 col-sm-4 text-right">
                            
                        </div>
                        <div class="col-xs-4 col-sm-4 text-center">
                           
                        </div>
                        <div class="col-xs-4 col-sm-4">
                            
                        </div>
                        <div class="col-md-6 col-sm-6">
                            
                        </div>
                        <div class="col-md-6 col-sm-6 text-right">
                            
                        </div>
                    </div>
                </div>
                <input type="text" class="form-control" id="input">

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <div class="panel panel-bordered">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="table-responsive">
                                       
                                    </div>
                                </div>




                                <audio id="audio" controls>
                                    <source type="audio/wav" src="audio.wav">
                                    </audio>













                                <div class="col-xs-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="table-visitor">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px">N&deg;</th>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

 
@stop

@section('css')
    <style>
        @font-face {
            font-family: 'Seven Segment';
            src: url({{ asset('fonts/Seven-Segment.ttf') }});
        }
        .td-actions img{
            filter: grayscale(100%);
        }
        .td-actions img:hover{
            filter: grayscale(0%);;
            /* width: 28px */
        }
        .img-avatar{
            width: 30px;
            height: 30px;
            border-radius: 15px;
            margin-right: 5px
        }
        #label-score{
            font-family: 'Seven Segment';
            font-size: 100px
        }
        #timer{
            font-family: 'Seven Segment';
            font-size: 60px;
            color: #E74C3C
        }
    </style>
@endsection

@section('javascript')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.0/socket.io.js" integrity="sha512-nYuHvSAhY5lFZ4ixSViOwsEKFvlxHMU2NHts1ILuJgOS6ptUmAGt/0i5czIgMOahKZ6JN84YFDA+mCdky7dD8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
<script src="timbre.js"></script>
{{-- <script src="{{ asset('js/timbre.js') }}"></script>
<script src="{{ asset('js/timbre.dev.js') }}"></script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/timbre/14.11.25/timbre.dev.js" integrity="sha512-FNA6oLEBWNffaS8MhG6CvJRZ2mQpdqZs3njPhXsZiPETVujawQtDS2ZRnlH2VH8PsV2LlkeykmOZXfS1WC05yA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/timbre/14.11.25/timbre.dev.min.js" integrity="sha512-/RVOKApS+psbtm+g9PMembypokIV/Op6/Q6AagVU+QNlMlCItoniSHDfyImD3nMnP6c+mMrEgkanKPMNL1J/wQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // const socket = io("{{ env('SOCKET_URL').':'.env('SOCKET_PORT') }}");
     




            
        //al cargar la ventana
        window.onload = function() {

        //opcion de html5 para pedir permisos en el navegador para la notificacion
            Notification.requestPermission(function(permission){
        
            //opciones de la notificacion
                var opciones = {
                        body: "El texto que quiera en la notificacion",
                        icon: "https://t2.gstatic.com/licensed-image?q=tbn:ANd9GcQdAnprsidzbOSZ4jI1SvcFeIEuFKwBLrILGo8tLCEA4ixMzfxUQfk6onBDhipea4sD"
                    };
                
                var notification = new Notification("EL TITULO DE LA ",opciones);

            
                var sine1 = T("sin", {freq:440, mul:0.5});
                var sine2 = T("sin", {freq:660, mul:0.5});
                var sine3 = T("sin", {freq:880, mul:0.5});

                T("perc", {r:500}, sine1, sine2, sine3).on("ended", function() {
                this.pause();
                }).bang().play();


            });//finaliza la notificacion
        }//finaliza la carga de la ventana




        input.oninput = function() {
            text = input.value;
            // alert(text)
            socket.emit(`reload prueba`, {id: text});

        };
    </script>

{{-- <script>
  T("sin", {freq:880, mul:0.5}).play();
</script> --}}




@stop
