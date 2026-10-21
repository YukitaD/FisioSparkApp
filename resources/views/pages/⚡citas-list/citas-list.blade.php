    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Citas Programadas') }}
        </h2>
    </x-slot>

    <div> {{-- Único elemento raíz de Livewire --}}
        <div class="citas-lista">
            {{-- Cabecera --}}
            <div class="citas-lista__header">
                <div>
                    <h2 class="citas-lista__title">Citas Programadas</h2>
                    <p class="citas-lista__subtitle">Lleva el control diario de tus consultas clínicas</p>
                </div>
                <div>
                    <a href="{{ route('home') }}" class="citas-lista__btn-back">
                        ← Volver al inicio
                    </a>
                </div>
            </div>

            {{-- Mensaje de éxito --}}
            @if (session()->has('message-citas'))
                <div class="citas-lista__message">
                    {{ session('message-citas') }}
                </div>
            @endif

            {{-- Filtros --}}
            <div class="citas-lista__filters">
                <div>
                    <label class="citas-lista__filter-label">Filtrar por Fecha</label>
                    <input type="date" wire:model.live="filtro_fecha" class="citas-lista__filter-input">
                </div>
                <div>
                    <label class="citas-lista__filter-label">Filtrar por Estado</label>
                    <select wire:model.live="filtro_estado" class="citas-lista__filter-input">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendientes 🟡</option>
                        <option value="asistida">Asistidas 🟢</option>
                        <option value="cancelada">Canceladas 🔴</option>
                        <option value="no_asistio">No Asistió ⚫</option>
                    </select>
                </div>
            </div>

            {{-- Tabla de citas --}}
            <div class="citas-lista__table-wrapper">
                @if($this->citas->isEmpty())
                    <div class="citas-lista__empty">
                        <p>No hay citas registradas para los filtros seleccionados.</p>
                    </div>
                @else
                    <table class="citas-lista__table">
                        <thead class="citas-lista__table-head">
                            <tr>
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="citas-lista__table-body">
                            @foreach($this->citas as $cita)
                                <tr class="citas-lista__table-row">
                                    {{-- Hora --}}
                                    <td>
                                        {{ $cita->hora_formateada ?? \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}
                                    </td>
                                    
                                    {{-- Paciente --}}
                                    <td>
                                        <div class="citas-lista__patient-name">
                                            {{ $cita->paciente?->nombre_completo ?? 'Paciente no asignado' }}
                                        </div>
                                        <div class="citas-lista__patient-id">
                                            {{ $cita->paciente?->cedula_completa }}
                                        </div>
                                    </td>
                                    
                                    {{-- Motivo --}}
                                    <td>
                                        {{ $cita->motivo }}
                                    </td>
                                    
                                    {{-- Estado --}}
                                    <td>
                                        <span class="citas-lista__badge citas-lista__badge--{{ $cita->estado }}">
                                            {{ $cita->estado_badge['icon'] ?? '' }} {{ ucfirst($cita->estado) }}
                                        </span>
                                    </td>
                                    
                                    {{-- Acciones --}}
                                    <td>
                                        <select 
                                            class="citas-lista__action-select"
                                            wire:change="cambiarEstado({{ $cita->id }}, $event.target.value)">
                                            <option value="">-- Cambiar Estado --</option>
                                            <option value="pendiente">Marcar Pendiente</option>
                                            <option value="asistida">Marcar Asistida</option>
                                            <option value="cancelada">Marcar Cancelada</option>
                                            <option value="no_asistio">Marcar No Asistió</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
