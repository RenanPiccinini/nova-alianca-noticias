<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal Nova Aliança') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-[#071e3d] via-[#0b2b5c] to-[#041021] min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full sm:max-w-md">
            <div class="text-center mb-6">
                <div class="inline-block relative">
                    <img src="{{ asset('images/logo-nova-alianca-round.jpg') }}" alt="Logo Portal Nova Aliança" class="w-24 h-24 mx-auto object-contain rounded-full border-4 border-white/20 shadow-2xl">
                </div>
                <h2 class="mt-3 text-2xl font-black text-white uppercase tracking-tight">Portal Nova Aliança</h2>
                <p class="text-xs text-blue-200 uppercase font-semibold tracking-wider">Sistema de Criação de Folders & Boletins</p>
            </div>

            <div class="w-full bg-white rounded-2xl shadow-2xl p-8 border border-slate-100">
                {{ $slot }}
            </div>

            <div class="text-center mt-6 text-xs text-blue-300/80">
                &copy; {{ date('Y') }} Portal de Notícias Nova Aliança. Aconteceu virou notícia.
            </div>
        </div>
    </body>
</html>
