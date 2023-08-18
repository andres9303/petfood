<div class="text-gray-500">
    <div class="md:flex md:justify-between md:space-x-4">
        <div class="mt-4 w-full md:w-1/2">
            {!! Form::label('date', 'Fecha Preparación (*)') !!}
            {!! Form::date('date', $now, ['class' => 'block mt-1 w-full'.($errors->has('date') ? ' is-invalid' : '')]) !!}
        </div>
        <div class="mt-4 w-full md:w-1/2">
            {!! Form::label('text', 'Observaciones') !!}
            {!! Form::text('text', null, ['class' => 'block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) !!}
        </div>
    </div>
    <div class="overflow-x-auto mt-4">
        <h2 class="text-2xl font-semibold mb-4">Pedidos</h2>
        <p class="mb-4"></p>
        <table class="min-w-full divide-y divide-gray-200">
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
                            @if (isset($produce))
                            {!! Form::checkbox('reqs[]', $order->id, $order->doc_id == $produce->id) !!}
                            @else
                            {!! Form::checkbox('reqs[]', $order->id) !!}   
                            @endif
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
</div>