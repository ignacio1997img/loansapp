@extends('voyager::master')

@section('page_title', 'Abonar Pago')

{{-- @if (auth()->user()->hasPermission('add_contracts') || auth()->user()->hasPermission('edit_contracts')) --}}

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="voyager-dollar"></i> Abonar Pago
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
                        {{-- <div class="panel-heading">
                            <h6 id="h4" class="panel-title">Detalle del Prestamos</h6>
                        </div> --}}
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 id="h4" class="panel-title">Fecha de solicitud</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <small>{{date("d-m-Y", strtotime($loan->date))}}</small>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                                <div class="col-md-4">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 id="h4" class="panel-title">Ruta Asiganada</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <small>{{$route->route->name}}</small>
                                    </div>
                                    <hr style="margin:0;">
                                </div>   
                                <div class="col-md-4">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 id="h4" class="panel-title">Garante</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <small>{{$loan->guarantor_id? $loan->guarantor->first_name.' '.$loan->guarantor->last_name1.' '.$loan->guarantor->last_name2:'SN'}}</small>
                                    </div>
                                    <hr style="margin:0;">
                                </div>                               
                            </div>
                            <div class="row">
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 id="h4" class="panel-title">CI</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <small>{{$loan->people->ci}}</small>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 id="h4" class="panel-title">Beneficiario</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <small>{{$loan->people->first_name}} {{$loan->people->last_name}}</small>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel-heading" style="border-bottom:0;">
                                            <h3 id="h4" class="panel-title">Celular</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:0;">
                                            <small>{{$loan->people->cell_phone?$loan->people->cell_phone:'SN'}}</small>
                                        </div>
                                        <hr style="margin:0;">
                                    </div>                                 
                            </div>
                            {{-- <h4 id="h4">Detalle del Dinero</h4>
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
                            </div> --}}

                            <h3 id="h3"><i class="voyager-dollar"></i> Detalle del Pago</h3>
                            <hr>
                            @if ($loan->debt != 0)                                
                            
                            <form id="agent" action="{{route('loans-daily-money.store')}}" method="POST">
                            @csrf
                                <div class="row">
                                    <input type="hidden" name="date" value="{{$date}}">
                                       
                                    <div class="form-group col-md-8">
                                        <input type="hidden" name="loan_id" value="{{$loan->id}}">
                                    </div>  
                                    <div class="form-group col-md-2">
                                        <small>Cuota</small>
                                        <input type="text" name="amount" id="amount" onkeypress='return inputNumeric(event)' onchange="subTotal()" onkeyup="subTotal()" id="bloquear" style="text-align: right" class="form-control text" required>                                    
                                        <b class="text-danger" id="label-amount" style="display:none">El monto ingresado es mayor a la deuda pendiente..</b>
                                    </div>   
                                    <div class="form-group col-md-2">
                                        <small>Registrado Por</small>
                                        <select name="agent_id" id="agent_id" class="form-control select2" required>
                                            <option value="{{$register->id}}" selected>{{$register->name}} - {{$register->role->name}}</option>                                  
                                        </select>
                                    </div>                                
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" id="btn-sumit" disabled class="btn btn-success"><i class="fa-solid fa-money-bill"></i> Pagar</button>
                                    </div>
                                </div>
                            </form> 
                            @endif
                            <div class="col-md-8">
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
                                            $number = 0;
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
                                                                    <td @if($day_f == $aux) style="height: 50px; text-align: center; background-color: #F8FF07" @else style="height: 50px; text-align: center" @endif>
                                                                        <small @if($day_f == $aux && $loan->loanDay[$number]->late==1) style="font-size: 25px; color:#FF0000;" @else style="font-size: 25px;"@endif>{{$aux}} </small>
                                                                        @if( $day_f == $aux && $date === $year1.'-'.$month_f1.($aux <= 9 ? '-0'.$aux : '-'.$aux)) <i class="fa-solid fa-calendar-days"></i>@endif

                                                                        <br>
                                                                        @if($day_f == $aux)
                                                                            @if ($loan->loanDay[$number]->debt==0)
                                                                                <label class="label label-success">Pagado</label>
                                                                            @else
                                                                                <label class="label label-primary">{{$loan->loanDay[$number]->amount - $loan->loanDay[$number]->debt}}</label>-<label class="label label-danger">{{$loan->loanDay[$number]->amount}}</label>                                                                                          
                                                                            @endif
                                                                            @php
                                                                                $number++;
                                                                            @endphp
                                                                        @endif
                                                                    </td>
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
                                                                                <small @if($count <= 24 && $loan->loanDay[$number]->late==1) style="font-size: 25px; color:#FF0000;" @else style="font-size: 25px;"@endif>{{ $aux <= $day_l1? $aux:''}}</small>
                                                                                @if( $aux <= $day_l1 && $date === $year1.'-'.$month_f1.($aux <= 9 ? '-0'.$aux : '-'.$aux)) <i class="fa-solid fa-calendar-days"></i> @endif

                                                                                <br>
                                                                                @if($count <= 24)
                                                                                    @if ($loan->loanDay[$number]->debt==0)
                                                                                        <label class="label label-success">Pagado</label>
                                                                                    @else
                                                                                        <label class="label label-primary">{{$loan->loanDay[$number]->amount - $loan->loanDay[$number]->debt}}</label>-<label class="label label-danger">{{$loan->loanDay[$number]->amount}}</label>                                                                                          
                                                                                    @endif
                                                                                    @php
                                                                                        $number++;
                                                                                    @endphp
                                                                                @endif
                                                                            </td>    
                                                                        @else
                                                                            <td style="height: 50px; text-align: center; background-color: #CCCFD2"><small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small>
                                                                                @if( $aux <= $day_l1 && $date === $year1.'-'.$month_f1.($aux <= 9 ? '-0'.$aux : '-'.$aux)) <i class="fa-solid fa-calendar-days"></i> @endif
                                                                            </td>
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
                                            $number =0;
                        
                                            $inicio1 = \Carbon\Carbon::parse($loan->loanDay[0]->date);
                                            $day_f = $inicio1->format("d");                
                                            $year1 = $inicio1->format("Y");                    
                                            $month_f1 = $inicio1->format("m");
                                            $day_f1 = date('d', mktime(0,0,0, $month_f1, 1, $year1));
                                            $day_l1 = date("d", mktime(0,0,0, $month_f1+1, 0, $year1));
                        
                                            $date1 = $year1.'-'.$month_f1.'-'.$day_f1;
                                            $week1 = \Carbon\Carbon::parse($date1);
                                            $week1 = $week1->format("N");
                                            // dd($date1);
                        
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
                                                    <td style="text-align: center; width: 15%">LUN</td>
                                                    <td style="text-align: center; width: 15%">MAR</td>
                                                    <td style="text-align: center; width: 15%">MIE</td>
                                                    <td style="text-align: center; width: 15%">JUE</td>
                                                    <td style="text-align: center; width: 15%">VIE</td>
                                                    <td style="text-align: center; width: 15%">SAB</td>
                                                    <td style="text-align: center; width: 10%">DOM</td>
                                                </tr>
                                                @for ($x = 1; $x <= 6; $x++)
                                                    <tr>
                                                        @for ($i = 1; $i <= 7; $i++)      
                                                                @if ($i == $week1 && !$ok)   
                                                                    @php
                                                                        $aux++;
                                                                        $ok=true;
                                                                    @endphp                 
                                                                                       {{-- Primer numero del calendario --}}
                                                                    <td @if($day_f == $aux) style="height: 80px; text-align: center; background-color: #F8FF07" @else style="height: 80px; text-align: center" @endif>
                                                                        <small @if($day_f == $aux && $loan->loanDay[$number]->late==1) style="font-size: 25px; color:#FF0000;" @else style="font-size: 25px;"@endif>{{$aux}}</small>
                                                                        @if( $day_f == $aux && $date === $year1.'-'.$month_f1.($aux <= 9 ? '-0'.$aux : '-'.$aux)) <i class="fa-solid fa-calendar-days"></i>@endif
                                                                        
                                                                        <br>
                                                                        @if($day_f == $aux)
                                                                            @if ($loan->loanDay[$number]->debt==0)
                                                                                {{-- <label class="label label-success">Pagado</label> --}}
                                                                                <img src="{{ asset('images/icon/pagado.png') }}" width="80px">
                                                                            @else
                                                                                <label class="label label-primary">{{$loan->loanDay[$number]->amount - $loan->loanDay[$number]->debt}}</label>-<label class="label label-danger">{{$loan->loanDay[$number]->amount}}</label>                                                                                          
                                                                            @endif
                                                                            @php
                                                                                $number++;
                                                                            @endphp
                                                                        @endif
                                                                    </td>
                                                                @else
                                                                    @if ($ok)
                                                                        @php
                                                                            $aux++;
                                                                        @endphp  
                                                                        @if ($i != 7)
                                                                            <td @if($day_f == $aux) style="height: 80px; text-align: center; background-color: #F8FF07;" @else style="height: 80px; text-align: center" @endif>
                                                                                <small @if($aux >= $day_f && $aux <= $day_l1 && $loan->loanDay[$number]->late==1) style="font-size: 25px; color:#FF0000;" @else style="font-size: 25px;"@endif>{{ $aux <= $day_l1? $aux:''}}</small>
                                                                                @if( $aux <= $day_l1 && $date === $year1.'-'.$month_f1.($aux <= 9 ? '-0'.$aux : '-'.$aux)) <i class="fa-solid fa-calendar-days"></i> @endif
                                                                                {{-- para mostrar su deuda de cada numero --}}
                                                                                <br>
                                                                                @if($aux >= $day_f && $aux <= $day_l1)
                                                                                    @if ($loan->loanDay[$number]->debt==0)
                                                                                        {{-- <label class="label label-success">Pagado</label> --}}
                                                                                        <img src="{{ asset('images/icon/pagado.png') }}" width="80px">
                                                                                    @else
                                                                                        <label class="label label-primary">{{$loan->loanDay[$number]->amount - $loan->loanDay[$number]->debt}}</label>-<label class="label label-danger">{{$loan->loanDay[$number]->amount}}</label>                                                                                          
                                                                                    @endif
                                                                                    @php
                                                                                        $number++;
                                                                                    @endphp
                                                                                @endif
                                                                            </td>    
                                                                        @else
                                                                            <td style="height: 80px; text-align: center; background-color: #CCCFD2"><small style="font-size: 25px">{{ $aux <= $day_l1? $aux:''}}</small>
                                                                                @if( $aux <= $day_l1 && $date === $year1.'-'.$month_f1.($aux <= 9 ? '-0'.$aux : '-'.$aux)) <i class="fa-solid fa-calendar-days"></i> @endif
                                                                            </td>
                                                                        @endif                                                                                       
                                                                    @else
                                                                        <td style="height: 80px; text-align: center"></td>                                                                                           
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
                                                    <td style="text-align: center; width: 15%">LUN</td>
                                                    <td style="text-align: center; width: 15%">MAR</td>
                                                    <td style="text-align: center; width: 15%">MIE</td>
                                                    <td style="text-align: center; width: 15%">JUE</td>
                                                    <td style="text-align: center; width: 15%">VIE</td>
                                                    <td style="text-align: center; width: 15%">SAB</td>
                                                    <td style="text-align: center; width: 10%">DOM</td>
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
                                                                    <td @if($day_l == $aux) style="height: 80px; text-align: center; background-color: #F8FF07" @else style="height: 80px; text-align: center" @endif>
                                                                        <small @if($loan->loanDay[$number]->late==1) style="font-size: 25px; color:#FF0000;" @else style="font-size: 25px;"@endif>{{$aux}}</small>
                                                                        @if( $day_l == $aux && $date === $year2.'-'.$month_f2.($aux <= 9 ? '-0'.$aux : '-'.$aux)) <i class="fa-solid fa-calendar-days"></i>@endif

                                                                        <br>
                                                                        {{-- @if($day_l == $aux) --}}
                                                                            @if ($loan->loanDay[$number]->debt==0)
                                                                                {{-- <label class="label label-success">Pagado</label> --}}
                                                                                <img src="{{ asset('images/icon/pagado.png') }}" width="80px">

                                                                            @else
                                                                                <label class="label label-primary">{{$loan->loanDay[$number]->amount - $loan->loanDay[$number]->debt}}</label>-<label class="label label-danger">{{$loan->loanDay[$number]->amount}}</label>                                                                                          
                                                                            @endif
                                                                            @php
                                                                                $number++;
                                                                            @endphp
                                                                        {{-- @endif --}}
                                                                    </td>
                                                                @else
                                                                    @if ($ok)
                                                                        @php
                                                                            $aux++;
                                                                        @endphp  
                                                                        @if ($i != 7)
                                                                            <td @if($day_l == $aux) style="height: 80px; text-align: center; background-color: #F8FF07" @else style="height: 80px; text-align: center" @endif>
                                                                                <small @if($aux <= $day_l && $loan->loanDay[$number]->late==1) style="font-size: 25px; color:#FF0000;" @else style="font-size: 25px;"@endif>{{ $aux <= $day_l2? $aux:''}}</small>
                                                                                @if( $aux <= $day_l2 && $date === $year2.'-'.$month_f2.($aux <= 9 ? '-0'.$aux : '-'.$aux)) <i class="fa-solid fa-calendar-days"></i>@endif

                                                                                <br>
                                                                                @if($aux <= $day_l)
                                                                                    @if ($loan->loanDay[$number]->debt==0)
                                                                                        {{-- <label class="label label-success">Pagado</label> --}}
                                                                                        <img src="{{ asset('images/icon/pagado.png') }}" width="80px">
                                                                                    @else
                                                                                        <label class="label label-primary">{{$loan->loanDay[$number]->amount - $loan->loanDay[$number]->debt}}</label>-<label class="label label-danger">{{$loan->loanDay[$number]->amount}}</label>                                                                                          
                                                                                    @endif
                                                                                    @php
                                                                                        $number++;
                                                                                    @endphp
                                                                                @endif
                                                                            </td>    
                                                                        @else
                                                                            <td style="height: 80px; text-align: center; background-color: #CCCFD2"><small style="font-size: 25px">{{ $aux <= $day_l2? $aux:''}}</small>
                                                                                @if( $aux <= $day_l2 && $date === $year2.'-'.$month_f2.($aux <= 9 ? '-0'.$aux : '-'.$aux)) <i class="fa-solid fa-calendar-days"></i>@endif
                                                                            </td>
                                                                        @endif                                                                                       
                                                                    @else
                                                                        <td style="height: 80px; text-align: center"> <small style="font-size: 25px"></td>                                                                                           
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

                            <div class="col-md-4">
                                <div class="row">
                                    {{-- <div class="panel-heading">
                                        <h6 id="h4" class="panel-title">Detalle de deuda</h6>
                                    </div> --}}
                                    <canvas id="myChart"></canvas>
                                </div>
                                {{-- <hr> --}}
                                <div class="row">
                                    <table width="100%" cellpadding="20">
                                        <tr>
                                            <td><small>Monto Pagado</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountTotal - $loan->debt, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        <tr>
                                            <td><small>Deuda</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->debt, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        
                                        {{-- <tr>                                            
                                            <td><small>TOTAL PAGADO</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountTotal, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr> --}}
                                    </table>
                                </div>
                                <h3 id="h4" style="text-align: center"><i class="voyager-dollar"></i> Detalle del Prestamo</h3>
                                <hr>
                                <div class="row">
                                    <table width="100%" cellpadding="20">
                                        <tr>
                                            <td><small>Dias Total a Pagar</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->day, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        <tr>
                                            <td><small>Interes Prestamo</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->porcentage, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        <tr>                                            
                                            <td><small>Pago Diario</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountTotal/$loan->day, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                    </table>
                                </div>
                                <h3 id="h4" style="text-align: center"><i class="voyager-dollar"></i> Detalle del Pago</h3>
                                <hr>
                                <div class="row">
                                    <table width="100%" cellpadding="20">
                                        <tr>
                                            <td><small>Monto Prestado</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountLoan, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        <tr>
                                            <td><small>Interes a Pagar</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountPorcentage, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                        <tr>                                            
                                            <td><small>TOTAL A PAGAR</small></td>
                                            <td class="text-right"><h3>{{ number_format($loan->amountTotal, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="row">
                                    <small>Observación</small>
                                    <textarea name="observation" id="observation" disabled class="form-control text" cols="30" rows="3">{{$loan->observation}}</textarea>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>                
            </div>            
        </div>

        <div id="popup-button">
            <div class="col-md-12" style="padding-top: 5px">
                <h4 class="text-muted">Desea imprimir el comprobante?</h4>
            </div>
            <div class="col-md-12 text-right">
                <button onclick="javascript:$('#popup-button').fadeOut('fast')" class="btn btn-default">Cerrar</button>
                <a id="btn-print" onclick="printDailyMoney()"  title="Imprimir" class="btn btn-danger">Imprimir <i class="glyphicon glyphicon-print"></i></a>
                {{-- <button type="submit" id="btn-print" title="Imprimir" class="btn btn-danger" onclick="printDailyMoney()" class="btn btn-primary">Imprimir <i class="glyphicon glyphicon-print"></i></button> --}}

            </div>
        </div>
    @stop

    @section('css')
        <style>
.form-group{
            margin-bottom: 10px !important;
        }
        .label-description{
            cursor: pointer;
        }
        #popup-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            height: 100px;
            background-color: white;
            box-shadow: 5px 5px 15px grey;
            z-index: 1000;

            /* Mostrar/ocultar popup */
            @if(session('loan_id'))
            animation: show-animation 1s;
            @else
            right: -500px;
            @endif
        }

        @keyframes show-animation {
            0% {
                right: -500px;
            }
            100% {
                right: 20px;
            }
        }
        </style>
    @endsection

    @section('javascript')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
        <script>
            function inputNumeric(event) {
                if(event.charCode >= 48 && event.charCode <= 57){
                    return true;
                }
                return false;        
            }
        </script>
        <script>
            $(document).ready(function(){
                $("#bloquear").on('paste', function(e){
                    e.preventDefault();
                    // alert('Esta acción está prohibida');
                })                
                // $("#bloquear").on('copy', function(e){
                //     e.preventDefault();
                //     alert('Esta acción está prohibida');
                // })
            })
            $(document).ready(function(){                

                const data = {
                    labels: [
                        'Deuda Total',
                        'Total Pagado'
                    ],
                    datasets: [{
                        label: 'My First Dataset',
                        data: ["{{$loan->debt}}", "{{$loan->amountTotal - $loan->debt}}"],
                        backgroundColor: [
                        'red',
                        'rgb(54, 205, 1)'
                        ],
                        hoverOffset: 4
                    }]
                };
                const config = {
                    type: 'pie',
                    data: data,
                };
                var myChart = new Chart(
                    document.getElementById('myChart'),
                    config
                );               
            });
            function subTotal()
            {
                let amount = $(`#amount`).val() ? parseFloat($(`#amount`).val()) : 0;
                let debt = {{$loan->debt}}
                if(amount > debt)
                {
                    $('#btn-sumit').attr('disabled', 'disabled');
                    $('#label-amount').css('display', 'block');
                }
                else
                {
                    $('#btn-sumit').removeAttr('disabled');
                    $('#label-amount').css('display', 'none');

                }
                // alert(debt)

                // porcentage = porcentage/100;
                // let amountPorcentage = amountLoan*porcentage;
                // let amountTotal = amountLoan+amountPorcentage;
                // let amountDay = amountTotal / day;

                // $(`#amountPorcentage1`).val(amountPorcentage);
                // $(`#amountTotal1`).val(amountTotal);         

                // $(`#amountPorcentage`).val(amountPorcentage);
                // $(`#amountTotal`).val(amountTotal);  

                // $(`#amountDay1`).val(amountDay);
                // $(`#amountDay`).val(amountDay);  
            }

            let loan_id=0;
            let transaction_id=0;
            $(document).ready(function(){

                @if(session('loan_id'))
                    loan_id = "{{ session('loan_id') }}";
                    transaction_id = "{{ session('transaction_id') }}";
                @endif

                // Ocultar popup de impresión
                setTimeout(() => {
                    $('#popup-button').fadeOut('fast');
                }, 8000);
            });
            function printDailyMoney()
            {
                // alert(transaction_id);
                window.open("{{ url('admin/loans/daily/money/print') }}/"+loan_id+"/"+transaction_id, "Recibo", `width=320, height=700`)
            }
            

            


        </script>
    @stop

{{-- @endif --}}