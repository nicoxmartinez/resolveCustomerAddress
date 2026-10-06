<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Domicilio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with(['tipoCliente', 'tipoDocumento', 'domicilio'])->get();

        return ClienteResource::collection($clientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            // Validaciones de Cliente
            'nombre'                           => 'required|string|max:255',
            'tipo_cliente'                     => 'required|array',
            'tipo_cliente.id_tipo_cliente'     => 'required|exists:tipo_cliente,id_tipo_cliente',
            'tipo_documento'                   => 'required|array',
            'tipo_documento.id_tipo_documento' => 'required|exists:tipo_documento,id_tipo_documento',
            'documento'                        => 'required|string|unique:cliente,documento',

            // Validaciones de Domicilio
            'domicilio'              => 'required|array',
            'domicilio.calle'        => 'required|string|max:255',
            'domicilio.numero'       => 'required|integer',
            'domicilio.localidad'    => 'required|string|max:255',
            'domicilio.provincia'    => 'required|string|max:255',
            'domicilio.pais'         => 'required|string|max:255',
            'domicilio.barrio'       => 'nullable|string|max:255',
            'domicilio.entre_calles' => 'nullable|string|max:255',
            'domicilio.cp'           => 'nullable|string|max:255',
        ]);

        $domicilioData = $datos['domicilio'];
        $direccionString = $domicilioData['calle'] . ' ' . $domicilioData['numero'] . ' ' . $domicilioData['barrio'];

        $queryParams = array_filter([
            'direccion'    => $direccionString,
            'departamento' => $domicilioData['localidad'],
            'provincia'    => $domicilioData['provincia'],
        ]);

        $response = Http::get('https://apis.datos.gob.ar/georef/api/v2.0/direcciones', $queryParams);

        if ($response->failed()) {
            return response()->json([
                'error' => 'No se pudo comunicar con el servicio de Georef'
            ], 502);
        }

        $geoData = $response->json();
        if (!empty($geoData['direcciones'])) {
            $primerResultado = $geoData['direcciones'][0];

            $domicilioData['latitud'] = $primerResultado['ubicacion']['lat'] ?? null;
            $domicilioData['longitud'] = $primerResultado['ubicacion']['lon'] ?? null;

            if (empty($domicilioData['barrio'])) {
                $barrio = $primerResultado['localidad']['nombre'] ?? null;
                $domicilioData['barrio'] = "{$barrio}";
            }

            if (empty($domicilioData['entre_calles'])) {
                $cruce1 = $primerResultado['calle_cruce_1']['nombre'] ?? null;
                $cruce2 = $primerResultado['calle_cruce_2']['nombre'] ?? null;
                if ($cruce1 && $cruce2) {
                    $domicilioData['entre_calles'] = "{$cruce1} y {$cruce2}";
                } elseif ($cruce1 || $cruce2) {
                    $domicilioData['entre_calles'] = $cruce1 ?? $cruce2;
                }
            }
        } else {
            return response()->json([
                'error' => 'No se pudo encontrar el domicilio'
            ], 422);
        }

        $cliente = DB::transaction(function () use ($datos, $domicilioData) {
            $domicilio = Domicilio::create($domicilioData);

            return Cliente::create([
                'nombre'            => $datos['nombre'],
                'id_tipo_cliente'   => $datos['tipo_cliente']['id_tipo_cliente'],
                'id_tipo_documento' => $datos['tipo_documento']['id_tipo_documento'],
                'documento'         => $datos['documento'],
                'id_domicilio'      => $domicilio->id_domicilio,
            ]);
        });

        $cliente->load(['domicilio', 'tipoCliente', 'tipoDocumento']);

        return response()->json(new ClienteResource($cliente), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $data = Cliente::with(['tipoCliente', 'tipoDocumento', 'domicilio'])->findOrFail($id);

        $cliente = new ClienteResource($data);

        return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $cliente = Cliente::findOrFail($request['id_cliente']);

        $datos = $request->validate([
            // Validaciones de Cliente
            'nombre'                           => 'required|string|max:255',
            'tipo_cliente'                     => 'required|array',
            'tipo_cliente.id_tipo_cliente'     => 'required|exists:tipo_cliente,id_tipo_cliente',
            'tipo_documento'                   => 'required|array',
            'tipo_documento.id_tipo_documento' => 'required|exists:tipo_documento,id_tipo_documento',
            'documento'                        => [
                'required',
                'string',
                Rule::unique('cliente', 'documento'),
            ],

            // Validaciones de Domicilio
            'domicilio'              => 'required|array',
            'domicilio.id_domicilio' => 'required|exists:domicilio,id_domicilio',
            'domicilio.calle'        => 'required|string|max:255',
            'domicilio.numero'       => 'required|integer',
            'domicilio.localidad'    => 'required|string|max:255',
            'domicilio.provincia'    => 'required|string|max:255',
            'domicilio.pais'         => 'required|string|max:255',
            'domicilio.barrio'       => 'nullable|string|max:255',
            'domicilio.entre_calles' => 'nullable|string|max:255',
            'domicilio.cp'           => 'nullable|string|max:255',
        ]);

        $domicilioData = $datos['domicilio'];
        $direccionString = $domicilioData['calle'] . ' ' . $domicilioData['numero'] . ' ' . ($domicilioData['barrio'] ?? '');

        $queryParams = array_filter([
            'direccion'    => trim($direccionString),
            'departamento' => $domicilioData['localidad'],
            'provincia'    => $domicilioData['provincia'],
        ]);

        $response = Http::get(env('GEOREF_API_URL'), $queryParams);

        if ($response->failed()) {
            return response()->json([
                'error' => 'No se pudo comunicar con el servicio de Georef'
            ], 502);
        }

        $geoData = $response->json();

        if (!empty($geoData['direcciones'])) {
            $primerResultado = $geoData['direcciones'][0];

            $domicilioData['latitud'] = $primerResultado['ubicacion']['lat'] ?? null;
            $domicilioData['longitud'] = $primerResultado['ubicacion']['lon'] ?? null;

            if (empty($domicilioData['barrio'])) {
                $barrio = $primerResultado['localidad']['nombre'] ?? null;
                $domicilioData['barrio'] = $barrio ? "{$barrio}" : null;
            }

            if (empty($domicilioData['entre_calles'])) {
                $cruce1 = $primerResultado['calle_cruce_1']['nombre'] ?? null;
                $cruce2 = $primerResultado['calle_cruce_2']['nombre'] ?? null;
                if ($cruce1 && $cruce2) {
                    $domicilioData['entre_calles'] = "{$cruce1} y {$cruce2}";
                } elseif ($cruce1 || $cruce2) {
                    $domicilioData['entre_calles'] = $cruce1 ?? $cruce2;
                }
            }
        } else {
            return response()->json([
                'error' => 'No se pudo encontrar el domicilio'
            ], 422);
        }

        DB::transaction(function () use ($cliente, $datos, $domicilioData) {
            $idDomicilio = $domicilioData['id_domicilio'];
            $domicilio = Domicilio::findOrFail($idDomicilio);
            $domicilio->update($domicilioData);

            $cliente->update([
                'nombre'            => $datos['nombre'],
                'id_tipo_cliente'   => $datos['tipo_cliente']['id_tipo_cliente'],
                'id_tipo_documento' => $datos['tipo_documento']['id_tipo_documento'],
                'documento'         => $datos['documento'],
                'id_domicilio'      => $domicilio->id_domicilio,
            ]);
        });

        $cliente->load(['domicilio', 'tipoCliente', 'tipoDocumento']);

        return response()->json(new ClienteResource($cliente), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
