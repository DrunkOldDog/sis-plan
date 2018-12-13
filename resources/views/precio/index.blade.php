@extends('layout.principal')
@section('content')
<div id="content">
<div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
              <!-- ver los errores del programa-->
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Error!</strong> Revise los campos obligatorios.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(Session::has('success'))
                <div class="alert alert-info">
                    {{Session::get('success')}}
                </div>
                @endif

                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Edicion de Precios:</h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                            <form method="post" action="{{url('precios')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                  <div class="form-group col-md-12">
                                    <h6>Ambientes</h6><hr>
                                  </div>
                                @foreach($ambientes as $ambiente)
                                  <label for="habitacion" id="comple" class="col-sm-1 col-form-label">{{$ambiente->nombre}}:</label>
                                  <div class="form-group col-md-2">
                                      <input type="number" class="form-control" name="ambiId[]" min="1" value="{{$ambiente->id_ambientes}}" hidden>
                                      <input type="number" class="form-control" name="ambi[]" min="1" value="{{$ambiente->precio}}">
                                  </div>
                                @endforeach
            
                                  <div class="form-group col-md-12"></div>

                                  <div class="form-group col-md-12">
                                      <h6>Habitaciones</h6><hr>
                                  </div>
                                  @foreach($habitaciones as $habitacion)
                                    <label for="habitacion" id="comple" class="col-sm-1 col-form-label">{{$habitacion->nombre}}:</label>
                                    <div class="form-group col-md-2">
                                        <input type="number" class="form-control" name="habiId[]" min="1" value="{{$habitacion->id_habitaciones}}" hidden>
                                        <input type="number" class="form-control" name="habi[]" min="1" value="{{$habitacion->precio}}">
                                    </div>
                                  @endforeach
                                  
                                  <div class="form-group col-md-12"></div>

                                  <div class="form-group col-md-12">
                                    <h6>Servicios</h6><hr>
                                </div>
                                @foreach($servicios as $servicio)
                                  <label for="habitacion" id="comple" class="col-sm-1 col-form-label">{{$servicio->nombre}}:</label>
                                  <div class="form-group col-md-2">
                                      <input type="number" class="form-control" name="servId[]" min="1" value="{{$servicio->id_servicios}}" hidden>
                                      <input type="number" class="form-control" name="serv[]" min="1" value="{{$servicio->precio}}">
                                  </div>
                                @endforeach
                                
                                <div class="form-group col-md-12"></div>

                                <div class="form-group col-md-12">
                                    <h6>Producto</h6><hr>
                                </div>
                                @foreach($productos as $producto)
                                  <label for="habitacion" id="comple" class="col-sm-1 col-form-label">{{$producto->nombre}}:</label>
                                  <div class="form-group col-md-2">
                                      <input type="number" class="form-control" name="prodId[]" min="1" value="{{$producto->id_productos}}" hidden>
                                      <input type="number" class="form-control" name="prod[]" min="1" value="{{$producto->precio}}">
                                  </div>
                                @endforeach
                                
                                <div class="form-group col-md-12"></div>

                                </div>

                                <div class="row">
                                  <div class="col-md-4"></div>
                                  <div class="form-group col-md-4" style="margin-top:60px">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                  </div>
                                </div>
                              </form>

                    </div> <!-- end card body-->
            </div>
        </div>
    </div>
</div>
@endsection
