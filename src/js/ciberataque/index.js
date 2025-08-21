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

const datatable = new DataTable('#tablaCiberAtaque', {
  data: null,
  language: lenguaje,
  pageLength: 3,
  lengthMenu: [3, 9, 11, 25, 100],
  columns: [
    {
      title: 'No.',
      data: 'ata_id',
      width: '0.5%',
      render: (data, type, row, meta) => meta.row + 1
    },
    { 
      title: 'Nombre', 
      data: 'ata_nombre',
      width: '2%', 
      render: d => d ? d.toUpperCase() : '' 
    },
    { 
      title: 'Descripción', 
      data: 'ata_descripcion',
      width: '10%',
      render: d => d ?? '' 
    },
    { 
      title: 'Situación', 
      data: 'ata_situacion',
      visible: false 
    },
    {
      title: 'Acciones',
      data: 'ata_id',
      searchable: false,
      orderable: false,
      width: '1%',
      render: (data, type, row, meta) =>

        `
        <button class='btn btn-warning modificar' 
        data-ata_id="${data}" 
        data-ata_nombre="${row.ata_nombre}" 
        data-ata_descripcion="${row.ata_descripcion}" 
        data-ata_situacion="${row.ata_situacion ?? 1}">
        <i class='bi bi-pencil-square'></i></button>
        
        <button class='btn btn-danger eliminar' data-ata_id="${data}"><i class="bi bi-trash-fill"></i></button>
        `
        
      }
    ]
  }
);

btnModificar.parentElement.style.display = 'none';
btnModificar.disabled = true;
btnCancelar.parentElement.style.display = 'none';
btnCancelar.disabled = true;


const buscar = async () => {
  try {
    Swal.fire({
      title: 'Cargando...',
      text: '¡Buscando los registros de Ciber-Ataques',
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: () => { Swal.showLoading(); }
    });

    const url = "/App_CIBER/API/ciberataque/buscar";
    const config = { method: 'GET' };

    const respuesta = await fetch(url, config);
    const { datos } = await respuesta.json();

    datatable.clear().draw();
    if (datos) {
      datatable.rows.add(datos).draw();
    }

    Swal.close();
  } catch (error) {
    Swal.close();
    console.error(error);
  }
};
buscar();

const guardar = async (e) => {
  e.preventDefault();

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
    const url = "/App_CIBER/API/ciberataque/guardar";

    const config = { method: 'POST', body };

    const respuesta = await fetch(url, config);
    const { codigo, mensaje } = await respuesta.json();
    const icon = codigo === 1 ? 'success' : 'error';

    Toast.fire({icon, title: mensaje });

    if (codigo === 1) {

      Swal.fire({
        title: '¡Éxito!', 
        text: mensaje, 
        icon: 'success', 
        timer: 3000 
      });

      formulario.reset();
      buscar();

    }

  } catch (error) {
    console.error(error);
    Swal.fire({ 
      title: 'Error', 
      text: 'No se pudo guardar el registro.', 
      icon: 'error' });
  }
};

const traerDatos = (e) => {
  const btn = e.currentTarget;

  const ca = {
    ata_id: btn.getAttribute('data-ata_id'),
    ata_nombre: btn.getAttribute('data-ata_nombre'),
    ata_descripcion: btn.getAttribute('data-ata_descripcion'),
    ata_situacion: btn.getAttribute('data-ata_situacion')
  };

  formulario.ata_id.value          = ca.ata_id || '';
  formulario.ata_nombre.value      = ca.ata_nombre || '';
  formulario.ata_descripcion.value = ca.ata_descripcion || '';

  if (formulario.ata_situacion) {
    formulario.ata_situacion.value = ca.ata_situacion || '1';
  }

  tabla.parentElement.parentElement.style.display = 'none';

  btnGuardar.parentElement.style.display = 'none';
  btnGuardar.disabled = true;
  btnModificar.parentElement.style.display = '';
  btnModificar.disabled = false;
  btnCancelar.parentElement.style.display = '';
  btnCancelar.disabled = false;
};


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

  btnCancelar.parentElement.style.display = '';
  btnCancelar.disabled = false;

  try {
    const body = new FormData(formulario);
    const url = "/App_CIBER/API/ciberataque/modificar";
    const config = { method: 'POST', body };

    const respuesta = await fetch(url, config);
    const { codigo, mensaje } = await respuesta.json();
    const icon = Number(codigo) === 1 ? 'success' : 'error';

    Toast.fire({ icon, title: mensaje });

    if (Number(codigo) === 1) {

      await Swal.fire({
        title: '¡Modificado!',
        text: 'Se ha modificado con éxito.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
      });

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

      const url = '/App_CIBER/API/ciberataque/eliminar';
      const config = { method: 'POST', body };

      const respuesta = await fetch(url, config);
      const { codigo, mensaje } = await respuesta.json();

      if (codigo === 4) {
        Swal.fire({ 
          title: '¡Éxito!', 
          text: mensaje, 
          icon: 'success', 
          timer: 1500 });
        
          datatable.row(boton.currentTarget.closest('tr')).remove().draw();

      } else {
        Swal.fire({ 
          title: '¡Error!', 
          text: mensaje, 
          icon: 'error' });
      }
    } catch (error) {
      console.error(error);
      Swal.fire({ title: 'Error', text: 'No se pudo eliminar el registro.', icon: 'error' });
    }
  }
};

formulario.addEventListener('submit', guardar);
btnCancelar.addEventListener('click', cancelar);
btnModificar.addEventListener('click', modificar);
datatable.on('click', '.modificar', traerDatos);
datatable.on('click', '.eliminar', eliminar);
