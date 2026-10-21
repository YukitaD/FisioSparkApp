<div> 
    <div class="facturas">

        @if(session('message'))
            <div class="flash">{{ session('message') }}</div>
        @endif

        <div class="facturas-header">
            <h1 class="facturas-title">
                Facturación
                @if($paciente)
                    <span class="facturas-subtitle">— {{ $paciente->nombre_completo }}</span>
                @endif
            </h1>
            <a href="{{ route('home') }}" class="btn btn-secondary">← Volver al inicio</a>
        </div>

        <section class="card">
            <div class="filtros">
                <select wire:model.live="mesSeleccionado" class="input">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ now()->setMonth($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
                <select wire:model.live="anioSeleccionado" class="input">
                    @for($a = now()->year - 2; $a <= now()->year; $a++)
                        <option value="{{ $a }}">{{ $a }}</option>
                    @endfor
                </select>
            </div>

            <div class="resumen-grid">
                <div class="resumen-card resumen-blue">
                    <p class="resumen-label">Citas cobradas</p>
                    <p class="resumen-valor">{{ $this->resumen['cantidad'] }}</p>
                </div>
                <div class="resumen-card resumen-green">
                    <p class="resumen-label">Total en USD</p>
                    <p class="resumen-valor">${{ number_format($this->resumen['totalUSD'], 2) }}</p>
                </div>
                <div class="resumen-card resumen-yellow">
                    <p class="resumen-label">Total en Bs</p>
                    <p class="resumen-valor">Bs {{ number_format($this->resumen['totalVES'], 2) }}</p>
                </div>
            </div>
        </section>

        @if($paciente)
            <section class="card">
                <h2 class="card-title">Registrar pago</h2>

                @if($this->citasPendientes->isEmpty())
                    <p class="empty">No hay citas pendientes de cobro para este paciente.</p>
                @else
                    <div class="form-pago">
                        <div class="form-group">
                            <label class="label">Cita</label>
                            <select wire:model="citaSeleccionada" class="input">
                                <option value="">Seleccione una cita</option>
                                @foreach($this->citasPendientes as $cita)
                                    <option value="{{ $cita->id }}">
                                        {{ $cita->fecha_hora?->format('d/m/Y H:i') }} — {{ $cita->motivo ?? 'Sin motivo' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('citaSeleccionada') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="label">Monto</label>
                            <input type="number" step="0.01" wire:model="monto" class="input">
                            @error('monto') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="label">Moneda</label>
                            <select wire:model="moneda" class="input">
                                <option value="USD">USD ($)</option>
                                <option value="VES">VES (Bs)</option>
                            </select>
                        </div>

                        <button wire:click="registrarPago" class="btn btn-primary">
                            Registrar pago
                        </button>
                    </div>
                @endif
            </section>
        @endif

        <section class="card">
            <h2 class="card-title">Facturas registradas</h2>

            @if($this->facturas->isEmpty())
                <p class="empty">No hay facturas registradas.</p>
            @else
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Fecha pago</th>
                            @if(!$paciente)
                                <th>Paciente</th>
                            @endif
                            <th>Cita</th>
                            <th>Monto</th>
                            <th>Moneda</th>
                            @if(auth()->user()->isAdmin())
                                <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->facturas as $factura)
                            <tr>
                                <td>{{ $factura->fecha_pago?->format('d/m/Y') }}</td>
                                @if(!$paciente)
                                    <td>{{ $factura->paciente?->nombre_completo ?? 'N/A' }}</td>
                                @endif
                                <td>
                                    {{ $factura->cita ? $factura->cita->fecha_hora?->format('d/m/Y H:i') : '—' }}
                                </td>
                                <td>{{ number_format($factura->monto, 2) }}</td>
                                <td>{{ $factura->moneda }}</td>
                                @if(auth()->user()->isAdmin())
                                    <td>
                                        <button wire:click="eliminarFactura({{ $factura->id }})"
                                                wire:confirm="¿Eliminar esta factura?"
                                                class="btn btn-danger">
                                            Eliminar
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
</div>