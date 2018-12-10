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
                                <!-- se verifica si fue direccionado desde Home, para llenar directamente los datos del cliente-->
                                    <div class="form-row">
                                            <div class="form-group col-md-4">
                                              <label for="Nombre">Nombre:</label>
                                            <input type="text" class="form-control" name="nombre" @if(isset($id_crear)) value="{{$usuario[0]->name}}" readonly @else value="{{old('nombre')}}" @endif>
                                            </div>
                                            <div class="form-group col-md-4">
                                              <label for="Apellido">Apellido:</label>
                                              <input type="text" class="form-control" name="apellido" @if(isset($id_crear)) value="{{$usuario[0]->last_name}}" readonly @else value="{{old('apellido')}}" @endif>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="CI">CI:</label>
                                                <input type="text" class="form-control" name="ci" @if(isset($id_crear)) value="{{$usuario[0]->ci}}" readonly @else value="{{old('ci')}}" @endif>
                                              </div>
                                    </div>
                                <hr>
                                <h5>Reserva</h5>
                                <hr>
                                <?php date_default_timezone_set('America/La_Paz');?>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                      <label for="fec_evento">Fecha Evento:</label>
                                      <input type="date" @if(old('fec_evento')) value="{{old('fec_evento')}}" @else value="{{date("Y-m-d")}}" @endif class="form-control" name="fec_evento">
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label for="hor_ini_evento">Hora Inicio Evento:</label>
                                      <input type="time" class="form-control" name="hor_ini_evento" value="{{old('hor_ini_evento')}}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="hor_fin_evento">Hora Fin Evento:</label>
                                        <input type="time" class="form-control" name="hor_fin_evento" value="{{old('hor_fin_evento')}}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="hor_fin_evento">Evento:</label>
                                        <select name="id_eventos" id="id_eventos" class="form-control">
                                                @foreach($eventos as $evento)
                                                <!-- verifica el evento seleccionado en home, y si no se selecciono, se asigna 1 a la variable-->
                                                @if(isset($id_crear)) 
                                                    @if($evento->id_eventos == $id_crear)
                                                    <option value ="{{$evento->id_eventos}}">{{ $evento->nombre }}&nbsp;({{ $evento->precio_total }}Bs.)</option>
                                                    @endif
                                                @else
                                                    @if(!$evento->estado)
                                                    <option value ="{{$evento->id_eventos}}">{{ $evento->nombre }}&nbsp;({{ $evento->precio_total }}Bs.)</option>
                                                    @endif
                                                @endif     
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
