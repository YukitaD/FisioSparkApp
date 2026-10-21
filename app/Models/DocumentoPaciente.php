<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoPaciente extends Model
{
    Protected $table = 'documento_pacientes';
    protected $fillable = [
        'paciente_id',
        'nombre_original',
        'ruta_archivo',
        'mime_type',
        'tamano',
    ];
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    //MOstrar tamano legible del archivo
    public function getTmanañoLegibleAttribute()
    {
        $bytes = $this->tamano ?? 0;
        if ($bytes <1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }
}
