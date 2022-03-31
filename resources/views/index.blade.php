@extends('layout')

@section('content')


        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">
                Generos Musicales
            </div>
            <div class="card-body">
                <div class="col-md-12 mt-1 mb-2">
                    <button type="button" id="registrarGenero" class="btn btn-success btn-sm">Registrar</button>
                </div>
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Genero Musical</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($generos as $genero)
                            <tr>
                                <td>{{ $genero->id }}</td>
                                <td>{{ $genero->genero }}</td>
                                <td>
                                   <a href="javascript:void(0)" class="btn btn-warning edit" data-id="{{ $genero->id }}">Editar</a>
                                   <a href="javascript:void(0)" class="btn btn-danger delete" data-id="{{ $genero->id }}">Eliminar</a>
                                 </td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                  {!! $generos->links() !!}
            </div>
        </div>



        <!-- boostrap model -->
    <div class="modal fade" id="modal-generos" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tituloGeneroModal"></h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="javascript:void(0)" id="addEditGeneroForm" name="addEditGeneroForm" class="form-horizontal" method="POST">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" id="id">
                <div class="row">
                  <label for="name" class="col-sm-2 control-label">Genero </label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="genero" name="genero"  value=""  required="">
                  </div>
                </div>
              </form>
                <div class="col-sm-12 mt-3">
                  <button class="btn btn-secondary btn-sm col-12" id="btn-guardar" value="nuevoGenero">Guardar</button>
                </div>
            </div>
            <div class="modal-footer">

            </div>
          </div>
        </div>
      </div>
  <!-- end bootstrap model -->


@endsection

@section('js')
    <script type="text/javascript">
    $(document).ready(function(){

        $("#registrarGenero").on('click',function(){

            $("#addEditGeneroForm").trigger("reset");
            $("#tituloGeneroModal").html("Registrar Genero Musical");
            $("#modal-generos").modal('show');
        });


        /*REGISTRAR UN GENERO NUEVO*/
        $("#btn-guardar").on('click',function(e){
            e.preventDefault();

            //AGREGAR CABECERAS CON TOKEN CSRF EN LLAMADA AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('generos.store') }}",
                type: "POST",
                data: $("#addEditGeneroForm").serialize(),
                dataType: 'json',
                success: function (data) {

                    Swal.fire({
                            position: 'top-center',
                            icon: data.estatus,
                            title: data.mensaje,
                            showConfirmButton: false
                    })

                    $("#addEditGeneroForm").trigger("reset");
                    $("#modal-generos").modal('hide');
                    setTimeout(function(){
                       $(location).attr('href','{{ route('generos.index')}}');
                    }, 2000);
                }
            });
        });




    });
    </script>


@endsection
