@extends('layout.mainlayout')
@section('content')
<div id="content">
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-12 order-xl-1">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">Promociones Disponibles:</h3>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <?php date_default_timezone_set('America/La_Paz');?>
                        <div class="card-body">
                            <div class="form-row">
                                @foreach($eventos as $evento)
                                @if($evento->fecha_inicio && $evento->estado == 0)
                                    @if($evento->fecha_fin >= date("Y-m-d"))
                                    <div class="col-md-4">
                                        <div class="thumbnail">
                                            <a href="{{ route('test.route', $evento->id_eventos) }}" method="GET">
                                            <img src="../../../images/{{$evento->foto}}" alt="Lights" style="width:100%">
                                            <h5 class="subtit">{{$evento->nombre}}</h5>
                                            <div class="caption">
                                                    Del: {{date("d/m/Y", strtotime($evento->fecha_inicio))}}&nbsp;al: {{date("d/m/Y", strtotime($evento->fecha_fin))}}
                                                    <h6>Precio: {{$evento->precio_total}}Bs. con un descuento del {{$evento->descuento*100}}%!!!</h6>
                                                    <p>{{$evento->descripcion}}</p>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                                @endforeach
                            </div>                
                        </div>
                        <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0">Paquetes Disponibles:</h3>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                    <div class="form-row">
                                        @foreach($eventos as $evento)
                                        @if(!$evento->fecha_inicio && $evento->estado == 0)
                                        <div class="col-md-4">
                                            <div class="thumbnail">
                                                <a href="{{ route('test.route', $evento->id_eventos) }}" method="GET">
                                                <img src="../../../images/{{$evento->foto}}" alt="Lights" style="width:100%">
                                                <h5 class="subtit">{{$evento->nombre}}</h5>
                                                <div class="caption">
                                                        <h6>Precio: {{$evento->precio_total}}Bs.</h6>
                                                        <p>{{$evento->descripcion}}</p>
                                                </div>
                                                </a>
                                                </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>                
                                </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
