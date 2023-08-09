<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        <style>
            .menu-container {
              height: 100%;
              overflow-y: auto;
            }
            .menu-container::-webkit-scrollbar {
              width: 0.25rem;
              background-color: rgba(255, 255, 255, 0.1);
            }
            .menu-container::-webkit-scrollbar-thumb {
              background-color: rgba(255, 255, 255, 0.5);
            }
            .menu-container::-webkit-scrollbar-thumb:hover {
              background-color: rgba(255, 255, 255, 0.75);
            }
        </style>
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="h-screen min-h-screen flex">
            <x-banner />
            @livewire('navigation-menu')

            <div class="w-full h-full overflow-auto bg-gray-200">
                <div class=" px-1 py-1">
                    <!-- Page Heading -->
                    <header class="bg-alternative-500 shadow">
                        <div class="m-0 py-4 px-4 sm:px-6 lg:px-8">
                            <h2 class="font-semibold text-xl text-contrast-alternative-500 leading-tight">
                                <i class="fas fa-link"></i>  {{ $header }}
                            </h2>
                        </div>
                    </header>
                </div>
                <!-- Page Content -->
                <main>
                    <div class="py-1 px-1">
                        <div class="mx-auto">
                            <div class="bg-primary-100 overflow-hidden shadow-xl sm:rounded-lg">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        
        @stack('scripts')
    </body>
</html>
