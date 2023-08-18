<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('date', 'Fecha (*)') !!}
        {!! Form::date('date', $now, ['class' => 'block mt-1 w-full'.($errors->has('date') ? ' is-invalid' : '')]) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('pet_id', 'Mascota (*)') !!}
        <a href="{{ route('pet.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
            <i class="fa fa-plus"></i>
        </a>
        {!! Form::select('pet_id', $pets->pluck('fullPet', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
    </div>  
    <div class="mt-4">
        {!! Form::label('product_id', 'Dieta (*)') !!}
        <a href="{{ route('diet.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
            <i class="fa fa-plus"></i>
        </a>
        {!! Form::select('mvtos[0][product_id]', $diets->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
    </div>  
    <div class="mt-4">
        {!! Form::label('unit_id', 'Unidad Porción (*)') !!}
        <a href="{{ route('unit.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
            <i class="fa fa-plus"></i>
        </a>
        {!! Form::select('mvtos[0][unit_id]', $units->pluck('name', 'id'), $unit_selected, ['class' => 'block mt-1 w-full']) !!}
    </div> 
    <div class="mt-4">
        {!! Form::label('cant', 'Cantidad Porción (*)') !!}
        {!! Form::number('mvtos[0][cant]', null, ['class' => 'block mt-1 w-full'.($errors->has('cant') ? ' is-invalid' : ''),'step' => '0.0001', 'placeholder' => 'Cantidad de cada porción']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('cant_src', 'Cantidad de porciones (*)') !!}
        {!! Form::number('mvtos[0][cant_src]', null, ['class' => 'block mt-1 w-full'.($errors->has('cantSrc') ? ' is-invalid' : ''),'step' => '0.0001', 'placeholder' => 'Número de porciones']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('text', 'Observaciones') !!}
        {!! Form::textarea('text', null, ['class' => 'block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) !!}
    </div>
</div>
