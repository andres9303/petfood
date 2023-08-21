<x-app-layout>
    <x-slot name="header">
        Reportes - Preparaciones - Producción Dietas
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </x-slot>

        <x-slot name="content">
            <div class="mt-4 text-gray-500">
                <h2 class="text-2xl font-semibold mb-4">Dietas</h2>
                <livewire:table.report.production-table/>
            </div>
            <div class="mt-4 text-gray-500">
                <h2 class="text-2xl font-semibold mb-4">Ingredientes</h2>
                <livewire:table.report.ingredient-table/>
            </div>
        </x-slot>
    </x-crud-index>
</x-app-layout>