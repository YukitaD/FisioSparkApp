<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $fillable = [
        'nombres',
        'apellidos',
        'tipo_cedula',
        'cedula',
        'tipo_rif',
        'rif',
        'correo',
        'telefono',
        'fecha_nacimiento',
        'genero',
        'direccion'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];


    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

   
    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'paciente_id');
    }

   
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    public function getCedulaCompletaAttribute(): string
    {
        return "{$this->tipo_cedula}-{$this->cedula}";
    }

   
    public function getRifCompletoAttribute(): string
    {
        return "{$this->tipo_rif}-{$this->rif}";
    }

    public function getEdadAttribute(): ?int
    {
        if (!$this->fecha_nacimiento || $this->fecha_nacimiento->isFuture()) {
            return null;
        }
        return $this->fecha_nacimiento->age;
    }

       public function getFechaNacimientoFormateadaAttribute(): string
    {
        return $this->fecha_nacimiento ? $this->fecha_nacimiento->format('d/m/Y') : '';
    }

       public function getGeneroTextoAttribute(): string
    {
        $generos = [
            'masculino' => 'Masculino',
            'femenino' => 'Femenino',
        ];
        return $generos[$this->genero] ?? 'No especificado';
    }

   
    public function getInicialesAttribute(): string
    {
        $iniciales = '';
        if ($this->nombres) {
            $iniciales .= strtoupper(substr($this->nombres, 0, 1));
        }
        if ($this->apellidos) {
            $iniciales .= strtoupper(substr($this->apellidos, 0, 1));
        }
        return $iniciales;
    }

    public function getContactoCompletoAttribute(): string
    {
        $contacto = $this->telefono;
        if ($this->correo) {
            $contacto .= " | {$this->correo}";
        }
        return $contacto;
    }

       public function scopeSearch($query, $termino)
    {
        if (empty($termino)) {
            return $query;
        }

        return $query->where(function ($q) use ($termino) {
            $q->where('nombres', 'like', "%{$termino}%")
                ->orWhere('apellidos', 'like', "%{$termino}%")
                ->orWhere('cedula', 'like', "%{$termino}%")
                ->orWhere('correo', 'like', "%{$termino}%")
                ->orWhere('telefono', 'like', "%{$termino}%");
        });
    }

    
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('nombres', $direction)
            ->orderBy('apellidos', $direction);
    }

  
    public function scopeWithcitas($query)
    {
        return $query->has('citas');
    }

   
    public function scopeWithoutcitas($query)
    {
        return $query->doesntHave('citas');
    }

    public function tienecitas(): bool
    {
        return $this->citas()->exists();
    }

 
    public function contarcitas(): int
    {
        return $this->citas()->count();
    }

    
    public function proximacita()
    {
        return $this->citas()
            ->where('fecha_hora', '>', now())
            ->orderBy('fecha_hora', 'asc')
            ->first();
    }

    public function ultimacita()
    {
        return $this->citas->sortByDesc('fecha_hora')->first();
    }

    public function citasPendientes()
    {
        return $this->citas->where('estado','pendiente');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoPaciente::class, 'paciente_id');
    }
}