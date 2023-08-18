<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('name', 'Nombre de grupo') !!}
        {!! Form::text('name', null, ['class' => 'block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Nombre del grupo']) !!}
    </div>
</div>