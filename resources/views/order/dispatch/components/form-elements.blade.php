<div class="mt-4">
    {!! Form::label('date', 'Fecha despachos (*)') !!}
    {!! Form::date('date', $now, ['class' => 'block mt-1 w-full text-gray-500'.($errors->has('date') ? ' is-invalid' : '')]) !!}
</div>
<div class="mt-4">
    {!! Form::label('text', 'Observaciones') !!}
    {!! Form::textarea('text', null, ['class' => 'block mt-1 w-full text-gray-500'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) !!}
</div>