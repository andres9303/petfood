<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('class', 'Categoría') !!}
        <a href="{{ route('category.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
            <i class="fa fa-plus"></i>
        </a>
        {!! Form::select('class', $categories->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('code', 'Código de la Dieta') !!}
        {!! Form::text('code', null, ['class' => 'block mt-1 w-full'.($errors->has('code') ? ' is-invalid' : ''), 'placeholder' => 'Código de la dieta']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('name', 'Nombre de la Dieta (*)') !!}
        {!! Form::text('name', null, ['class' => 'block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Nombre de la dieta']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('unit_id', 'Unidad Base (*)') !!}
        <a href="{{ route('unit.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
            <i class="fa fa-plus"></i>
        </a>
        {!! Form::select('unit_id', $units->pluck('name', 'id'), $unit_selected, ['class' => 'block mt-1 w-full']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('valueu', 'Precio de venta (*)') !!}
        {!! Form::number('valueu', null, ['class' => 'block mt-1 w-full'.($errors->has('valueu') ? ' is-invalid' : ''),'step' => '0.0001', 'placeholder' => 'Precio de venta por unidad de la dieta']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('state', 'Estado') !!}
        {!! Form::checkbox('state') !!}
    </div>
</div>
