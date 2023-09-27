<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Persona</th>
                    <th>Actículos</th>
                    <th>Total</th>
                    <th>Deuda</th>
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
                        <td>{{ date('d', strtotime($item->date)).'/'.$meses[intval(date('m', strtotime($item->date)))].'/'.date('Y', strtotime($item->date)) }}</td>
                        <td>{{ $item->person->first_name }} {{ $item->person->last_name1 }} {{ $item->person->last_name2 }}</td>
                        <td>
                            <ul>
                                @foreach ($item->details as $detail)
                                    <li>{{ floatval($detail->quantity) ? intval($detail->quantity) : $detail->quantity }} {{ $detail->type->name }} a Bs. {{ $detail->price }}</li>
                                    @php
                                        $subtotal += $detail->quantity * $detail->price;
                                    @endphp
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $subtotal }}</td>
                        <td></td>
                        <td></td>
                        <td>
                            {{ $item->user ? $item->user->name : '' }} <br>
                            {{ date('d/', strtotime($item->created_at)).$meses[intval(date('m', strtotime($item->created_at)))].date('/Y H:i', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right"></td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="7" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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