@extends('layout.principal')
@section('content')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Registro de Parqueos:</h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                            <form method="post" action="{{action('EventoController@update', $id)}}">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                    <br>
                                    <div class="form-row">
                                            <div class="form-group col-md-6">
                                              <label for="Nombre">Nombre:</label>
                                              <input type="text" class="form-control" name="nombre" value="{{$evento->nombre}}">
                                            </div>
                                            <div class="form-group col-md-6">
                                              <label for="Precio">Precio:</label>
                                              <input type="text" class="form-control" name="precio" value="{{$evento->precio}}">
                                            </div>
                                          </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                      <label for="Descripcion">Descripcion:</label>
                                      <textarea class="form-control" rows="3" name="descripcion">{{$evento->descripcion}}</textarea>
                                    </div>
                                  </div>

                                        <div class="row">
                                                <div class="form-group col-md-4">
                                                  <label for="Imagen">Imagen:</label>
                                                  <input type="file" accept="image/*" name="filename">
                                               </div>
                                        </div>
                                        
                                <div class="row">
                                  <div class="col-md-4"></div>
                                  <div class="form-group col-md-4" style="margin-top:60px">
                                    <button type="submit" class="btn btn-success">Update</button>
                                  </div>
                                </div>
                              </form>

                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
