<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('name', 'Nombre (*)') !!}
        {!! Form::text('name', null, ['class' => 'block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Nombre de la raza']) !!}
    </div>    
    <div class="mt-4">
        {!! Form::label('animal_id', 'Tipo de mascota (*)') !!}
        <a href="{{ route('animal.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
            <i class="fa fa-plus"></i>
        </a>
        {!! Form::select('animal_id', $animals->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('state', 'Estado') !!}
        {!! Form::checkbox('state') !!}
    </div>
</div>
