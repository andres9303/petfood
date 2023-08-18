<x-app-layout>
    <x-slot name="header">
        {{ __('Home') }}
    </x-slot>

    <!-- Accesos directos -->
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        @if ($shortcuts->count() > 0)
        <h1 class="mb-5 text-gray-400 font-bold">Accesos directos</h1>
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-4 md:grid-cols-3 sm:grid-cols-2"> 
            @foreach ($shortcuts as $menu)
            <a href="{{ route($menu->route.'.index') }}" class="flex items-center p-4 bg-alternative-400 shadow rounded-lg transition-all hover:bg-alternative-500">
                <div class="flex items-center justify-center w-10 h-10 mr-4 bg-alternative-500 rounded-md">
                    <i class="text-alternative-100 {{ $menu->icon }}"></i>
                </div>
                <div class="text-lg font-medium text-contrast-alternative-500">{{ $menu->text }}</div>
            </a> 
            @endforeach                   
        </div>
        @endif
        
    </div>
</x-app-layout>
