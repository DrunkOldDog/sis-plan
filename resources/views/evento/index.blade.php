@extends('layout.principal')
@section('content')
<div id="content">
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Paquetes Disponibles:</h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Descripcion</th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($eventos as $evento)
                            <?php 
                            $precios = DB::table('servicios_evento')
                                    ->select('servicios.precio')
                                    ->join('servicios', 'servicios.id_servicios', '=', 'servicios_evento.id_servicios')
                                    ->join('eventos', 'eventos.id_eventos', '=', 'servicios_evento.id_eventos')
                                    ->where('eventos.id_eventos', $evento['id_eventos'])
                                    ->orderBy('id_servicios_evento')
                                    ->get();
                            $replik = 0;
                            foreach ($ambientes as $ambiente) {
                                if($ambiente->id_ambientes == $evento['id_ambientes']){
                                    $replik = $ambiente->precio;
                                }
                            }
                            $sum = 0;
                            foreach($precios as $precio){
                            $sum+= $precio->precio;
                            }
                            $price = App\Evento::find($evento['id_eventos']);
                            $price->precio_total = $evento['precio']+$sum+$replik;
                            $price->save();
                            ?>
                            <tr>
                                <td>{{$evento['nombre']}}</td>
                                <td>{{$evento['precio']+$sum+$replik}}Bs.</td>
                                <td><p>{{$evento['descripcion']}}</p></td>

                                <td><a href="{{action('EventoController@edit', $evento['id_eventos'])}}" class="btn btn-warning">Edit</a></td>
                                <td>
                                    <form action="{{action('EventoController@destroy', $evento['id_eventos'])}}" method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn btn-danger" type="submit" onclick="return confirm('¿Quiere borrar el paquete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <a href="{{action('EventoController@create')}}" class="btn btn-primary">Registro</a>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
