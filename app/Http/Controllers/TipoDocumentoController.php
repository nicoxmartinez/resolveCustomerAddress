<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        $tiposDocumento = TipoDocumento::all();

        if ($tiposDocumento->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron resultados'
            ], 404);
        }

        return response()->json($tiposDocumento, 200);
    }
}
