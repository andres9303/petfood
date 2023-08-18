<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('name', 'Nombre de la categoría de productos (*)') !!}
        {!! Form::text('name', null, ['class' => 'block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Nombre de la categoría de productos']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('text', 'Observación') !!}
        {!! Form::text('text', null, ['class' => 'block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Observación']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('order', 'Orden') !!}
        {!! Form::number('order', null, ['class' => 'block mt-1 w-full'.($errors->has('order') ? ' is-invalid' : ''), 'placeholder' => 'Orden']) !!}
    </div>
</div>
