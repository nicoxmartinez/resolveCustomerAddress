@component('components.layout')
    <h1>Registrar Nuevo Cliente</h1>

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

    <form method="POST" action="{{ route('cliente.store') }}">
        @csrf
        
        Tipo Cliente:<br/>
        <select name="tipo_cliente[id_tipo_cliente]">
            <option value="">Seleccione...</option>
            @foreach($tiposCliente as $tipo)
                <option value="{{ $tipo->id_tipo_cliente }}" {{ old('tipo_cliente.id_tipo_cliente') == $tipo->id_tipo_cliente ? 'selected' : '' }}>
                    {{ $tipo->nombre ?? $tipo->descripcion ?? 'Tipo ' . $tipo->id_tipo_cliente }}
                </option>
            @endforeach
        </select><br/><br/>

        Nombre:<br/>
        <input type="text" name="nombre" value="{{ old('nombre') }}"/><br/><br/>

        Tipo Documento:<br/>
        <select name="tipo_documento[id_tipo_documento]">
            <option value="">Seleccione...</option>
            @foreach($tiposDocumento as $doc)
                <option value="{{ $doc->id_tipo_documento }}" {{ old('tipo_documento.id_tipo_documento') == $doc->id_tipo_documento ? 'selected' : '' }}>
                    {{ $doc->nombre ?? $doc->descripcion ?? 'Doc ' . $doc->id_tipo_documento }}
                </option>
            @endforeach
        </select><br/><br/>

        Documento:<br/>
        <input type="text" name="documento" value="{{ old('documento') }}"/><br/><br/>

        <h2>Dirección</h2>
        
        Calle:<br/>
        <input type="text" name="domicilio[calle]" value="{{ old('domicilio.calle') }}"/><br/><br/>

        Número:<br/>
        <input type="number" name="domicilio[numero]" value="{{ old('domicilio.numero') }}"/><br/><br/>

        Localidad:<br/>
        <input type="text" name="domicilio[localidad]" value="{{ old('domicilio.localidad') }}"/><br/><br/>

        Barrio:<br/>
        <input type="text" name="domicilio[barrio]" value="{{ old('domicilio.barrio') }}"/><br/><br/>

        Entre Calles:<br/>
        <input type="text" name="domicilio[entre_calles]" value="{{ old('domicilio.entre_calles') }}"/><br/><br/>

        Código Postal:<br/>
        <input type="text" name="domicilio[cp]" value="{{ old('domicilio.cp') }}"/><br/><br/>

        Provincia:<br/>
        <input type="text" name="domicilio[provincia]" value="{{ old('domicilio.provincia') }}"/><br/><br/>

        País:<br/>
        <input type="text" name="domicilio[pais]" value="{{ old('domicilio.pais', 'Argentina') }}"/><br/><br/>

        <button type="submit">Guardar</button>
    </form>
@endComponent