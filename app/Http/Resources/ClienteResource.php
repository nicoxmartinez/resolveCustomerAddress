<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_cliente'     => $this->id_cliente,
            'nombre'         => $this->nombre,
            'tipo_cliente'   => [
                'id_tipo_cliente' => $this->tipoCliente->id_tipo_cliente,
                'descripcion'     => $this->tipoCliente->descripcion,
            ],
            'tipo_documento' => [
                'id_tipo_documento' => $this->tipoDocumento->id_tipo_documento,
                'codigo'           => $this->tipoDocumento->sigla ?? $this->tipoDocumento->codigo,
                'descripcion'      => $this->tipoDocumento->nombre ?? $this->tipoDocumento->descripcion,
            ],
            'documento'      => $this->documento,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'domicilio'      => [
                'id_domicilio' => $this->domicilio->id_domicilio,
                'calle'        => $this->domicilio->calle,
                'numero'       => $this->domicilio->numero,
                'localidad'    => $this->domicilio->localidad,
                'barrio'       => $this->domicilio->barrio,
                'entre_calles' => $this->domicilio->entre_calles,
                'cp'           => $this->domicilio->cp,
                'provincia'    => $this->domicilio->provincia,
                'pais'         => $this->domicilio->pais,
                'latitud'      => $this->domicilio->latitud,
                'longitud'     => $this->domicilio->longitud,
            ],
        ];
    }
}
