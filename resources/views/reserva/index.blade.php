@extends('layout.principal')
@section('content')
<div id="content">
    <div class="container-fluid mt--7">
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
        <div class="row">
            <div class="col">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Reservas Disponibles:</h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead>
                            <tr>
                                <th>Fecha Evento</th>
                                <th>Fecha Registro</th>
                                <th>Horario Evento</th>
                                <th>Evento</th>
                                <th>Cliente</th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reservas as $reserva)
                            <tr>
                                <td>{{$reserva['fec_evento']}}</td>
                                <td>{{$reserva['fec_reserva']}}</td>
                                <td><option>{{$reserva['hor_ini_evento']}}</option>
                                    <option>{{$reserva['hor_fin_evento']}}</option></td>
                                <td>@foreach($eventos as $evento)
                                    @if($reserva['id_eventos'] == $evento->id_eventos)
                                        <a href="{{action('EventoController@show', $evento->id_eventos)}}">{{$evento->nombre}}</a>
                                    @endif
                                @endforeach</td>
                                <td>@foreach($clientes as $cliente)
                                        @if($reserva['id_clientes'] == $cliente->id_clientes){{$cliente->apellido}}&nbsp;{{$cliente->nombre}}@endif
                                    @endforeach</td>
                                @if(auth()->user()->isAdmin != 0)
                                <td><a href="{{action('ReservaController@edit', $reserva['id_reservas'])}}" class="btn btn-warning">Editar</a></td>
                                @endif
                                <td>
                                    <form action="{{action('ReservaController@destroy', $reserva['id_reservas'])}}" method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn btn-danger" type="submit" onclick="return confirm('¿Quiere borrar la reserva?')">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(auth()->user()->isAdmin != 0) 
                        <a href="{{action('ReservaController@create')}}" class="btn btn-primary">Registro</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
