<?php

namespace App\Http\Controllers;

use App\Models\TipoCliente;
use Illuminate\Http\JsonResponse;

class TipoClienteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        $tiposCliente = TipoCliente::all();
        if ($tiposCliente->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron resultados',
            ], 404);
        }

        return response()->json($tiposCliente, 200);
    }
}
