@extends('layout.principal')
@section('content')
<div id="content">
<div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Creacion de Ambientes:</h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                            <form method="post" action="{{url('ambientes')}}" enctype="multipart/form-data">
                                @csrf
                                    <br>
                                    <div class="row">
                                            <div class="form-group col-md-6">
                                              <label for="Nombre">Nombre:</label>
                                              <input type="text" class="form-control" name="nombre">
                                            </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                          <label for="Capacidad">Capacidad:</label>
                                          <input type="number" class="form-control" name="capacidad">
                                        </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                      <label for="Precio">Precio:</label>
                                      <input type="text" class="form-control" name="precio">
                                    </div>
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
