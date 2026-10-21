<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Cita;
use App\Models\Paciente;
use Carbon\Carbon;

new class extends Component
{
    public $paciente_id = '';
    public $fecha_hora = '';
    public $motivo = '';
    public $notas = '';
    public $minFecha;

    protected function rules(): array
    {
        return [
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_hora'  => 'required|date|after:now',
            'motivo'      => 'required|string|max:255',
            'notas'       => 'nullable|string|max:500',
        ];
    }

    protected function messages(): array
    {
        return [
            'paciente_id.required' => 'Debe seleccionar un paciente.',
            'paciente_id.exists'   => 'El paciente seleccionado no existe.',
            'fecha_hora.required'  => 'La fecha y hora son obligatorias.',
            'fecha_hora.after'     => 'La cita debe ser programada para una fecha futura.',
            'motivo.required'      => 'El motivo de la cita es obligatorio.',
            'motivo.max'           => 'El motivo no puede exceder los 255 caracteres.',
        ];
    }

    public function mount()
    {
        $this->minFecha = Carbon::now()->addMinutes(30)->format('Y-m-d\TH:i');
        $this->fecha_hora = $this->minFecha;
    }

    public function agendarCita()
    {
        $this->validate();

        try {
            Cita::create([
                'paciente_id' => $this->paciente_id,
                'fecha_hora'  => Carbon::parse($this->fecha_hora),
                'motivo'      => $this->motivo,
                'notas'       => $this->notas,
                'estado'      => 'pendiente',
            ]);

            $paciente = Paciente::find($this->paciente_id);
            $nombrePaciente = $paciente ? $paciente->nombre_completo : '';

            $fechaFormateada = Carbon::parse($this->fecha_hora)->format('d/m/Y \a \l\a\s H:i');
            session()->flash('message', "✅ Cita agendada con éxito para {$nombrePaciente} el {$fechaFormateada}");

            $this->reset(['paciente_id', 'fecha_hora', 'motivo', 'notas']);
            $this->minFecha = Carbon::now()->addMinutes(30)->format('Y-m-d\TH:i');
            $this->fecha_hora = $this->minFecha;

            $this->dispatch('cita-agendada');

        } catch (\Illuminate\Database\QueryException $e) {
            session()->flash('error', 'Error al agendar la cita. Por favor, intenta nuevamente.');
            logger()->error('Error al agendar cita: ' . $e->getMessage());
        }
    }

    #[Computed]
    public function pacientes()
    {
        return Paciente::orderBy('nombres', 'asc')->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
};