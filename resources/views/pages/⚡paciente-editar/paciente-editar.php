<?php

use Livewire\Component;
use App\Models\Paciente;
use Illuminate\Validation\Rule;

new class extends Component
{
    public ?Paciente $paciente = null;

    // Propiedades del formulario
    public $nombres;
    public $apellidos;
    public $tipo_cedula = 'V';
    public $cedula;
    public $tipo_rif = 'V';
    public $rif;
    public $telefono;
    public $correo;
    public $fecha_nacimiento;
    public $genero;
    public $direccion;

    public function mount($paciente = null): void
    {
        // Soporte para id o para Route Model Binding directo
        if ($paciente instanceof Paciente) {
            $this->paciente = $paciente;
        } elseif ($paciente) {
            $this->paciente = Paciente::findOrFail($paciente);
        } else {
            $this->paciente = request()->route('paciente') instanceof Paciente
                ? request()->route('paciente')
                : Paciente::findOrFail(request()->route('paciente'));
        }

        // Cargar datos en el formulario
        $this->nombres          = $this->paciente->nombres;
        $this->apellidos        = $this->paciente->apellidos;
        $this->tipo_cedula      = $this->paciente->tipo_cedula ?? 'V';
        $this->cedula          = $this->paciente->cedula;
        $this->tipo_rif         = $this->paciente->tipo_rif ?? 'V';
        $this->rif             = $this->paciente->rif;
        $this->telefono        = $this->paciente->telefono;
        $this->correo          = $this->paciente->correo;
        $this->fecha_nacimiento = $this->paciente->fecha_nacimiento?->format('Y-m-d');
        $this->genero          = $this->paciente->genero;
        $this->direccion       = $this->paciente->direccion;
    }

    public function rules(): array
    {
        return [
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'tipo_cedula'      => 'required|string|in:V,E',
            'cedula'           => ['required', 'string', Rule::unique('pacientes', 'cedula')->ignore($this->paciente->id)],
            'tipo_rif'         => 'nullable|string|in:V,J,E',
            'rif'              => ['nullable', 'string', Rule::unique('pacientes', 'rif')->ignore($this->paciente->id)],
            'telefono'         => 'required|string|max:20',
            'correo'           => ['nullable', 'email', Rule::unique('pacientes', 'correo')->ignore($this->paciente->id)],
            'fecha_nacimiento' => 'nullable|date',
            'genero'           => 'nullable|string|in:masculino,femenino',
            'direccion'        => 'nullable|string|max:500',
        ];
    }

    public function actualizar()
    {
        $datosValidados = $this->validate();
        
        $this->paciente->update($datosValidados);

        session()->flash('message', 'Paciente actualizado correctamente.');

        // Redirige directamente a la vista de detalles del paciente
        return redirect()->route('paciente-detalles', $this->paciente->id);
    }
};