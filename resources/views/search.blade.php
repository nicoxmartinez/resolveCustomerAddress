@component('components.layout')
<h1>Buscar Cliente</h1>
<input /><button>Buscar</button>
<p>Resultados:</p>
<ul>
    @foreach($clientes as $cliente)
        <li>
            <a href="{{ route('edit', $cliente->id_cliente) }}">{{ $cliente->nombre }}</a> - {{ $cliente->tipoCliente->nombre ?? $cliente->tipoCliente->descripcion ?? 'Tipo ' . $cliente->tipo_cliente_id }}- {{ $cliente->tipoDocumento->nombre ?? $cliente->tipoDocumento->codigo ?? 'Doc ' . $cliente->tipo_documento_id }} - {{ $cliente->documento }} - {{ $cliente->domicilio->calle }} {{ $cliente->domicilio->numero }} {{ $cliente->domicilio->localidad }} {{ $cliente->domicilio->provincia }}
        </li>
    @endforeach
</ul>
@endComponent
