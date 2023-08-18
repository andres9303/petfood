<div>
    <div class="text-gray-500">
        <div class="mt-4">
            {!! Form::label('code', 'Identificación - NIT (*)') !!}
            {!! Form::text('code', null, [
                'wire:model.lazy' => 'code',
                'class' => 'block mt-1 w-full'.($errors->has('code') ? ' is-invalid' : ''),
                'placeholder' => 'Identificación - NIT'
            ]) !!}
        </div>
        <div class="mt-4">
            {!! Form::label('name', 'Razón social (*)') !!}
            {!! Form::text('name', null, ['class' => 'block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Razón social', 'wire:model' => 'name']) !!}
        </div>
        <div class="mt-4">
            {!! Form::label('address', 'Dirección') !!}
            {!! Form::text('address', null, ['class' => 'block mt-1 w-full'.($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Dirección del centro de costos', 'wire:model' => 'address']) !!}
        </div>
        <div class="mt-4">
            {!! Form::label('phone', 'Teléfono') !!}
            {!! Form::number('phone', null, ['class' => 'block mt-1 w-full'.($errors->has('phone') ? ' is-invalid' : ''), 'placeholder' => 'Télefono de contacto', 'wire:model' => 'phone']) !!}
        </div>
        <div class="mt-4">
            {!! Form::label('email', 'Correo electrónico') !!}
            {!! Form::text('email', null, ['class' => 'block mt-1 w-full'.($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Correo electrónico del centro de costos', 'wire:model' => 'email']) !!}
        </div>
        <div class="mt-4">
            {!! Form::label('birth', 'Fecha Nacimiento') !!}
            {!! Form::date('birth', null, ['class' => 'block mt-1 w-full'.($errors->has('birth') ? ' is-invalid' : ''), 'wire:model' => 'birth']) !!}
        </div>
    </div>
</div>
