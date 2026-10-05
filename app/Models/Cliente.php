<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';
    protected $hidden = [
        'id_tipo_cliente',
        'id_tipo_documento',
        'id_domicilio',
    ];

    protected $fillable = [
        'nombre',
        'id_tipo_cliente',
        'id_tipo_documento',
        'documento',
        'id_domicilio',
    ];

    public function tipoCliente(): BelongsTo
    {
        return $this->belongsTo(TipoCliente::class, 'id_tipo_cliente', 'id_tipo_cliente');
    }

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento', 'id_tipo_documento');
    }

    public function domicilio(): BelongsTo
    {
        return $this->belongsTo(Domicilio::class, 'id_domicilio', 'id_domicilio');
    }
}
