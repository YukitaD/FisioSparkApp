<?php

use App\Models\Cita;
use App\Models\Factura;
use App\Models\Paciente;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component
{
    public ?Paciente $paciente = null;

    // Resumen mensual
    public int $mesSeleccionado;
    public int $anioSeleccionado;

    // Formulario de Registro de pago
    public ?int $citaSeleccionada = null;
    public string $moneda = 'USD';
    public string $monto = '25.00';

    public function mount(?int $pacienteId = null): void
    {
        if ($pacienteId) {
            $this->paciente = Paciente::find($pacienteId);
        } elseif (request()->route('paciente') instanceof Paciente) {
            $this->paciente = request()->route('paciente');
        } elseif (request()->route('paciente')) {
            $this->paciente = Paciente::find(request()->route('paciente'));
        }

        $this->mesSeleccionado = now()->month;
        $this->anioSeleccionado = now()->year;
    }

    // Facturas registradas
    #[Computed]
    public function facturas()
    {
        $query = Factura::with(['paciente', 'cita'])->latest('fecha_pago');
        
        if ($this->paciente) {
            $query->where('paciente_id', $this->paciente->id);
        }

        return $query->get();
    }

    // Citas del paciente pendientes de pago
    #[Computed]
    public function citasPendientes()
    {
        if (!$this->paciente) {
            return collect();
        }

        return $this->paciente->citas()
            ->whereDoesntHave('factura')
            ->orderBy('fecha_hora', 'desc')
            ->get();
    }

    // Resumen mensual de facturación
    #[Computed]
    public function resumen()
    {
        $query = Factura::whereMonth('fecha_pago', $this->mesSeleccionado)
            ->whereYear('fecha_pago', $this->anioSeleccionado);

        if ($this->paciente) {
            $query->where('paciente_id', $this->paciente->id);
        }

        return [
            'cantidad' => $query->count(),
            'totalUSD' => (clone $query)->where('moneda', 'USD')->sum('monto'),
            'totalVES' => (clone $query)->where(function ($q) {
                $q->where('moneda', 'VES')->orWhere('moneda', 'BS');
            })->sum('monto'),
            'mes'      => now()->setMonth($this->mesSeleccionado)->translatedFormat('F Y'),
        ];
    }

    // Registrar pago
    public function registrarPago(): void
    {
        $this->validate([
            'citaSeleccionada' => 'required|exists:citas,id',
            'moneda'           => 'required|in:USD,VES,BS',
            'monto'            => 'required|numeric|min:0.01',
        ], [
            'citaSeleccionada.required' => 'Debes seleccionar una cita.',
            'citaSeleccionada.exists'   => 'La cita seleccionada no es válida.',
            'monto.required'            => 'El monto es obligatorio.',
            'monto.numeric'             => 'El monto debe ser un número válido.',
        ]);

        $cita = Cita::findOrFail($this->citaSeleccionada);

        // Evitar duplicados
        if ($cita->factura()->exists()) {
            $this->addError('citaSeleccionada', 'Esta cita ya tiene un pago registrado.');
            return;
        }

        Factura::create([
            'paciente_id' => $cita->paciente_id,
            'cita_id'     => $cita->id,
            'fecha_pago'  => now(),
            'moneda'      => $this->moneda,
            'monto'       => $this->monto,
        ]);

        // Resetear campos
        $this->reset(['citaSeleccionada']);
        $this->monto = '25.00';
        $this->moneda = 'USD';

        unset($this->facturas, $this->citasPendientes, $this->resumen);

        session()->flash('message', 'Pago registrado exitosamente.');
    }

    // Eliminar factura
    public function eliminarFactura(int $facturaId): void
    {
        // Verificación adaptable de permisos
        if (method_exists(auth()->user(), 'isAdmin') && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para eliminar facturas.');
        }

        Factura::findOrFail($facturaId)->delete();
        
        unset($this->facturas, $this->resumen, $this->citasPendientes);
        session()->flash('message', 'Factura eliminada exitosamente.');
    }
};