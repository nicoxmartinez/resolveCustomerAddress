<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tipo_documento';
    protected $primaryKey = 'id_tipo_documento';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'descripcion',
    ];

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'id_tipo_documento', 'id_tipo_documento');
    }
}
