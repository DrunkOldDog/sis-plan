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
                                <h3 class="mb-0">Edicion de Ambientes:</h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                            <form action="{{action('AmbienteController@update', $id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="Nombre">Nombre:</label>
                                            <input type="text" class="form-control" name="nombre" value="{{$ambiente->nombre}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="Capacidad">Capacidad:</label>
                                            <input type="number" class="form-control" name="capacidad" value="{{$ambiente->capacidad}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="Precio">Precio:</label>
                                            <input type="text" class="form-control" name="precio" value="{{$ambiente->precio}}">
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
</div>
@endsection
