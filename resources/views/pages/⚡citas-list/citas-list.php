<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use App\Models\Cita;
use Carbon\Carbon;

new class extends Component
{
    public $filtro_estado = '';
    public $filtro_fecha = '';

    public function mount()
    {
        $this->filtro_fecha = Carbon::today()->format('Y-m-d');
    }

    #[On('cita-agendada')]
    public function actualizarLista()
    {
        unset($this->citas);
        session()->flash('message-citas', '📋 ¡Nueva cita agendada!');
    }

    public function cambiarEstado($citaId, $nuevoEstado)
    {
        if (empty($nuevoEstado)) {
            return;
        }

        $cita = Cita::findOrFail($citaId);
        $cita->update(['estado' => $nuevoEstado]);
        
        session()->flash('message-citas', '✅ Estado de la cita actualizado.');
        
        unset($this->citas);
    }

    #[Computed(persist: false)]
    public function citas()
    {
        $query = Cita::with('paciente')->orderBy('fecha_hora', 'asc');

        if ($this->filtro_estado) {
            $query->where('estado', $this->filtro_estado);
        }

        if ($this->filtro_fecha) {
            $query->whereDate('fecha_hora', $this->filtro_fecha);
        }

        return $query->get();
    }
};