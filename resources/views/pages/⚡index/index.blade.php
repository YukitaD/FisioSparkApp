<div> 
        
    <div class="pacientes-control-wrapper"> 
        
        <div class="pacientes-control">
            {{-- Cabecera --}}
            <div class="pacientes-control__header">
                <div>
                    <h2 class="pacientes-control__title">Control de Pacientes</h2>
                    <p class="pacientes-control__subtitle">Listado general y acciones disponibles</p>
                </div>
                
                <div class="pacientes-control__actions">
                    <a href="/agenda" class="pacientes-control__btn pacientes-control__btn--agenda">
                        <svg class="pacientes-control__btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Agenda
                </a>
                    <a href="/facturas" class="pacientes-control__btn pacientes-control__btn--facturas">
                        <svg class="pacientes-control__btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Facturas
                </a>
                    <a href="/registrar-paciente" class="pacientes-control__btn pacientes-control__btn--nuevo">
                        <svg class="pacientes-control__btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nuevo
                    </a>
                    <a href="/citas" class="pacientes-control__btn pacientes-control__btn--citas">
                        <svg class="pacientes-control__btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Citas
                </a>
                </div>
            </div>

            {{-- Buscador --}}
            <div class="pacientes-control__search">
                <div class="pacientes-control__search-box">
                    <div class="pacientes-control__search-icon">
                        <svg class="pacientes-control__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="buscar" 
                           placeholder="Buscar por nombre, apellido o cédula..." 
                           class="pacientes-control__search-input">
                </div>
            </div>

            {{-- Mensajes flash --}}
            @if (session()->has('message'))
                <div class="pacientes-control__message">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="pacientes-control__message pacientes-control__message--error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tabla de pacientes --}}
            <div class="pacientes-control__table-wrapper">
                <table class="pacientes-control__table">
                    <tbody class="pacientes-control__table-body">
                        @forelse($this->pacientes as $paciente)
                            <tr class="pacientes-control__table-row pacientes-control__table-row--clickable"
                                onclick="window.location='{{ route('paciente-detalles', $paciente->id) }}'"
                                style="cursor: pointer;">
                                
                                {{-- Nombre y fecha --}}
                                <td>
                                    <div class="pacientes-control__patient-name">
                                        {{ $paciente->nombres }} {{ $paciente->apellidos }}
                                    </div>
                                    <div class="pacientes-control__patient-registry">
                                        Reg: {{ $paciente->created_at?->format('d/m/Y') }}
                                    </div>
                                </td>
                                
                                {{-- Cédula y RIF --}}
                                <td>
                                    <div class="pacientes-control__patient-id">
                                        <span class="pacientes-control__badge pacientes-control__badge--id">C.I</span>
                                        {{ $paciente->cedula }}
                                    </div>
                                    <div class="pacientes-control__patient-rif">
                                        <span class="pacientes-control__badge pacientes-control__badge--rif">RIF</span>
                                        {{ $paciente->rif ?? 'No registra' }}
                                    </div>
                                </td>
                                
                                {{-- Teléfono y correo --}}
                                <td>
                                    <div class="pacientes-control__patient-phone">{{ $paciente->telefono }}</div>
                                    <div class="pacientes-control__patient-email">{{ $paciente->correo }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="pacientes-control__empty">
                                    No se encontraron pacientes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                        
            {{-- Paginación --}}
            <div class="pacientes-control__pagination">
                {{ $this->pacientes->links() }}
            </div>
        </div>

    </div>

</div> <!-- <-- Cierre del div raíz principal -->