<x-app-layout>
    <x-slot name="header">
        Compras - Gastos - Nuevo Gasto
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a href="{{ route('bill.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </x-slot>

        <x-slot name="content">
            <div class="flex justify-center mt-8">
                <div class="w-full md:w-1/2">
                    <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <div class="mb-4">
                        {!! Form::open(['route' => 'bill.store']) !!}
                            @include('shopping.bill.components.form-elements')
                            @if ($errors->any())
                            <div class="flex items-center bg-red-100 text-red-500 text-sm font-bold px-4 py-3" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            <span class="align-middle">{{ $error }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="mt-6">
                                {!! Form::button('<i class="fa fa-save"></i> Crear', ['type' => 'submit', 'class' => 'bg-blue-500 block mt-1 w-full hover:bg-blue-600 text-white py-2 px-4']) !!}
                            </div>
                        {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-crud-index>
</x-app-layout>
