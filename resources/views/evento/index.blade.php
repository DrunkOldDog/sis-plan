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
                        @if(auth()->user()->isAdmin != 0)
                            <h3 class="mb-0">Paquetes Disponibles:</h3>
                        @else 
                            <h3 class="mb-0">Mis Paquetes:</h3>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Precio</th>
                                @if(auth()->user()->isAdmin != 0)
                                <th>Descripcion</th>
                                @endif
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($eventos as $evento)
                            <tr>
                                <td>{{$evento['nombre']}}</td>
                                <td>{{$evento['precio_total']}}Bs.</td>
                                @if(auth()->user()->isAdmin != 0)
                                <td><p>{{$evento['descripcion']}}</p></td>
                                <td><a href="{{action('EventoController@edit', $evento['id_eventos'])}}" class="btn btn-warning">Editar</a></td>
                                @else 
                                <td><a href="{{action('EventoController@show', $evento['id_eventos'])}}" class="btn btn-primary">Ver</a></td>
                                @endif
                                <td>
                                    <form action="{{action('EventoController@destroy', $evento['id_eventos'])}}" method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn btn-danger" type="submit" onclick="return confirm('¿Quiere borrar el paquete?')">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(auth()->user()->isAdmin != 0)
                            <a href="{{action('EventoController@create')}}" class="btn btn-primary">Registro</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
