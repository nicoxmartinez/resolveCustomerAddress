@component('components.layout')
    <h1>Editar Cliente {{ $cliente->nombre }}</h1>
        @if(session('success'))
            <div style="color: green; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="color: red; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <form method="POST" action="{{ route('cliente.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">
        <label for="tipo_cliente">Tipo Cliente:</label><br/>
        <select id="tipo_cliente" name="tipo_cliente[id_tipo_cliente]">
            <option value="">Seleccione...</option>
            @foreach($tiposCliente as $tipo)
                <option value="{{ $tipo->id_tipo_cliente }}" {{ $cliente->id_tipo_cliente == $tipo->id_tipo_cliente ? 'selected' : '' }}>
                    {{ $tipo->nombre ?? $tipo->descripcion ?? 'Tipo ' . $tipo->id_tipo_cliente }}
                </option>
            @endforeach
        </select><br/><br/>
        <label for="nombre">Nombre:</label><br/>
        <input type="text" name="nombre" value="{{ $cliente->nombre }}"><br/><br/>
        <label for="tipo_documento">Tipo Documento:</label><br/>
        <select id="tipo_documento" name="tipo_documento[id_tipo_documento]">
            <option value="">Seleccione...</option>
            @foreach($tiposDocumento as $doc)
                <option value="{{ $doc->id_tipo_documento }}" {{ $cliente->id_tipo_documento == $doc->id_tipo_documento ? 'selected' : '' }}>
                    {{ $doc->nombre ?? $doc->codigo ?? 'Doc ' . $doc->id_tipo_documento }}
                </option>
            @endforeach
        </select><br/><br/>
        <label for="documento">Documento:</label><br/>
        <input type="text" name="documento" value="{{ $cliente->documento }}"><br/><br/>
        <h2>Dirección</h2>
        <input type="hidden" name="domicilio[id_domicilio]" value="{{ $cliente->domicilio->id_domicilio ?? '' }}">
        <label for="calle">Calle:</label><br/>
        <input type="text" name="domicilio[calle]" value="{{ $cliente->domicilio->calle }}"><br/><br/>
        <label for="numero">Número:</label><br/>
        <input type="number" name="domicilio[numero]" value="{{ $cliente->domicilio->numero }}"><br/><br/>
        <label for="localidad">Localidad:</label><br/>
        <input type="text" name="domicilio[localidad]" value="{{ $cliente->domicilio->localidad }}"><br/><br/>
        <label for="partido">Partido:</label><br/>
        <input type="text" name="domicilio[partido]" value="{{ $cliente->domicilio->partido }}"><br/><br/>
        <label for="codigo_postal">Código Postal:</label><br/>
        <input type="text" id="codigo_postal" name="domicilio[codigo_postal]" value="{{ $cliente->domicilio->codigo_postal }}"><br/><br/>
        <label for="entre_calles">Entre Calles:</label><br/>
        <input type="text" id="entre_calles" name="domicilio[entre_calles]" value="{{ $cliente->domicilio->entre_calles }}"><br/><br/>
        <label for="provincia">Provincia:</label><br/>
        <input type="text" name="domicilio[provincia]" value="{{ $cliente->domicilio->provincia }}"><br/><br/>
        <label for="pais">País:</label><br/>
        <input type="text" name="domicilio[pais]" value="{{ $cliente->domicilio->pais }}"><br/><br/>
        <button type="submit">Guardar</button>
    </form>    
@endComponent