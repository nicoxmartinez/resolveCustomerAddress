document.addEventListener('DOMContentLoaded', function () {
    const tipoClienteSelect = document.getElementById('tipo_cliente');
    const tipoDocumentoSelect = document.getElementById('tipo_documento');

    if (tipoClienteSelect && tipoDocumentoSelect) {
        function verificarTipoCliente() {
            // Si el tipo de cliente es Persona Jurídica
            if (tipoClienteSelect.value === '2') {
                tipoDocumentoSelect.value = '2'; // Forzamos CUIT
            }
        }

        // Escuchar cuando el usuario cambie el select en pantalla
        tipoClienteSelect.addEventListener('change', verificarTipoCliente);

        verificarTipoCliente();
    }
});