<x-app-layout>
    <x-slot name="header">
        Compras - Lista de compras
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">

        </x-slot>

        <x-slot name="content">
            <div class="flex justify-center mt-0">
                <div class="w-full">
                    <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <div class="mb-4 text-gray-500">
                        {!! Form::open(['route' => 'shopping-list.index', 'method' => 'GET']) !!}
                            <div class="overflow-x-auto mt-4">
                                <h2 class="text-2xl font-semibold mb-4">Pedidos</h2>
                                <p class="mb-4">Seleccione los pedidos a producir para calcular el inventario necesario y la lista de compras necesaria.</p>
                                <p class="mb-4 text-xl font-semibold text-alternative-500 {{ count($orders) > 0 ? 'hidden' : 'block' }}">No se encontraron pedidos pendientes.</p>
                                <table class="min-w-full divide-y divide-gray-200 {{ count($orders) > 0 ? 'block' : 'hidden' }}">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">                        
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Fecha Requerido
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cliente
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Mascota
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Dieta
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Porciones
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Unidad
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cant. Porción
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Detalle
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($orders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {!! Form::checkbox('reqs[]', $order->id) !!}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $order->formatted_date }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $order->person->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $order->pet->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $order->mvtos[0]->product->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ number_format($order->mvtos[0]->cant_src) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $order->mvtos[0]->unit->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ number_format($order->mvtos[0]->cant, 4) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $order->text }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-6 {{ count($orders) > 0 ? 'block' : 'hidden' }}">
                                {!! Form::button('<i class="fas fa-cart-plus"></i> Calcular Lista de Compras', ['type' => 'submit', 'class' => 'bg-green-500 block mt-1 w-full hover:bg-green-600 text-white py-2 px-4']) !!}
                            </div>
                        {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4 {{ $reqs ? 'block' : 'hidden' }}">
                        <div class="text-gray-500">
                            <livewire:table.shopping.list-table orders="{{ $reqs }}"/>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-crud-index>
</x-app-layout>
