<div class="paciente-registro">
    <div class="paciente-registro__card">
        {{-- Cabecera --}}
        <div class="paciente-registro__header">
            <div class="paciente-registro__icon-wrapper">
                <svg class="paciente-registro__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h2 class="paciente-registro__title">Registrar Nuevo Paciente</h2>
        </div>

        {{-- Alerta de éxito --}}
        @if (session()->has('success'))
            <div class="paciente-registro__alert" role="alert">
                <svg class="paciente-registro__alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Formulario --}}
        <form wire:submit.prevent="guardar" class="paciente-registro__form">
            {{-- Nombres y Apellidos --}}
            <div class="paciente-registro__row">
                <div class="paciente-registro__field">
                    <label class="paciente-registro__label">Nombres <span class="paciente-registro__required">*</span></label>
                    <input type="text" wire:model.blur="nombres" class="paciente-registro__input @error('nombres') paciente-registro__input--error @enderror">
                    @error('nombres') <span class="paciente-registro__error">{{ $message }}</span> @enderror
                </div>

                <div class="paciente-registro__field">
                    <label class="paciente-registro__label">Apellidos <span class="paciente-registro__required">*</span></label>
                    <input type="text" wire:model.blur="apellidos" class="paciente-registro__input @error('apellidos') paciente-registro__input--error @enderror">
                    @error('apellidos') <span class="paciente-registro__error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Cédula y RIF --}}
            <div class="paciente-registro__row">
                {{-- Cédula --}}
                <div class="paciente-registro__field">
                    <label class="paciente-registro__label">Cédula de Identidad <span class="paciente-registro__required">*</span></label>
                    <div class="paciente-registro__input-group">
                        <select wire:model.change="tipo_cedula" class="paciente-registro__select-prefix">
                            <option value="V">V</option>
                            <option value="E">E</option>
                        </select>
                        <input type="text" wire:model.blur="cedula" placeholder="Ej. 25123456" class="paciente-registro__input paciente-registro__input--group-right @error('cedula') paciente-registro__input--error @enderror">
                    </div>
                    @error('cedula') <span class="paciente-registro__error">{{ $message }}</span> @enderror
                </div>

                {{-- RIF --}}
                <div class="paciente-registro__field">
                    <label class="paciente-registro__label">RIF <span class="paciente-registro__required">*</span></label>
                    <div class="paciente-registro__input-group">
                        <select wire:model.change="tipo_rif" class="paciente-registro__select-prefix">
                            <option value="V">V (Natural)</option>
                            <option value="J">J (Jurídico)</option>
                            <option value="E">E (Extranjero)</option>
                        </select>
                        <input type="text" wire:model.blur="rif" placeholder="Ej. 251234560" class="paciente-registro__input paciente-registro__input--group-right @error('rif') paciente-registro__input--error @enderror">
                    </div>
                    @error('rif') <span class="paciente-registro__error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Correo y Teléfono --}}
            <div class="paciente-registro__row">
                <div class="paciente-registro__field">
                    <label class="paciente-registro__label">Correo Electrónico <span class="paciente-registro__required">*</span></label>
                    <input type="email" wire:model.blur="correo" placeholder="ejemplo@correo.com" class="paciente-registro__input @error('correo') paciente-registro__input--error @enderror">
                    @error('correo') <span class="paciente-registro__error">{{ $message }}</span> @enderror
                </div>

                <div class="paciente-registro__field">
                    <label class="paciente-registro__label">Teléfono <span class="paciente-registro__required">*</span></label>
                    <input type="text" wire:model.blur="telefono" placeholder="Ej. 04141234567" class="paciente-registro__input @error('telefono') paciente-registro__input--error @enderror">
                    @error('telefono') <span class="paciente-registro__error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Fecha de Nacimiento y Género --}}
            <div class="paciente-registro__row">
                <div class="paciente-registro__field">
                    <label class="paciente-registro__label">Fecha de Nacimiento</label>
                    <input type="date" wire:model.blur="fecha_nacimiento" class="paciente-registro__input @error('fecha_nacimiento') paciente-registro__input--error @enderror">
                    @error('fecha_nacimiento') <span class="paciente-registro__error">{{ $message }}</span> @enderror
                </div>

                <div class="paciente-registro__field">
                    <label class="paciente-registro__label">Género</label>
                    <select wire:model.blur="genero" class="paciente-registro__select @error('genero') paciente-registro__input--error @enderror">
                        <option value="">Seleccione...</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                    </select>
                    @error('genero') <span class="paciente-registro__error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Dirección --}}
            <div class="paciente-registro__field">
                <label class="paciente-registro__label">Dirección de Habitación</label>
                <textarea wire:model.blur="direccion" rows="3" placeholder="Ciudad, Municipio, Sector..." class="paciente-registro__textarea @error('direccion') paciente-registro__input--error @enderror"></textarea>
                @error('direccion') <span class="paciente-registro__error">{{ $message }}</span> @enderror
            </div>

            {{-- Botón de Envío --}}
            <div class="paciente-registro__actions">
                <button type="submit" class="paciente-registro__submit">
                    Registrar Paciente
                </button>
            </div>
        </form>
    </div>
</div>