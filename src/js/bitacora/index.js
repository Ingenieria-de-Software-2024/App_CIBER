import { Toast, validarFormulario } from "../funciones.js";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje.js";

const formulario = document.getElementById('formBitacora');
const tabla = document.getElementById('tablaBitacora');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');

btnModificar.parentElement.style.display = 'none';
btnModificar.disabled = true;
btnCancelar.parentElement.style.display = 'none';
btnCancelar.disabled = true;


const datatable = new DataTable ('#tablaBitacora', {
    data: null,
    language: lenguaje,
    pageLength: 5,
    lengthMenu: [5, 10, 15],
    columns: [
        {
            title: 'No.',
            data: 'bita_id',
            width: '2%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Fecha',
            data: 'bita_fecha'
        },
        {
            title: 'Malware',
            data: 'bita_malware'
        },
        {
            title: 'Phishing',
            data: 'bita_pishing'
        },
        {
            title: 'Comando y Control',
            data: 'bita_coman_cont'
        },
        {
            title: 'Cryptominería',
            data: 'bita_cryptomineria'
        },
        {
            title: 'DDoS',
            data: 'bita_ddos'
        },
        {
            title: 'Conexiones Bloqueadas',
            data: 'bita_conex_bloq'
        },
        {
            title: 'Total',
            data: 'bita_total'
        },
        {
            title: 'Acciones',
            data: 'bita_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                let html = `
                <button class='btn btn-warning modificar'
                    data-bita_id="${data}"
                    data-bita_fecha="${row.bita_fecha}"
                    data-bita_malware="${row.bita_malware}"
                    data-bita_pishing="${row.bita_pishing}"
                    data-bita_coman_cont="${row.bita_coman_cont}"
                    data-bita_cryptomineria="${row.bita_cryptomineria}"
                    data-bita_ddos="${row.bita_ddos}"
                    data-bita_conex_bloq="${row.bita_conex_bloq}"
                    data-bita_total="${row.bita_total}">
                    <i class='bi bi-pencil-square'></i>
                </button>
                
                <button class='btn btn-danger eliminar' data-bita_id="${data}">
                    <i class="bi bi-trash-fill"></i>
                </button>
                `;
                return html;
            }
        }
    ]
});

const guardar = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formulario, ['bita_id'])) {
        Swal.fire({ title: "Campos vacíos", text: "Debe llenar todos los campos", icon: "info" });
        return;
    }

    try {
        const body = new FormData(formulario);
        const url = "/sigecom/API/bitacora/guardar";
        const config = { method: 'POST', body };
        const respuesta = await fetch(url, config);
        const { codigo, mensaje } = await respuesta.json();
        Toast.fire({ icon: codigo === 1 ? 'success' : 'error', title: mensaje });

        if (codigo === 1) {
            formulario.reset();
            buscar();
        }
    } catch (error) {
        console.log(error);
    }
};

// const modificar = async (e) => {
//     e.preventDefault();

//     if (!validarFormulario(formulario)) {
//         Swal.fire({ title: "Campos vacíos", text: "Debe llenar todos los campos", icon: "info" });
//         return;
//     }

//     try {
//         const body = new FormData(formulario);
//         const url = "/sigecom/API/bitacora/modificar";
//         const config = { method: 'POST', body };
//         const respuesta = await fetch(url, config);
//         const { codigo, mensaje } = await respuesta.json();

//         Toast.fire({ icon: codigo === 1 ? 'success' : 'error', title: mensaje });

//         if (codigo === 1) {
//             formulario.reset();
//             buscar();
//             cancelar();
//         }
//     } catch (error) {
//         console.error("Error al modificar:", error);
//         Swal.fire({ title: 'Error', text: 'Ocurrió un error al modificar la bitácora.', icon: 'error' });
//     }
// };

// const eliminar = async (evento) => {
//     const id = evento.currentTarget.dataset.bita_id;

//     const confirmacion = await Swal.fire({
//         title: '¿Eliminar este registro?',
//         text: 'Esta acción no se puede deshacer.',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonText: 'Sí, eliminar',
//         cancelButtonText: 'Cancelar'
//     });

//     if (confirmacion.isConfirmed) {
//         try {
//             const body = new FormData();
//             body.append('bita_id', id);
//             const url = "/sigecom/API/bitacora/eliminar";
//             const config = { method: 'POST', body };
//             const respuesta = await fetch(url, config);
//             const { codigo, mensaje } = await respuesta.json();

//             if (codigo === 4) {
//                 Swal.fire({ title: 'Eliminado', text: mensaje, icon: 'success', timer: 1500 });
//                 datatable.row(evento.currentTarget.closest('tr')).remove().draw();
//             } else {
//                 Swal.fire({ title: 'Error', text: mensaje, icon: 'error' });
//             }
//         } catch (error) {
//             console.log(error);
//         }
//     }
// };

// const traerDatos = (e) => {
//     const el = e.currentTarget.dataset;

//     formulario.bita_id.value = el.bita_id || '';
//     formulario.bita_fecha.value = el.bita_fecha || '';
//     formulario.bita_malware.value = el.bita_malware || '';
//     formulario.bita_pishing.value = el.bita_pishing || '';
//     formulario.bita_coman_cont.value = el.bita_coman_cont || '';
//     formulario.bita_cryptomineria.value = el.bita_cryptomineria || '';
//     formulario.bita_ddos.value = el.bita_ddos || '';
//     formulario.bita_conex_bloq.value = el.bita_conex_bloq || '';
//     formulario.bita_total.value = el.bita_total || '';

//     tabla.parentElement.parentElement.style.display = 'none';
//     btnGuardar.parentElement.style.display = 'none';
//     btnGuardar.disabled = true;
//     btnModificar.parentElement.style.display = '';
//     btnModificar.disabled = false;
//     btnCancelar.parentElement.style.display = '';
//     btnCancelar.disabled = false;
// };

// const cancelar = () => {
//     tabla.parentElement.parentElement.style.display = '';
//     formulario.reset();
//     btnGuardar.parentElement.style.display = '';
//     btnGuardar.disabled = false;
//     btnModificar.parentElement.style.display = 'none';
//     btnModificar.disabled = true;
//     btnCancelar.parentElement.style.display = 'none';
//     btnCancelar.disabled = true;
// };

// const buscar = async () => {
//     try {
//         const url = "/sigecom/API/bitacora/buscar";
//         const config = { method: 'GET' };
//         const respuesta = await fetch(url, config);
//         const { datos } = await respuesta.json();
//         console.log(datos)
//         return;
//         datatable.clear().draw();
//         if (datos) {
//             datatable.rows.add(datos).draw();
//         }
//     } catch (error) {
//         console.log(error);
//     }
// };
// buscar();

formulario.addEventListener('submit', guardar);
// btnModificar.addEventListener('click', modificar);
// btnCancelar.addEventListener('click', cancelar);
// datatable.on('click', '.modificar', traerDatos);
// datatable.on('click', '.eliminar', eliminar);