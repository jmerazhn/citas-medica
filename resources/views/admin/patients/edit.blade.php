<x-admin-layout title="Pacientes" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'url' => route('admin.dashboard'),
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pacientes',
        'href' => route('admin.patients.index'),
    ],
    [
        'name' => 'Editar Paciente',
    ],
]">
    <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')
        <x-wire-card class="mb-8">

            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center space-x-5">
                    <img src="{{ $patient->user->profile_photo_url }}"
                        class="h-20 w-20 rounded-full object-cover object-center" alt="{{ $patient->user->name }}">
                    <div>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $patient->user->name }}
                        </p>
                    </div>
                </div>
                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.patients.index') }}">
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit" primary>
                        <i class="fa-solid fa-check"></i>
                        Guardar Cambios
                    </x-wire-button>

                </div>
            </div>
        </x-wire-card>


        {{-- Tabs --}}
        <x-wire-card>

            <div x-data="{ tab: 'datos-personales' }">
                <div class="border-b border-default">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-body">
                        <li class="me-2">
                            <a href="#"
                                x-on:click.prevent="tab = 'datos-personales'"
                                :class="{
                                    'inline-flex items-center justify-center p-4 text-blue border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group':
                                        tab === 'datos-personales',
                                    'inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 group':
                                        tab !== 'datos-personales',
                                }"
                                >
                                <i
                                    class="fa-solid fa-user me-2"></i>
                                Datos personales
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="#"
                                x-on:click.prevent="tab = 'antecedentes'"
                                :class="{
                                    'inline-flex items-center justify-center p-4 text-blue border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500':
                                        tab === 'antecedentes',
                                    'inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300':
                                        tab !== 'antecedentes',
                                }"
                                aria-current="page">
                                <i
                                    class="fa-solid fa-file-medical me-2"></i>
                                Antecedentes
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="#"
                                x-on:click.prevent="tab = 'informacion-general'"
                                :class="{
                                    'inline-flex items-center justify-center p-4 text-blue border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500':
                                        tab === 'informacion-general',
                                    'inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300':
                                        tab !== 'informacion-general',
                                }"
                                >
                                <i
                                    class="fa-solid fa-info-circle me-2"></i>
                                Informacion General
                            </a>
                        </li>
                        <li class="me-2">
                            <a href="#"
                                x-on:click.prevent="tab = 'contacto-emergencia'"
                                :class="{
                                    'inline-flex items-center justify-center p-4 text-blue border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500':
                                        tab === 'contacto-emergencia',
                                    'inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300':
                                        tab !== 'contacto-emergencia',
                                }"
                                >
                                <i
                                    class="fa-solid fa-user-shield me-2"></i>
                                Contacto de Emergencia
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="px-4 mt-4">
                    {{-- Datos personales --}}
                    <div x-show="tab === 'datos-personales'">
                        <x-wire-alert info title="Edicion de usuario" class="mb-2">
                            <div>
                                <p>Para editar esta informacion por dirigete a el modulo de
                                    <a href="{{ route('admin.users.edit', $patient->user) }}"
                                        class="text-blue-600 hover:underline"
                                        target="_blank">Usuarios</a>
                                        perfil de {{ $patient->user->name }}.
                                </p>
                            </div>
                        </x-wire-alert>
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-500 font-semibold text-sm">
                                    Telefono:
                                </span>
                                <span class="text-gray-900 text-sm ml-1">
                                    {{ $patient->user->phone ?? 'N/A' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 font-semibold text-sm">
                                    Email:
                                </span>
                                <span class="text-gray-900 text-sm ml-1">
                                    {{ $patient->user->email ?? 'N/A' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 font-semibold text-sm">
                                    Direccion
                                </span>
                                <span class="text-gray-900 text-sm ml-1">
                                    {{ $patient->user->address ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Antecedentes --}}
                    <div x-show="tab === 'antecedentes'">
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div>
                                <x-wire-textarea
                                    label="Alergias conocidas"
                                    name="allergies">
                                    {{ old('allergies', $patient->allergies) }}
                                </x-wire-textarea>
                            </div>
                            <div>
                                <x-wire-textarea
                                    label="Enfermedades cronicas"
                                    name="chronic_conditions">
                                    {{ old('chronic_conditions', $patient->chronic_conditions) }}
                                </x-wire-textarea>
                            </div>
                            <div>
                                <x-wire-textarea
                                    label="Antecedentes quirurgicos"
                                    name="surgical_history">
                                    {{ old('surgical_history', $patient->surgical_history) }}
                                </x-wire-textarea>
                            </div>
                            <div>
                                <x-wire-textarea
                                    label="Historial medico familiar"
                                    name="family_medical_history">
                                    {{ old('family_medical_history', $patient->family_medical_history) }}
                                </x-wire-textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Informacion General --}}
                    <div x-show="tab === 'informacion-general'">
                        <x-wire-native-select
                            label="Tipo de Sangre"
                            class="mb-4"
                            name="blood_type_id">
                            <option value="">
                                Seleccione un tipo de sangre
                            </option>
                            @foreach ($bloodTypes as $bloodType)
                                <option value="{{ $bloodType->id }}" @selected($bloodType->id === $patient->blood_type_id)>
                                    {{ $bloodType->name }}
                                </option>
                            @endforeach
                        </x-wire-native-select>

                        <x-wire-textarea
                            label="Observaciones"
                            name="observations">
                            {{ old('observations', $patient->observations) }}
                        </x-wire-textarea>
                            
                    </div>

                    {{-- Contacto de Emergencia --}}
                    <div x-show="tab === 'contacto-emergencia'">
                        <div class="space-y-4">
                            <x-wire-input
                                label="Nombre del contacto de emergencia"
                                name="emergency_contact_name"
                                type="text"
                                value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                                class="w-full" />
                            <x-wire-input
                                label="Telefono del contacto de emergencia"
                                name="emergency_contact_phone"
                                type="text"
                                value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                                class="w-full" />
                            <x-wire-input
                                label="Relacion con el contacto de emergencia"
                                name="emergency_contact_relationship"
                                type="text"
                                value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}"
                                class="w-full" />

                        </div>
                    </div>

                </div>
            </div>
        </x-wire-card>


    </form>
</x-admin-layout>
