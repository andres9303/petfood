<x-app-layout>
    <x-slot name="header">
            Seguridad - Formularios
    </x-slot>

    <x-crud-index>
        <x-slot name="actions">            
        </x-slot>

        <x-slot name="content">
            <livewire:table.security.menu-table/>
        </x-slot>
    </x-crud-index>
</x-app-layout>