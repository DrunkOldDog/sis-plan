@extends('layout.principal')
@section('content')
<div id="content">
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Usuarios:</h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Privilegios</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                            <tr>
                                @if($user['username'] != 'admin')
                                <td>{{$user['name']}}</td>
                                <td>{{$user['last_name']}}</td>
                                <td>{{$user['username']}}</td>
                                <td>{{$user['email']}}</td>
                                @if($user['isAdmin'] == 0)
                                    <td><a href="{{action('RolController@edit', $user['id'])}}" class="btn btn-warning">Dar Privilegio</a></td>
                                @else
                                <td>
                                    <form action="{{action('RolController@destroy', $user['id'])}}" method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn btn-danger" type="submit" onclick="return confirm('¿Quiere borrar el privilegio?')">Quitar Privilegio</button>
                                    </form>
                                </td>
                                @endif
                                @endif
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
