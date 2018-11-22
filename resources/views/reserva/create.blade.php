@extends('layout.principal')
@section('content')
<div id="content">
<div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Creacion de Reservas:</h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                            <form method="post" action="{{url('reservas')}}" enctype="multipart/form-data">
                                @csrf
                                <h5>Datos Cliente</h5>
                                <hr>
                                    <div class="form-row">
                                            <div class="form-group col-md-4">
                                              <label for="Nombre">Nombre:</label>
                                              <input type="text" class="form-control" name="nombre">
                                            </div>
                                            <div class="form-group col-md-4">
                                              <label for="Apellido">Apellido:</label>
                                              <input type="text" class="form-control" name="apellido">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="CI">CI:</label>
                                                <input type="text" class="form-control" name="ci">
                                              </div>
                                    </div>
                                <hr>
                                <h5>Reserva</h5>
                                <hr>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                      <label for="fec_evento">Fecha Evento:</label>
                                      <input type="date" value="{{date("Y-m-d")}}" class="form-control" name="fec_evento">
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label for="hor_ini_evento">Hora Inicio Evento:</label>
                                      <input type="time" class="form-control" name="hor_ini_evento" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="hor_fin_evento">Hora Fin Evento:</label>
                                        <input type="time" class="form-control" name="hor_fin_evento" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="hor_fin_evento">Evento:</label>
                                        <select name="id_eventos" id="id_eventos" class="form-control">
                                            @foreach($eventos as $evento)
                                            <option value ="{{$evento->id_eventos}}">{{ $evento->nombre }}&nbsp;({{ $evento->precio_total }}Bs.)</option>
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
@endsection
