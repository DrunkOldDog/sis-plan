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
                                <h3 class="mb-0">Edicion de Reservas:</h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                            <form action="{{action('ReservaController@update', $id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                    <br>
                                    <h5>Datos Cliente</h5>
                                <hr>
                                    <div class="form-row">
                                        @foreach($clientes as $cliente)
                                        @if($cliente->id_clientes == $reserva->id_clientes)
                                            <div class="form-group col-md-4">
                                              <label for="Nombre">Nombre:</label>
                                              <input type="text" class="form-control" value="{{$cliente->nombre}}" name="nombre" readonly>
                                            </div>
                                            <div class="form-group col-md-4">
                                              <label for="Apellido">Apellido:</label>
                                              <input type="text" class="form-control" value="{{$cliente->apellido}}" name="apellido" readonly>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="CI">CI:</label>
                                                <input type="text" class="form-control" value="{{$cliente->ci}}" name="ci" readonly>
                                              </div>
                                            @endif
                                        @endforeach
                                    </div>
                                <hr>
                                <h5>Reserva</h5>
                                <hr>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                      <label for="fec_evento">Fecha Evento:</label>
                                      <input type="date" value="{{$reserva->fec_evento}}" class="form-control" name="fec_evento">
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label for="hor_ini_evento">Hora Inicio Evento:</label>
                                      <input type="time" class="form-control" value="{{$reserva->hor_ini_evento}}" name="hor_ini_evento" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="hor_fin_evento">Hora Fin Evento:</label>
                                        <input type="time" class="form-control" value="{{$reserva->hor_fin_evento}}" name="hor_fin_evento" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="hor_fin_evento">Evento:</label>
                                        <select name="id_eventos" id="id_eventos" class="form-control">
                                                @foreach($eventos as $evento)
                                                @if($evento->id_eventos == $reserva->id_eventos)
                                                <option value="{{$evento->id_eventos}}" selected>{{ $evento->nombre }}&nbsp;({{ $evento->precio_total }}Bs.)</option>
                                                @else
                                                <option value ="{{$evento->id_eventos}}">{{ $evento->nombre }}&nbsp;({{ $evento->precio_total }}Bs.)</option>@endif
                                                @endforeach
                                             </select>                                 
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
</div>
@endsection
