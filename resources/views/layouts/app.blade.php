<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @hasSection('title')

            <title>@yield('title') - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif

        <!-- Favicon -->
		<link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ url(mix('css/app.css')) }}">
        @livewireStyles

        <!-- Scripts -->
        <script src="{{ url(mix('js/app.js')) }}" defer></script>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="text-blue-300 bg-gray-900">
        <header class="flex justify-between items-center fixed z-10 bg-gray-900 w-full">
            <a href="index.html" id="logo" class="ml-20 px-6 py-2 italic text-5xl">{{ config('app.name') }}</a>
            <livewire:search-dropdown>
        </header>
        <div class="flex justify-between px-10 pt-20">
            <nav>
                <ul class="text-2xl">
                    <li class="border-b mr-4 border-solid border-gray-700"><a class="block px-4 py-3" href="#">Главная</a></li>
                    <li class="border-b mr-4 border-solid border-gray-700"><a class="block px-4 py-3" href="#">Фильмы</a></li>
                    <li class="mr-4"><a class="block px-4 py-3" href="#">Сериалы</a></li>
                </ul>
            </nav>
            <main class="text-black bg-gray-400 px-10 py-3 w-full">
                @yield('content')
            </main>
        </div>
        <footer class="h-16 text-center text-white my-5">&copy; 2020 MyHomeMedia</footer>
        @livewireScripts
    </body>
</html>
