<x-app-layout>
    <x-slot name="header">
        Mascotas - Mascotas - Historial de Mascotas - {{ $pet->name }}
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a href="{{ route('pet.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </x-slot>

        <x-slot name="content">
            <div class="flex justify-center">
                <div class="w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        <div class="bg-alternative-500 text-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4 w-full">
                            <div class="flex justify-center items-center mb-4">
                                <div class="w-32 h-32 bg-gray-300 rounded-full flex justify-center items-center">
                                    <img src="{{ $pet->image ? asset('storage/' . $pet->image->url) : asset('storage/mascota/default.png') }}" alt="Imagen {{ $pet->name }}" class="w-28 h-28 rounded-full">
                                </div>
                            </div>
                            <div class="text-center mb-4">
                                <p class="text-xl font-semibold">{{ $pet->name }}</p>
                                <p class="text-lg font-semibold">{{ $pet->race->name }}</p>                        
                                <p class="text-lg font-semibold">{{ $pet->person->name }}</p>   
                                <p class="text-sm mt-2">
                                    <span class="px-2 py-1 rounded-full {{ $pet->state == 1 ? 'bg-green-500' : 'bg-red-500' }}">
                                        {{ $pet->state == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </p>                     
                                <p class="text-sm font-semibold">Fecha Nacimiento</p>
                                <p class="text-sm">[{{ $pet->date ?? '-' }}]</p>
                                <p class="text-sm font-semibold">¿Vive en casa o en apartamento?</p>
                                <p class="text-sm">[{{ $pet->living ?? '-' }}]</p>
                                <p class="text-sm font-semibold">¿Convive con otras mascotas?</p>
                                <p class="text-sm">[{{ $pet->sib == 1 ? 'Si' : 'NO' }}]</p>
                                <p class="text-sm font-semibold">Dieta actual</p>
                                <p class="text-sm">[{{ $pet->diet ?? '-' }}]</p>
                                <p class="text-sm font-semibold">Nivel de actividad</p>
                                <p class="text-sm">[{{ $pet->exercise ?? '-' }}]</p>
                                <p class="text-sm font-semibold">Alergias</p>
                                <p class="text-sm">[{{ $pet->allergy ?? '-' }}]</p>
                                <p class="text-sm font-semibold">Vacunación</p>
                                <p class="text-sm">[{{ $pet->vaccine ?? '-' }}]</p>
                                <p class="text-sm font-semibold">Desparasitación</p>
                                <p class="text-sm">[{{ $pet->deworming ?? '-' }}]</p>
                                <p class="text-sm font-semibold">Problemas previos de salud</p>
                                <p class="text-sm">[{{ $pet->health ?? '-' }}]</p>
                                <p class="text-sm font-semibold">Estado reproductivo</p>
                                <p class="text-sm">[{{ $pet->reproductive ?? '-' }}]</p>
                                <p class="text-sm font-semibold">Peso</p>
                                <p class="text-sm">[{{ number_format($pet->weight) ?? '0' }} kg]</p>
                                <p class="text-sm font-semibold">Observaciones</p>
                                <p class="text-sm">[{{ $pet->text ?? '-' }}]</p>
                            </div>
                        </div>
        
                        <div class="bg-gray-50 text-gray-500 shadow-md rounded px-8 pt-6 pb-8 mb-4 w-full md:col-span-2 block">
                            <a href="{{ route('pet.trace.create', ['pet' => $pet]) }}" class="inline-flex items-center justify-center mb-4 px-4 py-2 text-sm font-medium text-white bg-alternative-500 border border-transparent rounded-md hover:bg-alternative-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-alternative-500" role="button">
                                <i class="fa fa-plus"></i>&nbsp; Agregar Seguimiento
                            </a>
                            <h2 class="text-2xl font-semibold mb-4">Pedidos</h2>
                            <p class="mb-4">Historial de pedidos realizados.</p>                            
                            <livewire:table.pet.order-table pet_id="{{ $pet->id }}"/>

                            @foreach ($traces as $trace)
                            <div class="flex items-start space-x-4 mt-4">
                                <div class="flex-shrink-0">
                                <div class="flex items-center mt-4 justify-center w-16 h-16 text-alternative-400 hover:text-alternative-500 rounded-full bg-alternative-500 hover:bg-alternative-400">
                                    <i class="fas fa-book text-3xl"></i>
                                </div>
                                </div>
                                <div class="bg-alternative-50 hover:bg-alternative-100 rounded-lg p-4 flex-grow">
                                    <h2 class="text-xl font-semibold mb-4">{{ $trace->item->name }}: {{ \Carbon\Carbon::parse($trace->date)->format('d/m/Y') }}</h2>
                                    <p class="font-medium text-gray-700">{{ $trace->text }}</p>
                                    <div class="mt-2 flex justify-end">
                                        <a href="{{ route('pet.trace.edit', ['pet' => $pet, 'trace' => $trace]) }}" class="mt-1 text-sm font-bold text-alternative-500 hover:text-alternative-600">
                                            Editar Seguimiento
                                        </a>
                                        <form style="display:inline;" action="{{ route('pet.trace.destroy', ['pet' => $pet, 'trace' => $trace]) }}" method="post">
                                            @method('delete')
                                            @csrf                                        
                                            <button type="submit" class="ml-4 text-sm font-bold text-red-500 hover:text-red-600" title="Eliminar">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>                        
                    </div>
                </div>
            </div>
        </x-slot>
    </x-crud-index>
</x-app-layout>