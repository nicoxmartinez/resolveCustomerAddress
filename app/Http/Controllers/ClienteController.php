<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Domicilio;
use App\Models\TipoCliente;
use App\Models\TipoDocumento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Muestra la vista de búsqueda de clientes (Index).
     */
    public function index()
    {
        $clientes = Cliente::with(['tipoCliente', 'tipoDocumento', 'domicilio'])->get();

        return view('search', compact('clientes'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $tiposCliente = TipoCliente::all();
        $tiposDocumento = TipoDocumento::all();

        return view('create', compact('tiposCliente', 'tiposDocumento'));
    }

    /**
     * Almacena el recurso recién creado en el almacenamiento.
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
            'domicilio.partido'      => 'nullable|string|max:255',
            'domicilio.entre_calles' => 'nullable|string|max:255',
            'domicilio.codigo_postal'=> 'nullable|string|max:255',
        ]);

        $domicilioData = $datos['domicilio'];
        $direccionString = $domicilioData['calle'] . ' ' . $domicilioData['numero'] . ' ' . $domicilioData['localidad'];

        $queryParams = array_filter([
            'direccion'    => $direccionString,
            'departamento' => $domicilioData['partido'],
            'provincia'    => $domicilioData['provincia'],
        ]);

        $response = Http::get('https://apis.datos.gob.ar/georef/api/v2.0/direcciones', $queryParams);

        // Muestra la URL completa de la solicitud
        //echo "URL de la solicitud: " . $response->effectiveUri() . "\n"; 

        if ($response->failed()) {
            return response()->json([
                'error' => 'No se pudo comunicar con el servicio de Georef'
            ], 502);
        }

        $geoData = $response->json();
        if (!empty($geoData['direcciones'])) {
            $primerResultado = $geoData['direcciones'][0];

            switch (true) {
                case 'isset($primerResultado[calle])':
                    $domicilioData['calle'] = $primerResultado['calle']['nombre'] ?? null;
                case 'isset($primerResultado[localidad])':
                    $domicilioData['localidad'] = $primerResultado['localidad']['nombre'] ?? null;
                case 'isset($primerResultado[departamento])':
                    $domicilioData['partido'] = $primerResultado['departamento']['nombre'] ?? null;
                case 'isset($primerResultado[entre_calles])':
                    $cruce1 = $primerResultado['calle_cruce_1']['nombre'] ?? null;
                    $cruce2 = $primerResultado['calle_cruce_2']['nombre'] ?? null;
                    if ($cruce1 && $cruce2) {
                        $domicilioData['entre_calles'] = "{$cruce1} y {$cruce2}";
                    } elseif ($cruce1 || $cruce2) {
                        $domicilioData['entre_calles'] = $cruce1 ?? $cruce2;
                    }
                case 'isset($primerResultado[provincia])':
                    $domicilioData['provincia'] = $primerResultado['provincia']['nombre'] ?? null;
                case 'isset($primerResultado[latitud])':
                    $domicilioData['latitud'] = $primerResultado['ubicacion']['lat'] ?? null;
                case 'isset($primerResultado[longitud])':
                    $domicilioData['longitud'] = $primerResultado['ubicacion']['lon'] ?? null;
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
     * Muestra el recurso especificado.
     */
    public function show(string $id): JsonResponse
    {
        $data = Cliente::with(['tipoCliente', 'tipoDocumento', 'domicilio'])->findOrFail($id);

        $cliente = new ClienteResource($data);

        return response()->json($cliente, 200);
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit(string $id)
    {
        $cliente = Cliente::with(['tipoCliente', 'tipoDocumento', 'domicilio'])->findOrFail($id);
        $tiposCliente = TipoCliente::all();
        $tiposDocumento = TipoDocumento::all();

        return view('edit', compact('cliente', 'tiposCliente', 'tiposDocumento'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
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
                Rule::unique('cliente', 'documento')->ignore($cliente->id_cliente, 'id_cliente'),
            ],

            // Validaciones de Domicilio
            'domicilio'              => 'required|array',
            'domicilio.id_domicilio' => 'required|exists:domicilio,id_domicilio',
            'domicilio.calle'        => 'required|string|max:255',
            'domicilio.numero'       => 'required|integer',
            'domicilio.localidad'    => 'required|string|max:255',
            'domicilio.provincia'    => 'required|string|max:255',
            'domicilio.pais'         => 'required|string|max:255',
            'domicilio.partido'      => 'nullable|string|max:255',
            'domicilio.entre_calles' => 'nullable|string|max:255',
            'domicilio.codigo_postal' => 'nullable|string|max:255',
        ]);

        $domicilioData = $datos['domicilio'];
        $direccionString = $domicilioData['calle'] . ' ' . $domicilioData['numero'] . ' ' . ($domicilioData['partido'] ?? '');

        $queryParams = array_filter([
            'direccion'    => trim($direccionString),
            'departamento' => $domicilioData['partido'],
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

            switch (true) {
                case 'isset($primerResultado[calle])':
                    $domicilioData['calle'] = $primerResultado['calle']['nombre'] ?? null;
                case 'isset($primerResultado[localidad])':
                    $domicilioData['localidad'] = $primerResultado['localidad']['nombre'] ?? null;
                case 'isset($primerResultado[departamento])':
                    $domicilioData['partido'] = $primerResultado['departamento']['nombre'] ?? null;
                case 'isset($primerResultado[entre_calles])':
                    $cruce1 = $primerResultado['calle_cruce_1']['nombre'] ?? null;
                    $cruce2 = $primerResultado['calle_cruce_2']['nombre'] ?? null;
                    if ($cruce1 && $cruce2) {
                        $domicilioData['entre_calles'] = "{$cruce1} y {$cruce2}";
                    } elseif ($cruce1 || $cruce2) {
                        $domicilioData['entre_calles'] = $cruce1 ?? $cruce2;
                    }
                case 'isset($primerResultado[provincia])':
                    $domicilioData['provincia'] = $primerResultado['provincia']['nombre'] ?? null;
                case 'isset($primerResultado[latitud])':
                    $domicilioData['latitud'] = $primerResultado['ubicacion']['lat'] ?? null;
                case 'isset($primerResultado[longitud])':
                    $domicilioData['longitud'] = $primerResultado['ubicacion']['lon'] ?? null;
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
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $domicilioId = $cliente->id_domicilio;

        DB::transaction(function () use ($cliente, $domicilioId) {
            $cliente->delete();
            Domicilio::where('id_domicilio', $domicilioId)->delete();
        });

        return response()->json(['message' => 'Cliente eliminado correctamente'], 200);
    }

    /**
     * Obtiene un cliente por su número de documento.
     */
    public function getClienteByDocumento(string $documento): JsonResponse
    {
        $data = Cliente::with(['tipoCliente', 'tipoDocumento', 'domicilio'])
            ->where('documento', $documento)
            ->firstOrFail();

        $cliente = new ClienteResource($data);

        return response()->json($cliente, 200);
    }
}
