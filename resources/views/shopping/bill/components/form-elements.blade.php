<div class="mt-4">
    {!! Form::label('code', 'Código Factura') !!}
    {!! Form::text('code', null, ['class' => 'block mt-1 w-full'.($errors->has('code') ? ' is-invalid' : ''), 'placeholder' => 'Código de la Factura']) !!}
</div>
<div class="mt-4">
    {!! Form::label('num', 'Número Factura (*)') !!}
    {!! Form::number('num', null, ['class' => 'block mt-1 w-full'.($errors->has('num') ? ' is-invalid' : ''), 'placeholder' => 'Número de la Factura']) !!}
</div>
<div class="mt-4">
    {!! Form::label('date', 'Fecha (*)') !!}
    {!! Form::date('date', $now, ['class' => 'block mt-1 w-full'.($errors->has('date') ? ' is-invalid' : '')]) !!}
</div>
<div class="mt-4">
    {!! Form::label('person_id', 'Proveedor (*)') !!}
    <a href="{{ route('supplier.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
        <i class="fa fa-plus"></i>
    </a>
    {!! Form::select('person_id', $persons->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
</div>
<div class="mt-4">
    {!! Form::label('product_id', 'Producto (*)') !!}
    <a href="{{ route('product.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
        <i class="fa fa-plus"></i>
    </a>
    {!! Form::select('mvtos[0][product_id]', $products->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
</div>  
<div class="mt-4">
    {!! Form::label('unit_id', 'Unidad (*)') !!}
    <a href="{{ route('unit.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
        <i class="fa fa-plus"></i>
    </a>
    {!! Form::select('mvtos[0][unit_id]', $units->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
</div> 
<div class="mt-4">
    {!! Form::label('cant', 'Cantidad (*)') !!}
    {!! Form::number('mvtos[0][cant]', null, ['class' => 'block mt-1 w-full'.($errors->has('cant') ? ' is-invalid' : ''),'step' => '0.0001', 'placeholder' => 'Cantidad']) !!}
</div>
<div class="mt-4">
    {!! Form::label('valueu', 'Valor Unitario (*)') !!}
    {!! Form::number('mvtos[0][valueu]', null, ['class' => 'block mt-1 w-full'.($errors->has('valueu') ? ' is-invalid' : ''),'step' => '0.01', 'placeholder' => 'Valor Unitario']) !!}
</div>
<div class="mt-4">
    {!! Form::label('iva', '% IVA') !!}
    {!! Form::number('mvtos[0][iva]', null, ['class' => 'block mt-1 w-full'.($errors->has('iva') ? ' is-invalid' : ''),'step' => '0.0001', 'placeholder' => 'Porcentaje de IVA']) !!}
</div>
<div class="mt-4">
    {!! Form::label('text', 'Observaciones') !!}
    {!! Form::textarea('text', null, ['class' => 'block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) !!}
</div>