<?php

use Livewire\Component;
use App\Models\Paciente;
use Illuminate\Validation\Rule;

new class extends Component
{
    //Propiedades formulario
    public $nombres = '';
    public $apellidos = '';
    public $tipo_cedula = 'V';
    public $cedula = '';
    
    //propiedades RIF
    public $tipo_rif = 'V'; // 'V' (Natural), 'J' (Jurídico), 'E' (Extranjero)
    public $rif = '';

    public $correo = '';
    public $telefono = '';
    public $fecha_nacimiento = '';
    public $genero = '';
    public $direccion = '';

    protected function rules()
    {
        return [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_cedula' => 'required|in:V,E',
            'cedula' => [
                'required', 'string', 'max:40',
                Rule::unique('pacientes')->where(function ($query) {
                    return $query->where('tipo_cedula', $this->tipo_cedula);
                })
            ],
            // Validación RIF
            'tipo_rif' => 'required|in:V,J,E',
            'rif' => [
                'required', 'string', 'max:20',
                Rule::unique('pacientes')->where(function ($query) {
                    return $query->where('tipo_rif', $this->tipo_rif);
                })
            ],
            'correo' => 'required|email|unique:pacientes,correo',
            'telefono' => 'required|string|unique:pacientes,telefono|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|in:masculino,femenino',
            'direccion' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'required' => 'Este campo es obligatorio.',
        'email' => 'El formato de correo no es válido.',
        'unique' => 'Este dato ya se encuentra registrado.',
        'in' => 'La opción seleccionada no es válida.',
        'cedula.unique' => 'Esta combinación de tipo y número de cédula ya existe.',
        'rif.unique' => 'Este número de RIF ya se encuentra registrado para ese tipo.',
    ];

    public function updated($propertyName)
    {
        // Revalidación cruzada si cambian los selectores de tipo
        if ($propertyName === 'tipo_cedula') {
            $this->validateOnly('cedula');
        } elseif ($propertyName === 'tipo_rif') {
            $this->validateOnly('rif');
        } else {
            $this->validateOnly($propertyName);
        }
    }

    public function guardar()
    {
        $validatedData = $this->validate();

        paciente::create($validatedData);

        return redirect()->to('/index');
    }
};
