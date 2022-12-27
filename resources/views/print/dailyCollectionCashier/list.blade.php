
<div class="col-md-12 text-right">

    {{-- <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Excel</button> --}}
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table id="dataStyle" style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:5px">N&deg;</th>
                        <th rowspan="2" style="text-align: center">CI</th>
                        <th rowspan="2" style="text-align: center">CLIENTE</th>
                        <th rowspan="4" style="text-align: center">ATENDIDO POR</th>
                        <th colspan="3" style="text-align: center">DETALLE DEL PRESTAMOS</th>
                        <th colspan="3" style="text-align: center">DETALLE DE PAGO</th>
                    </tr>
                    <tr>
                        <th style="text-align: center">CODIGO PRESTAMO</th>
                        <th style="text-align: center">FECHA DE PRESTAMO</th>
                        <th style="text-align: center">TOTAL DEL PRESTAMO</th>

                        <th style="text-align: center">ID PAGO</th>
                        <th style="text-align: center">FECHA DE PAGO</th>
                        <th style="text-align: center">TOTAL PAGADO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @forelse ($data as $item)
                        <tr style="text-align: center">
                            <td>{{ $count }}</td>
                            <td>{{ $item->ci }}</td>
                            <td style="text-align: right">{{ $item->last_name1}} {{ $item->last_name2}} {{ $item->first_name}}</td>
                            <td style="text-align: right">{{ $item->name}}</td>
                            <td style="text-align: right">{{ $item->code}}</td>
                            <td style="text-align: center">{{date('d/m/Y', strtotime($item->dateDay))}}</td>
                            <td style="text-align: right">{{ number_format($item->amountTotal,2) }}</td>
                            <td style="text-align: right">{{ $item->loanDayAgent_id}}</td>
                            <td style="text-align: center">{{date('d/m/Y H:m:s', strtotime($item->loanDayAgent_fecha))}}</td>
                            <td style="text-align: right"><small>{{ number_format($item->amount,2) }}</small></td>
                                                                                  
                            
                        </tr>
                        @php
                            $count++;
                            
                        @endphp
                        
                    @empty
                        <tr style="text-align: center">
                            <td colspan="10">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function(){

})
</script>