@extends('layout')

@section('content')
<div class="container mt-3 d-flex justify-content-center">
    <div class="card mt-3">
        <div class="card-header bg-secondary text-white">
            Generos Musicales
        </div>
        <div class="card-body">
            <div class="mt-1 mb-2">
                <button type="button" id="registrarGenero" class="btn btn-success btn-sm">Registrar</button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Genero</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($generos as $genero)
                        <tr>
                            <td>{{ $genero->id }}</td>
                            <td>{{ $genero->genero }}</td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-warning btn-sm edit"
                                    data-id="{{ $genero->id }}">Editar</a>

                                <a href="javascript:void(0)" class="btn btn-danger btn-sm delete"
                                    data-id="{{ $genero->id }}">Eliminar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $generos->links() !!}
        </div>
    </div>



    <!-- boostrap model -->
    <div class="modal fade" id="modal-generos" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloGeneroModal"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="addEditGeneroForm" name="addEditGeneroForm"
                        class="form-horizontal" method="POST">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <label for="name" class="col-sm-2 control-label">Genero </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="genero" name="genero" value="" required="">
                            </div>
                        </div>
                    </form>
                    <div class="col-sm-12 mt-3">
                        <button class="btn btn-secondary btn-sm col-12" id="btn-guardar"
                            value="nuevoGenero">Guardar</button>
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
            $("#registrarGenero").on('click', function() {

                $("#addEditGeneroForm").trigger("reset");
                $("#tituloGeneroModal").html("Registrar Genero Musical");
                $("#modal-generos").modal('show');
            });


            /*REGISTRAR UN GENERO NUEVO*/
            $("#btn-guardar").on('click', function(e) {
                e.preventDefault();

                //SE DEFINEN LA VARIABLES DE LA RUTA STORE Y LA ACCION A ENVIAR POR AJAX
                let url = "generos";
                let accion="GUARDAR";

                //SE VERIFICA SI EL VALOR DEL BOTON GUARDAR ES "EDITAR"
                if ($(this).val() === "editaGenero") {

                    let id = $("#id").val();
                    url = "generos/"+id;//SE REASIGNA EL VALOR DE LA VARIABLE RUTA PARA EDITAR
                    accion="EDITAR";//SE REASIGNA EL VALOR DE LA VARIABLE ACCION PARA DEFINIR METODO AJAX
                }
                GuardaEdita(url, accion);//FUNCION RECIBE LA URL Y LA ACCION PARA ENVIARDATOS POR AJAX
            });

            $(".edit").on('click', function() {

                let id = $(this).data('id'); //OBTIENE EL ID DEL REGISTRO
                let url = "generos/" + id + "/edit"; //OBTIENE LA RUTA PARA ACCEDER AL CONTROLADOR

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {

                        $("#addEditGeneroForm").trigger("reset"); //LIMPIA EL FORMULARIO MODAL
                        $("#tituloGeneroModal").html("Editar Genero Musical"); //ESCRIBE TITULO EN EL MODAL

                        $("#id").val(data.id); //ASIGNA VALORES EN EL FORMULARIO
                        $("#genero").val(data.genero); //ASIGNA VALORES EN EL FORMULARIO

                        $("#btn-guardar").val('editaGenero'); //CAMBIA EL VALOR DEL BOTON EN EL FORMULARIO MODAL
                        $("#btn-guardar").html('Editar'); //CAMBIA EL TEXTO DEL BOTON EN EL FORMULARIO MODAL

                        $("#modal-generos").modal('show'); //MUESTRA EL MODAL
                    }
                });
            });

            $(".delete").on('click', function() {

                let id = $(this).data('id'); //OBTIENE EL ID DEL REGISTRO
                let url= "generos/"+id; //OBTIENE LA RUTA PARA ACCEDER AL CONTROLADOR


                Swal.fire({
                    title: 'Deseas eliminar el genero?',
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
                                    $(location).attr('href','/generos');
                                }, 2000);

                            }
                        });
                    }
                })
            });

            function GuardaEdita(uri, accion) {

                //AGREGAR CABECERAS CON TOKEN CSRF EN LLAMADA AJAX
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let metodo=(accion=="EDITAR")? 'PUT' : 'POST';
                //RECIBER POR PARAMETRO EL METODO A UTILIZAR(POST/PUT) Y LA URL QUE INDICARA A QUE CONTROLADOR Y METODO DIRIGIRSE
                $.ajax({
                    url: uri,
                    type: metodo,
                    data: $("#addEditGeneroForm").serialize(),
                    dataType: 'json',
                    success: function(data) {
                        //ALERTAS DE SWEETALER2
                        Swal.fire({
                            position: 'top-center',
                            icon: data.estatus,
                            title: data.mensaje,
                            showConfirmButton: false
                        })

                        $("#addEditGeneroForm").trigger("reset"); //LIMPIA EL FORMULARIO MODAL
                        $("#modal-generos").modal('hide'); //OCULTA EL MODAL
                        //REDIRECCIONA A LA RUTA INDEX EN 2 SEGS.
                        setTimeout(function() {
                            $(location).attr('href', '/generos');
                        }, 2000);
                    }
                });
            }
        });
    </script>
@endsection
