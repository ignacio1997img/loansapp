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
                                    <div class="form-group col-md-12">
                                        <small for="customer_id">Beneficiario del Prestamo</small>
                                        <select name="people_id" class="form-control" id="select_people_id" required></select>
                                    </div>
                                    {{-- <div class="form-group col-md-4">
                                        <small for="customer_id">Fotocopia CI/NIT (Fotografía)</small>
                                        <input type="file" accept="image/jpeg,image/jpg,image/png,application/pdf" name="fileCi" id="fileCi" class="form-control text imageLength">
                                    </div> --}}
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
                                        <select name="category_id" id="select_category" class="form-control select2">
                                    
                                        </select>
                                    </div>    
                          
                                    
                                    {{-- <div class="form-group col-md-4">
                                        <div class="form-line">
                                            <button type="button" style='width:50px; height:50px' id="bt_add" class="btn btn-success btn-lg"><i class="fa-solid fa-cart-shopping" style="font-size: 20px" ></i></button>
                                        </div>
                                    </div>  --}}
                                </div>

                                
                                <div id="garment_detail">
                                    
                                </div>
                                <hr style="border: 5px solid #22a7f0; border-radius: 10px;">
                                













                                <hr>                               

                                <div class="row">
                                    <div class="form-group col-md-2">
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
        <script src="{{ url('js/main.js') }}"></script>

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
            // function agregar()
            // {
            //     // let modelos = '{{$modelo}}';
            //     let modelos = @json($modelo);
            //     let marcas = @json($marca);
            //     let joyas = @json($typeMetal);


            //     // alert(joyas[0].quilate)


            //     let modelo_list = '<option value="" disabled selected>--Seleccione una opción--</option>';
            //     let marca_list = '<option value="" disabled selected>--Seleccione una opción--</option>';
            //     let joya_list = '<option value="" disabled selected>--Seleccione una opción--</option>';
 
            //     modelos.map(
            //         item => {
            //             modelo_list += `<option value="${item.id}">${item.name}</option>`;
            //         }
            //     );

            //     marcas.map(
            //         item => {
            //             marca_list += `<option value="${item.id}">${item.name}</option>`;
            //         }
            //     );

            //     joyas.map(
            //         item => {
            //             joya_list += `<option value="${item.id}" data-quilate="${item.quilate}">${item.name}</option>`;
            //             // alert(item.quilate)
            //         }
            //     );

            //     // $('#room_id').html(marca_list);


            //     categoryText=$("#category_id option:selected").text();
            //     categoryVal =$("#category_id").val();
            //     articleText=$("#article_idd option:selected").text();
            //     articleVal =$("#article_idd").val()??0;
            //     id++;
            //     auxJoya ='';
            //     // alert(articleVal)
            //     if(articleVal != 0)
            //     {   fila = '';
            //         $.get('{{route('articles-developer.ajax')}}/'+articleVal, function (data) {
            //         fila = '<hr style="border: 5px solid #22a7f0; border-radius: 10px;" id="hr-'+id+'"><h3 id="titleHead" class="title-'+id+'" style="text-align: center">'+articleText+'</h3><button  id="btn-'+id+'" onclick="removeDiv('+id+')" title="Borrar" class="btn btn-sm btn-danger delete"><i class="voyager-trash"></i></button>'+
            //                     '<div class="row" id="div-'+id+'"> <input type="hidden" name="article[]" value="'+articleVal+'"><input type="hidden" name="group[]" value="'+id+'">';
            //                         for (i = 0; i < data.length; i++) 
            //                         {
            //         fila+=              '<div class="form-group col-md-4">'+
            //                                 '<small>'+data[i].title+'</small>'+
            //                                 '<input type="hidden" name="developer'+id+'[]" value="'+data[i].id+'">'+
            //                                 '<input type="hidden" name="title'+id+'[]" value="'+data[i].title+'">';

            //                                 // Para los input
            //                                 if(data[i].tool == 'input'){
            //         fila+=                  '<input type="'+data[i].type+'" name="name'+id+'[]" id="'+data[i].detail+'-'+id+'"'; if(data[i].type=='number'){fila+='style="text-align: right" value="0" min="1" step=".01"';} fila+='class="form-control" '+data[i].required+'>'+
            //                                 '<input type="hidden" name="value'+id+'[]" value="">';
            //                                 }

            //                                 let aux = "'"+data[i].detail+'-'+id+"'";
            //                                 if(data[i].tool == 'select'){
            //         // fila+=                  '<input type="'+data[i].type+'" name="amountLoan" id="amountLoan"'; if(data[i].type=='number'){fila+='style="text-align: right" value="0" min="1" step=".01"';} fila+='class="form-control" '+data[i].required+'>';
            //         fila+=                      '<select name="name'+id+'[]" id="'+data[i].detail+'-'+id+'" onchange="mostrarprecio('+aux+','+id+');" class="form-control select2" '+data[i].required+'>';
            //                                         if(data[i].concatenar == 'modelo_list')
            //                                         {
            //         fila+=                              modelo_list;
            //                                         }
            //                                         if(data[i].concatenar == 'marca_list')
            //                                         {
            //         fila+=                              marca_list;
            //                                         }
            //                                         if(data[i].concatenar == 'joya_list')
            //                                         {
            //                                             auxJoya = 'joya_list';
            //         fila+=                              joya_list;
            //                                         }
            //         fila+=                      '</select>'+        
            //                                     '<input type="hidden" name="value'+id+'[]" value="'+data[i].concatenar+'">';                                    
            //                                 }
            //         fila+=              '</div>';                    

            //                             if(data[i].concatenar == 'joya_list')
            //                             {
            //                                 let auxprice = "'quilate-"+id+"'";
            //         fila+=                  '<div class="form-group col-md-4">'+
            //                                     '<small>Tipo</small>'+
            //                                     '<select name="name'+id+'[]" id="quilate-'+id+'" onchange="mostrarprice('+auxprice+','+id+');"  class="form-control select2" '+data[i].required+'>';

            //         fila+=                      '</select> <input type="hidden" name="title'+id+'[]" value="Tipo">'+
            //                                     '<input type="hidden" name="value'+id+'[]" value="quilate">'+
            //                                     '<input type="hidden" name="developer'+id+'[]" value="">'+
            //                                 '</div>';
            //                             }

            //                         }     
            //         fila+=      '</div>'+
            //                     '<div class="row" id="div-foot-'+id+'">'+
            //                         '<h4 id="titleHead" style="text-align: center"><i class="fa-solid fa-money-bill"></i> Monto por este Artículo / Prenda</h4>'+
            //                         '<div class="form-group col-md-2">'+
            //                             '<small>Monto a prestar (Bs.)</small>';
            //                             if(auxJoya == 'joya_list')
            //                             {
            //         fila+=                  '<input type="hidden" name="amountLoan'+id+'" id="amountLoan-'+id+'" onchange="subTotal('+id+')" onkeyup="subTotal('+id+')" style="text-align: right" value="1" min="1" step=".01" class=" text" required>'+  
            //                                 '<input type="number" disabled id="amount-'+id+'" style="text-align: right" value="1" min="1" step=".01" class="form-control text" required>';
            //                             }
            //                             else
            //                             {
            //         fila+=                  '<input type="number" name="amountLoan'+id+'" id="amountLoan-'+id+'" onchange="subTotal('+id+')" onkeyup="subTotal('+id+')" style="text-align: right" value="0" min="1" step=".01" class="form-control text" required>';
            //                             }   
            //         fila+=          '</div>'+
            //                         '<div class="form-group col-md-2" '; if(auxJoya != 'joya_list'){fila+='style="visibility:hidden"';   } fila+='  >'+
            //                             '<small>Cantidad de Gramo</small>'+
            //                             '<input type="number" id="price-'+id+'" class="form-control text" onchange="subTotal('+id+')" onkeyup="subTotal('+id+')" name="price'+id+'" value="1" required>'+
            //                         '</div>'+
            //                         '<div class="form-group col-md-6">'+
                                        
            //                         '</div>'+
            //                         '<div class="form-group col-md-2">'+
            //                             '<small>Monto (Bs.)</small>'+
            //                             '<input type="number" id="subAmount-'+id+'" disabled style="text-align: right" value="0" min="1" step=".01" class="form-control text">'+
            //                             '<input type="hidden" id="subAmountLoan-'+id+'" name="subAmountLoan'+id+'" value="0" class="form-control text-subtotal" required>'+

                                            
            //                         '</div>'+
            //                     '</div>';
            //             $('#garment_detail').append(fila);
            //             toastr.success('Prenda agregada exitosamente..', 'Información');

            //         });     
            //     }
            //     else
            //     {
            //         toastr.error('Seleccione un artículo..', 'Información');
            //     }
            //     $('#garment_detail').append(fila);
            // }

            // function mostrarprecio(cadena,id) {
            //     let joya_id = $('#'+cadena+' option:selected').val();
            //     let quilates_list = '';
            //     $('#quilate-'+id).empty();
            //     $.get('{{route('garments-quilate.ajax')}}/'+joya_id, function (data) {
            //         quilates_list = '<option value="" disabled selected>--Seleccione una opción--</option>';

            //         for(i=0; i<data.length; i++)
            //         {
            //             quilates_list+= '<option value="'+data[i].id+'" data-price="'+data[i].price+'">Tipo de Kilate:'+data[i].name+',    Precio Por Gramo: '+data[i].price+'</option>';
            //         }
            //         $('#quilate-'+id).append(quilates_list);
            //     }); 
            //     $('#price-'+id).val(1);
            // }

            // function mostrarprice(cadena,id) {
            //     let priceG = $('#'+cadena+' option:selected').data('price');
            //     $('#amountLoan-'+id).val(priceG);
            //     $('#amount-'+id).val(priceG);
            //     subTotal(id);
            // }
            




            // function subTotal(id)
            // {
            //     let amountLoan = $(`#amountLoan-${id}`).val() ? parseFloat($(`#amountLoan-${id}`).val()) : 0;
            //     let price = $(`#price-${id}`).val() ? parseFloat($(`#price-${id}`).val()) : 0;

            //     let total = amountLoan*price;
            //     $(`#subAmount-${id}`).val(total.toFixed(2));
            //     $(`#subAmountLoan-${id}`).val(total.toFixed(2));

            //     getTotal();                
            // }





        </script>


        <script>
            let modelos = @json($modelo);
            let marcas = @json($marca);
            let articles = @json($article);

            let modelo_list = '<option value="" disabled selected>--Seleccione una opción--</option>';
            let marca_list = '<option value="" disabled selected>--Seleccione una opción--</option>';

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

                $('#select_category').select2({
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
                        url: "{{ url('admin/garments/category/ajax') }}",        
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
                    templateResult: formatResultCustomers_category,
                    templateSelection: (opt) => {
                        categorySelected = opt;
                        
                        return opt.id?'Categoría: '+opt.name:'<i class="fa fa-search"></i> Buscar... ';
                        // return '<i class="fa fa-search"></i> Buscar... ';
                    }
                }).change(function(){
                    if($('#select_category option:selected').val()){
                        let product = categorySelected;
                        $('#select_category').empty();     


                        let article_list = '<option value="" disabled selected>--Seleccione una opción--</option>';       

                        articles.map(
                            item => {
                                if(item.categoryGarment_id==product.id){
                                    article_list += `<option value="${item.id}">${item.name}</option>`;
                                }
                            }
                        );

                        if(product.id == 5 && $(`#div-${product.id}`).val() === undefined)
                        {
                            let typeMetals = @json($typeMetal);
                            let typeMetal_list = '<option value="" disabled selected>--Seleccione una opción--</option>';

                            typeMetals.map(
                            item => {
                                typeMetal_list += `<option value="${item.id}" data-quilate="${item.quilate}">${item.name}</option>`;
                                }
                            );

                            $('#garment_detail').append(`
                                    <div class="row" id="div-${product.id}">
                                        <input type="hidden" name="category[]" value="${product.id}" class="form-control" required>
                                        <h3 id="titleHead" class="title-'+id+'" style="text-align: center">CATEGORIA: ${product.name}</h3>
                                        <div class="form-group col-md-12">
                                            <div class="table-responsive">
                                                <table id="dataStyle" class="tables tablesMetales table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">
                                                                <button  id="btn-${product.id}" onclick="removeDiv(${product.id})" title="Borrar" class="btn btn-sm btn-danger delete"><i class="voyager-trash"></i></button>
                                                            </th>
                                                            <th style="text-align: center; width: 100px">Detalle</th>    
                                                            <th style="text-align: center; width: 150px">Tipo de Material</th>  
                                                            <th style="text-align: center; width: 80px">Kilate</th>    
                                                            <th style="text-align: center; width: 80px">Peso Bruto</th>    
                                                            <th style="text-align: center; width: 80px">Peso Piedra</th>    
                                                            <th style="text-align: center; width: 80px">Peso Neto</th>    
                                                            <th style="text-align: center; width: 80px">Cantidad a dar</th>    
            
                                                            <th style="text-align: center; width: 80px">Sub Total</th>
                                                            <th style="text-align: center; width: 10%">Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table-bodyMetales">

                                                        <tr class="tr-item" id="tr-item-metales-${product.id}-1">
                                                            <td style="text-align: center" class="td-item-${product.id}"></td>
                                                            <td>
                                                                <select name="article${product.id}[]" id="article-${product.id}-1" class="form-control" required>
                                                                     ${article_list}
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="typeMetal${product.id}[]" id="typeMetal-${product.id}-1" onchange="showKilate(${product.id},1);" class="form-control" required>
                                                                    ${typeMetal_list}
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="kilate${product.id}[]" id="kilate-${product.id}-1" onchange="calculoNetoMetal(${product.id},1);" class="form-control" required>
                                                                
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="pesobruto${product.id}[]" min="1" step="0.01" value="0.0" id="pesobruto-${product.id}-1" onkeyup="calculoNetoMetal(${product.id},1)" onchange="calculoNetoMetal(${product.id},1)" style="text-align: right" class="form-control" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="pesopiedra${product.id}[]" min="0" step="0.01" value="0.0" id="pesopiedra-${product.id}-1" onkeyup="calculoNetoMetal(${product.id},1)" onchange="calculoNetoMetal(${product.id},1)" style="text-align: right" class="form-control" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" disabled value="0" id="pesonetod-${product.id}-1" style="text-align: right" class="form-control">
                                                                <input type="hidden" name="pesoneto${product.id}[]" min="0" step="0.01" value="0" id="pesoneto-${product.id}-1" style="text-align: right" class="form-control" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <b id="possibility-${product.id}-1">0.00  -  0.00</b>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="subtotal${product.id}[]" min="0" step="0.1" value="0" id="subtotal-${product.id}-1" onkeyup="getTotal()" onchange="getTotal()" style="text-align: right" class="form-control text-subtotal" required>
                                                            </td>
                                                            <td class="text-right">
                                                                <button type="button" onclick="addTrMetales(${product.id})" class="btn btn-link"><i class="fa-solid fa-cart-plus text-success"> Agregar</i></button>
                                                            </td>
                                                        </tr>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>                                
                            `);

                            $('#article-'+product.id+'-1').select2();
                            $('#typeMetal-'+product.id+'-1').select2();
                            $('#kilate-'+product.id+'-1').select2();
                            setNumber(product.id);
                            getTotal()
                            toastr.success('Detalle agregado', 'Exito')

                        }else{
                            toastr.info('EL detalle ya está agregado', 'Información')
                        }
                    }
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

            function formatResultCustomers_category(option){
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
                                    <small>Categoría: </small><b style="font-size: 15px; color: black">${option.name}</b><br>
                                    <small>Detalle: </small><b style="font-size: 15px; color: black">${option.description}</b>
                                </div>
                            </div>`);
            }

            //__________________________________________________________________::::::::   KILATE   :::::::::_____________________________________________________
            function showKilate(id, subid)
            {
                let typeMetal = $('#typeMetal-'+id+'-'+subid+' option:selected').val();
                let quilates_list = '';
                $('#kilate-'+id+'-'+subid).empty();
                calculoNetoMetal(id, subid);

                
                $.get('{{route('garments-quilate.ajax')}}/'+typeMetal, function (data) {
                    quilates_list = '<option value="" disabled selected>--Seleccione una opción--</option>';

                    for(i=0; i<data.length; i++)
                    {
                        quilates_list+= '<option value="'+data[i].id+'" data-price="'+data[i].price+'">Tipo de Kilate:'+data[i].name+'</option>';
                    }
                    $('#kilate-'+id+'-'+subid).append(quilates_list);
                }); 
            }

            function addTrMetales(id)
            {
                // alert(id)
                let subid = getNumber(`td-item-${id}`)+1;

                let article_list = '<option value="" disabled selected>--Seleccione una opción--</option>';
                articles.map(
                    item => {
                        if(item.categoryGarment_id==id){
                            article_list += `<option value="${item.id}">${item.name}</option>`;
                        }
                    }
                );

                let typeMetals = @json($typeMetal);
                let typeMetal_list = '<option value="" disabled selected>--Seleccione una opción--</option>';

                typeMetals.map(
                    item => {
                        typeMetal_list += `<option value="${item.id}" data-quilate="${item.quilate}">${item.name}</option>`;
                    }
                );
                // alert(subid);
                $('#table-bodyMetales').append(`
                    <tr class="tr-item" id="tr-item-metales-${id}-${subid}">
                        <td style="text-align: center" class="td-item-${id}"></td>
                        <td>
                            <select name="article${id}[]" id="article-${id}-${subid}" class="form-control" required>
                                ${article_list}
                            </select>
                        </td>
                        <td>
                            <select name="typeMetal${id}[]" id="typeMetal-${id}-${subid}" onchange="showKilate(${id},${subid});" class="form-control" required>
                                ${typeMetal_list}
                            </select>
                        </td>
                        <td>
                            <select name="kilate${id}[]" id="kilate-${id}-${subid}" onchange="calculoNetoMetal(${id},${subid});" class="form-control" required>
                                                                
                            </select>
                        </td>
                        <td>
                            <input type="number" name="pesobruto${id}[]" min="1" step="0.01" value="0.0" id="pesobruto-${id}-${subid}" onkeyup="calculoNetoMetal(${id},${subid})" onchange="calculoNetoMetal(${id},${subid})" style="text-align: right" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="pesopiedra${id}[]" min="0" step="0.01" value="0.0" id="pesopiedra-${id}-${subid}" onkeyup="calculoNetoMetal(${id},${subid})" onchange="calculoNetoMetal(${id},${subid})" style="text-align: right" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" disabled value="0" id="pesonetod-${id}-${subid}" style="text-align: right" class="form-control">
                            <input type="hidden" name="pesoneto${id}[]" min="0" step="0.01" value="0" id="pesoneto-${id}-${subid}" style="text-align: right" class="form-control" required>
                        </td>
                        <td class="text-center">
                            <b id="possibility-${id}-${subid}">0.00  -  0.00</b>
                        </td>
                        <td>
                            <input type="number" name="subtotal${id}[]" min="0" step="0.1" value="0" id="subtotal-${id}-${subid}" onkeyup="getTotal()" onchange="getTotal()" style="text-align: right" class="form-control text-subtotal" required>
                        </td>
                        <td class="text-right">
                            <button type="button" onclick="removeTr('tr-item-metales-${id}-${subid}')" class="btn btn-link"><i class="fa-solid fa-trash text-danger"> Quitar</i></button>
                        </td>
                    </tr>
                `);
                // toastr.success('Detalle agregado', 'Exito')

                $('#article-'+id+'-'+subid).select2();
                $('#typeMetal-'+id+'-'+subid).select2();
                $('#kilate-'+id+'-'+subid).select2();
                setNumber(id);
                getTotal()
                toastr.success('Detalle agregado', 'Exito')

            }
            

            function calculoNetoMetal(id, subid)
            {
                let bruto = $(`#pesobruto-${id}-${subid}`).val() ? parseFloat($(`#pesobruto-${id}-${subid}`).val()) : 0;
                let piedra = $(`#pesopiedra-${id}-${subid}`).val() ? parseFloat($(`#pesopiedra-${id}-${subid}`).val()) : 0;

                let priceG = $('#kilate-'+id+'-'+subid+' option:selected').data('price')?parseFloat($('#kilate-'+id+'-'+subid+' option:selected').data('price')) : 0;

                // alert(priceG);

                let neto = bruto>=piedra?(bruto-piedra):(piedra-bruto);
                
                let totalMonto = priceG*neto;
                
                let porcentageAux = totalMonto*0.3;

                let min=totalMonto-porcentageAux
                let max=totalMonto;

                $(`#pesopiedra-${id}-${subid}`).attr("max", bruto);

                
                // $(`#subtotal-${id}-${subid}`).attr("min", min);
                $(`#subtotal-${id}-${subid}`).attr("max", max);



                $(`#pesonetod-${id}-${subid}`).val(neto.toFixed(2));
                $(`#pesoneto-${id}-${subid}`).val(neto.toFixed(2));
                $(`#subtotal-${id}-${subid}`).val(totalMonto.toFixed(2));
                $(`#possibility-${id}-${subid}`).text(min.toFixed(2)+'  -  '+max.toFixed(2));
                getTotal()
            }

            function setNumber(id){
                var length = 0;
                $(".td-item-"+id).each(function(index) {
                    $(this).text(index +1);
                    length++;
                });
            }







            function removeTr(id){
                $(`#${id}`).remove();
                getTotal();
                toastr.error('Detalle Eliminado..', 'Eliminado');

            }
            function removeDiv(id){
                $(`#div-${id}`).remove();
                getTotal();
                toastr.error('Detalle Eliminado..', 'Eliminado');

            }

            function getNumber(id){
                var length = 0;
                $("."+id).each(function(index) {
                    length++;
                });
                return length;
            }

            function getTotal(){
                let total = 0;
                $(".text-subtotal").each(function(index) {
                    total += parseFloat($(this).val());
                });
                // alert(total)



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




                // var articles_country = [];

                // $('#category_id').change(function(){
                //     let articles = $('#category_id option:selected').data('articles');
                //     let articles_list = '<option value="" disabled selected>--Selecciona un articulo--</option>';
                //     // alert(articles[name])
                //     if(articles.length){
                //         // articles_country = articles; 
                //         articles.map(articles => {
                //             articles_list += `<option value="${articles.id}">${articles.name}</option>`;
                //         });
                //     }
                //     // else{
                //     //     articles_list += `<option value="">Ninguna</option>`;
                //     // }
                //     $('#article_id').html(articles_list);
                // });
        </script>
    @stop

@endif