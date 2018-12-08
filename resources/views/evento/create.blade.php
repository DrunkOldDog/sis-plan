@extends('layout.principal')
@section('content')
<div id="content">
<div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Creacion de Paquetes:</h3>
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
                                              <label for="Nombre">Nombre:</label>
                                              <input type="text" class="form-control" name="nombre">
                                            </div>
                                            <div class="form-group col-md-6">
                                              <label for="Precio">Precio:</label>
                                              <input type="text" class="form-control" name="precio">
                                            </div>
                                    </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                      <label for="Descripcion">Descripcion:</label>
                                      <textarea class="form-control" rows="3" name="descripcion"></textarea>
                                    </div>
                                  </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                          <label for="Imagen">Imagen:</label>
                                          <input type="file" accept="image/*" name="filename">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <label for="Ambientes">Ambientes:</label>
                                    <select name="id_ambientes" id="id_ambientes" class="form-control">
                                            @foreach($ambientes as $ambiente)
                                            <option value ="{{$ambiente->id_ambientes}}">{{ $ambiente->nombre }}&nbsp;({{ $ambiente->precio }}Bs.)</option>
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
                                    ?>
                                      <label for="complemento" id="comple" class="col-sm-2 col-form-label">{{$complemento->nombre}}&nbsp;({{$complemento->precio}}Bs.):</label>
                                      <div class="form-group col-md-1">
                                          <input type="number" class="form-control" name="espe[]" value="0">
                                      </div>
                                    @endforeach
                                    <br><br><br>
                                  @endforeach
                                  
                                  <div class="form-group col-md-12"></div>
                                  <?php $chozni = 0; ?>
                                  @foreach($servicios as $servicio)
                                  @if($servicio->id_servicios != $serviciosespecificos[$chozni]->id_servicios)
                                  <div class="form-group col-md-4">
                                     <label class="checkbox-inline"><input type="checkbox" name="servi[]" value="{{$servicio->id_servicios}}"></label>&nbsp;{{$servicio->nombre}}&nbsp;({{$servicio->precio}}Bs.)
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
