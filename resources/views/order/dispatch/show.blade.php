<x-app-layout>
    <x-slot name="header">
        Gestión de Pedidos - Preparaciones - Preparación {{ $produce->num }}
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a href="{{ route('produce.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </x-slot>

        <x-slot name="content">
            <div class="flex justify-center mt-0">
                <div class="w-full">
                    <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <div class="mb-4">
                            <div class="text-gray-500">
                                <div class="md:flex md:justify-between md:space-x-4">
                                    <div class="mt-4 w-full">
                                        <h2 class="text-2xl font-semibold mb-4">Preparación # {{ $produce->num }} del {{ $produce->formatted_date }} - 
                                            <span class="px-2 py-1 text-lg text-white rounded-full {{ $produce->state == 2 ? 'bg-green-500' : ($produce->state == 1 ? 'bg-blue-500' : 'bg-red-500') }}">
                                                {{ $produce->state == 2 ? 'Despachado' : ($produce->state == 1 ? 'Completo' : 'Pendiente') }}
                                            </span>
                                        </h2>
                                        <p class="mb-4">{{ $produce->text }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <h2 class="text-2xl font-semibold mb-4">Pedidos 
                                        @if ($produce->state == 0)
                                        <a href="{{ route('produce.edit', ['produce' => $produce]) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
                                            <i class="fa fa-edit mr-1"></i> Planificar
                                        </a>                                            
                                        @endif
                                    </h2>
                                    <p class="mb-4"></p>
                                    <livewire:table.order.produce.order-table produce_id="{{$produce->id}}"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <div class="mt-4 text-gray-500">
                            <h2 class="text-2xl font-semibold mb-4">Dietas {{ $produce->state == 0 ? 'Requeridas' : 'Producidas' }}</h2>
                            <p class="mb-4"></p>
                            <livewire:table.order.produce.diet-table produce_id="{{$produce->id}}"/>
                        </div>
                    </div>

                    <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <div class="mt-4 text-gray-500">
                            <h2 class="text-2xl font-semibold mb-4">Productos {{ $produce->state == 0 ? 'Necesarios' : 'Consumidos' }}</h2>
                            <p class="mb-4"></p>
                            <livewire:table.order.produce.product-table produce_id="{{$produce->id}}"/>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-crud-index>
</x-app-layout>
