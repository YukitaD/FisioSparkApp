<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Paciente;

new class extends Component
{
    use WithPagination;

    public $buscar = '';

    public function updatingBuscar(): void
    {
        $this->resetPage();
    }

    public function eliminar(int $id): void
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();
        session()->flash('message', 'Paciente eliminado correctamente');
    }

    #[Computed]
    public function pacientes()
    {
        $terminoBusqueda = trim($this->buscar);
        
        if (empty($terminoBusqueda)) {
            return Paciente::latest()->paginate(10);
        }
        
        return Paciente::where(function ($query) use ($terminoBusqueda) {
            $query->where('nombres', 'like', '%' . $terminoBusqueda . '%')
                ->orWhere('apellidos', 'like', '%' . $terminoBusqueda . '%')
                ->orWhere('cedula', 'like', '%' . $terminoBusqueda . '%');
        })
        ->latest()
        ->paginate(10);
    }
};