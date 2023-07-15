@extends('voyager::master')

@section('page_title', 'Crear Prendario')

@if (auth()->user()->hasPermission('add_garments'))

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-handshake"></i> Crear Prendario
        </h1>
        {{-- <a href="{{ route('garments.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a> --}}
    @stop
    @php
        $joya_id =0;
    @endphp

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('garments.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading">
                                <h5 id="h4" class="panel-title">Detalle del Prendario</h5>
                            </div>
                            <div class="panel-body">
                                @if (!$cashier)  
                                    <div class="alert alert-warning">
                                        <strong>Advertencia:</strong>
                                        <p>No puedes registrar debido a que no tiene una caja asignada.</p>
                                    </div>
                                @else     
                                    @if ($cashier->status != 'abierta')
                                        <div class="alert alert-warning">
                                            <strong>Advertencia:</strong>
                                            <p>No puedes registrar debido a que no tiene una caja activa.</p>
                                        </div>
                                    @endif
                                @endif
                                <h5>Datos Personales</h5>
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <small for="customer_id">Beneficiario del Prestamo</small>
                                        <select name="people_id" class="form-control" id="select_people_id" required></select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <small for="customer_id">Fotocopia CI/NIT (Fotografía)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png,application/pdf" name="fileCi" id="fileCi" class="form-control text imageLength">
                                    </div>
                                </div>
                                <hr>
                                <h5>Detalle del Contrato</h5>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <small>Tipo</small>
                                        <select name="type" id="type" class="form-control select2" required>
                                            <option value="" disabled selected>--Selecciona una opción--</option>
                                            <option value="compacto">Minuta de Compacto de Rescate</option>
                                            <option value="contratoprivado">Contrato Privado</option>
                                        </select>
                                    </div>    
                                    <div class="form-group col-md-4">
                                        <small>Cantidad Mes</small>
                                        <select name="month" id="month" class="form-control select2" required>
                                            <option value="" disabled selected>--Selecciona una opción--</option>
                                            <option value="1">1 Mes</option>
                                            <option value="3">3 Mes</option>
                                        </select>
                                    </div>                                 
                                </div>
                                <hr>
                                <h5>Detalle de la Prenda</h5>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <small>Categoria</small>
                                        <select name="category_id" id="category_id" class="form-control select2" required>
                                            <option value="" disabled selected>--Selecciona una opción--</option>
                                            @foreach ($category as $item)
                                                <option value="{{$item->id}}" data-articles="{{ $item->article }}">{{$item->name}}</option>                                                
                                            @endforeach
                                        </select>
                                    </div>    
                                    <div class="form-group col-md-4">
                                        <small>Articulo</small>
                                        <select name="article_id" id="article_id" class="form-control select2" required>
                                            
                                        </select>
                                    </div>    
                                    
                                    
                                    <div class="form-group col-md-4">
                                        {{-- <button type="button" id="bt_add" class="btn btn-success"><i class="voyager-basket"></i> Agregar Artículo</button> --}}
                                        <div class="form-line">
                                            <button type="button" style="height:50px; padding-center: 30px;" id="bt_add" class="btn btn-success"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                    </div> 
                                </div>


                                {{-- <div class="form-group">
                                    <label>Habitaciones</label>
                                    <select name="room_id" id="sasa" class="form-control select2" required>
                                        <option value="aa">aaa</option>
                                        <option value="aa">b</option>
                                    </select>
                                </div> --}}
                                
                                <div id="garment_detail">
                                    <div class="row">
                                       
                                    </div>
                                </div>
                                <hr style="border: 5px solid #22a7f0; border-radius: 10px;">
                                













                                {{-- <div class="row">
                                    <div class="form-group col-md-12">
                                        <small for="customer_id">Artículo</small>
                                        <select name="article_id" class="form-control" id="select_article_id" required></select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <small for="customer_id">Prenda (Fotografía)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png" multiple name="filePrenda[]" id="filePrenda" class="form-control text imageLength">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <small for="customer_id">Documento Respaldo (Documento)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png" multiple name="docPrenda[]" id="docPrenda" class="form-control text imageLength">
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <small for="customer_id">Detalle/Descripcion de la Prenda</small>
                                        <textarea name="articleDescription" id="articleDescription" class="richTextBox"></textarea>
                                    </div>
                                    
                                </div> --}}

                                <hr>
                                {{-- <h5>Detalle del Contrato</h5> --}}

                               

                                <div class="row">
                                    <div class="form-group col-md-2">
                                        {{-- <small>Monto a Prestar (Bs.)</small>
                                        <input type="number" name="amountLoan" id="amountLoan" style="text-align: right" value="0" min="1" step=".01" onchange="subTotal()" onkeyup="subTotal()" class="form-control text" required> --}}
                                        {{-- <input type="number" name="amountLoan" id="amountLoan" style="text-align: right" value="0" min="1" step=".01" onkeypress="return filterFloat(event,this);" onchange="subTotal()" onkeyup="subTotal()" class="form-control text" required> --}}
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Precio del Dolar ($)</small>
                                        <input type="number" id="priceDollar" value="{{setting('configuracion.dollar')}}" style="text-align: right" disabled class="form-control text">
                                        <input type="hidden" name="priceDollar" value="{{setting('configuracion.dollar')}}" style="text-align: right" class="form-control text" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Monto a Prestar ($)</small>
                                        <input type="number" id="amountLoanDollar" style="text-align: right" disabled value="0" class="form-control text">
                                        <input type="hidden" id="amountLoanDollar1" step="any" name="amountLoanDollar" style="text-align: right" value="0" class="form-control text" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <small>Interes Prestamos (%)</small>
                                        <input type="number" min="1" id="porcentage" name="porcentage" value="{{setting('configuracion.porcentageGarment')}}" onchange="subTotal()" onkeyup="subTotal()" style="text-align: right" class="form-control text" >
                                        <input type="hidden" min="1" name="porcentage" id="porcentage1" value="{{setting('configuracion.porcentageGarment')}}" style="text-align: right" class="form-control text" required>
                                    </div>    
                                    <div class="form-group col-md-2">
                                        <small>Interes a Pagar (Bs.)</small>
                                        <input type="number" id="amountPorcentage" value="0" step="any" style="text-align: right" disabled class="form-control text">
                                        <input type="hidden" id="amountPorcentage1" name="amountPorcentage" value="0" step="any" style="text-align: right" class="form-control text" required>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <small>Total Dinero a Prestar (Bs.)</small>
                                        <input type="number" disabled id="amountTotal1" value="0" style="text-align: right" class="form-control text" required>
                                        <input type="hidden" name="amountTotal" id="amountTotal" value="0" style="text-align: right" class="form-control text" required>
                                        
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        {{-- <label for="observation"></label> --}}
                                        <small>Observación</small>
                                        <textarea name="observation" id="observation" class="form-control text" cols="30" rows="5"></textarea>
                                    </div>                                  
                                </div>
                                @if ($cashier)    
                                    @if ($cashier->status == 'abierta')

                                        <input type="hidden" name="cashierRegister_id" value="{{$cashier->id}}">
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <button type="submit" id="btn_submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
  
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script> --}}
        <script>

            $(document).ready(function(){

                $('#bt_add').click(function() {
                    agregar();
                });


                var additionalConfig = {
                    selector: 'textarea.richTextBox[name="articleDescription"]',
                    // selector: 'textarea#basic-example',
                    height: 10,
                    menubar: false,



                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter |'+
                    'alignright alignjustify',
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
                }
                
                tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));

                $('#agent').submit(function(e){
                    $('#btn_submit').text('Guardando...');
                    $('#btn_submit').attr('disabled', true);

                });









            })

            var id=0;
            let fila = '';
            function agregar()
            {
                // let modelos = '{{$modelo}}';
                let modelos = @json($modelo);
                let marcas = @json($marca);
                let joyas = @json($joya);


                // alert(joyas[0].quilate)


                let modelo_list = '<option value="" disabled selected>--Seleccione una opción--</option>';
                let marca_list = '<option value="" disabled selected>--Seleccione una opción--</option>';
                let joya_list = '<option value="" disabled selected>--Seleccione una opción--</option>';
 
                modelos.map(
                    item => {
                        modelo_list += `<option value="${item.id}">${item.name}</option>`;
                    }
                );

                marcas.map(
                    item => {
                        marca_list += `<option value="${item.id}">${item.name}</option>`;
                    }
                );

                joyas.map(
                    item => {
                        joya_list += `<option value="${item.id}" data-quilate="${item.quilate}">${item.name}</option>`;
                        // alert(item.quilate)
                    }
                );

                // $('#room_id').html(marca_list);


                categoryText=$("#category_id option:selected").text();
                categoryVal =$("#category_id").val();
                articleText=$("#article_id option:selected").text();
                articleVal =$("#article_id").val()??0;
                id++;
                auxJoya ='';
                // alert(articleVal)
                if(articleVal != 0)
                {   fila = '';
                    $.get('{{route('articles-developer.ajax')}}/'+articleVal, function (data) {
                    fila = '<hr style="border: 5px solid #22a7f0; border-radius: 10px;" id="hr-'+id+'"><h3 id="titleHead" class="title-'+id+'" style="text-align: center">Detalle del Artículo / Producto</h3><button  id="btn-'+id+'" onclick="removeDiv('+id+')" title="Borrar" class="btn btn-sm btn-danger delete"><i class="voyager-trash"></i></button>'+
                                '<div class="row" id="div-'+id+'">';
                                    for (i = 0; i < data.length; i++) {
                    fila+=              '<div class="form-group col-md-4">'+
                                            '<small>'+data[i].title+'</small>';

                                            // Para los input
                                            if(data[i].tool == 'input'){
                    fila+=                  '<input type="'+data[i].type+'" name="valores[]" id="amountLoan"'; if(data[i].type=='number'){fila+='style="text-align: right" value="0" min="1" step=".01"';} fila+='class="form-control" '+data[i].required+'>'+
                                            '<input type="hidden" name="title[]" value="'+data[i].title+'">';
                                            }

                                            let aux = "'"+data[i].detail+'-'+id+"'";
                                            if(data[i].tool == 'select'){
                    // fila+=                  '<input type="'+data[i].type+'" name="amountLoan" id="amountLoan"'; if(data[i].type=='number'){fila+='style="text-align: right" value="0" min="1" step=".01"';} fila+='class="form-control" '+data[i].required+'>';
                    fila+=                      '<select name="valores[]" id="'+data[i].detail+'-'+id+'" onchange="mostrarprecio('+aux+','+id+');" class="form-control select2" '+data[i].required+'>';
                                                    if(data[i].concatenar == 'modelo_list')
                                                    {
                    fila+=                              modelo_list;
                                                    }
                                                    if(data[i].concatenar == 'marca_list')
                                                    {
                    fila+=                              marca_list;
                                                    }
                                                    if(data[i].concatenar == 'joya_list')
                                                    {
                                                        auxJoya = 'joya_list';
                    fila+=                              joya_list;
                                                    }
                    fila+=                      '</select>'+
                                                '<input type="hidden" name="title[]" value="'+data[i].title+'">';                                            
                                            }
                    fila+=              '</div>';                    

                                        if(data[i].concatenar == 'joya_list')
                                        {
                                            let auxprice = "'quilate-"+id+"'";
                    fila+=                  '<div class="form-group col-md-4">'+
                                                '<small>Quilate</small>'+
                                                '<select name="valores[]" id="quilate-'+id+'" onchange="mostrarprice('+auxprice+','+id+');"  class="form-control select2" '+data[i].required+'>';

                    fila+=                      '</select> <input type="hidden" name="title[]" value="Quilate">'+
                                            '</div>';
                                        }

                                    }     
                    fila+=      '</div>'+
                                '<div class="row" id="div-foot-'+id+'">'+
                                    '<h4 id="titleHead" style="text-align: center"><i class="fa-solid fa-money-bill"></i> Monto por este Artículo / Prenda</h4>'+
                                    '<div class="form-group col-md-2">'+
                                        '<small>Monto a prestar (Bs.)</small>'+
                                        '<input type="number" name="amountLoan[]" id="amountLoan-'+id+'" onchange="subTotal('+id+')" onkeyup="subTotal('+id+')" style="text-align: right" value="0" min="1" step=".01" class="form-control text" required>'+       
                                    '</div>'+
                                    '<div class="form-group col-md-2" '; if(auxJoya != 'joya_list'){fila+='style="visibility:hidden"';   } fila+='  >'+
                                        '<small>Precio</small>'+
                                        '<input type="number" disabled id="pric-'+id+'" style="text-align: right" value="1" min="1" step=".01" class="form-control text" required>'+      
                                        '<input type="hidden" id="price-'+id+'" name="price[]" value="1" required>'+
                                    '</div>'+
                                    '<div class="form-group col-md-6">'+
                                        
                                    '</div>'+
                                    '<div class="form-group col-md-2">'+
                                        '<small>Monto (Bs.)</small>'+
                                        '<input type="number" id="subAmount-'+id+'" disabled style="text-align: right" value="0" min="1" step=".01" class="form-control text">'+
                                        '<input type="hidden" id="subAmountLoan-'+id+'" name="subAmountLoan[]" value="0" class="form-control text-subtotal" required>'+

                                            
                                    '</div>'+
                                '</div>';
                        $('#garment_detail').append(fila);
                        toastr.success('Prenda agregada exitosamente..', 'Información');

                    });     
                }
                else
                {
                    toastr.warning('Seleccione un artículo..', 'Información');
                }
                $('#garment_detail').append(fila);
            }

            function mostrarprecio(cadena,id) {
                let joya_id = $('#'+cadena+' option:selected').val();
                let quilates_list = '';
                $('#quilate-'+id).empty();
                $.get('{{route('garments-quilate.ajax')}}/'+joya_id, function (data) {
                    quilates_list = '<option value="" disabled selected>--Seleccione una opción--</option>';

                    for(i=0; i<data.length; i++)
                    {
                        quilates_list+= '<option value="'+data[i].id+'" data-price="'+data[i].price+'">Tipo de Kilate:'+data[i].name+',    Precio Por Gramo: '+data[i].price+'</option>';
                    }
                    $('#quilate-'+id).append(quilates_list);
                }); 
                $('#price-'+id).val(1);
                $('#pric-'+id).val(1);
            }

            function mostrarprice(cadena,id) {
                let priceG = $('#'+cadena+' option:selected').data('price');
                $('#price-'+id).val(priceG);
                $('#pric-'+id).val(priceG);
                subTotal(id);
            }
            function removeDiv(id){
                $(`#div-${id}`).remove();
                $(`#div-foot-${id}`).remove();
                $(`#btn-${id}`).remove();
                $(`#hr-${id}`).remove();
                $(`.title-${id}`).remove();
                // $('#select_producto').val("").trigger("change");
                // setNumber();
                getTotal();
            }







            function subTotal(id)
            {
                let amountLoan = $(`#amountLoan-${id}`).val() ? parseFloat($(`#amountLoan-${id}`).val()) : 0;
                let price = $(`#price-${id}`).val() ? parseFloat($(`#price-${id}`).val()) : 0;

                let total = amountLoan*price;
                $(`#subAmount-${id}`).val(total.toFixed(2));
                $(`#subAmountLoan-${id}`).val(total.toFixed(2));

                getTotal();                
            }

            function getTotal(){
                let total = 0;
                $(".text-subtotal").each(function(index) {
                    total += parseFloat($(this).val());
                });



                $('#amountTotal1').val(total.toFixed(2));
                $('#amountTotal').val(total.toFixed(2));


                let priceDollar = $(`#priceDollar`).val() ? parseFloat($(`#priceDollar`).val()) : 0;

                let porcentage = $(`#porcentage`).val() ? parseFloat($(`#porcentage`).val()) : 0;
                $(`#porcentage1`).val(porcentage);

                porcentage = porcentage/100;
                // priceDollar = priceDollar/100;
                

                let amountPorcentage = total*porcentage;
                let amountLoanDollar = total/priceDollar;

                

                $(`#amountPorcentage`).val(amountPorcentage.toFixed(2));
                $(`#amountLoanDollar`).val(amountLoanDollar.toFixed(2));
                $(`#amountPorcentage1`).val(amountPorcentage.toFixed(2));
                $(`#amountLoanDollar1`).val(amountLoanDollar.toFixed(2));

            }
            // function subTotal()
            // {
            //     let amountLoan = $(`#amountLoan`).val() ? parseFloat($(`#amountLoan`).val()) : 0;
            //     let priceDollar = $(`#priceDollar`).val() ? parseFloat($(`#priceDollar`).val()) : 0;

            //     let porcentage = $(`#porcentage`).val() ? parseFloat($(`#porcentage`).val()) : 0;
            //     $(`#porcentage1`).val(porcentage);

            //     porcentage = porcentage/100;
            //     // priceDollar = priceDollar/100;
                

            //     let amountPorcentage = amountLoan*porcentage;
            //     let amountLoanDollar = amountLoan/priceDollar;

                

            //     $(`#amountPorcentage`).val(amountPorcentage.toFixed(2));
            //     $(`#amountLoanDollar`).val(amountLoanDollar.toFixed(2));
            //     $(`#amountPorcentage1`).val(amountPorcentage.toFixed(2));
            //     $(`#amountLoanDollar1`).val(amountLoanDollar.toFixed(2));
                
            // }





            function filterFloat(evt,input){
                // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
                var key = window.Event ? evt.which : evt.keyCode;    
                var chark = String.fromCharCode(key);
                var tempValue = input.value+chark;
                if(key >= 48 && key <= 57){
                    if(filter(tempValue)=== false){
                        return false;
                    }else{       
                        return true;
                    }
                }
                // else{
                //     if(key == 8 || key == 13 || key == 46 || key == 0) {            
                //         return true;              
                //     }else{
                //         return false;
                //     }
                // }
            }
            function filter(__val__){
                var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
                if(preg.test(__val__) === true){
                    return true;
                }else{
                return false;
                }
                
            }

        </script>


        <script>
            $(document).ready(function(){
                var productSelected;
                var articleSelected;
                
                $('#select_people_id').select2({
                // tags: true,
                    placeholder: '<i class="fa fa-search"></i> Buscar...',
                    escapeMarkup : function(markup) {
                        return markup;
                    },
                    language: {
                        inputTooShort: function (data) {
                            return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                        },
                        noResults: function () {
                            return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                        }
                    },
                    quietMillis: 250,
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ url('admin/loans/people/ajax') }}",        
                        processResults: function (data) {
                            let results = [];
                            data.map(data =>{
                                results.push({
                                    ...data,
                                    disabled: false
                                });
                            });
                            return {
                                results
                            };
                        },
                        cache: true
                    },
                    templateResult: formatResultCustomers_people,
                    templateSelection: (opt) => {
                        productSelected = opt;
                        // alert(opt)
                        
                        return opt.first_name?opt.first_name+' '+opt.last_name1+' '+opt.last_name2:'<i class="fa fa-search"></i> Buscar... ';
                    }
                }).change(function(){
                
                });

                $('#select_article_id').select2({
                // tags: true,
                    placeholder: '<i class="fa fa-search"></i> Buscar...',
                    escapeMarkup : function(markup) {
                        return markup;
                    },
                    language: {
                        inputTooShort: function (data) {
                            return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                        },
                        noResults: function () {
                            return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                        }
                    },
                    quietMillis: 250,
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ url('admin/garments/article/ajax') }}",        
                        processResults: function (data) {
                            let results = [];
                            data.map(data =>{
                                results.push({
                                    ...data,
                                    disabled: false
                                });
                            });
                            return {
                                results
                            };
                        },
                        cache: true
                    },
                    templateResult: formatResultCustomers_article,
                    templateSelection: (opt) => {
                        articleSelected = opt;
                        
                        return opt.id?opt.name+', Modelo: '+opt.model.name+', Marca: '+opt.marca.name+'. Categoría: '+opt.category.name:'<i class="fa fa-search"></i> Buscar... ';
                    }
                }).change(function(){
                
                });


            })

            function formatResultCustomers_people(option){
            // Si está cargando mostrar texto de carga
                if (option.loading) {
                    return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
                }
                let image = "{{ asset('images/default.jpg') }}";
                if(option.image){
                    image = "{{ asset('storage') }}/"+option.image.replace('.', '-cropped.');
                    // alert(image)
                }
                
                // Mostrar las opciones encontradas
                return $(`  <div style="display: flex">
                                <div style="margin: 0px 10px">
                                    <img src="${image}" width="50px" />
                                </div>
                                <div>
                                    <small>CI: </small><b style="font-size: 15px; color: black">${option.ci?option.ci:'No definido'}</b><br>
                                    <b style="font-size: 15px; color: black">${option.first_name} ${option.last_name1} ${option.last_name2} </b>
                                </div>
                            </div>`);
            }

            function formatResultCustomers_article(option){
            // Si está cargando mostrar texto de carga
                if (option.loading) {
                    return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
                }
                let image = "{{ asset('images/default.jpg') }}";
                if(option.image){
                    image = "{{ asset('storage') }}/"+option.image.replace('.', '-cropped.');
                    // alert(image)
                }
                
                // Mostrar las opciones encontradas
                return $(`  <div style="display: flex">
                                <div style="margin: 0px 10px">
                                    <img src="${image}" width="50px" />
                                </div>
                                <div>
                                    <small>Artículo: </small><b style="font-size: 15px; color: black">${option.name}</b><br>
                                    <small>Modelo: </small><b style="font-size: 15px; color: black">${option.model.name}</b><br>
                                    <small>Marca: </small><b style="font-size: 15px; color: black">${option.marca.name}</b><br>
                                    <small>Categoría: </small><b style="font-size: 15px; color: black">${option.category.name}</b><br>
                                </div>
                            </div>`);
            }




                var articles_country = [];

                $('#category_id').change(function(){
                    let articles = $('#category_id option:selected').data('articles');
                    let articles_list = '<option value="" disabled selected>--Selecciona un articulo--</option>';
                    // alert(articles[name])
                    if(articles.length){
                        // articles_country = articles; 
                        articles.map(articles => {
                            articles_list += `<option value="${articles.id}">${articles.name}</option>`;
                        });
                    }
                    // else{
                    //     articles_list += `<option value="">Ninguna</option>`;
                    // }
                    $('#article_id').html(articles_list);
                });
        </script>
    @stop

@endif