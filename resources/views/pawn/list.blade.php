<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>N&deg;</th>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Persona</th>
                    <th>Actículos</th>
                    <th>Detalles</th>
                    <th>Estado</th>
                    <th>Registrado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $meses = ['', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
                    $cont = 1;
                @endphp
                @forelse ($data as $item)
                    @php
                        $subtotal = 0;
                    @endphp
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ date('d', strtotime($item->date)).'/'.$meses[intval(date('m', strtotime($item->date)))].'/'.date('Y', strtotime($item->date)) }}</td>
                        <td>
                            {{ $item->person->first_name }} {{ $item->person->last_name1 }} {{ $item->person->last_name2 }} <br>
                            <b>CI: {{ $item->person->ci ?? 'No definido' }}</b>
                        </td>
                        <td>
                            <ul>
                                @foreach ($item->details as $detail)
                                    @php
                                        $features_list = '';
                                        foreach ($detail->features_list as $feature) {
                                            $features_list .= '<span><b>'.$feature->feature->name.'</b>: '.$feature->value.'</span><br>';
                                        }
                                    @endphp
                                    <li @if($detail->features_list->count() || $detail->observations) data-toggle="popover" data-trigger="hover" data-placement="top" data-html="true" title="" data-content="<div>{!! $features_list ? $features_list : '' !!}{!! $detail->observations && $detail->features_list->count() ? '<hr>' : '' !!} {{ $detail->observations }}</div>" style="cursor: pointer" @endif style="font-size: 12px">{{ floatval($detail->quantity) ? intval($detail->quantity) : $detail->quantity }}{{ $detail->type->unit }} {{ $detail->type->name }} a {{ floatval($detail->price) ? intval($detail->price) : $detail->price }}<span style="font-size: 10px">Bs.</span></li>
                                    @php
                                        $subtotal += $detail->quantity * $detail->price;
                                    @endphp
                                @endforeach
                            </ul>
                        </td>
                        @php
                            $interest_rate = $subtotal * ($item->interest_rate /100);
                            $payment = $item->payments->sum('amount');
                            $debt = $subtotal + $interest_rate - $payment;
                        @endphp
                        <td style="width: 150px">
                            <table style="width: 100%">
                                <tr>
                                    <td><b>Prestamos</b></td>
                                    <td class="text-right">{{ $subtotal }}<span style="font-size: 10px">Bs.</span></td>
                                </tr>
                                <tr>
                                    <td><b>Interes</b></td>
                                    <td class="text-right">{{ $interest_rate }}<span style="font-size: 10px">Bs.</span></td>
                                </tr>
                                <tr>
                                    <td><b>Total</b></td>
                                    <td class="text-right">{{ $subtotal + $interest_rate }}<span style="font-size: 10px">Bs.</span></td>
                                </tr>
                                <tr>
                                    <td><b>Deuda</b></td>
                                    <td class="text-right">{{ $debt }}<span style="font-size: 10px">Bs.</span></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            @php
                                switch ($item->status) {
                                    case 'pendiente':
                                        $label = 'warning';
                                        break;
                                    case 'pagado':
                                        $label = 'primary';
                                        break;
                                    default:
                                        $label = 'default';
                                        break;
                                }
                            @endphp
                            <label class="label label-{{ $label }}">{{ $item->status }}</label>
                        </td>
                        <td>
                            {{ $item->user ? $item->user->name : '' }} <br>
                            {{ date('d/', strtotime($item->created_at)).$meses[intval(date('m', strtotime($item->created_at)))].date('/Y H:i', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="margin-right: 5px">
                                    Más <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                    @if ($debt)
                                    <li><a href="#" class="btn-payment" data-toggle="modal" data-target="#payment-modal" data-id="{{ $item->id }}" data-debt="{{ $debt }}" title="Pagar">Pagar</a></li>
                                    <li class="divider"></li>
                                    @endif
                                    <li><a href="{{ route('pawn.print', $item->id) }}" title="Imprimir" target="_blank">Imprimir</a></li>
                                </ul>
                            </div>
                            @if (auth()->user()->hasPermission('read_pawn'))
                                <a href="{{ route('pawn.show', $item->id) }}" title="Ver" class="btn btn-sm btn-warning view">
                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                </a>
                            @endif
                            @if (auth()->user()->hasPermission('delete_pawn'))
                                <button title="Borrar" disabled class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('pawn.destroy', ['pawn' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Eliminar</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="9" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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

<style>
    #dataTable ul {
        padding-left: 20px
    }
    .bread-actions .btn{
        border: 0px
    }
    .mce-edit-area{
        max-height: 250px !important;
        overflow-y: auto;
    }
</style>

<script>
    moment.locale('es');
    var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();

        $('.btn-payment').click(function(){
            let id = $(this).data('id');
            let debt = $(this).data('debt');
            $('#form-payment input[name="id"]').val(id);
            $('#form-payment input[name="amount"]').val(debt);
            $('#form-payment input[name="amount"]').attr('max', debt);
        });

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