<div>
    <div class="flex items-center bg-red-100 text-red-500 text-sm font-bold px-4 py-3 {{ $error ? 'block' : 'hidden' }}" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span class="align-middle">{{ $error }}</span>
    </div>
    <div class="mb-4">
        <div class="mt-4 text-gray-500">
            <h2 class="text-2xl font-semibold mb-4">Dietas Producidas</h2>
            <p class="mb-4"></p>
            <livewire:table.order.produce.diet-table produce_id="{{ $produce->id }}"/>
        </div>
    </div>

    <div class="mb-4">
        <h2 class="text-2xl font-semibold mb-4">Pedidos</h2>
        <p class="mb-4"></p>
        <livewire:table.order.produce.order-table produce_id="{{ $produce->id }}"/>
    </div>    

    <div class="overflow-x-auto w-full md:col-span-2 block mt-4">
        <h2 class="text-2xl font-semibold mb-4">Despachos</h2>
        <p class="mb-4">Confirma las cantidades que se han despachado.</p>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">                        
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nro. Pedido
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mascota
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Producto
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Und. porción
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cant. porción
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nro porciones
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cant. Total
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Valor unidad
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Valor Total
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a wire:click.prevent="edit({{ $order->id }})" role="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fa fa-edit mr-1"></i> Cambiar
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $order->mvto->doc->num }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $order->mvto->doc->pet->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $order->product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $order->unit->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->cant / ($order->cant_src > 0 ? $order->cant_src : 1), 4) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->cant_src, 4) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->cant, 4) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->valueu, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->valuet) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> 
    </div>

    <!-- Modal Cambio de Dieta -->
    <div class="fixed inset-0 z-10 flex items-center justify-center bg-opacity-50 bg-gray-900 {{ $showModal ? 'block' : 'hidden' }}">
        <div class="bg-white p-6 rounded shadow-md w-full md:w-1/3">
            <h2 class="text-xl font-semibold mb-4">Editar cantidad a despachar</h2>    
            <div class="mb-4">
                <p><strong>Producto:</strong> {{ $order_selected ? $order_selected->product->name : '' }}</p>
                <p><strong>Unidad:</strong> {{ $order_selected ? $order_selected->unit->name : '' }}</p>
                <p><strong>Mascota:</strong> {{ $order_selected ? $order_selected->mvto->doc->pet->name : '' }}</p>
            </div>

            {!! Form::label('cant', 'Nueva Cantidad:') !!}
            {!! Form::number('cant', null, ['class' => 'block mt-1 w-full'.($errors->has('cant') ? ' is-invalid' : ''), 'placeholder' => 'Cantidad preparada', 'step' => 0.0001, 'wire:model' => 'cant_selected']) !!}
            
            {!! Form::label('valueu', 'Nuevo Valor Unitario:') !!}
            {!! Form::number('valueu', null, ['class' => 'block mt-1 w-full'.($errors->has('valueu') ? ' is-invalid' : ''), 'placeholder' => 'Valor unitario', 'step' => 0.01, 'wire:model' => 'valueu_selected']) !!}
            
            <div class="mt-4">
                <a wire:click.prevent="changeModal" role="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-cancel mr-1"></i> Cancelar
                </a>
                <a wire:click.prevent="update" role="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded ml-2">
                    <i class="fas fa-save mr-1"></i> Guardar
                </a>
            </div>
        </div>
    </div>
</div>
