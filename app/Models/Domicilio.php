<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Domicilio extends Model
{
    use HasFactory;

    protected $table = 'domicilio';
    protected $primaryKey = 'id_domicilio';
    
    public $timestamps = false;

    protected $fillable = [
        'calle',
        'numero',
        'localidad',
        'barrio',
        'entre_calles',
        'cp',
        'provincia',
        'pais',
        'latitud',
        'longitud',
    ];

    protected $casts = [
        'numero' => 'integer',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'id_domicilio', 'id_domicilio');
    }
}
