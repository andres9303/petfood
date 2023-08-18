<x-app-layout>
    <x-slot name="header">
        Gestión de Pedidos - Despachos
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">
        </x-slot>

        <x-slot name="content">
            <livewire:table.order.dispatch-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>
