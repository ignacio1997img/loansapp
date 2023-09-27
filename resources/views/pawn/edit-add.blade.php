@extends('voyager::master')

@section('page_title', 'Registrar empeño')

@section('page_header')
    <h1 id="titleHead" class="page-title">
        <i class="fa-solid fa-handshake"></i> Registrar empeño
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">    
        <form id="form-submit" action="{{ route('pawn.store') }}" method="POST" enctype="multipart/form-data">
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
                                        @foreach (App\Models\ItemType::with(['category'])->where('status', 1)->get() as $item)
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
                                                <th>Características</th>
                                                <th>Observaciones</th>
                                                <th class="text-right">Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-details">
                                            <tr class="tr-empty">
                                                <td colspan="8">No hay artículos seleccionados</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="6">TOTAL Bs.</td>
                                                <td class="text-right" id="td-total"><h4>0.00</h4></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
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
        var index = 0;
        var features = @json($features);
        $(document).ready(function(){
            customSelect('#select-people_id', '{{ url("admin/people/search/ajax") }}', formatResultPeople, data => data.first_name+' '+data.last_name1+' '+data.last_name2, null);
            $('#select-item_id').change(function(){
                let type = $('#select-item_id option:selected').data('item');
                if (type) {
                    $('.tr-empty').css('display', 'none');
                    $('#table-details').append(`
                        <tr id="tr-item-${index}">
                            <td class="td-number"></td>
                            <td>
                                ${type.name} <br>
                                <span style="font-size: 12px">${type.category.name}</span>
                                <input type="hidden" name="item_type_id[]" value="${type.id}" />
                            </td>
                            <td width="120px">
                                <div class="input-group">
                                    <input type="number" name="quantity[]" id="input-quantity-${index}" onchange="getSubtotal(${index})" onkeyup="getSubtotal(${index})" class="form-control" value="1" min="1" required>
                                    <span class="input-group-addon"><small>${type.unit}</small></span>
                                </div>
                            </td>
                            <td width="150px">
                                <div class="input-group">
                                    <input type="number" name="price[]" id="input-price-${index}" onchange="getSubtotal(${index})" onkeyup="getSubtotal(${index})" class="form-control" value="${type.price}" required>
                                    <span class="input-group-addon"><small>Bs.</small></span>
                                </div>
                            </td>
                            <td>
                                <table id="table-features-${index}"></table>
                                <a class="btn btn-link" onclick="addFeature(${index})"><i class="voyager-plus"></i> agregar</a>
                            </td>
                            <td><textarea name="observation[]" class="form-control"></textarea></td>
                            <td id="td-subtotal-${index}" class="td-subtotal text-right">${type.price}</td>
                            <td class="text-right"><button type="button" class="btn btn-link" onclick="removeTr(${index})"><span class="voyager-trash text-danger"></span></button></td>
                        </td>
                    `);
                    generateNumber();
                    index++;
                    $('#select-item_id').val('').trigger('change');
                    getTotal();
                }
            });
        });

        function addFeature(index){
            let featuresList = '';
            features.map((feature) => {
                featuresList = featuresList+`<option value="${feature.id}">${feature.name}</option>`
            });
            // Se va a nombrar los atributos de cada item concatenando el numero de item asignado
            // para agruparlos al momento de recorerlos
            $(`#table-features-${index}`).append(`
                <tr id="tr-features-${index}">
                    <td><select name="features_${index}[]" style="height:24px !important">${featuresList}</select></td>
                    <td><input name="features_value_${index}[]" required /></td>
                    <td><button type="button" class="btn-danger" onclick="removeTrFeature(${index})">x</button></td>
                </tr>
            `)
        }

        function getSubtotal(index){
            let price = $(`#input-price-${index}`).val() ? parseFloat($(`#input-price-${index}`).val()) : 0;
            let quantity = $(`#input-quantity-${index}`).val() ? parseFloat($(`#input-quantity-${index}`).val()) : 0;
            if (quantity > 0) {
                $(`#td-subtotal-${index}`).text((price*quantity).toFixed(2));
                getTotal();
            } else {
                $(`#input-quantity-${index}`).val(1);
                getSubtotal(index);
                toastr.warning('La cantidad debe ser de al menos 1', 'Advertencia');
            }
        }

        function getTotal(){
            let total = 0;
            $('.td-subtotal').each(function(){
                let value = parseFloat($(this).text());
                total += value;
            });
            $(`#td-total`).html(`<h4>${total.toFixed(2)}</h4>`);
        }

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
            generateNumber();
            getTotal();
        }

        function removeTrFeature(index){
            $(`#tr-features-${index}`).remove();
            generateNumber();
            getTotal();
        }
    </script>
@stop