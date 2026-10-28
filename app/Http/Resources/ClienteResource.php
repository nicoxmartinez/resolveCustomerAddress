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
            'idCliente'      => $this->id_cliente,
            'nombre'         => $this->nombre,
            'tipoCliente'   => [
                'idTipoCliente'   => $this->tipoCliente->id_tipo_cliente,
                'descripcion'     => $this->tipoCliente->descripcion,
            ],
            'tipoDocumento' => [
                'idTipoDocumento'  => $this->tipoDocumento->id_tipo_documento,
                'codigo'           => $this->tipoDocumento->sigla ?? $this->tipoDocumento->codigo,
                'descripcion'      => $this->tipoDocumento->nombre ?? $this->tipoDocumento->descripcion,
            ],
            'documento'      => $this->documento,
            'fechaCreacion'          => $this->created_at,
            'fechaActualizacion'     => $this->updated_at,
            'domicilio'      => [
                'idDomicilio'  => $this->domicilio->id_domicilio,
                'calle'        => $this->domicilio->calle,
                'numero'       => $this->domicilio->numero,
                'localidad'    => $this->domicilio->localidad,
                'partido'      => $this->domicilio->partido,
                'entreCalles'  => $this->domicilio->entre_calles,
                'codigoPostal' => $this->domicilio->codigo_postal,
                'provincia'    => $this->domicilio->provincia,
                'pais'         => $this->domicilio->pais,
                'latitud'      => $this->domicilio->latitud,
                'longitud'     => $this->domicilio->longitud,
            ],
        ];
    }
}
