<div class="mt-4">
    {!! Form::label('code', 'Código Factura') !!}
    {!! Form::text('code', null, ['class' => 'block mt-1 w-full text-gray-500'.($errors->has('code') ? ' is-invalid' : ''), 'placeholder' => 'Código de la Factura']) !!}
</div>
<div class="mt-4">
    {!! Form::label('num', 'Número Factura (*)') !!}
    {!! Form::number('num', null, ['class' => 'block mt-1 w-full text-gray-500'.($errors->has('num') ? ' is-invalid' : ''), 'placeholder' => 'Número de la Factura']) !!}
</div>
<div class="mt-4">
    {!! Form::label('date', 'Fecha (*)') !!}
    {!! Form::date('date', $now, ['class' => 'block mt-1 w-full text-gray-500'.($errors->has('date') ? ' is-invalid' : '')]) !!}
</div>
<div class="mt-4">
    {!! Form::label('person_id', 'Proveedor (*)') !!}
    <a href="{{ route('supplier.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
        <i class="fa fa-plus"></i>
    </a>
    {!! Form::select('person_id', $persons->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full text-gray-500']) !!}
</div>
<div class="mt-4">
    {!! Form::label('text', 'Observaciones') !!}
    {!! Form::textarea('text', null, ['class' => 'block mt-1 w-full text-gray-500'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) !!}
</div>