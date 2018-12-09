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
                        <h3 class="mb-0">Ambientes Disponibles:</h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Capacidad</th>
                                <th>Precio</th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ambientes as $ambiente)
                            <tr>
                                <td>{{$ambiente['nombre']}}</td>
                                <td>{{$ambiente['capacidad']}}&nbsp;Personas</td>
                                <td>{{$ambiente['precio']}}Bs.</td>

                                <td><a href="{{action('AmbienteController@edit', $ambiente['id_ambientes'])}}" class="btn btn-warning">Edit</a></td>
                                <td>
                                    <form action="{{action('AmbienteController@destroy', $ambiente['id_ambientes'])}}" method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn btn-danger" type="submit" onclick="return confirm('¿Quiere borrar el paquete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <a href="{{action('AmbienteController@create')}}" class="btn btn-primary">Registro</a>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
