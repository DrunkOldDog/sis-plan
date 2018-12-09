@extends('layout.principal')
@section('content')
<div id="content">
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Promociones Disponibles:</h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Descripcion</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($eventos as $evento)
                            <tr>
                                <td>{{$evento['nombre']}}</td>
                                <td>{{$evento['precio_total']}}Bs.</td>
                                <td><p>{{$evento['descripcion']}}</p></td>

                                <td><a href="{{action('PromocionController@edit', $evento['id_eventos'])}}" class="btn btn-warning">Edit</a></td>
                                <td>
                                    <form action="{{action('PromocionController@destroy', $evento['id_eventos'])}}" method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn btn-danger" type="submit" onclick="return confirm('¿Quiere borrar el paquete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <a href="{{action('PromocionController@create')}}" class="btn btn-primary">Registro</a>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
