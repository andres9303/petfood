<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('name', 'Nombre (*)') !!}
        {!! Form::text('name', null, ['class' => 'block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Nombre del tipo de mascota']) !!}
    </div>    
    <div class="mt-4">
        {!! Form::label('state', 'Estado') !!}
        {!! Form::checkbox('state') !!}
    </div>
</div>
