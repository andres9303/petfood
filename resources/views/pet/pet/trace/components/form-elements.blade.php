<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('date', 'Fecha Seguimiento (*)') !!}
        {!! Form::date('date', $now, ['class' => 'block mt-1 w-full'.($errors->has('date') ? ' is-invalid' : '')]) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('item_id', 'Tipo seguimiento (*)') !!}
        {!! Form::select('item_id', $items->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('text', 'Seguimiento (*)') !!}
        {!! Form::textarea('text', null, ['class' => 'block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) !!}
    </div>
</div>
