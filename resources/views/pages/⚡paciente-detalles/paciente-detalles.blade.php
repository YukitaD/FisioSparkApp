    <div> {{-- Único elemento raíz obligatorio para Livewire --}}
        <div class="paciente-detalles">
            <a href="{{ route('home') }}" class="paciente-detalles__back">← Volver al listado</a>
            
            <h2>{{ $paciente->nombres }} {{ $paciente->apellidos }}</h2>
            <p>C.I: {{ $paciente->cedula }} | RIF: {{ $paciente->rif ?? 'No registra' }}</p>
            <p><strong>Teléfono:</strong> {{ $paciente->telefono }}</p>
            <p><strong>Correo:</strong> {{ $paciente->correo }}</p>

            {{-- Acciones --}}
            <div class="paciente-detalle__actions">
                <a href="{{ route('paciente-editar', $paciente->id) }}" class="btn btn-editar">Editar</a>
                <button type="button"
                        wire:click="eliminar"
                        wire:confirm="¿Estás seguro de eliminar este paciente?"
                        class="btn btn-eliminar">
                    Borrar
                </button>
            </div>

            {{-- Mensajes flash --}}
            @if (session()->has('message'))
                <div class="paciente-detalles__message">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="paciente-detalles__error-msg" style="color: red; margin-bottom: 1rem;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Sección de documentos --}}
            <div class="paciente-detalles__documentos">
                <h3>Documentos del paciente</h3>

                {{-- Formulario de subida --}}
                <form wire:submit="guardarPdf" class="paciente-detalles__upload">
                    <input type="file"
                           wire:model="archivo"
                           accept="application/pdf"
                           class="paciente-detalles__file-input">
                    
                    @error('archivo')
                        <span class="paciente-detalles__error">{{ $message }}</span>
                    @enderror

                    <button type="submit"
                            class="paciente-detalles__btn-subir"
                            wire:loading.attr="disabled"
                            wire:target="archivo,guardarPdf">
                        <span wire:loading.remove wire:target="guardarPdf">Subir PDF</span>
                        <span wire:loading wire:target="archivo">Cargando archivo...</span>
                        <span wire:loading wire:target="guardarPdf">Guardando...</span>
                    </button>
                </form>

                {{-- Listado de documentos --}}
                @if ($this->documentos->isEmpty())
                    <p class="paciente-detalles__empty">No hay documentos registrados.</p>
                @else
                    <ul class="paciente-detalles__lista">
                        @foreach ($this->documentos as $doc)
                            <li class="paciente-detalles__item">
                                <div class="paciente-detalles__item-info">
                                    <span class="paciente-detalles__item-nombre">
                                        {{ $doc->nombre_original }}
                                    </span>
                                    <span class="paciente-detalles__item-meta">
                                        {{ $doc->tamano_legible ?? 'N/D' }} — {{ $doc->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <div class="paciente-detalles__item-acciones">
                                    <button type="button"
                                            wire:click="descargar({{ $doc->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="descargar({{ $doc->id }})"
                                            class="btn btn-descargar">
                                        <span wire:loading.remove wire:target="descargar({{ $doc->id }})">Descargar</span>
                                        <span wire:loading wire:target="descargar({{ $doc->id }})">Descargando...</span>
                                    </button>
                                    <button type="button"
                                            wire:click="eliminarDocumento({{ $doc->id }})"
                                            wire:confirm="¿Eliminar este documento? Esta acción no se puede deshacer."
                                            class="btn btn-eliminar-doc">
                                        Eliminar
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
