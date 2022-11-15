@extends('voyager::master')

@section('page_title', 'Requisitos')

{{-- @if (auth()->user()->hasPermission('add_contracts') || auth()->user()->hasPermission('edit_contracts')) --}}

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-file"></i> Requisitos
        </h1>
        <a href="{{ route('loans.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 id="h4" class="panel-title">Requisitos Para el Prestamos</h6></div>
                            <div class="panel-body">
                                @if ($requirement->status == 2)
                                <form id="agent" action="{{route('loans-requirement-daily.store', ['loan'=>$requirement->loan_id])}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <small>Fot. CI (imagen)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png,application/pdf" name="ci" id="ci" class="form-control text">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <small>Fot. Luz o Agua (imagen)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png,application/pdf" name="luz" id="luz" class="form-control text">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <small>Foto Croquis (imagen)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png,application/pdf" name="croquis" id="croquis" class="form-control text">
                                    </div>
                                    
                                    <div class="form-group col-md-3">
                                        <small>Foto Empresa (imagen)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png,application/pdf" name="business" id="business" class="form-control text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
                                </form>  
                                <br>
                                <br>
                                <br>
                                @endif

                                @if ($ok && $requirement->status==2)
                                    <div class="row">
                                        <div class="col-md-12 text-left">
                                            <button class="btn btn-success" data-toggle="modal" data-target="#success-modal">Aprobar Requisitos</button>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="row" id="div-results" style="min-height: 120px">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="dataStyle" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Tipo</th>                
                                                        <th style="text-align: center">Estado</th>
                                                        @if ($requirement->status == 2)
                                                            <th class="text-right">Acciones</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="height: 50px">Fotocopia Carnet Identidad</td>
                                                        <td style="text-align: center">
                                                            @if (!$requirement->ci)
                                                                <label class="label label-danger">SN</label>
                                                            @else                                                                
                                                                <a href="{{asset('storage/'.$requirement->ci)}}" title="Ver" target="_blank">
                                                                    <img @if(strpos($requirement->ci, ".pdf")) src="{{asset('images/icon/pdf.png')}}" @else src="{{asset('storage/'.$requirement->ci)}}" @endif href="{{asset('storage/'.$requirement->croquis)}}" class="zoom" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px"/>
                                                                </a> 
                                                            @endif
                                                        </td>
                                                        @if ($requirement->status == 2)
                                                        <td class="no-sort no-click bread-actions text-right">
                                                            @if ($requirement->croquis)
                                                                <form action="{{ route('loans-daily-requirement.delete', ['loan'=>$requirement->loan_id, 'col'=>0]) }}" method="GET">
                                                                    {{ csrf_field() }}
                                                                    <button type="submit" title="Borrar" class="btn btn-sm btn-danger delete" >
                                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                                    </button>
                                                                </form>
                                                            @endif                                                            
                                                        </td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 50px">Fotocopia de Luz o Agua</td>
                                                        <td style="text-align: center">
                                                            @if (!$requirement->luz)
                                                                <label class="label label-danger">SN</label>
                                                            @else
                                                                <a href="{{asset('storage/'.$requirement->luz)}}" title="Ver" target="_blank">
                                                                    <img @if(strpos($requirement->luz, ".pdf")) src="{{asset('images/icon/pdf.png')}}" @else src="{{asset('storage/'.$requirement->luz)}}" @endif href="{{asset('storage/'.$requirement->croquis)}}" class="zoom" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px"/>
                                                                </a> 
                                                            @endif
                                                        </td>
                                                        @if ($requirement->status == 2)
                                                        <td class="no-sort no-click bread-actions text-right">
                                                            @if ($requirement->croquis)
                                                                <form action="{{ route('loans-daily-requirement.delete', ['loan'=>$requirement->loan_id, 'col'=>1]) }}" method="GET">
                                                                    {{ csrf_field() }}
                                                                    <button type="submit" title="Borrar" class="btn btn-sm btn-danger delete" >
                                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                                    </button>
                                                                </form>
                                                            @endif                                                            
                                                        </td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 50px">Foto de Croquis</td>
                                                        <td style="text-align: center">
                                                            @if (!$requirement->croquis)
                                                                <label class="label label-danger">SN</label>
                                                            @else
                                                                <a href="{{asset('storage/'.$requirement->croquis)}}" title="Ver" target="_blank">
                                                                    <img @if(strpos($requirement->croquis, ".pdf")) src="{{asset('images/icon/pdf.png')}}" @else src="{{asset('storage/'.$requirement->croquis)}}" @endif  href="{{asset('storage/'.$requirement->croquis)}}" class="zoom" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px"/>
                                                                </a>                                                                
                                                            @endif                                                            
                                                        </td>
                                                        @if ($requirement->status == 2)
                                                        <td class="no-sort no-click bread-actions text-right">
                                                            @if ($requirement->croquis)
                                                                <form action="{{ route('loans-daily-requirement.delete', ['loan'=>$requirement->loan_id, 'col'=>2]) }}" method="GET">
                                                                    {{ csrf_field() }}
                                                                    <button type="submit" title="Borrar" class="btn btn-sm btn-danger delete" >
                                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                                    </button>
                                                                </form>
                                                            @endif                                                            
                                                        </td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 50px">Foto del Negocio</td>
                                                        <td style="text-align: center">
                                                            @if (!$requirement->business)
                                                                <label class="label label-danger">SN</label>
                                                            @else
                                                                <a href="{{asset('storage/'.$requirement->business)}}" title="Ver" target="_blank">
                                                                    <img @if(strpos($requirement->business, ".pdf")) src="{{asset('images/icon/pdf.png')}}" @else src="{{asset('storage/'.$requirement->business)}}" @endif href="{{asset('storage/'.$requirement->croquis)}}" class="zoom" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px"/>
                                                                </a> 
                                                            @endif
                                                        </td>
                                                        @if ($requirement->status == 2)
                                                        <td class="no-sort no-click bread-actions text-right">
                                                            @if ($requirement->business)
                                                                <form action="{{ route('loans-daily-requirement.delete', ['loan'=>$requirement->loan_id, 'col'=>3]) }}" method="GET">
                                                                    {{ csrf_field() }}
                                                                    <button type="submit" title="Borrar" class="btn btn-sm btn-danger delete" >
                                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                                    </button>
                                                                </form>
                                                            @endif                                                            
                                                        </td>
                                                        @endif
                                                    </tr>
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

        <div class="modal modal-success fade" data-backdrop="static" tabindex="-1" id="success-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-thumbs-up"></i> Requisitos</h4>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('loans-daily-requirement.success', ['loan'=>$requirement->loan_id]) }}" method="GET">
                            {{ csrf_field() }}    
                                <div class="text-center" style="text-transform:uppercase">
                                    <i class="fa-solid fa-thumbs-up" style="color: rgb(67,209,128); font-size: 5em;"></i>
                                    <br>
                                    <p><b>Desea aprobar los requisitos para el prestamo..?</b></p>
                                </div>
                            <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, aprobar">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('css')
    <style>
        img.zoom {
                /* width: 350px;
                height: 200px; */
                width: 350px;
                height: 200px;
                -webkit-transition: all .2s ease-in-out;
                -moz-transition: all .2s ease-in-out;
                -o-transition: all .2s ease-in-out;
                -ms-transition: all .2s ease-in-out;
            }
            
            .transition {
                -webkit-transform: scale(5.0); 
                -moz-transform: scale(5.0);
                -o-transform: scale(5.0);
                transform: scale(5.0);
            }
        </style>
    @endsection

    @section('javascript')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script> --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>
    
        <script>
            $(document).ready(function(){
                $('.zoom').hover(function() {
                    $(this).addClass('transition');
                }, function() {
                    $(this).removeClass('transition');
                });
            });
        </script>
        <script>
            function deleteItem(url){
                $('#delete_form').attr('action', url);
            }

            $(document).on('change','.text',function(){
                // this.files[0].size recupera el tamaño del archivo
                // alert(this.files[0].size);

                // swal({
                //         title: "Error",
                //         text: "El Campo Nro. CITE tiene que tener minimo 2 letras y 5 numeros.\nEjemplo: DF-1/2022",
                //         // text: "Esta acción ya no se podrá deshacer, Así que piénsalo bien.",
                //         type: "error",
                //         showCancelButton: false
                //         });
                
                
                var fileName = this.files[0].name;
                var fileSize = this.files[0].size;

                if(fileSize > 5000000){
                    swal({
                        title: "Error",
                        text: "El archivo no debe superar los 5MB",
                        type: "error",
                        showCancelButton: false
                    });
                    this.value = '';
                    this.files[0].name = '';
                }
                
                    // recuperamos la extensión del archivo
                    var ext = fileName.split('.').pop();
                    
                    // Convertimos en minúscula porque 
                    // la extensión del archivo puede estar en mayúscula
                    ext = ext.toLowerCase();
                    accept="image/jpeg,image/jpg,image/png"
                    // console.log(ext);
                    switch (ext) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'pdf': break;
                        default:
                            swal({
                                title: "Error",
                                text: "El archivo no tiene la extensión adecuada",
                                type: "error",
                                showCancelButton: false
                            });
                            this.value = ''; // reset del valor
                            this.files[0].name = '';
                    }
            });
            
        </script>
        
    @stop

{{-- @endif --}}