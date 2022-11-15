<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataStyle" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha Solicitud</th>
                    <th>Nombre Cliente</th>                    
                    <th>Tipo de Préstamos</th>                    
                    <th>Monto Prestado</th>                    
                    <th>Interés a Cobrar</th>   
                    <th>Total a Pagar</th>
                    <th>Deuda Pendiente</th>                    
                    <th style="text-align: center">Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ date("d-m-Y", strtotime($item->date)) }}</td>
                    <td>{{$item->people->first_name}} {{$item->people->last_name1}} {{$item->people->last_name2}}</td>
                    <td>{{$item->typeLoan}}</td>
                    <td style="text-align: right"> <small>Bs.</small> {{$item->amountLoan}}</td>
                    <td style="text-align: right"> <small>Bs.</small> {{$item->amountPorcentage}}</td>
                    <td style="text-align: right"> <small>Bs.</small> {{$item->amountTotal}}</td>
                    {{-- <td style="text-align: right"> <small>Bs.</small> {{$item->debt}}</td> --}}
                    <td style="text-align: right">
                        @if ($item->debt == 0)
                            <label class="label label-success">PAGADO</label>
                        @else
                            <label class="label label-danger"><small>Bs.</small> {{$item->debt}}</label>
                        @endif
                    </td>
                    <td style="text-align: right">
                        @if ($item->status == 0)
                            <label class="label label-success">PAGADO</label>
                        @endif
                        @if ($item->status == 1)
                            <label class="label label-primary">{{$item->delivered=='Si'?'ENTREGADO':'APROBADO'}}</label>                            
                        @endif
                        @if ($item->status == 2)
                            <label class="label label-danger">PENDIENTE</label>                            
                        @endif
                    </td>
                    <td class="no-sort no-click bread-actions text-right">
                        @if ($item->status == 1 && $item->delivered == 'No')
                            <a href="#" data-toggle="modal" data-target="#notificar-modal" data-name="{{ $item->people->first_name }} {{ $item->people->last_name1 }} {{ $item->people->last_name2 }}" data-phone="{{$item->people->cell_phone}}" title="Notificar al beneficiario" class="btn btn-sm">
                                <i class="fa-brands fa-square-whatsapp" style="color: #43d180; font-size: 35px;"></i>
                            </a>
                            <a title="Entregar dinero al Beneficiario" class="btn btn-sm btn-success" onclick="deliverItem('{{ route('loans-money.deliver', ['loan' => $item->id]) }}',{{$item->id}})" data-toggle="modal" data-target="#deliver-modal">
                                <i class="fa-solid fa-money-check-dollar"></i><span class="hidden-xs hidden-sm"> Entregar</span>
                            </a>
                        @endif

                        <div class="btn-group" style="margin-right: 3px">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                Mas <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                {{-- @if ($item->status == 1)
                                    <li><a onclick="agentItem('{{ route('loans-agent.update', ['loan' => $item->id]) }}')" class="btn-rotation" data-toggle="modal" data-target="#agent-modal" title="Cambiar Cobrador" >Cambiar Cobradores</a></li>
                                @endif --}}
                                @if ($item->status == 1 && $item->delivered == 'Si')
                                    <li><a href="{{ route('loans-print.calendar', ['loan'=>$item->id])}}" class="btn-rotation"  data-toggle="modal" target="_blank" title="Imprimir Calendario" >Imprimir Calendario</a></li> 
                                
                                    <li><a onclick="loan({{$item->id}})" class="btn-rotation"  data-toggle="modal" title="Imprimir Contrato" >Imprimir Contrato</a></li>
                                @endif                      
                            </ul>
                        </div>

                            <a href="{{ route('loans.show', ['loan' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a> 
                            <a href="{{ route('loans-requirement-daily.create', ['loan' => $item->id]) }}" title="Requisitos" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-file"></i><span class="hidden-xs hidden-sm"> Requisitos</span>
                            </a>
                            
                            @if ($item->status==2 && $item->loanRequirement[0]->status == 1)
                                <a title="Aprobar prestamo" class="btn btn-sm btn-dark" onclick="successItem('{{ route('loans.success', ['loan' => $item->id]) }}')" data-toggle="modal" data-target="#success-modal">
                                    <i class="fa-solid fa-money-check-dollar"></i><span class="hidden-xs hidden-sm"> Aprobar Prestamos</span>
                                </a>
                            @endif


                            @if ($item->status == 1 && $item->delivered == 'Si')
                                <a href="{{ route('loans-daily.money', ['loan' => $item->id]) }}" title="Abonar Pago"  class="btn btn-sm btn-success">
                                    <i class="voyager-dollar"></i><span class="hidden-xs hidden-sm"> Abonar Pago</span>
                                </a>
                            @endif

                            

                        {{-- @if (auth()->user()->hasPermission('edit_people'))
                            <a href="{{ route('voyager.people.edit', ['loan' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a> 
                        @endif--}}
                        @if ($item->status == 2)
                            <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('loans.destroy', ['loan' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                            </button>
                        @endif
                    </td>
                    
                    
                </tr>
                @empty
                    <tr>
                        <td style="text-align: center" valign="top" colspan="10" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<script>
   
   var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>