<div> 
    <div class="cita-card">
        <div class="cita-card__inner">

            {{-- Cabecera --}}
            <div class="cita-card__header">
                <div>
                    <h2 class="cita-card__title">Agendar Nueva Cita</h2>
                    <p class="cita-card__subtitle">Programa una consulta para tus pacientes</p>
                </div>
                <a href="{{ route('home') }}" class="cita-card__back-link">
                    ← Volver
                </a>
            </div>

            {{-- Mensaje de éxito --}}
            @if (session()->has('message'))
                <div class="cita-card__message cita-card__message--success">
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            {{-- Mensaje de error --}}
            @if (session()->has('error'))
                <div class="cita-card__message cita-card__message--error">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Formulario --}}
            <form wire:submit="agendarCita" class="cita-card__form">

                {{-- Campo: Paciente --}}
                <div class="cita-card__field">
                    <label class="cita-card__label">Paciente *</label>
                    <select wire:model="paciente_id" class="cita-card__input">
                        <option value="">-- Seleccione un Paciente --</option>
                        @foreach($this->pacientes as $paciente)
                            <option value="{{ $paciente->id }}">
                                {{ $paciente->nombre_completo }} ({{ $paciente->cedula_completa }})
                            </option>
                        @endforeach
                    </select>
                    @error('paciente_id') <span class="cita-card__error">{{ $message }}</span> @enderror
                </div>

                {{-- Campo: Fecha y Hora --}}
                <div class="cita-card__field">
                    <label class="cita-card__label">Fecha y Hora *</label>
                    <input type="datetime-local"
                           wire:model="fecha_hora"
                           min="{{ $minFecha }}"
                           class="cita-card__input">
                    @error('fecha_hora') <span class="cita-card__error">{{ $message }}</span> @enderror
                </div>

                {{-- Campo: Motivo --}}
                <div class="cita-card__field">
                    <label class="cita-card__label">Motivo de la Cita *</label>
                    <input type="text"
                           wire:model="motivo"
                           placeholder="Ej: Control Post-Operatorio"
                           class="cita-card__input">
                    @error('motivo') <span class="cita-card__error">{{ $message }}</span> @enderror
                </div>

                {{-- Campo: Notas --}}
                <div class="cita-card__field">
                    <label class="cita-card__label">Notas Adicionales</label>
                    <textarea wire:model="notas"
                              rows="3"
                              placeholder="Indicaciones previas, observaciones..."
                              class="cita-card__input"></textarea>
                    @error('notas') <span class="cita-card__error">{{ $message }}</span> @enderror
                </div>

                {{-- Botón de envío --}}
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="agendarCita"
                        class="cita-card__submit">
                    <span wire:loading.remove wire:target="agendarCita">Agendar Cita</span>
                    <span wire:loading wire:target="agendarCita">Guardando...</span>
                </button>
            </form>
        </div>
    </div>
</div>