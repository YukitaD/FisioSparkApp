<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'paciente_id',
        'cita_id',
        'monto',
        'moneda',
        'fecha_pago',
    ];
    
    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto' => 'decimal:2'
    ];

    const MONEDAS = [
        'USD' => 'Dólar estadounidense',
        'EUR' => 'Euro',
        'VES' => 'Bolívar venezolano',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function cita()
    {
        return $this->belongsTo(cita::class);
    }
}
