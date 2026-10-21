    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modificar Paciente: {{ $paciente->nombres }} {{ $paciente->apellidos }}
        </h2>
    </x-slot>

    <div> 
        <div class="paciente-editar">
            {{-- Cabecera --}}
            <div class="paciente-editar__header">
                <div>
                    <h2 class="paciente-editar__title">Modificar Datos del Paciente</h2>
                    <p class="paciente-editar__subtitle">Actualiza la información del expediente clínico</p>
                </div>
                <a href="{{ route('paciente-detalles', $paciente->id) }}" class="paciente-editar__back-link">
                    <svg class="paciente-editar__back-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al paciente
                </a>
            </div>

            {{-- Mensaje de éxito --}}
            @if (session()->has('message'))
                <div class="paciente-editar__message">
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            {{-- Mensaje de error --}}
            @if (session()->has('error'))
                <div class="paciente-editar__message paciente-editar__message--error">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Formulario --}}
            <form wire:submit="actualizar" class="paciente-editar__form">
                {{-- Fila 1: Nombres y Apellidos --}}
                <div class="paciente-editar__row">
                    <div class="paciente-editar__field">
                        <label class="paciente-editar__label">Nombres</label>
                        <input type="text" wire:model="nombres" class="paciente-editar__input @error('nombres') paciente-editar__input--error @enderror">
                        @error('nombres') <span class="paciente-editar__error">{{ $message }}</span> @enderror
                    </div>

                    <div class="paciente-editar__field">
                        <label class="paciente-editar__label">Apellidos</label>
                        <input type="text" wire:model="apellidos" class="paciente-editar__input @error('apellidos') paciente-editar__input--error @enderror">
                        @error('apellidos') <span class="paciente-editar__error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Fila 2: Cédula y RIF --}}
                <div class="paciente-editar__row">
                    <div class="paciente-editar__field">
                        <label class="paciente-editar__label">Cédula de Identidad</label>
                        <div class="paciente-editar__compound-input">
                            <select wire:model="tipo_cedula" class="paciente-editar__input paciente-editar__select-prefix">
                                <option value="V">V</option>
                                <option value="E">E</option>
                            </select>
                            <input type="text" wire:model="cedula" class="paciente-editar__input @error('cedula') paciente-editar__input--error @enderror">
                        </div>
                        @error('cedula') <span class="paciente-editar__error">{{ $message }}</span> @enderror
                    </div>

                    <div class="paciente-editar__field">
                        <label class="paciente-editar__label">RIF</label>
                        <div class="paciente-editar__compound-input">
                            <select wire:model="tipo_rif" class="paciente-editar__input paciente-editar__select-prefix">
                                <option value="V">V (Natural)</option>
                                <option value="J">J (Jurídico)</option>
                                <option value="E">E (Extranjero)</option>
                            </select>
                            <input type="text" wire:model="rif" class="paciente-editar__input @error('rif') paciente-editar__input--error @enderror">
                        </div>
                        @error('rif') <span class="paciente-editar__error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Fila 3: Teléfono y Correo --}}
                <div class="paciente-editar__row">
                    <div class="paciente-editar__field">
                        <label class="paciente-editar__label">Teléfono</label>
                        <input type="text" wire:model="telefono" class="paciente-editar__input @error('telefono') paciente-editar__input--error @enderror">
                        @error('telefono') <span class="paciente-editar__error">{{ $message }}</span> @enderror
                    </div>

                    <div class="paciente-editar__field">
                        <label class="paciente-editar__label">Correo Electrónico</label>
                        <input type="email" wire:model="correo" class="paciente-editar__input @error('correo') paciente-editar__input--error @enderror">
                        @error('correo') <span class="paciente-editar__error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Fila 4: Fecha de Nacimiento y Género --}}
                <div class="paciente-editar__row">
                    <div class="paciente-editar__field">
                        <label class="paciente-editar__label">Fecha de Nacimiento</label>
                        <input type="date" wire:model="fecha_nacimiento" class="paciente-editar__input @error('fecha_nacimiento') paciente-editar__input--error @enderror">
                        @error('fecha_nacimiento') <span class="paciente-editar__error">{{ $message }}</span> @enderror
                    </div>

                    <div class="paciente-editar__field">
                        <label class="paciente-editar__label">Género</label>
                        <select wire:model="genero" class="paciente-editar__input @error('genero') paciente-editar__input--error @enderror">
                            <option value="">Seleccione...</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                        </select>
                        @error('genero') <span class="paciente-editar__error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Dirección --}}
                <div class="paciente-editar__field">
                    <label class="paciente-editar__label">Dirección de Habitación</label>
                    <textarea wire:model="direccion" rows="3" placeholder="Ciudad, Municipio, Sector..." class="paciente-editar__input @error('direccion') paciente-editar__input--error @enderror"></textarea>
                    @error('direccion') <span class="paciente-editar__error">{{ $message }}</span> @enderror
                </div>

                {{-- Acciones / Botones --}}
                <div class="paciente-editar__actions">
                    <a href="{{ route('paciente-detalles', $paciente->id) }}" class="paciente-editar__btn paciente-editar__btn--cancel">
                        Cancelar
                    </a>
                    <button type="submit" class="paciente-editar__btn paciente-editar__btn--submit">
                        <span wire:loading.remove wire:target="actualizar">Actualizar Paciente</span>
                        <span wire:loading wire:target="actualizar" class="paciente-editar__loading">Guardando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
