<?php

use App\Models\DocumentoPaciente;
use App\Models\Paciente;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads;

    public ?Paciente $paciente = null;

    public $archivo = null;

    public function mount($paciente = null): void
    {
        if ($paciente instanceof Paciente) {
            $this->paciente = $paciente;
            return;
        }

        if ($paciente) {
            $this->paciente = Paciente::findOrFail($paciente);
            return;
        }

        if (request()->route('paciente') instanceof Paciente) {
            $this->paciente = request()->route('paciente');
        } elseif (request()->route('paciente')) {
            $this->paciente = Paciente::findOrFail(request()->route('paciente'));
        }
    }

    #[Computed]
    public function documentos()
    {
        return $this->paciente?->documentos()->latest()->get() ?? collect();
    }

    protected function rules(): array
    {
        return [
            'archivo' => 'required|file|mimes:pdf|max:10240',
        ];
    }

    protected function messages(): array
    {
        return [
            'archivo.required' => 'Debes seleccionar un archivo.',
            'archivo.mimes'    => 'Solo se permiten archivos PDF.',
            'archivo.max'      => 'El archivo no debe pesar más de 10 MB.',
        ];
    }

    public function guardarPdf(): void
    {
        $this->validate();

        if (!$this->archivo || !$this->archivo->exists()) {
            $this->addError('archivo', 'El archivo temporal ha expirado o no es válido.');
            return;
        }

        $nombreOriginal = $this->archivo->getClientOriginalName();
        $mimeType       = $this->archivo->getMimeType();
        $tamano         = $this->archivo->getSize();
        $nombreSeguro   = bin2hex(random_bytes(16)) . '.' . $this->archivo->getClientOriginalExtension();
        
        $ruta = $this->archivo->storeAs(
            'documentos/' . $this->paciente->id,
            $nombreSeguro,
            'local'
        );

        $this->paciente->documentos()->create([
            'nombre_original' => $nombreOriginal,
            'ruta_archivo'    => $ruta,
            'mime_type'       => $mimeType,
            'tamano'          => $tamano,
        ]);

        $this->archivo = null;
        unset($this->documentos);
        session()->flash('message', 'Documento subido correctamente.');
    }

    public function descargar($documentoId)
    {
        $documento = DocumentoPaciente::findOrFail($documentoId);

        if (!Storage::disk('local')->exists($documento->ruta_archivo)) {
            session()->flash('error', 'El archivo no existe en el almacenamiento.');
            return;
        }
        
        return Storage::disk('local')->download($documento->ruta_archivo, $documento->nombre_original);
    }

    public function eliminarDocumento($documentoId): void
    {
        $documento = $this->paciente->documentos()->findOrFail($documentoId);
        
        if (Storage::disk('local')->exists($documento->ruta_archivo)) {
            Storage::disk('local')->delete($documento->ruta_archivo);
        }
        
        $documento->delete();
        unset($this->documentos);
        session()->flash('message', 'Documento eliminado correctamente.');
    }

    public function eliminar()
    {
        Storage::disk('local')->deleteDirectory('documentos/' . $this->paciente->id);
        $this->paciente->delete();
        session()->flash('message', 'Paciente eliminado correctamente.');
        
        return redirect()->route('home');
    }
};