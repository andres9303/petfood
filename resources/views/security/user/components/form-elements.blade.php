<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('name', 'Nombre (*)') !!}
        {!! Form::text('name', null, ['class' => 'block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Nombre del usuario']) !!}
    </div>

    <div class="mt-4">
        {!! Form::label('username', 'Usuario (*)') !!}
        {!! Form::text('username', null, ['class' => 'block mt-1 w-full'.($errors->has('username') ? ' is-invalid' : ''), 'placeholder' => 'Inicio de sesión']) !!}
    </div>

    <div class="mt-4">
        {!! Form::label('email', 'Correo electrónico (*)') !!}
        {!! Form::email('email', null, ['class' => 'block mt-1 w-full'.($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'usuario@mail.com']) !!}
    </div>