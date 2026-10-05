<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoCliente extends Model
{
    use HasFactory;

    protected $table = 'tipo_cliente';
    protected $primaryKey = 'id_tipo_cliente';

    // Desactivamos timestamps ya que la tabla de parámetros no los requiere
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
    ];

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'id_tipo_cliente', 'id_tipo_cliente');
    }
}
