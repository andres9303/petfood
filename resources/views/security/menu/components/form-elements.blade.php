<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('text', 'Nombre del formulario') !!}
        {!! Form::text('text', null, ['class' => 'block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Nombre del formulario']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('icon', 'Icono del formulario') !!}
        {!! Form::text('icon', null, ['class' => 'block mt-1 w-full'.($errors->has('icon') ? ' is-invalid' : ''), 'placeholder' => 'Nombre del grupo']) !!}
    </div>
</div>
