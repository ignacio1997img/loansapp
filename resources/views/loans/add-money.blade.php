@extends('voyager::master')

@section('page_title', 'Abonar Pago')

{{-- @if (auth()->user()->hasPermission('add_contracts') || auth()->user()->hasPermission('edit_contracts')) --}}

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="voyager-dollar"></i> Abonar Pago
        </h1>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-heading">
                            <h6 id="h4" class="panel-title">Detalle del Prestamos</h6>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                    {{-- <div class="form-group col-md-2">
                                        <small>Fecha</small>
                                        <input type="text" name="date" value="{{$loan->date}}" disabled class="form-control text">
                                    </div>   --}}
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 class="panel-title">Fecha</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p>{{date("d-m-Y", strtotime($loan->date))}}</p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 class="panel-title">Cobrador Asignado</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p>{{$agent->agent->name}}</p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>                                 
                            </div>
                            <div class="row">
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 class="panel-title">CI</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p>{{$loan->people->ci}}</p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 class="panel-title">Beneficiario</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p>{{$loan->people->first_name}} {{$loan->people->last_name}}</p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 class="panel-title">Celular</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <p>{{$loan->people->cell_phone?$loan->people->cell_phone:'SN'}}</p>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>                                 
                            </div>
                            <h4 id="h4">Detalle del Dinero</h4>
                            <hr>
                            <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Monto a Prestar</small>
                                        <input type="text" disabled style="text-align: right" value="{{$loan->amountLoan}}" class="form-control text">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Dias Total A Pagar</small>
                                        <input type="text" value="{{$loan->day}}" style="text-align: right" disabled class="form-control text">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Interes Prestamos</small>
                                        <input type="text" id="porcentage1" style="text-align: right" disabled value="{{$loan->porcentage}}" class="form-control text">
                                    </div>
                                    
                                    <div class="form-group col-md-2">
                                        <small>Pago Diario</small>
                                        <input type="text" id="amountDay1" style="text-align: right" disabled value="{{$loan->amountTotal/$loan->day}}" class="form-control text">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Interes a Pagar</small>
                                        <input type="text" id="amountPorcentage1" style="text-align: right" disabled value="{{$loan->amountPorcentage}}" class="form-control text">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Total a Pagar</small>
                                        <input type="text" id="amountTotal1" style="text-align: right" disabled value="{{$loan->amountTotal}}" class="form-control text">
                                    </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small>Observación</small>
                                    <textarea name="observation" id="observation" disabled class="form-control text" cols="30" rows="3">{{$loan->observation}}</textarea>
                                </div>                                  
                            </div>

                            <h3 id="h3"><i class="voyager-dollar"></i> Detalle del Pago</h3>
                            <hr>
                            <form id="agent" action="{{route('loans-daily-money.store')}}" method="POST">
                            @csrf
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <small>Fecha</small>
                                        <select name="day_id" id="day_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona una fecha --</option>
                                            @foreach ($loanday as $item)
                                                <option value="{{$item->id}}">{{date("d-m-Y", strtotime($item->date))}}</option>
                                            @endforeach                                       
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Cuota</small>
                                        <input type="number" name="amount" onkeypress='return inputNumeric(event)' style="text-align: right" class="form-control text" required>                                    
                                    </div>      
                                    <div class="form-group col-md-4">
                                        <input type="hidden" name="loan_id" value="{{$loan->id}}">
                                    </div>  
                                    <div class="form-group col-md-4">
                                        <small>Registrado Por</small>
                                        <select name="agent_id" id="agent_id" class="form-control select2" required>
                                            <option value="{{$register->id}}" selected>{{$register->name}} - {{$register->role->name}}</option>                                  
                                        </select>
                                    </div>                                
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
                            </form> 
                            
                            <table width="100%" border="1" cellpadding="5" style="font-size: 12px">
                                
                                @php
                                        $meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                                        "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                        $aux=0;
                    
                                        $fecha = \Carbon\Carbon::parse($loan->loanDay[$aux]->date);
                                        $fecha = $fecha->format("N");
                                        $cant = count($loan->loanDay);
                                        $ok=true;
                    
                                        $inicio = \Carbon\Carbon::parse($loan->loanDay[0]->date);
                                        $inicio = $inicio->format("n");
                    
                                        $fin = \Carbon\Carbon::parse($loan->loanDay[23]->date);
                                        $fin = $fin->format("n");
                                @endphp
                                @if ($inicio == $fin)   
                                    @php
                    
                                        $inicio1 = \Carbon\Carbon::parse($loan->loanDay[0]->date);
                                        $day_f = $inicio1->format("d");                
                                        $year1 = $inicio1->format("Y");                    
                                        $month_f1 = $inicio1->format("m");
                                        $day_f1 = date('d', mktime(0,0,0, $month_f1, 1, $year1));
                                        $day_l1 = date("d", mktime(0,0,0, $month_f1+1, 0, $year1));
                    
                                        $date1 = $year1.'-'.$month_f1.'-'.$day_f1;
                                        $week1 = \Carbon\Carbon::parse($date1);
                                        $week1 = $week1->format("N");
                                       
                                        // dd($week2);
                                        $aux = 0;
                                        $ok = false;
                                        $count = 0;
                                    @endphp
                                
                                    <tr style="background-color: #666666; color: white; font-size: 18px">
                                        <td colspan="7" style="text-align: center">{{$meses[$month_f1]}}</td>
                                    </tr>
                                    <tr style="background-color: #666666; color: white; font-size: 18px">
                                        <td style="text-align: center">LUN</td>
                                        <td style="text-align: center">MAR</td>
                                        <td style="text-align: center">MIE</td>
                                        <td style="text-align: center">JUE</td>
                                        <td style="text-align: center">VIE</td>
                                        <td style="text-align: center">SAB</td>
                                        <td style="text-align: center">DOM</td>
                                    </tr>
                    
                    
                                            @for ($x = 1; $x <= 6; $x++)
                                                <tr>
                                                    @for ($i = 1; $i <= 7; $i++)      
                                                            @if ($i == $week1 && !$ok)   
                                                                @php
                                                                    $aux++;
                                                                    $ok=true;
                                                                    $count++;
                                                                @endphp                                     
                                                                <td @if($day_f == $aux) style="height: 50px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif> <small style="font-size: 25px">{{$aux}}</small> </td>
                                                            @else
                                                                @if ($ok)
                                                                    @php
                                                                        $aux++;
                                                                    @endphp  
                                                                    @if ($i != 7)
                                                                        @php
                                                                            $count++;
                                                                        @endphp
                                                                        <td @if($day_f == $aux || $count == 24) style="height: 50px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif>
                                                                            <small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small>
                                                                        </td>    
                                                                    @else
                                                                        <td style="height: 50px; text-align: center; background-color: #CCCFD2"><small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small></td>
                                                                    @endif                                                                                       
                                                                @else
                                                                    <td style="height: 50px; text-align: center"></td>                                                                                           
                                                                @endif
                                                            @endif
                                                            @if ($day_l1 == $aux)
                                                                @php
                                                                    $x=10;
                                                                @endphp
                                                            @endif                             
                                                    @endfor  
                                                </tr>          
                                            @endfor  
                                           
                                @else
                                    @php
                    
                                        $inicio1 = \Carbon\Carbon::parse($loan->loanDay[0]->date);
                                        $day_f = $inicio1->format("d");                
                                        $year1 = $inicio1->format("Y");                    
                                        $month_f1 = $inicio1->format("m");
                                        $day_f1 = date('d', mktime(0,0,0, $month_f1, 1, $year1));
                                        $day_l1 = date("d", mktime(0,0,0, $month_f1+1, 0, $year1));
                    
                                        $date1 = $year1.'-'.$month_f1.'-'.$day_f1;
                                        $week1 = \Carbon\Carbon::parse($date1);
                                        $week1 = $week1->format("N");
                                        // dd($day_l1);
                    
                                        $inicio2 = \Carbon\Carbon::parse($loan->loanDay[23]->date);
                                        $day_l = $inicio2->format("d");   
                                        $year2 = $inicio2->format("Y");                    
                                        $month_f2 = $inicio2->format("m");
                                        $day_f2 = date('d', mktime(0,0,0, $month_f2, 1, $year2));
                                        $day_l2 = date("d", mktime(0,0,0, $month_f2+1, 0, $year2));
                    
                                        $date2 = $year2.'-'.$month_f2.'-'.$day_f2;                    
                                        $week2 = \Carbon\Carbon::parse($date2);
                                        $week2 = $week2->format("N");
                                        // dd($week2);
                                        $aux = 0;
                                        $ok = false;
                                    @endphp
                                    @for ($a = 1; $a <= 2; $a++)
                                        @if ($a==1)
                                            <tr style="background-color: #666666; color: white; font-size: 18px">
                                                <td colspan="7" style="text-align: center">{{$meses[$month_f1]}}</td>
                                            </tr>
                                            <tr style="background-color: #666666; color: white; font-size: 18px">
                                                <td style="text-align: center">LUN</td>
                                                <td style="text-align: center">MAR</td>
                                                <td style="text-align: center">MIE</td>
                                                <td style="text-align: center">JUE</td>
                                                <td style="text-align: center">VIE</td>
                                                <td style="text-align: center">SAB</td>
                                                <td style="text-align: center">DOM</td>
                                            </tr>
                                            @for ($x = 1; $x <= 6; $x++)
                                                <tr>
                                                    @for ($i = 1; $i <= 7; $i++)      
                                                            @if ($i == $week1 && !$ok)   
                                                                @php
                                                                    $aux++;
                                                                    $ok=true;
                                                                @endphp                                     
                                                                <td @if($day_f == $aux) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif> <small style="font-size: 25px">{{$aux}}</small> </td>
                                                            @else
                                                                @if ($ok)
                                                                    @php
                                                                        $aux++;
                                                                    @endphp  
                                                                    @if ($i != 7)
                                                                        <td @if($day_f == $aux) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif>
                                                                            <small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small>
                                                                        </td>    
                                                                    @else
                                                                        <td style="height: 50px; text-align: center; background-color: #CCCFD2"><small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small></td>
                                                                    @endif                                                                                       
                                                                @else
                                                                    <td style="height: 50px; text-align: center"></td>                                                                                           
                                                                @endif
                                                            @endif
                                                            @if ($day_l1 == $aux)
                                                                @php
                                                                    $x=10;
                                                                @endphp
                                                            @endif                             
                                                    @endfor  
                                                </tr>          
                                            @endfor  
                                        @else
                                            <tr style="background-color: #666666; color: white; font-size: 18px">
                                                <td colspan="7" style="text-align: center">{{$meses[$month_f2]}}</td>
                                            </tr>
                                            <tr style="background-color: #9b9a9a; color: white; font-size: 18px">
                                                <td style="text-align: center">LUN</td>
                                                <td style="text-align: center">MAR</td>
                                                <td style="text-align: center">MIE</td>
                                                <td style="text-align: center">JUE</td>
                                                <td style="text-align: center">VIE</td>
                                                <td style="text-align: center">SAB</td>
                                                <td style="text-align: center">DOM</td>
                                            </tr>
                                            @php
                                                $aux = 0;
                                                $ok = false;
                                            @endphp
                                            @for ($x = 1; $x <= 6; $x++)
                                                <tr>
                                                    @for ($i = 1; $i <= 7; $i++)      
                                                            @if ($i == $week2 && !$ok)   
                                                                @php
                                                                    $aux++;
                                                                    $ok=true;
                                                                @endphp                                     
                                                                <td @if($day_l == $aux) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif> <small style="font-size: 25px">{{$aux}}</small> </td>
                                                            @else
                                                                @if ($ok)
                                                                    @php
                                                                        $aux++;
                                                                    @endphp  
                                                                    @if ($i != 7)
                                                                        <td @if($day_l == $aux) style="height: 40px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif> <small style="font-size: 25px">{{ $aux <= $day_l2? $aux:''}}</small></td>    
                                                                    @else
                                                                        <td style="height: 50px; text-align: center; background-color: #CCCFD2"><small style="font-size: 25px">{{ $aux <= $day_l2? $aux:''}}</small></td>
                                                                    @endif                                                                                       
                                                                @else
                                                                    <td style="height: 50px; text-align: center"> <small style="font-size: 25px"></td>                                                                                           
                                                                @endif
                                                            @endif
                                                            @if ($day_l2 == $aux)
                                                                @php
                                                                    $x=10;
                                                                @endphp
                                                            @endif                             
                                                    @endfor  
                                                </tr>          
                                            @endfor   
                                        @endif        
                                    @endfor            
                                @endif
                            </table>




                        </div>
                    </div>
                </div>                
            </div>            
        </div>
    @stop

    @section('css')
        <style>

        </style>
    @endsection

    @section('javascript')
        <script>
            function inputNumeric(event) {
                if(event.charCode >= 48 && event.charCode <= 57){
                    return true;
                }
                return false;        
            }
        </script>
    @stop

{{-- @endif --}}