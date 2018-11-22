@extends('layout.mainlayout')
@section('content')
<div id="content">
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-12 order-xl-1">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">Reservas Disponibles:</h3>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="form-row">
                                @foreach($eventos as $evento)
                                <div class="col-md-4">
                                    <div class="thumbnail">
                                        <a href="{{ action('ReservaController@create') }}">
                                        <img src="../../../images/{{$evento->foto}}" alt="Lights" style="width:100%">
                                        <h5 class="subtit">{{$evento->nombre}}</h5>
                                        <div class="caption">
                                                <p>{{$evento->descripcion}}</p>
                                        </div>
                                        </a>
                                        </div>
                                </div>
                                @endforeach
                            </div>                
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
