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

    {{-- <x-demo.nav/> --}}

    <x-demo.nav testClass="abc">
        <li><a href="#">Contact 2</a></li>

        <x-slot:navClassLi1>navClassLi 1</x-slot:navClassLi1>
        <x-slot:navClassLi2>navClassLi 2</x-slot:navClassLi2>
    </x-demo.nav>


    <x-demo.demo-component2 navClassLi4="navClassLi4"/>



    <x-demo.nav testClass="abc">
        <li><a href="#">Contact 2</a></li>

        <x-slot name="navClassLi1">navClassLi1</x-slot>
        <x-slot name="navClassLi2">navClassLi2</x-slot>
    </x-demo.nav>
            


    <main>
        @yield('content')
    </main>

    @include('layout.components.footer')
</body>

</html>
