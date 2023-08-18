<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('name', 'Nombre del item (*)') !!}
        {!! Form::text('name', null, ['class' => 'block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Nombre del item']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('text', 'Texto') !!}
        {!! Form::text('text', null, ['class' => 'block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Texto del item']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('order', 'Orden') !!}
        {!! Form::number('order', null, ['class' => 'block mt-1 w-full'.($errors->has('order') ? ' is-invalid' : ''), 'placeholder' => 'Orden del item']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('factor', 'Factor') !!}
        {!! Form::number('factor', null, ['class' => 'block mt-1 w-full'.($errors->has('factor') ? ' is-invalid' : ''), 'placeholder' => 'Factor del item', 'step' => '0.01']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('catalog_id', 'Lista (*)') !!}
        {!! Form::select('catalog_id', $catalogs->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('item_id', 'Item base') !!}
        {!! Form::select('item_id', $items->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
    </div>
</div>
