@extends('templates.app')

@section('title', 'Editar cuenta | AeroControl')

@section('content')
    <div class="w-full flex items-center justify-center">
        <div class="w-full max-w-xl px-4">
            @include('shared.account-form', [
                'action' => route('cuenta.actualizar'),
                'method' => 'PATCH',
                'submitText' => 'Guardar cambios',
                'user' => $user,
                'eyebrow' => 'Cuenta',
                'heading' => 'Editar cuenta',
                'description' => 'Actualiza tus datos personales desde una sola tarjeta centrada y responsive.',
            ])
        </div>
    </div>
@endsection
