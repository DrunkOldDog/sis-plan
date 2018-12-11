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
                                <h3 class="mb-0">Edicion de Paquetes:</h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                    <br>
                                    <div class="form-row">
                                            <div class="form-group col-md-6">
                                              <label for="Nombre">Nombre:</label>
                                              <input type="text" class="form-control" name="nombre" value="{{$evento->nombre}}" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                              <label for="Precio">Precio:</label>
                                              <input type="text" class="form-control" name="precio" value="{{$evento->precio}}" readonly>
                                            </div>
                                          </div>

                                        @if(!isset($evento->estado))
                                          <div class="row">
                                            <div class="form-group col-md-12">
                                              <label for="Descripcion">Descripcion:</label>
                                              <textarea class="form-control" rows="3" name="descripcion">{{$evento->descripcion}}</textarea>
                                            </div>
                                          </div>
        
                                          <label for="Imagen">Imagen:</label>
                                          <div class="row">
                                                <div class="form-group col-md-6">
                                                    <img class="imagensilla" width="500" height="400" src="../../../images/{{$evento->foto}}">
                                                    <input type="file" accept="image/*" name="imagen">
                                                </div>
                                            </div>
                                        @endif
                                        <br>

                                        @if(isset($evento->fecha_inicio))
                                        <?php date_default_timezone_set('America/La_Paz');?>
                                         <div class="form-row">
                                          <div class="form-group col-md-4">
                                            <label for="Fecha_Inicio">Fecha Inicio:</label>
                                            <input type="date" @if(old('fecha_inicio')) value="{{old('fecha_inicio')}}" @else value="{{$evento->fecha_inicio}}" @endif min="{{date("Y-m-d")}}" class="form-control" name="fecha_inicio" required>
                                          </div>
                                          <div class="form-group col-md-4">
                                            <label for="Fecha_Fin">Fecha Fin:</label>
                                            <input type="date" @if(old('fecha_fin')) value="{{old('fecha_fin')}}" @else value="{{$evento->fecha_fin}}" @endif min="{{date("Y-m-d")}}" class="form-control" name="fecha_fin" required>
                                          </div>
                                          <div class="form-group col-md-4">
                                              <label for="Descuento">Descuento:</label>
                                              <input type="text" class="form-control" value="{{$evento->descuento}}" name="descuento">
                                            </div>
                                        </div>
                                        @endif

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                        <label for="Nombre">Servicios Adicionales:</label>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <h6>Ambientes</h6>
                                        <hr>
                                    </div>
                                    <?php $chozniAmb = 0;?>
                                    @foreach($ambientes as $ambiente)
                                          <div class="form-group col-md-4">
                                             <label class="checkbox-inline">
                                                @foreach($marcadosAmbi as $marcadoAmbi)
                                                <?php $chozniAmb = 0;?>
                                                    @if($marcadoAmbi == $ambiente->id_ambientes)
                                                         <input type="checkbox" name="ambients[]" value="{{$ambiente->id_ambientes}}" checked disabled>
                                                         <?php $chozniAmb = 1;?>
                                                         @break
                                                    @endif
                                                @endforeach
                                                @if($chozniAmb != 1) 
                                                <input type="checkbox" name="ambients[]" value="{{$ambiente->id_ambientes}}" disabled>
                                                @endif
                                            </label>&nbsp;{{$ambiente->nombre}}&nbsp;({{$ambiente->precio}}Bs.)
                                          </div>
                                    @endforeach

                                        @foreach($serviciosespecificos as $seresp)
                                    <div class="form-group col-md-12">
                                      <h6>{{$seresp->nombre}}</h6>
                                      <hr>
                                    </div>
                                    <?php
                                      //obtener los productos
                                      $complementos = DB::table('productos')
                                                    ->select('*')
                                                    ->join('productos_servicio', 'productos.id_productos', '=', 'productos_servicio.id_productos')
                                                    ->where('productos_servicio.id_servicios', $seresp->id_servicios)
                                                    ->where('productos.estado', true)
                                                    ->orderBy('productos.id_productos')
                                                    ->get();
                                    ?>
                                      @foreach($complementos as $complemento)
                                      <?php
                                        $cantidades = DB::table('productos_servicio')
                                                      ->select('*')
                                                      ->where('id_productos', $complemento->id_productos)
                                                      ->where('id_servicios', $seresp->id_servicios)
                                                      ->orderBy('id_productos_servicio')
                                                      ->get();
                                        
                                        $cantidadProducto = DB::table('productos_evento')
                                                        ->select('*')
                                                        ->where('id_eventos', $evento['id_eventos'])
                                                        ->where('id_productos_servicio', $cantidades[0]->id_productos_servicio)
                                                        ->get();
                                      ?>
                                        <label for="complemento" id="comple" class="col-sm-2 col-form-label">{{$complemento->nombre}}&nbsp;({{$complemento->precio}}Bs.):</label>
                                        <div class="form-group col-md-1">
                                            <input type="number" class="form-control" name="espe[]" min="0" value="{{$cantidadProducto[0]->cantidad}}" disabled >
                                        </div>
                                      @endforeach
                                      <br><br><br>
                                  @endforeach
                                  <div class="form-group col-md-12"></div>

                                  <div class="form-group col-md-12">
                                        <h6>Habitaciones</h6>
                                        <hr>
                                    </div>
                                    @foreach($habitaciones as $habitacion)
                                    <?php
                                        $cantidadHabitacion = DB::table('habitaciones_evento')
                                                                ->select('*')
                                                                ->where('id_habitaciones',$habitacion->id_habitaciones)
                                                                ->where('id_eventos', $evento['id_eventos'])
                                                                ->get();
                                    ?>
                                      <label for="habitacion" id="comple" class="col-sm-2 col-form-label">{{$habitacion->nombre}}&nbsp;({{$habitacion->precio}}Bs.):</label>
                                      <div class="form-group col-md-1">
                                          <input type="number" class="form-control" name="habi[]" min="0" value="{{$cantidadHabitacion[0]->cantidad}}" disabled>
                                      </div>
                                    @endforeach
                                    
                                    <div class="form-group col-md-12"></div>
                                    
                                  <?php $chozni = 0; $choznita = 0?>
                                        @foreach($servicios as $servicio)
                                        @if($servicio->id_servicios != $serviciosespecificos[$chozni]->id_servicios)
                                          <div class="form-group col-md-4">
                                             <label class="checkbox-inline">
                                                @foreach($marcados as $marcado)
                                                <?php $choznita = 0;?>
                                                    @if($marcado == $servicio->id_servicios)
                                                         <input type="checkbox" name="servi[]" value="{{$servicio->id_servicios}}" checked disabled>
                                                         <?php $choznita = 1;?>
                                                         @break
                                                    @endif
                                                @endforeach
                                                @if($choznita != 1) 
                                                <input type="checkbox" name="servi[]" value="{{$servicio->id_servicios}}" disabled>
                                                @endif
                                            </label>&nbsp;{{$servicio->nombre}}&nbsp;({{$servicio->precio}}Bs.)
                                          </div>
                                          @else 
                                            <?php 
                                                if(count($serviciosespecificos)-1>$chozni){
                                                $chozni += 1;
                                                }
                                            ?>
                                          @endif
                                        @endforeach
                                </div>       
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                      <label for="Total">Total:</label>
                                      <input type="text" class="form-control" name="precio_total" value="{{$evento->precio_total}}Bs." readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                <div class="form-group col-md-3">
                                    <a href="{{action('EventoController@show', $evento['id_eventos'])}}" class="btn btn-danger">Pagar</a>
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
