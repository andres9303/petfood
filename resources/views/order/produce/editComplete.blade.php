<x-app-layout>
    <x-slot name="header">
        Gestión de Pedidos - Preparaciones - Completar Preparación {{ $produce->num }}
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
                    {!! Form::model($produce, ['route' => ['produce.update.complete', ['produce' => $produce]], 'method' => 'put' ]) !!}
                        <div class="mt-6">
                            {!! Form::button('<i class="fa fa-check-double mr-2"></i> Completar Preparación', ['type' => 'submit', 'class' => 'mb-2 inline-flex items-center bg-green-500 block border border-transparent rounded-md hover:bg-green-600 text-white py-2 px-4']) !!}
                        </div>
                    {!! Form::close() !!}
                    @livewire('forms.produce-form', ['produce' => $produce])
                </div>
            </div>
        </x-slot>
    </x-crud-index>
</x-app-layout>