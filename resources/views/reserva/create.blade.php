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
                                @if(isset($id_crear))
                                    <h5>Incluye:</h5>
                                    <hr>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="Productos">Productos:</label>
                                            <ul>
                                            @foreach($incProducto as $product)
                                                <li>{{$product->cantidad}} Unidades de {{$product->nombre}}</li>
                                            @endforeach
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="Servicios">Servicios:</label>
                                            <ul>
                                            @foreach($incServicio as $service)
                                                <li>Servicio de {{$service->nombre}}</li>
                                            @endforeach
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="Habitaciones">Habitaciones:</label>
                                            <ul>
                                            @foreach($incHabitacion as $room)
                                                <li>{{$room->cantidad}} Habitaciones {{$room->nombre}}</li>
                                            @endforeach
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="Ambientes">Ambientes:</label>
                                            <ul>
                                            @foreach($incAmbiente as $ambient)
                                                <li><strong>{{$ambient->nombre}}</strong>. Capacidad para <strong>{{$ambient->capacidad}}</strong> personas.</li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
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
                                @if(isset($id_crear))
                                    @foreach($eventos as $evento)
                                        @if($evento->id_eventos == $id_crear && $evento->fecha_fin)
                                            <?php $fecha_fin = $evento->fecha_fin; $fecha_inicio =  $evento->fecha_inicio;?>
                                        @endif
                                    @endforeach
                                @endif
                                <?php date_default_timezone_set('America/La_Paz');?>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                      <label for="fec_evento">Fecha Evento:  @if(isset($fecha_inicio)) (Del: {{date("d/m/Y", strtotime($fecha_inicio))}} al {{date("d/m/Y", strtotime($fecha_fin))}}) @endif</label>
                                      <input type="date" @if(old('fec_evento')) value="{{old('fec_evento')}}" @else value="{{date("Y-m-d")}}" @endif min="{{date("Y-m-d")}}" @if(isset($fecha_fin)) max="{{$fecha_fin}}" @endif class="form-control" name="fec_evento">
                                    </div>
                                    <div class="form-group col-md-4">
                                      <label for="hor_ini_evento">Hora Inicio Evento:</label>
                                      <input type="time" class="form-control" name="hor_ini_evento" @if(old('hor_ini_evento')) value="{{old('hor_ini_evento')}}" @else value="{{date("H:00", strtotime(date("H:00")) + 60*60)}}" @endif required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="hor_fin_evento">Hora Fin Evento:</label>
                                        <input type="time" class="form-control" name="hor_fin_evento" @if(old('hor_fin_evento')) value="{{old('hor_fin_evento')}}" @else value="{{date("H:00", strtotime(date("H:00")) + 60*120)}}" @endif required>
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
                                                    @if(!$evento->estado && !$evento->fecha_inicio)
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
