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
                            <form action="{{action('EventoController@update', $id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                    <br>
                                    <div class="form-row">
                                            <div class="form-group col-md-6">
                                              <label for="Nombre">Nombre:</label>
                                              <input type="text" class="form-control" name="nombre" value="{{$evento->nombre}}">
                                            </div>
                                            <div class="form-group col-md-6">
                                              <label for="Precio">Precio:</label>
                                              <input type="text" class="form-control" name="precio" value="{{$evento->precio}}">
                                            </div>
                                          </div>

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

                                    <br>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                          <label for="Ambientes">Ambientes:</label>
                                          <select name="id_ambientes" id="id_ambientes" class="form-control">
                                                  @foreach($ambientes as $ambiente)
                                                  @if($ambiente->id_ambientes == $evento['id_ambientes'])
                                                  <option value="{{$ambiente->id_ambientes}}" selected>{{ $ambiente->nombre }}&nbsp;({{ $ambiente->precio }}Bs.)</option>
                                                  @else
                                                  <option value ="{{$ambiente->id_ambientes}}">{{ $ambiente->nombre }}&nbsp;({{ $ambiente->precio }}Bs.)</option>@endif
                                                  @endforeach
                                               </select>
                                        </div>
                                      </div>
                                <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="Nombre">Servicios Adicionales:</label>
                                        </div>

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
                                            <input type="number" class="form-control" name="espe[]" min="0" value="{{$cantidadProducto[0]->cantidad}}">
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
                                          <input type="number" class="form-control" name="habi[]" min="0" value="{{$cantidadHabitacion[0]->cantidad}}">
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
                                                         <input type="checkbox" name="servi[]" value="{{$servicio->id_servicios}}" checked>
                                                         <?php $choznita = 1;?>
                                                         @break
                                                    @endif
                                                @endforeach
                                                @if($choznita != 1) 
                                                <input type="checkbox" name="servi[]" value="{{$servicio->id_servicios}}">
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
