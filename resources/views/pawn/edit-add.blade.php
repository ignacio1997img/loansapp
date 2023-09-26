@extends('voyager::master')

@section('page_title', 'Registrar empeño')

@section('page_header')
    <h1 id="titleHead" class="page-title">
        <i class="fa-solid fa-handshake"></i> Registrar empeño
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">    
        <form id="form-submit" action="{{ route('garments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="cashier_id" value="">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            {{-- Verificar que se haya abierto caja --}}
                            @if (true)  
                                <div class="alert alert-warning">
                                    <strong>Advertencia:</strong>
                                    <p>No puedes registrar debido a que no tiene una caja asignada.</p>
                                </div>
                            @else     
                                @if (false)
                                    <div class="alert alert-warning">
                                        <strong>Advertencia:</strong>
                                        <p>No puedes registrar debido a que no tiene una caja activa.</p>
                                    </div>
                                @endif
                            @endif

                            <h5>Datos Generales</h5>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <small for="people_id">Beneficiario del Prestamo</small>
                                    <select name="people_id" class="form-control select2" id="select-people_id" required></select>
                                </div>
                                <div class="form-group col-md-6">
                                    <small for="date">Fecha</small>
                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <hr>
                            <h5>Detalle de artículos</h5>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small for="item_id">Tipo de artículo</small>
                                    <select name="item_id" class="form-control select2" id="select-item_id">
                                        <option value="" selected disabled>Seleccione tipo de artículo</option>
                                        @foreach (App\Models\ItemType::where('status', 1)->get() as $item)
                                            <option value="{{ $item->id }}" data-item='@json($item)'>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>N&deg;</th>
                                                <th>Tipo</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Observaciones</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-details">
                                            <tr class="tr-empty">
                                                <td colspan="6">No hay artículos seleccionados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" id="btn-submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>         
        </form>              
    </div>
@stop

@section('css')
    <style>
        .select2{
            width: 100% !important;
        }
    </style>
@stop

@section('javascript')
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        var trCount = 0;
        $(document).ready(function(){
            $('#select-item_id').change(function(){
                let type = $('#select-item_id option:selected').data('item');
                if (type) {
                    $('.tr-empty').css('display', 'none');
                    $('#table-details').append(`
                        <tr id="tr-item-${trCount}">
                            <td class="td-number"></td>
                            <td>${type.name}</td>
                            <td width="150px">
                                <div class="input-group">
                                    <input type="number" name="quantity[]" id="input-quantity-${trCount}" class="form-control" value="1" required>
                                    <span class="input-group-addon"><small>${type.unit}</small></span>
                                </div>
                            </td>
                            <td width="150px">
                                <div class="input-group">
                                    <input type="number" name="price[]" id="input-price-${trCount}" class="form-control" value="${type.price}" required>
                                    <span class="input-group-addon"><small>Bs.</small></span>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="">
                            </td>
                            <td class="text-right"><button type="button" class="btn btn-link" onclick="removeTr(${trCount})"><span class="voyager-trash text-danger"></span></button></td>
                        </td>
                    `);
                    generateNumber();
                    trCount++;
                    $('#select-item_id').val('').trigger('change');
                }
            });
        });

        function generateNumber(){
            let number = 1;
            $('.td-number').each(function(){
                $(this).text(number);
                number++;
            });

            // Si está vacío
            if(number == 1){
                $('.tr-empty').css('display', 'block');
            }
        }

        function removeTr(index){
            $(`#tr-item-${index}`).remove();
            // getTotal();
            generateNumber();
        }
    </script>
@stop