@extends('voyager::master')

@section('page_title', 'Crear prestamos')

@if (auth()->user()->hasPermission('add_loans'))

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-hand-holding-dollar"></i> Crear Prestamos
        </h1>
        <a href="{{ route('loans.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('loans.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading">
                                <h5 id="h4" class="panel-title">Detalle del Prestamos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    {{-- <br> --}}
                                    {{-- <div class="col-md-12 text-right"> --}}
                                        <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="diario" checked>Prestamo diario</label>
                                        <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="diarioespecial">Prestamo Diario Especial </label>
                                    {{-- </div> --}}
                                </h5>
                            </div>
                            <div class="panel-body">
                                @if (!$cashier)  
                                    <div class="alert alert-warning">
                                        <strong>Advertencia:</strong>
                                        <p>No puedes crear un nuevo prestamo debido a que no tiene una caja asignada.</p>
                                    </div>
                                @else     
                                    @if ($cashier->status != 'abierta')
                                        <div class="alert alert-warning">
                                            <strong>Advertencia:</strong>
                                            <p>No puedes crear un nuevo prestamo debido a que no tiene una caja activa.</p>
                                        </div>
                                    @endif
                                @endif
                                {{-- <div class="row">                                    
                                    <div class="col-md-12 text-right">
                                        <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="diario">Prestamo diario</label>
                                        <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="diarioespecial" checked>Prestamo Diario Especial </label>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Fecha</small>
                                        <input type="date" name="date" class="form-control text">
                                    </div>   
                                    <div class="form-group col-md-6">
                                        <small>Asignar Ruta</small>
                                        <select name="route_id" id="route_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona una ruta --</option>
                                            @foreach ($routes as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>  
                                            @endforeach
                                        </select>
                                    </div>                                  
                                </div>

                                {{-- <select>
                                    <optgroup label="Group Name">
                                        <option>Nested option</option>
                                    </optgroup>
                                </select> --}}

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <small>Beneficiario del Prestamo</small>
                                        <select name="people_id" id="people_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona un tipo --</option>
                                            @foreach ($people as $item)
                                                <option @if($item->status == 'entregado' && $item->debt > 0 ) disabled @endif value="{{$item->id}}" >{{$item->last_name1}} {{$item->last_name2}} {{$item->first_name}}</option>                                                
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <small>Asignar Garante</small>
                                        <select name="guarantor_id" id="guarantor_id" class="form-control select2">
                                            <option value="" disabled selected>-- Seleccionar un garante --</option>
                                            @foreach ($people as $item)
                                                <option value="{{$item->id}}">{{$item->last_name1}} {{$item->last_name2}} {{$item->first_name}}</option>  
                                            @endforeach
                                        </select>
                                    </div>                                    
                                </div>
                                <input type="hidden" name="type" id="text_type">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Monto a Prestar</small>
                                        <input type="number" name="amountLoan" id="amountLoan" style="text-align: right" value="0" onkeypress='return inputNumeric(event)' onchange="subTotal()" onkeyup="subTotal()" class="form-control text" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Dias Total A Pagar</small>
                                        <input type="number" id="day1" value="24" style="text-align: right" disabled onkeypress='return inputNumeric(event)' onchange="diasPagar()" onkeyup="diasPagar()" class="form-control text">
                                        <input type="hidden" name="day" id="day" onkeypress='return inputNumeric(event)' value="24" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Interes Prestamos</small>
                                        <input type="number" id="porcentage1" style="text-align: right" disabled value="20" onkeypress='return inputNumeric(event)' onchange="porcentagePagar()" onkeyup="porcentagePagar()" onchange="subTotal()" onkeyup="subTotal()" class="form-control text">
                                        <input type="hidden" name="porcentage" id="porcentage" onkeypress='return inputNumeric(event)' value="20" class="form-control">
                                    </div>    
                                    <div class="form-group col-md-2">
                                        <small>Interes a Pagar</small>
                                        <input type="number" id="amountPorcentage1" style="text-align: right" disabled value="0" onkeypress='return inputNumeric(event)' onchange="porcentageAmount()" onkeyup="porcentageAmount()" onchange="subTotal()" onkeyup="subTotal()" class="form-control text">
                                        <input type="hidden" name="amountPorcentage" id="amountPorcentage" onkeypress='return inputNumeric(event)' value="0" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Pago Diario</small>
                                        <input type="number" id="amountDay1" style="text-align: right" disabled value="0" class="form-control text">
                                        <input type="hidden" name="amountDay" id="amountDay"onkeypress='return inputNumeric(event)' value="0" class="form-control">
                                        <b class="text-danger" id="label-amount" style="display:none">Incorrecto..</b>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Total a Pagar</small>
                                        <input type="number" id="amountTotal1" style="text-align: right" disabled value="0" class="form-control text">
                                        <input type="hidden" name="amountTotal" id="amountTotal" value="0" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        {{-- <label for="observation"></label> --}}
                                        <small>Observación</small>
                                        <textarea name="observation" id="observation" class="form-control text" cols="30" rows="5"></textarea>
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($cashier)    
                    @if ($cashier->status == 'abierta')

                        <input type="hidden" name="cashier_id" value="{{$cashier->id}}">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" id="btn_submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    @endif
                @endif
                
            </form>              
        </div>
    @stop

    @section('css')
        <style>

        </style>
    @endsection

    @section('javascript')
        <script>

            $(document).ready(function(){
                $('#agent').submit(function(e){
                    // $('#btn_guardar').css('display', 'none');

                    // var uno = document.getElementById('btn_submit');
                    // uno.textContent = 'Guardando....'; 
                    $('#btn_submit').text('Guardando...');
                    $('#btn_submit').attr('disabled', true);

                });
            })

            $(document).ready(() => {
                $(`#text_type`).val($(".radio-type:checked").val());
                $('.radio-type').click(function(){
                    $(`#text_type`).val($(".radio-type:checked").val());
                    list();
                });
            });
            function list()
            {
                let type = $(".radio-type:checked").val();
                // alert(type);
                                        // <input type="number" value="24" style="text-align: right" disabled class="form-control text">
                                        // <input type="number" name="day" id="day" value="24" class="form-control">
                if(type=='diario')
                {
                    $('#amountLoan').val(0);

                    $('#day1').val(24);
                    $('#day').val(24);

                    $('#porcentage1').val(20);
                    $('#porcentage').val(20);

                    $('#amountPorcentage1').val(0);
                    $('#amountPorcentage').val(0);

                    $('#amountDay1').val(0);
                    $('#amountDay').val(0);

                    $('#amountTotal1').val(0);
                    $('#amountTotal').val(0);
                    
                    $('#day1').attr('disabled',true);
                    $('#porcentage1').attr('disabled',true);
                    $('#amountPorcentage1').attr('disabled',true);
                }
                if(type=='diarioespecial')
                {
                    $('#day1').val(0);
                    $('#day').val(0);

                    $('#porcentage1').val(0);
                    $('#porcentage').val(0);

                    $('#amountPorcentage1').val(0);
                    $('#amountPorcentage').val(0);

                    $('#amountDay1').val(0);
                    $('#amountDay').val(0);

                    $('#amountTotal1').val(0);
                    $('#amountTotal').val(0);

                    $('#porcentage1').val(0);
                    $('#porcentage').val(0);

                    $('#amountPorcentage1').val(0);
                    $('#amountPorcentage1').val(0);

                    $('#day1').attr('disabled',false);         
                    $('#porcentage1').attr('disabled',false);     
                    $('#amountPorcentage1').attr('disabled',false);

                    
                }
            }
            function diasPagar()
            {
                let day = $(`#day1`).val() ? parseFloat($(`#day1`).val()) : 0;
                $('#day').val(day);

                subTotal()
            }
            function porcentagePagar()
            {
                let porcentage = $(`#porcentage1`).val() ? parseFloat($(`#porcentage1`).val()) : 0;
                $('#porcentage').val(porcentage);

                let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;

                porcentage = porcentage/100;
                let amountPorcentage = amountLoan*porcentage;
                $(`#amountPorcentage1`).val(amountPorcentage);
                $(`#amountPorcentage`).val(amountPorcentage);

                subTotal()
            }
            function porcentageAmount()
            {
                let amountPorcentage = $(`#amountPorcentage1`).val() ? parseFloat($(`#amountPorcentage1`).val()) : 0;
                $('#amountPorcentage').val(amountPorcentage);

                let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;

                amountPorcentage = amountPorcentage/amountLoan;
                amountPorcentage = amountPorcentage*100;
                
                $(`#porcentage1`).val(amountPorcentage);
                $(`#porcentage`).val(amountPorcentage);

                subTotal();

            }
            function subTotal()
            {
                let type = $(".radio-type:checked").val();
                if(type=='diario')
                {
                    $(`#text_type`).val('diario');

                    let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;
                    let porcentage = $(`#porcentage`).val() ? parseFloat($(`#porcentage`).val()) : 0;

                    let day = $(`#day`).val() ? parseFloat($(`#day`).val()) : 0;

                    porcentage = porcentage/100;
                    let amountPorcentage = amountLoan*porcentage;
                    let amountTotal = amountLoan+amountPorcentage;
                    let amountDay = amountTotal / day;

                    // if (amountDay % 1 == 0) {
                    //     alert ("Es un numero entero");
                    // } else {
                    //     alert ("Es un numero decimal");
                    // }

                    $(`#amountPorcentage1`).val(amountPorcentage);
                    $(`#amountTotal1`).val(amountTotal);         

                    $(`#amountPorcentage`).val(amountPorcentage);
                    $(`#amountTotal`).val(amountTotal);  

                    $(`#amountDay1`).val(amountDay);
                    $(`#amountDay`).val(amountDay);  

                    if (amountDay % 1 == 0) {
                        $('#label-amount').css('display', 'none');
                        $('#btn_submit').attr('disabled',false);

                    } else {
                        $('#label-amount').css('display', 'block');
                        $('#btn_submit').attr('disabled',true);
                    }
                }
                if(type=='diarioespecial')
                {
                    $(`#text_type`).val('diarioespecial');

                    let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;
                    let day = $(`#day1`).val() ? parseFloat($(`#day1`).val()) : 0;

                    // porcentagePagar();
                    // porcentageAmount();
                    let porcentage = $(`#porcentage1`).val() ? parseFloat($(`#porcentage1`).val()) : 0;
                    $('#porcentage').val(porcentage);

                    porcentage = porcentage/100;
                    porcentage = amountLoan*porcentage;
                    $(`#amountPorcentage1`).val(porcentage);
                    $(`#amountPorcentage`).val(porcentage);


                    let amountPorcentage = $(`#amountPorcentage1`).val() ? parseFloat($(`#amountPorcentage1`).val()) : 0;


                    // porcentage = porcentage/100;
                    // let amountPorcentage = amountLoan*porcentage;
                    let amountTotal = amountLoan+amountPorcentage;
                    let amountDay = amountTotal / day;

                    // $(`#amountPorcentage1`).val(amountPorcentage);
                    $(`#amountTotal1`).val(amountTotal);         

                    // $(`#amountPorcentage`).val(amountPorcentage);
                    $(`#amountTotal`).val(amountTotal);  

                    $(`#amountDay1`).val(amountDay);
                    $(`#amountDay`).val(amountDay);  

                    if (amountDay % 1 == 0) {
                        $('#label-amount').css('display', 'none');
                        $('#btn_submit').attr('disabled',false);

                    } else {
                        $('#label-amount').css('display', 'block');
                        $('#btn_submit').attr('disabled',true);
                    }
                    
                }
            }




            $(function()
            {
                $('#people_id').on('change', onselect_guarantor);
            });

            function onselect_guarantor()
            {      
                var people =  $(this).val();

                var guarantor=$("#guarantor_id").val();

                if(people)
                {
                    if(people == guarantor || guarantor == null)
                    {
                        $.get('{{route('loans-ajax.notpeople')}}/'+people, function(data){
                            var html_guarantor=    '<option value="" disabled selected>-- Seleccionar un garante --</option>'
                                for(var i=0; i<data.length; ++i)
                                    html_guarantor += '<option value="'+data[i].id+'">'+data[i].last_name1+' '+data[i].last_name2+' '+data[i].first_name+'</option>'

                            $('#guarantor_id').html(html_guarantor);                           
                            
                        });
                    }
                    else
                    {
                        $.get('{{route('loans-ajax.notpeople')}}/'+people, function(data){
                            var html_people=    '<option value="" disabled selected>-- Seleccionar un garante --</option>'
                                for(var i=0; i<data.length; ++i)
                                    html_people += '<option value="'+data[i].id+'">'+data[i].last_name1+' '+data[i].last_name2+' '+data[i].first_name+'</option>'

                            $('#guarantor_id').html(html_people);                           
                            
                        });
                    }
                    
                }
                else
                {
                    alert(0)
                }
            }


            
            
            function inputNumeric(event) {
                if(event.charCode >= 48 && event.charCode <= 57){
                    return true;
                }
                return false;        
            }

            // $('#modalEditar').on('show.bs.modal', function (event) {
            //     var button = $(event.relatedTarget) //captura valor del data-empresa=""
            //     var id = button.data('id')
            //     var desc = button.data('desc')
            //     var grupo = button.data('grupo')
            //     var modal = $(this)
            //     modal.find('.modal-body #idempresa').val(id)
            //     modal.find('.modal-body #grupo').text(grupo)
            //     modal.find('.modal-body #desc').val(desc)
            // });
        </script>
    @stop

@endif