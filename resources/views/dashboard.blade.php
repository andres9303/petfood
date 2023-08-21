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

    <!-- Dashboard -->
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Inventario -->
            @can('view-menu',"report.inventory")
            <div class="flex items-center p-4 bg-alternative-500 rounded-lg shadow-md">
                <div class="flex-shrink-0 mr-4">
                    <span class="text-3xl text-alternative-200">
                        <i class="fas fa-box"></i>
                    </span>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-50">Total Inventario</p>
                    <p class="text-2xl font-bold text-green-500">${{ number_format($value_inventory) }}</p>
                </div>
                <a href="{{ route('report.inventory.index') }}" class="ml-auto text-green-500">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>  
            @endcan            
            <!-- Ventas Mes -->
            @can('view-menu',"report.sale")
            <div class="flex items-center p-4 bg-alternative-500 rounded-lg shadow-md">
                <div class="flex-shrink-0 mr-4">
                    <span class="text-3xl text-alternative-200">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-50">Total Ventas Mes</p>
                    <p class="text-2xl font-bold text-green-500">${{ number_format($value_sale) }}</p>
                </div>
                <a href="{{ route('report.sale.index') }}" class="ml-auto text-green-500">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endcan
            <!-- Gastos Mes -->
            @can('view-menu',"report.bill")
            <div class="flex items-center p-4 bg-alternative-500 rounded-lg shadow-md">
                <div class="flex-shrink-0 mr-4">
                    <span class="text-3xl text-alternative-200">
                        <i class="fas fa-receipt"></i>
                    </span>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-50">Total Gastos Mes</p>
                    <p class="text-2xl font-bold text-red-400">${{ number_format($value_bill) }}</p>
                </div>
                <a href="{{ route('report.bill.index') }}" class="ml-auto text-red-400">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endcan
            <!-- Costos Mes -->
            @can('view-menu',"report.cost")
            <div class="flex items-center p-4 bg-alternative-500 rounded-lg shadow-md">
                <div class="flex-shrink-0 mr-4">
                    <span class="text-3xl text-alternative-200">
                        <i class="fas fa-coins"></i>
                    </span>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-50">Total Costos Mes</p>
                    <p class="text-2xl font-bold text-red-400">${{ number_format($value_cost) }}</p>
                </div>
                <a href="{{ route('report.cost.index') }}" class="ml-auto text-red-400">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endcan
        </div>        
    </div>
    <div class="grid grid-cols-1 gap-4 bg-white lg:grid-cols-2">
        @can('view-menu',"report.balance")
        @livewire('graph.balance-graph')
        @endcan

        @can('view-menu',"report.inventory")
        @livewire('graph.inventory-graph')
        @endcan
    </div>
</x-app-layout>
