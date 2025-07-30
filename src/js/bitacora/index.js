const validarFormulario = (formulario, camposAExcluir = []) => {
    let esValido = true;
    const inputs = formulario.querySelectorAll('input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])');

    inputs.forEach(input => {

        if (input.readOnly || camposAExcluir.includes(input.id) || camposAExcluir.includes(input.name)) {
            input.classList.remove('is-invalid');
            return;
        }

        if (input.value.trim() === '') {
            esValido = false;
            input.classList.add('is-invalid');

        } else {

            input.classList.remove('is-invalid');

        }
    });

    return esValido;
};


const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje.js";

// ====================================================================
// REFERENCIAS HTML
// ====================================================================
const formulario = document.getElementById('formBitacora');
const tabla = document.getElementById('tablaBitacora');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');
const modificarBtnContainer = document.getElementById('modificarBtnContainer');
const cancelarBtnContainer = document.getElementById('cancelarBtnContainer');


const btnExportar = document.getElementById('btnExportar');
const btnExportarPdf = document.getElementById('btnExportarPdf');
const btnExportarXlsx = document.getElementById('btnExportarXlsx');

const fechaInicioBusqueda = document.getElementById('fechaInicioBusqueda');
const fechaFinBusqueda = document.getElementById('fechaFinBusqueda');
const btnBuscarFecha = document.getElementById('btnBuscarFecha');
const registrosPorPaginaSelect = document.getElementById('registrosPorPagina');

const bitaTotalInput = document.getElementById('bita_total');

// ====================================================================
// CONFIGURACIÓN INICIAL DE BOTONES
// ====================================================================
if (modificarBtnContainer) modificarBtnContainer.style.display = 'none';
if (btnModificar) btnModificar.disabled = true;
if (cancelarBtnContainer) cancelarBtnContainer.style.display = 'none';if (btnCancelar) btnCancelar.disabled = true;


// ====================================================================
// INICIALIZACIÓN DE DATATABLES
// ====================================================================
const datatable = new DataTable('#tablaBitacora', {
    data: null,
    language: lenguaje,
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50, 100], 
    autoWidth: false,
    dom: 'rtip', 

    columns: [
        {
            title: 'No.',
            data: 'bita_id',
            width: '2%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Fecha',
            data: 'bita_fecha',
            width: '8%',
        },
        {
            title: 'Malware',
            data: 'bita_malware',
            width: 'auto',
        },
        {
            title: 'Phishing',
            data: 'bita_pishing',
            width: 'auto',
        },
        {
            title: 'Comando y Control',
            data: 'bita_coman_cont',
            width: 'auto',
        },
        {
            title: 'Cryptominería',
            data: 'bita_cryptomineria',
            width: 'auto',
        },
        {
            title: 'DDoS',
            data: 'bita_ddos',
            width: 'auto',
        },
        {
            title: 'Conexiones Bloqueadas',
            data: 'bita_conex_bloq',
            width: 'auto',
        },
        {
            title: 'Total',
            data: 'bita_total',
            width: 'auto',
        },
        {
            title: 'Acciones',
            data: 'bita_id',
            width: '12%',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `
                <button class='btn btn-warning modificar'
                    data-bita_id="${data}"
                    data-bita_fecha="${row.bita_fecha}"
                    data-bita_malware="${row.bita_malware}"
                    data-bita_pishing="${row.bita_pishing}"
                    data-bita_coman_cont="${row.bita_coman_cont}"
                    data-bita_cryptomineria="${row.bita_cryptomineria}"
                    data-ddos="${row.bita_ddos}"
                    data-conex_bloq="${row.bita_conex_bloq}"
                    data-bita_total="${row.bita_total}">
                    <i class='bi bi-pencil-square'></i>
                </button>
                <button class='btn btn-danger eliminar' data-bita_id="${data}">
                    <i class="bi bi-trash-fill"></i>
                </button>
            `
        }
    ]
});

// ====================================================================
// FUNCIONES DE UTILIDAD
// ====================================================================

const mostrarAlerta = (icon, title, text) => {
    Swal.fire({
        icon: icon, title: title, text: text,
        showConfirmButton: false, timer: 2000, timerProgressBar: true, background: '#fff',
        customClass: { title: 'custom-title-class', text: 'custom-text-class' }
    });
};

const calcularTotal = () => {
    const camposNumericos = [
        'bita_malware', 'bita_pishing', 'bita_coman_cont',
        'bita_cryptomineria', 'bita_ddos', 'bita_conex_bloq'
    ];
    let total = 0;
    camposNumericos.forEach(campoId => {
        const input = document.getElementById(campoId);
        total += parseInt(input.value) || 0; 
    });
    if (bitaTotalInput) bitaTotalInput.value = total;
};


// ====================================================================
// FUNCIONES DE GESTIÓN DE DATOS (CRUD)
// ====================================================================

const buscar = async (fechaInicio = null, fechaFin = null, registrosPorPagina = datatable.page.len()) => {
    Swal.fire({
        title: 'Cargando registros...', text: 'Por favor espere', allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    try {
        let url = "/App_CIBER/API/bitacora/buscar";
        const params = new URLSearchParams();

        if (fechaInicio && fechaInicio !== '') {
            params.append('fecha_inicio', fechaInicio);
        }
        if (fechaFin && fechaFin !== '') {
            params.append('fecha_fin', fechaFin);
        }

        if (params.toString()) {
            url += '?' + params.toString();
        }

        console.log("URL de búsqueda:", url); 
        const config = { method: 'GET' };
        const respuesta = await fetch(url, config);
        
        if (!respuesta.ok) {
            const errorText = await respuesta.text();
            throw new Error(`Error HTTP: ${respuesta.status} - ${respuesta.statusText}. Respuesta: ${errorText}`);
        }

        const { datos, codigo, mensaje, detalle } = await respuesta.json();
        console.log("Respuesta de la API (buscar):", { datos, codigo, mensaje, detalle });

        datatable.clear().draw(); 

        if (codigo === 1 && datos) {
            datatable.rows.add(datos).draw();
        } else if (codigo === 2) {
            Toast.fire({ icon: 'info', title: mensaje });
        } else {
            mostrarAlerta('error', 'Error al buscar', mensaje || 'Hubo un problema al cargar los datos.');
            console.error("Detalle del error de la API (buscar):", detalle);
        }

        if (datatable.page.len() !== parseInt(registrosPorPagina)) {
            datatable.page.len(parseInt(registrosPorPagina)).draw();
        }

    } catch (error) {
        console.error("Error en función buscar:", error); 
        mostrarAlerta('error', 'Error de Conexión', 'Hubo un problema de red o servidor al cargar los datos. Revisa la consola para más detalles.');
    } finally {
        Swal.close();
    }
};

const convertirFecha = (fechaStr) => {
    if (!fechaStr || !fechaStr.includes('/')) return fechaStr;
    const [dia, mes, anio] = fechaStr.split('/');
    return `${anio}-${mes}-${dia}`;
};

const buscarPorFecha = () => {
    const fechaInicioFormateada = convertirFecha(fechaInicioBusqueda.value);
    const fechaFinFormateada = convertirFecha(fechaFinBusqueda.value);

    console.log("Filtrando desde:", fechaInicioFormateada, "hasta:", fechaFinFormateada);

    buscar(fechaInicioFormateada, fechaFinFormateada, registrosPorPaginaSelect.value);
};

const cambiarRegistrosPorPagina = () => {
    buscar(fechaInicioBusqueda.value, fechaFinBusqueda.value, registrosPorPaginaSelect.value);
};


const guardar = async (e) => {
    e.preventDefault();
    if (btnGuardar) btnGuardar.disabled = true;

    if (!validarFormulario(formulario, ['bita_id', 'bita_total'])) {
        mostrarAlerta("info", "Campos vacíos", "Debe llenar todos los campos.");
        if (btnGuardar) btnGuardar.disabled = false;
        return;
    }

    // ✅ Primero definimos inputFecha antes de usarla
    const inputFecha = formulario.querySelector('[name="bita_fecha"]');
    if (inputFecha && inputFecha.value.includes('/')) {
        const partes = inputFecha.value.split('/');
        if (partes.length === 3) {
            const [dia, mes, anio] = partes;
            inputFecha.value = `${anio}-${mes}-${dia}`;
        }
    }

    try {
        const body = new FormData(formulario);
        const url = "/App_CIBER/API/bitacora/guardar";
        const config = { method: 'POST', body };

        const respuesta = await fetch(url, config);
        const textoRespuesta = await respuesta.text();

        let jsonRespuesta = {};
        try {
            jsonRespuesta = JSON.parse(textoRespuesta);
        } catch (e) {
            console.error("❌ Respuesta inválida del servidor (no es JSON):", textoRespuesta);
            mostrarAlerta('error', 'Error de Conexión', 'El servidor no respondió con datos válidos.');
            return;
        }

        const { codigo, mensaje, detalle } = jsonRespuesta;

        if (codigo === 1) {
            Toast.fire({ icon: 'success', title: mensaje });
            formulario.reset();
            if (bitaTotalInput) bitaTotalInput.value = '';
            buscar(fechaInicioBusqueda.value, fechaFinBusqueda.value, registrosPorPaginaSelect.value);
        } else {
            mostrarAlerta('error', 'Error al guardar', mensaje || 'Ocurrió un error desconocido.');
            console.error("Detalle del error de la API (guardar):", detalle);
        }

    } catch (error) {
        console.error("Error en guardar:", error);
        mostrarAlerta('error', 'Error de Conexión', 'Hubo un problema de red o servidor al guardar el registro.');
    } finally {
        if (btnGuardar) btnGuardar.disabled = false;
    }
};
 


const traerDatos = (e) => {
    const el = e.currentTarget.dataset;
    formulario.bita_id.value = el.bita_id || '';
    formulario.bita_fecha.value = el.bita_fecha || '';
    formulario.bita_malware.value = el.bita_malware || '';
    formulario.bita_pishing.value = el.bita_pishing || '';
    formulario.bita_coman_cont.value = el.bita_coman_cont || '';
    formulario.bita_cryptomineria.value = el.bita_cryptomineria || '';
    formulario.bita_ddos.value = el.bita_ddos || '';
    formulario.bita_conex_bloq.value = el.bita_conex_bloq || '';
    formulario.bita_total.value = el.bita_total || '';

    if (btnGuardar) btnGuardar.style.display = 'none'; 
    if (modificarBtnContainer) { modificarBtnContainer.style.display = ''; if (btnModificar) btnModificar.disabled = false; }
    if (cancelarBtnContainer) { cancelarBtnContainer.style.display = ''; if (btnCancelar) btnCancelar.disabled = false; }
};

const modificar = async (e) => {
    e.preventDefault();
    if (btnModificar) btnModificar.disabled = true;

    // *** ¡CORREGIDO! validarFormulario ahora ignora campos readonly y los de la lista de exclusión.
    if (!validarFormulario(formulario, ['bita_id', 'bita_total'])) {
        mostrarAlerta("info", "Campos vacíos", "Debe llenar todos los campos.");
        if (btnModificar) btnModificar.disabled = false;
        return;
    }

    try {
        const body = new FormData(formulario);
        const url = "/App_CIBER/API/bitacora/modificar"; 
        const config = { method: 'POST', body };
        const respuesta = await fetch(url, config);
        const { codigo, mensaje, detalle } = await respuesta.json();

        if (codigo === 1) {
            Toast.fire({ icon: 'success', title: mensaje });
            formulario.reset();
            if (bitaTotalInput) bitaTotalInput.value = '';
            buscar(fechaInicioBusqueda.value, fechaFinBusqueda.value, registrosPorPaginaSelect.value); 
            cancelar();
        } else {
            mostrarAlerta('error', 'Error al modificar', mensaje || 'Ocurrió un error desconocido.');
            console.error("Detalle del error de la API (modificar):", detalle);
        }
    } catch (error) {
        console.error("Error en modificar:", error);
        mostrarAlerta('error', 'Error de Conexión', 'Ocurrió un error de red o servidor al modificar la bitácora.');
    } finally {
        if (btnModificar) btnModificar.disabled = false;
    }
};

const eliminar = async (evento) => {
    
    const id = evento.currentTarget.dataset.bita_id;

    const confirmacion = await Swal.fire({
        title: '¿Eliminar este registro?', text: 'Esta acción no se puede deshacer.', icon: 'warning',
        showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
    });

    if (confirmacion.isConfirmed) {
        try {
            const body = new FormData();
            body.append('bita_id', id);
            const url = "/App_CIBER/API/bitacora/eliminar"; 
            const config = { method: 'POST', body };
            const respuesta = await fetch(url, config);
            const { codigo, mensaje, detalle } = await respuesta.json();

            if (codigo === 1) { 
                Toast.fire({ icon: 'success', title: 'Eliminado', text: mensaje });
                datatable.row(evento.currentTarget.closest('tr')).remove().draw();
            } else {
                mostrarAlerta('error', 'Error al eliminar', mensaje || 'No se pudo eliminar el registro.');
                console.error("Detalle del error de error:", detalle);
            }
        } catch (error) {
            console.error("Error en eliminar:", error);
            mostrarAlerta('error', 'Error de Conexión', 'Hubo un problema de red o servidor al intentar eliminar.');
        }
    }
};

const cancelar = () => {
    formulario.reset();
    if (bitaTotalInput) bitaTotalInput.value = '';

    if (btnGuardar) btnGuardar.style.display = ''; 
    if (btnGuardar) btnGuardar.disabled = false;

    if (modificarBtnContainer) { modificarBtnContainer.style.display = 'none'; if (btnModificar) btnModificar.disabled = true; }
    if (cancelarBtnContainer) { cancelarBtnContainer.style.display = 'none'; if (btnCancelar) btnCancelar.disabled = true; }
};

// ====================================================================
// EVENT LISTENERS
// ====================================================================

if (formulario) formulario.addEventListener('submit', guardar);
if (btnBuscarFecha) btnBuscarFecha.addEventListener('click', buscarPorFecha);
if (registrosPorPaginaSelect) registrosPorPaginaSelect.addEventListener('change', cambiarRegistrosPorPagina);
if (btnModificar) btnModificar.addEventListener('click', modificar);
if (btnCancelar) btnCancelar.addEventListener('click', cancelar);
if (datatable) {
    datatable.on('click', '.modificar', traerDatos);
    datatable.on('click', '.eliminar', eliminar);
}



document.getElementById('btnResumenImagen').addEventListener('click', async () => {
    const fechaInicio = document.getElementById('fechaInicioBusqueda').value;
    const fechaFin = document.getElementById('fechaFinBusqueda').value;

    try {
        const response = await fetch(`/App_CIBER/API/bitacora/exportar-imagen?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`);
        const data = await response.json();

        if (!data.imagen) throw new Error("No se recibió imagen válida.");

        Swal.fire({
            title: 'Resumen de Amenazas',
            html: `<img src="${data.imagen}" alt="Resumen de Amenazas" class="img-fluid">`,
            width: 800,
            confirmButtonText: 'Cerrar'
        });

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo generar la imagen.'
        });
        console.error('Error:', error);
    }
});





btnExportarPdf.addEventListener('click', async () => {
    const fechaInicio = fechaInicioBusqueda.value;
    const fechaFin = fechaFinBusqueda.value;

    let nombreSugerido = 'Bitacora_Incidentes_Ciberdefensa';
    if (fechaInicio && fechaFin) {
        nombreSugerido = `bitacora_${fechaInicio}_${fechaFin}`;
    }

    const { value: nombreElegido } = await Swal.fire({
        title: 'Nombre del archivo PDF',
        input: 'text',
        inputLabel: 'Ingresa el nombre:',
        inputPlaceholder: nombreSugerido,
        inputValue: nombreSugerido,
        showCancelButton: true,
        confirmButtonText: 'Descargar',
        cancelButtonText: 'Cancelar',
        inputAttributes: { autocapitalize: 'off' }
    });

    if (!nombreElegido) return;

    try {
        let url = `/App_CIBER/API/bitacora/exportar-pdf`;
        const params = new URLSearchParams();

        if (fechaInicio) params.append('fecha_inicio', fechaInicio);
        if (fechaFin) params.append('fecha_fin', fechaFin);
        params.append('nombre', nombreElegido);

        url += '?' + params.toString();

        const respuesta = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!respuesta.ok) throw new Error("No se pudo generar el PDF");

        const blob = await respuesta.blob();
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${nombreElegido}.pdf`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(link.href);

    } catch (error) {
        Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
        console.error(error);
    }
});



btnExportarXlsx.addEventListener('click', async () => {
    const fechaInicio = fechaInicioBusqueda.value;
    const fechaFin = fechaFinBusqueda.value;

    // Generar nombre de archivo según si hay fechas o no
    let nombreSugerido = 'Bitacora_Incidentes_Ciberdefensa';
    if (fechaInicio && fechaFin) {
        nombreSugerido = `bitacora_${fechaInicio}_${fechaFin}`;
    }

    // Mostrar diálogo con nombre sugerido editable
    const { value: nombreElegido } = await Swal.fire({
        title: 'Nombre del archivo',
        input: 'text',
        inputLabel: 'Ingresa el nombre del archivo Excel:',
        inputPlaceholder: nombreSugerido,
        inputValue: nombreSugerido,
        showCancelButton: true,
        confirmButtonText: 'Descargar',
        cancelButtonText: 'Cancelar',
        inputAttributes: {
            autocapitalize: 'off' // ✅ ¡editable!
        }
    });

    if (!nombreElegido) return;

    try {
        let url = "/App_CIBER/API/bitacora/exportar-xlsx";
        const params = new URLSearchParams();

        if (fechaInicio) params.append('fecha_inicio', fechaInicio);
        if (fechaFin) params.append('fecha_fin', fechaFin);
        if (params.toString()) url += '?' + params.toString();

        const respuesta = await fetch(url);
        if (!respuesta.ok) throw new Error("No se pudo generar el archivo");

        const blob = await respuesta.blob();
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${nombreElegido}.xlsx`;
        link.click();
        URL.revokeObjectURL(link.href);
    } catch (error) {
        Swal.fire('Error', 'No se pudo generar el archivo Excel.', 'error');
        console.error(error);
    }
});


const camposNumericosForm = ['bita_malware', 'bita_pishing', 'bita_coman_cont', 'bita_cryptomineria', 'bita_ddos', 'bita_conex_bloq'];
camposNumericosForm.forEach(campo => {
    const input = document.getElementById(campo);
    if (input) {
        input.addEventListener('input', calcularTotal);
    }
});


// ====================================================================
// INICIALIZACIÓN: CARGAR DATOS AL CARGAR LA PÁGINA
// ====================================================================
buscar(null, null, registrosPorPaginaSelect ? registrosPorPaginaSelect.value : 5);