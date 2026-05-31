@extends('templates.form_in_and_out', ['title' => 'Registro | AeroControl'])

@section('content')
    <div class="w-full flex items-center justify-center">
        <div class="w-full max-w-xl px-4">
            @include('shared.account-form', [
                'action' => !empty($modo_admin) ? route('admin.cuentas.guardar') : route('registro.guardar'),
                'submitText' => 'Guardar cliente',
                'modo_admin' => $modo_admin ?? null,
                'user' => $user ?? null,
                'eyebrow' => 'Registro',
                'heading' => !empty($modo_admin) ? 'Alta de cuenta administrativa' : 'Crear cuenta',
                'description' => !empty($modo_admin)
                    ? 'Crea una cuenta interna y muestra solo los campos que correspondan según el rol.'
                    : 'Registra una cuenta de pasajero con sus datos básicos y acceso al sistema.',
            ])
        </div>
    </div>
@endsection