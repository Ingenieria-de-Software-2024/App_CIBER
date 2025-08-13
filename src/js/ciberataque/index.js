import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Dropdown } from "bootstrap";

const formulario   = document.getElementById('formCiberAtaque');
const tabla        = document.getElementById('tablaCiberAtaque');
const btnGuardar   = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar  = document.getElementById('btnCancelar');

// DataTable
const datatable = new DataTable('#tablaCiberAtaque', {
  data: null,
  language: lenguaje,
  pageLength: 15,
  lengthMenu: [3, 9, 11, 25, 100],
  columns: [
    {
      title: 'No.',
      data: 'ata_id',
      width: '2%',
      render: (data, type, row, meta) => meta.row + 1
    },
    { title: 'Nombre', data: 'ata_nombre', render: d => d ? d.toUpperCase() : '' },
    { title: 'Descripción', data: 'ata_descricion', render: d => d ?? '' },
    { title: 'Situación', data: 'ata_situacion', visible: false },
    {
      title: 'Acciones',
      data: 'ata_id',
      searchable: false,
      orderable: false,
      render: (data, type, row, meta) => `
        <button class='btn btn-warning modificar'
                data-ata_id="${data}"
                data-ata_nombre="${row.ata_nombre ?? ''}"
                data-ata_descricion='${(row.ata_descricion ?? '').replace(/'/g, "&#39;")}'
                data-ata_situacion="${row.ata_situacion ?? 1}">
          <i class='bi bi-pencil-square'></i>
        </button>
        <button class='btn btn-danger eliminar' data-ata_id="${data}">
          <i class="bi bi-trash-fill"></i>
        </button>
      `
    }
  ]
});

// Estado inicial de botones
btnModificar.parentElement.style.display = 'none';
btnModificar.disabled = true;
btnCancelar.parentElement.style.display = 'none';
btnCancelar.disabled = true;

// Guardar
const guardar = async (e) => {
  e.preventDefault();

  // Excluir el ID oculto en validación de "crear"
  if (!validarFormulario(formulario, ['ata_id'])) {
    Swal.fire({
      title: "Campos vacíos",
      text: "Debe llenar todos los campos obligatorios",
      icon: "info"
    });
    return;
  }

  try {
    const body = new FormData(formulario);
    const url = "/sigecom/API/ciberataque/guardar";
    const config = { method: 'POST', body };

    const respuesta = await fetch(url, config);
    const { codigo, mensaje } = await respuesta.json();
    const icon = codigo === 1 ? 'success' : 'error';

    Toast.fire({ icon, title: mensaje });
    if (codigo === 1) {
      formulario.reset();
      buscar();
    }
  } catch (error) {
    console.error(error);
    Swal.fire({ title: 'Error', text: 'No se pudo guardar el registro.', icon: 'error' });
  }
};

// Buscar
const buscar = async () => {
  try {
    const url = "/sigecom/API/ciberataque/buscar";
    const config = { method: 'GET' };

    const respuesta = await fetch(url, config);
    const { datos } = await respuesta.json();

    datatable.clear().draw();
    if (datos) {
      datatable.rows.add(datos).draw();
    }
  } catch (error) {
    console.error(error);
  }
};
buscar();

// Traer datos al formulario (Modificar)
const traerDatos = (e) => {
  const ds = e.currentTarget.dataset;

  formulario.ata_id.value         = ds.ata_id || '';
  formulario.ata_nombre.value     = ds.ata_nombre || '';
  formulario.ata_descricion.value = ds.ata_descricion || '';
  formulario.ata_situacion.value  = ds.ata_situacion || '1';

  // Ocultar tabla mientras editas
  tabla.parentElement.parentElement.style.display = 'none';

  // Alternar botones
  btnGuardar.parentElement.style.display = 'none';
  btnGuardar.disabled = true;

  btnModificar.parentElement.style.display = '';
  btnModificar.disabled = false;

  btnCancelar.parentElement.style.display = '';
  btnCancelar.disabled = false;
};

// Cancelar edición
const cancelar = () => {
  tabla.parentElement.parentElement.style.display = '';
  formulario.reset();

  btnGuardar.parentElement.style.display = '';
  btnGuardar.disabled = false;

  btnModificar.parentElement.style.display = 'none';
  btnModificar.disabled = true;

  btnCancelar.parentElement.style.display = 'none';
  btnCancelar.disabled = true;
};

// Modificar
const modificar = async (e) => {
  e.preventDefault();

  if (!validarFormulario(formulario)) {
    Swal.fire({
      title: "Campos vacíos",
      text: "Debe llenar todos los campos",
      icon: "info"
    });
    return;
  }

  try {
    const body = new FormData(formulario);
    const url = "/sigecom/API/ciberataque/modificar";
    const config = { method: 'POST', body };

    const respuesta = await fetch(url, config);
    const { codigo, mensaje } = await respuesta.json();
    const icon = codigo === 1 ? 'success' : 'error';

    Toast.fire({ icon, title: mensaje });

    if (codigo === 1) {
      formulario.reset();
      buscar();
      cancelar();
    }
  } catch (error) {
    console.error("Error al modificar:", error);
    Swal.fire({
      title: 'Error',
      text: 'Ocurrió un error al intentar modificar el registro.',
      icon: 'error'
    });
  }
};

// Eliminar
const eliminar = async (boton) => {
  const id = boton.currentTarget.dataset.ata_id;

  const confirmacion = await Swal.fire({
    title: '¿Eliminar ciberataque?',
    text: "Esta acción es irreversible.",
    icon: 'warning',
    showDenyButton: true,
    confirmButtonText: 'Sí, eliminar',
    denyButtonText: 'No, cancelar',
    confirmButtonColor: '#3085d6',
    denyButtonColor: '#d33'
  });

  if (confirmacion.isConfirmed) {
    try {
      const body = new FormData();
      body.append('ata_id', id);

      const url = '/sigecom/API/ciberataque/eliminar';
      const config = { method: 'POST', body };

      const respuesta = await fetch(url, config);
      const { codigo, mensaje } = await respuesta.json();

      if (codigo === 4) {
        Swal.fire({ title: '¡Éxito!', text: mensaje, icon: 'success', timer: 1500 });
        datatable.row(boton.currentTarget.closest('tr')).remove().draw();
      } else {
        Swal.fire({ title: '¡Error!', text: mensaje, icon: 'error' });
      }
    } catch (error) {
      console.error(error);
      Swal.fire({ title: 'Error', text: 'No se pudo eliminar el registro.', icon: 'error' });
    }
  }
};

// Eventos
formulario.addEventListener('submit', guardar);
btnCancelar.addEventListener('click', cancelar);
btnModificar.addEventListener('click', modificar);
datatable.on('click', '.modificar', traerDatos);
datatable.on('click', '.eliminar', eliminar);
