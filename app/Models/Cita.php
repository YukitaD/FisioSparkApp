<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'fecha_hora',
        'motivo',
        'notas',
        'estado'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

  
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    
    public function factura(): HasOne
    {
        return $this->hasOne(Factura::class, 'cita_id');
    }

    
    public function getHoraFormateadaAttribute(): string
    {
        return $this->fecha_hora ? $this->fecha_hora->format('h:i A') : '';
    }

   
    public function getFechaFormateadaAttribute(): string
    {
        return $this->fecha_hora ? $this->fecha_hora->format('d/m/Y') : '';
    }

    
    public function getFechaHoraFormateadaAttribute(): string
    {
        return $this->fecha_hora ? $this->fecha_hora->format('d/m/Y h:i A') : '';
    }

    
    public function getFechaHoraInputAttribute(): string
    {
        return $this->fecha_hora ? $this->fecha_hora->format('Y-m-d\TH:i') : '';
    }

   
    public function getHora24Attribute(): string
    {
        return $this->fecha_hora ? $this->fecha_hora->format('H:i') : '';
    }

  
    public function getDiaSemanaAttribute(): string
    {
        return $this->fecha_hora ? ucfirst($this->fecha_hora->translatedFormat('l')) : '';
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('fecha_hora', $date);
    }

 
   public function scopeWithStatus($query, $estado)
    {
        return $query->when(filled($estado), fn ($q) => $q->where('estado', $estado));
    }

   
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

  
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_hora', today());
    }

   
    public function scopeFuturas($query)
    {
        return $query->where('fecha_hora', '>', now());
    }

    public function isPendiente(): bool
    {
        return $this->estado === 'pendiente';
    }

   
    public function isAsistida(): bool
    {
        return $this->estado === 'asistida';
    }

     public function isCancelada(): bool
    {
        return $this->estado === 'cancelada';
    }

      public function isHoy(): bool
    {
        return $this->fecha_hora ? $this->fecha_hora->isToday() : false;
    }

      public function isFutura(): bool
    {
        return $this->fecha_hora ? $this->fecha_hora->isFuture() : false;
    }

    public function getEstadoBadgeAttribute(): array
{
    return match ($this->estado) {
        'pendiente'  => ['color' => 'yellow', 'icon' => '🟡', 'label' => 'Pendiente'],
        'asistida'   => ['color' => 'green',  'icon' => '🟢', 'label' => 'Asistida'],
        'cancelada'  => ['color' => 'red',    'icon' => '🔴', 'label' => 'Cancelada'],
        'no_asistio' => ['color' => 'gray',   'icon' => '⚫', 'label' => 'No asistió'],
        default      => ['color' => 'gray',   'icon' => '⚪', 'label' => 'Desconocido'],
    };
}
}