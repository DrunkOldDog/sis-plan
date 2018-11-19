<!DOCTYPE html>
<html lang="en">
<head>
    @include('layout.partials.head')
</head>
<body>
    @include('layout.partials.nav')
    @include('layout.partials.header')
    <div class="espacio"></div>
    @yield('content')
</body>
</html>
