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
                                <h3 class="mb-0">Dashboard Hotel Empresarial:</h3>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                            @csrf
                            <div class="form-row">
                                    <div class="form-group col-md-6">
                                            {!! $chart->container() !!}

                                    </div>
                                    <div class="form-group col-md-6">
                                         {!! $chartRes->container() !!}
                                    </div>
                            </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://unpkg.com/vue"></script>
        <script>
            var app = new Vue({
                el: '#app'
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
        <script type="text/javascript" src=https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
        {!! $chart->script() !!}
        {!! $chartRes->script() !!}
@endsection
