@extends('layout.principal')
@section('content')
<div id="content">
<div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Creacion de Paquetes:</h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                            <form method="post" action="{{url('eventos')}}" enctype="multipart/form-data">
                                @csrf
                                    <br>
                                    <div class="form-row">
                                            <div class="form-group col-md-6">
                                              <label for="Nombre">Nombre:</label>
                                              <input type="text" class="form-control" name="nombre">
                                            </div>
                                            <div class="form-group col-md-6">
                                              <label for="Precio">Precio:</label>
                                              <input type="text" class="form-control" name="precio">
                                            </div>
                                    </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                      <label for="Descripcion">Descripcion:</label>
                                      <textarea class="form-control" rows="3" name="descripcion"></textarea>
                                    </div>
                                  </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                          <label for="Imagen">Imagen:</label>
                                          <input type="file" accept="image/*" name="filename">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <label for="Ambientes">Ambientes:</label>
                                    <select name="id_ambientes" id="id_ambientes" class="form-control">
                                            @foreach($ambientes as $ambiente)
                                            <option value ="{{$ambiente->id_ambientes}}">{{ $ambiente->nombre }}&nbsp;({{ $ambiente->precio }}Bs.)</option>
                                            @endforeach
                                         </select>
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-12">
                                    <label for="Nombre">Servicios Adicionales:</label>
                                  </div>
                                  @foreach($servicios as $servicio)
                                  <div class="form-group col-md-4">
                                     <label class="checkbox-inline"><input type="checkbox" name="servi[]" value="{{$servicio->id_servicios}}"></label>&nbsp;{{$servicio->nombre}}&nbsp;({{$servicio->precio}}Bs.)
                                  </div>
                                  @endforeach
                                </div>

                                <div class="row">
                                  <div class="col-md-4"></div>
                                  <div class="form-group col-md-4" style="margin-top:60px">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                  </div>
                                </div>
                              </form>

                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
