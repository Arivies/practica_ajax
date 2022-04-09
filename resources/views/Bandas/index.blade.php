@extends('layout')

@section('content')
<div class="container mt-3 d-flex justify-content-center">
    <div class="card mt-3 col-8">
        <div class="card-header bg-secondary text-white">
            Bandas
        </div>
        <div class="card-body">
            <div class="mt-1 mb-2">
                <button type="button" id="registrarBanda" class="btn btn-success btn-sm">Registrar</button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Genero</th>
                        <th scope="col">Logo</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach ($bandas as $banda)
                        <tr>
                            <td>{{ $banda->id }}</td>
                            <td>{{ $banda->nombre }}</td>
                            <td>{{ $banda->genero->genero }}</td>
                            <td>
                                <img src="storage/bandas/{{$banda->logo }}" class="d-flex align-self-start rounded mr-3" height="70" width="80">
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-warning btn-sm edit"
                                    data-id="{{ $banda->id }}">Editar</a>

                                <a href="javascript:void(0)" class="btn btn-danger btn-sm delete"
                                    data-id="{{ $banda->id }}">Eliminar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $bandas->links() !!}
        </div>
    </div>



    <!-- boostrap model -->
    <div class="modal fade" id="modal-bandas" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloBandaModal"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="addEditBandaForm" name="addEditBandaForm"
                        class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <label for="name" class="col-sm-2 control-label">Nombre </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control form-control-sm" id="nombre" name="nombre" value="" required="">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="genero" class="col-sm-2 control-label">Genero </label>
                            <div class="col-sm-10">
                                <select class="form-control form-control-sm" name="genero" id="genero" required=""></select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="logo" class="col-sm-2 control-label">Logo </label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control form-control-sm" id="logo" name="logo" required="" accept="image/*">
                                <input type="hidden" class="form-control form-control-sm" id="logo_ant" name="logo_ant" >
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12" id="muestra_img"> </div>
                        </div>
                    </form>
                    <div class="col-sm-12 mt-3">
                        <button class="btn btn-secondary btn-sm col-12" id="btn-guardar"
                            value="nuevaBanda">Guardar</button>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
</div>
    <!-- end bootstrap model -->
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {

            //BOTON PARA ABRIR FORMULARIO MODAL
            $("#registrarBanda").on('click', function() {

                $("#addEditBandaForm").trigger("reset");
                $("#tituloBandaModal").html("Registrar Nueva Banda");
                $("#muestra_img").html("");
                let url = "{{ route('bandas.create') }}";
                $.ajax({
                    url: url,
                    type: "GET",
                    data: { },
                    dataType: 'json',
                    success: function(data) {

                        let datos=data.generos;
                        let genero=$("#genero");

                        genero.find('option').remove();
                        genero.append('<option value="0">Seleccione</option>');

                        $(datos).each(function(i,v){
                            genero.append('<option value="'+v.id+'">'+v.genero+'</option>');
                        })

                        $('input[type="file"][name="logo"]').on('change', function(){

                            $("#muestra_img").find('.img-fluid').remove();
                            let muestra_img='';
                            var reader = new FileReader();
                            let path= $(this)[0].value;
                                muestra_img = $("#muestra_img");
                                reader.onload = function(e){
                                $('<img/>',{'src':e.target.result,'class':'img-fluid','style':'max-width:100px;margin-bottom:10px;'}).appendTo(muestra_img);
                                }
                                muestra_img.show();
                                reader.readAsDataURL($(this)[0].files[0]);
                        });



                       // $("#addEditBandaForm").trigger("reset"); //LIMPIA EL FORMULARIO MODAL
                       // $("#tituloBandaModal").html("Editar Banda"); //ESCRIBE TITULO EN EL MODAL

                      //  $("#id").val(data.id); //ASIGNA VALORES EN EL FORMULARIO
                      //  $("#genero").val(data.genero); //ASIGNA VALORES EN EL FORMULARIO

                      //  $("#btn-guardar").val('editaGenero'); //CAMBIA EL VALOR DEL BOTON EN EL FORMULARIO MODAL
                     //   $("#btn-guardar").html('Editar'); //CAMBIA EL TEXTO DEL BOTON EN EL FORMULARIO MODAL

                     //   $("#modal-generos").modal('show'); //MUESTRA EL MODAL
                    }
                });



                $("#modal-bandas").modal('show');
            });


            /*REGISTRAR UN GENERO NUEVO*/
            $("#btn-guardar").on('click', function(e) {
                e.preventDefault();

                //SE DEFINEN LA VARIABLES DE LA RUTA STORE Y EL METODO
                let url = "{{ route('bandas.store') }}";
                let metodo = "POST";

                //SE VERIFICA SI EL VALOR DEL BOTON GUARDAR ES "EDITAR"
                if ($(this).val() === "editaBanda") {

                    let id = $("#id").val();
                    url = "{{ route('bandas.update', '+id+') }}";//SE REASIGNA EL VALOR DE LA VARIABLE RUTA PARA EDITAR
                    metodo = "PUT";//SE REASIGNA EL VALOR DE LA VARIABLE METODO A "PUT"
                }
                GuardaEdita(url, metodo);//FUNCION RECIBE LA URL Y EL METODO PARA ENVIARDATOS POR AJAX
            });

            $(".edit").on('click', function() {

                let id = $(this).data('id'); //OBTIENE EL ID DEL REGISTRO
                let url = "bandas/" + id + "/edit"; //OBTIENE LA RUTA PARA ACCEDER AL CONTROLADOR

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {

                        let generos=data.generos;
                        let bandas=data.bandas;

                        $("#addEditBandaForm").trigger("reset"); //LIMPIA EL FORMULARIO MODAL
                        $("#tituloBandaModal").html("Editar Banda"); //ESCRIBE TITULO EN EL MODAL

                        $("#id").val(bandas.id); //ASIGNA VALORES EN EL FORMULARIO
                        $("#nombre").val(bandas.nombre); //ASIGNA VALORES EN EL FORMULARIO

                        $("#logo_ant").val(bandas.logo); //ASIGNA VALORES EN EL FORMULARIO

                        let genero=$("#genero");
                            genero.find('option').remove();
                            genero.append('<option value="0">Seleccione</option>');
                            $(generos).each(function(i,v){
                                genero.append('<option value="'+v.id+'">'+v.genero+'</option>');
                            })
                            $("#genero option:eq("+bandas.genero_id+")").prop('selected',true);

                            $('input[type="file"][name="logo"]').val('');
                            $('input[type="file"][name="logo"]').on('change', function(){

                                $("#muestra_img").find('.img-fluid').remove();
                                let path=$(this)[0].value;
                                let img_file=$(this)[0].files[0];
                                img_previa(path,img_file);
                            });
                            $("#muestra_img").html('<img src="storage/bandas/'+bandas.logo+'" class="img-fluid rounded" width="100px" height="100px" />')


                        $("#btn-guardar").val('editaBanda'); //CAMBIA EL VALOR DEL BOTON EN EL FORMULARIO MODAL
                        $("#btn-guardar").html('Editar'); //CAMBIA EL TEXTO DEL BOTON EN EL FORMULARIO MODAL
                        $("#modal-bandas").modal('show'); //MUESTRA EL MODAL
                    }
                });
            });

            $(".delete").on('click', function() {

                let id = $(this).data('id'); //OBTIENE EL ID DEL REGISTRO
                let url ="{{ route('bandas.destroy', '+id+') }}"; //OBTIENE LA RUTA PARA ACCEDER AL CONTROLADOR


                Swal.fire({
                    title: 'Deseas eliminar la Banda?',
                    text: "Una vez eliminado ya no se revertira!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Deseo eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        //AGREGAR CABECERAS CON TOKEN CSRF EN LLAMADA AJAX
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    position: 'top-center',
                                    icon: data.estatus,
                                    title: data.mensaje,
                                    showConfirmButton: false
                                })
                                setTimeout(function() {
                                    $(location).attr('href',
                                        '{{ route('generos.index') }}');
                                }, 2000);

                            }
                        });
                    }
                })
            });

            function GuardaEdita(uri, metodo) {

                //AGREGAR CABECERAS CON TOKEN CSRF EN LLAMADA AJAX
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let forma=new FormData();
                forma.append('id',$("#id").val());
                forma.append('nombre',$("#nombre").val());
                forma.append('genero_id',$("#genero").val());
                forma.append('logo',$("#logo")[0].files[0]);
                forma.append('logo_ant',$("#logo_ant").val());

                //RECIBER POR PARAMETRO EL METODO A UTILIZAR(POST/PUT) Y LA URL QUE INDICARA A QUE CONTROLADOR Y METODO DIRIGIRSE
                $.ajax({
                    url: uri,
                    type: metodo,
                    processData: false,
                    contentType: false,
                    data: forma,
                    dataType: 'json',
                    success: function(data) {
                        //ALERTAS DE SWEETALER2
                        Swal.fire({
                            position: 'top-center',
                            icon: data.estatus,
                            title: data.mensaje,
                            showConfirmButton: false
                        })

                        $("#addEditBandaForm").trigger("reset"); //LIMPIA EL FORMULARIO MODAL
                        $("#modal-bandas").modal('hide'); //OCULTA EL MODAL
                        //REDIRECCIONA A LA RUTA INDEX EN 2 SEGS.
                       /* setTimeout(function() {
                            $(location).attr('href', '{{ route('bandas.index') }}');
                        }, 2000);*/
                    }
                });
            }

            function img_previa(img_path,img_file){
                let img_selector = $("#muestra_img");
                let ext = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();

                if(ext == 'jpeg' || ext == 'jpg' || ext == 'png'){
                        if(typeof(FileReader) != 'undefined'){

                            var reader = new FileReader();
                            reader.onload = function(e){
                                $('<img/>',{'src':e.target.result,'class':'img-fluid','style':'max-width:100px;margin-bottom:10px;'}).appendTo(img_selector);
                            }
                            img_selector.show();
                            reader.readAsDataURL(img_file);
                        }
                    }
                    else{
                        $(img_selector).html('Este archivo no contiene el formato de imagen!');
                    }




            }

            /*function img_previa(){
                $('input[type="file"][name="logo"]').val('');
                $('input[type="file"][name="logo"]').on('change', function(){


                    let img_path = $(this)[0].value;
                    alert(img_path);
                    let img_selector = $("#muestra_img");
                    let ext = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();

                    if(ext == 'jpeg' || ext == 'jpg' || ext == 'png'){
                        if(typeof(FileReader) != 'undefined'){

                            var reader = new FileReader();
                            reader.onload = function(e){
                                $('<img/>',{'src':e.target.result,'class':'img-fluid','style':'max-width:100px;margin-bottom:10px;'}).appendTo(img_selector);
                            }
                            img_selector.show();
                            reader.readAsDataURL($(this)[0].files[0]);
                        }
                        alert(img_selector);
                    }
                    else{
                        $(img_selector).html('Este archivo no contiene el formato de imagen!');
                    }

                });
            }*/
        });
    </script>
@endsection
