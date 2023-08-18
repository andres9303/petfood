<div class="text-gray-500">
    <div class="mt-4">
        {!! Form::label('race_id', 'Raza mascota (*)') !!}
        <a href="{{ route('race.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
            <i class="fa fa-plus"></i>
        </a>
        {!! Form::select('race_id', $races->pluck('fullRace', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
    </div>    
    <div class="mt-4">
        {!! Form::label('name', 'Nombre Mascota (*)') !!}
        {!! Form::text('name', null, ['class' => 'block mt-1 w-full'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Nombre de la mascota']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('person_id', 'Propietario (*)') !!}
        <a href="{{ route('client.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" role="button">
            <i class="fa fa-plus"></i>
        </a>
        {!! Form::select('person_id', $persons->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('date', 'Fecha Nacimiento') !!}
        {!! Form::date('date', null, ['class' => 'block mt-1 w-full'.($errors->has('date') ? ' is-invalid' : '')]) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('living', 'Vive en casa o en apartamento') !!}
        {!! Form::text('living', null, ['class' => 'block mt-1 w-full'.($errors->has('living') ? ' is-invalid' : ''), 'placeholder' => 'Vive en casa o en apartamento']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('sib', 'Convive con otras mascotas') !!}
        {!! Form::checkbox('sib') !!}
    </div>
    <div class="mt-4">
        {!! Form::label('diet', 'Dieta actual') !!}
        {!! Form::textarea('diet', null, ['class' => 'block mt-1 w-full'.($errors->has('diet') ? ' is-invalid' : ''), 'placeholder' => 'Con que está siendo alimentado, que come entre comidas, cuantas veces al día está comiendo']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('exercise', 'Nivel de actividad') !!}
        {!! Form::textarea('exercise', null, ['class' => 'block mt-1 w-full'.($errors->has('exercise') ? ' is-invalid' : ''), 'placeholder' => 'Si sale a caminar durante el día y por cuanto tiempo, si va a guarderías, si va a la finca los fines de semana']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('allergy', 'Alergias') !!}
        {!! Form::text('allergy', null, ['class' => 'block mt-1 w-full'.($errors->has('allergy') ? ' is-invalid' : ''), 'placeholder' => 'Alergias (de piel, respiratorias, a medicamentos, alimentarias)']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('vaccine', 'Vacunación') !!}
        {!! Form::text('vaccine', null, ['class' => 'block mt-1 w-full'.($errors->has('vaccine') ? ' is-invalid' : ''), 'placeholder' => 'Vacunación (al día o vencida)']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('deworming', 'Desparasitación') !!}
        {!! Form::text('deworming', null, ['class' => 'block mt-1 w-full'.($errors->has('deworming') ? ' is-invalid' : ''), 'placeholder' => 'Desparasitación (al día o vencida)']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('health', 'Problemas previos de salud') !!}
        {!! Form::textarea('health', null, ['class' => 'block mt-1 w-full'.($errors->has('health') ? ' is-invalid' : ''), 'placeholder' => 'Problemas previos de salud']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('reproductive', 'Estado reproductivo') !!}
        {!! Form::text('reproductive', null, ['class' => 'block mt-1 w-full'.($errors->has('reproductive') ? ' is-invalid' : ''), 'placeholder' => 'Estado reproductivo (entero o esterilizada/castrado)']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('weight', 'Peso') !!}
        {!! Form::number('weight', null, ['class' => 'block mt-1 w-full'.($errors->has('weight') ? ' is-invalid' : ''), 'placeholder' => 'Peso']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('text', 'Observaciones') !!}
        {!! Form::textarea('text', null, ['class' => 'block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) !!}
    </div>
    <div class="mt-4">
        {!! Form::label('state', 'Estado') !!}
        {!! Form::checkbox('state') !!}
    </div>
</div>
