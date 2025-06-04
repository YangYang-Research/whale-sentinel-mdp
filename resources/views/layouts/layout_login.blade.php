<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('authentications.includes.head')
</head>

<body class="bg-gradient-primary">

    @yield('login')
    
    @include('authentications.includes.script')
</body>

</html>