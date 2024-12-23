<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Simple HTML Boilerplate')</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
</head>

<body @isset($testClass) class="{{$testClass}}"@endisset>
    @include('layout.components.header')

    @include('layout.components.nav')

    <main>
        @yield('content')
    </main>

    @include('layout.components.footer')
</body>

</html>
