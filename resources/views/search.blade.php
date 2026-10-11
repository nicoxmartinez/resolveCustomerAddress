@component('components.layout')
<h1>Buscar Cliente</h1>
<input /><button>Buscar</button>
<p>Resultados:</p>
<ul>
    @foreach($clientes as $cliente)
        <li>
            {{ $cliente->nombre }} - {{ $cliente->tipoCliente->nombre ?? $cliente->tipoCliente->descripcion ?? 'Tipo ' . $cliente->tipo_cliente_id }} - {{ $cliente->tipoDocumento->nombre ?? $cliente->tipoDocumento->descripcion ?? 'Doc ' . $cliente->tipo_documento_id }} - {{ $cliente->documento }} - {{ $cliente->domicilio->calle }} {{ $cliente->domicilio->numero }}
        </li>
    @endforeach
</ul>
@endComponent
