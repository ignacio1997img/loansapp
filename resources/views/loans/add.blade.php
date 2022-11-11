@extends('voyager::master')

@section('page_title', 'Crear prestamos')

{{-- @if (auth()->user()->hasPermission('add_contracts') || auth()->user()->hasPermission('edit_contracts')) --}}

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-hand-holding-dollar"></i> Crear Prestamos
        </h1>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('loans.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Detalle del Prestamos</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Fecha</small>
                                        <input type="date" name="date" class="form-control text">
                                    </div>                                  
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <small>Beneficiario del Prestamo</small>
                                        <select name="people_id" id="people_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona un tipo --</option>
                                            @foreach ($people as $item)
                                                <option value="{{$item->id}}">{{$item->last_name}} {{$item->first_name}}</option>                                                
                                            @endforeach
                                        </select>
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
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Monto a Prestar</small>
                                        <input type="number" name="amountLoan" id="amountLoan" style="text-align: right" value="0" onkeypress='return inputNumeric(event)' onchange="subTotal()" onkeyup="subTotal()" class="form-control text" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Dias Total A Pagar</small>
                                        <input type="number" value="24" style="text-align: right" disabled class="form-control text">
                                        <input type="hidden" name="day" id="day" value="24" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Interes Prestamos</small>
                                        <input type="number" id="porcentage1" style="text-align: right" disabled value="20" class="form-control text">
                                        <input type="hidden" name="porcentage" id="porcentage"value="20" class="form-control">
                                    </div>
                                    
                                    <div class="form-group col-md-2">
                                        <small>Pago Diario</small>
                                        <input type="number" id="amountDay1" style="text-align: right" disabled value="0" class="form-control text">
                                        <input type="hidden" name="amountDay" id="amountDay" value="0" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Interes a Pagar</small>
                                        <input type="number" id="amountPorcentage1" style="text-align: right" disabled value="0" class="form-control text">
                                        <input type="hidden" name="amountPorcentage" id="amountPorcentage" value="0" class="form-control">
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

               
{{-- 
                <div class="row">
                    <div class="col-md-12 div-hidden div-5">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos de complementarios</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="details_work">Funciones generales</label>
                                        <textarea class="form-control richTextBox" name="details_work">
                                           
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>              
        </div>
    @stop

    @section('css')
        <style>

        </style>
    @endsection

    @section('javascript')
        <script>
            function subTotal()
            {
                let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;
                let porcentage = $(`#porcentage`).val() ? parseFloat($(`#porcentage`).val()) : 0;

                let day = $(`#day`).val() ? parseFloat($(`#day`).val()) : 0;

                porcentage = porcentage/100;
                let amountPorcentage = amountLoan*porcentage;
                let amountTotal = amountLoan+amountPorcentage;
                let amountDay = amountTotal / day;

                $(`#amountPorcentage1`).val(amountPorcentage);
                $(`#amountTotal1`).val(amountTotal);         

                $(`#amountPorcentage`).val(amountPorcentage);
                $(`#amountTotal`).val(amountTotal);  

                $(`#amountDay1`).val(amountDay);
                $(`#amountDay`).val(amountDay);  
            }
            
            function inputNumeric(event) {
                if(event.charCode >= 48 && event.charCode <= 57){
                    return true;
                }
                return false;        
            }
        </script>
    @stop

{{-- @endif --}}