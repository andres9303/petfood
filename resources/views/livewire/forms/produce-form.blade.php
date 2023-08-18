<div>
    <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
        
        <div class="overflow-x-auto w-full md:col-span-2 block mt-4">            
            <h2 class="text-2xl font-semibold mb-4">Dietas Producidas</h2>
            <p class="mb-4">Confirma las cantidades que se han producido de cada dieta.</p>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">                        
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Producto
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Unidad
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cantidad preparada
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($diets as $diet)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a wire:click.prevent="edit({{ $diet->id }})" role="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fa fa-edit mr-1"></i> Cambiar
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $diet->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $diet->unit->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($diet->cant, 4) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> 
        </div>
    </div>

    <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="overflow-x-auto w-full md:col-span-2 block mt-4">
            <h2 class="text-2xl font-semibold mb-4">Ingredientes consumidos</h2>
            <p class="mb-4">Confirma las cantidades que se han consumido para la preparación de las dietas producidas.</p>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">                        
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Producto
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Unidad
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cantidad Preparada
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cantidad Cruda
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recipes as $recipe)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a wire:click.prevent="edit({{ $recipe->id }})" role="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fa fa-edit mr-1"></i> Cambiar
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $recipe->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $recipe->unit->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($recipe->cant, 4) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($recipe->cant / $recipe->product->factor, 4) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> 
        </div>
    </div>

    <!-- Modal Cambio de Dieta -->
    <div class="fixed inset-0 z-10 flex items-center justify-center bg-opacity-50 bg-gray-900 {{ $showModal ? 'block' : 'hidden' }}">
        <div class="bg-white p-6 rounded shadow-md w-full md:w-1/3">
            <h2 class="text-xl font-semibold mb-4">Editar cantidad {{ $mvto_selected ? ($mvto_selected->concept == 50201 ? 'de la dieta preparada' : 'del ingrediente ya preparado') : '' }}</h2>
    
            <div class="mb-4">
                <p><strong>Producto:</strong> {{ $mvto_selected ? $mvto_selected->product->name : '' }}</p>
                <p><strong>Unidad:</strong> {{ $mvto_selected ? $mvto_selected->unit->name : '' }}</p>
            </div>
    
            {!! Form::label('cant', 'Nueva Cantidad:') !!}
            {!! Form::number('cant', null, ['class' => 'block mt-1 w-full'.($errors->has('cant') ? ' is-invalid' : ''), 'placeholder' => 'Cantidad preparada', 'step' => 0.0001, 'wire:model' => 'cant_selected']) !!}
            
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
