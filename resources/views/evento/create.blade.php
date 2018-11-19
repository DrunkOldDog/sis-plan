@extends('layout.principal')
@section('content')
<div id="content">
<div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Registro de Eventos:</h3>
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
                                              <label for="Latitud">Latitud:</label>
                                              <input type="text" class="form-control" name="latitud_x" id="lat" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                              <label for="Longitud">Longitud:</label>
                                              <input type="text" class="form-control" name="longitud_y" id="lon" readonly>
                                            </div>
                                          </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                      <label for="Direccion">Direccion:</label>
                                      <input type="text" class="form-control" name="direccion">
                                    </div>
                                  </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="Cantidad">Cantidad Vehiculos:</label>
                                            <input type="text" class="form-control" name="cantidad_p">
                                        </div>
                                    </div>

                                        <div class="row">
                                                <div class="form-group col-md-4">
                                                        <label for="Imagen">Imagen:</label>
                                                  <input type="file" accept="image/*" name="filename">
                                               </div>
                                        </div>

                                    <!-- no mostrar cuando este listo twilio para la verificacion de telefono -->
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="Contacto1">Contacto 1:</label>
                                            <input type="text" class="form-control" name="telefono_contacto_1">
                                        </div>
                                    </div>
                                    <!-- -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                          <label for="HoraApertura">Hora Apertura:</label>
                                          <input type="time" class="form-control" name="hora_apertura" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="HoraCierre">Hora Cierre:</label>
                                          <input type="time" class="form-control" name="hora_cierre" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="Tarifa">Tarifa:</label>
                                            <input type="text" class="form-control" name="tarifa_hora_normal">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="DiaFuncion">Dias Funcionamiento Parqueo:</label>
                                            <label class="checkbox-inline"><input type="checkbox" name="dia[]" value="Lunes">&nbsp;Lun</label>
                                            <label class="checkbox-inline"><input type="checkbox" name="dia[]" value="Martes">&nbsp;Mar</label>
                                            <label class="checkbox-inline"><input type="checkbox" name="dia[]" value="Miercoles">&nbsp;Mie</label>
                                            <label class="checkbox-inline"><input type="checkbox" name="dia[]" value="Jueves">&nbsp;Jue</label>
                                            <label class="checkbox-inline"><input type="checkbox" name="dia[]" value="Viernes">&nbsp;Vie</label>
                                            <label class="checkbox-inline"><input type="checkbox" name="dia[]" value="Sabado">&nbsp;Sab</label>
                                            <label class="checkbox-inline"><input type="checkbox" name="dia[]" value="Domingo">&nbsp;Dom</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="Nacionalidad">Estado Funcionamiento:</label>
                                            <input type="text" value="Inactivo" class="form-control" name="estado_funcionamiento" readonly>
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
