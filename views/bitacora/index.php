<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora de Ciberseguridad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        #formBitacora .form-label {
            color: black !important;
        }

        div.dataTables_info {
            color: black !important;
        }

        #historialContainer .form-label {
            color: black !important;
        }

        body {
            background-color: #343a40;
        }

        h1,
        h2 {
            font-weight: bold;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }
    </style>
</head>

<body>

    <div class="row justify-content-center p-4">
        <h1 class="text-light text-center"><b>Bitácora de Ciberseguridad</b></h1>

        <div class="col-lg-3 justify-content-center bg-light bg-gradient rounded text-center me-3">
            <div class="row justify-content-center">

                <div class="col-lg justify-content-center">
                    <h2 class="text-center mb-3 text-primary mt-3">Registro de Incidentes</h2>
                </div>

                <form id="formBitacora">
                    <input type="hidden" name="bita_id" id="bita_id">

                    <div class="row justify-content-center">
                        <div class="col-6 mb-3">
                            <label for="bita_fecha" class="form-label fw-bold">Fecha <i class="bi bi-calendar-event"></i></label>
                            <input type="date" name="bita_fecha" id="bita_fecha" class="form-control rounded-pill">
                        </div>

                        <div class="col-6 mb-3">
                            <label for="bita_malware" class="form-label fw-bold">Malware <i class="fa-solid fa-skull"></i></label>
                            <input type="number" name="bita_malware" id="bita_malware" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-6 mb-3">
                            <label for="bita_pishing" class="form-label fw-bold">Phishing <i class="fa-solid fa-envelope-open-text"></i></label>
                            <input type="number" name="bita_pishing" id="bita_pishing" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
                        </div>

                        <div class="col-6 mb-3">
                            <label for="bita_coman_cont" class="form-label fw-bold">Comando y Control <i class="fa-solid fa-terminal"></i></label>
                            <input type="number" name="bita_coman_cont" id="bita_coman_cont" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-6 mb-3">
                            <label for="bita_cryptomineria" class="form-label fw-bold">Cryptominería <i class="fa-solid fa-money-bill-wave"></i></label>
                            <input type="number" name="bita_cryptomineria" id="bita_cryptomineria" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
                        </div>

                        <div class="col-6 mb-3">
                            <label for="bita_ddos" class="form-label fw-bold">DDoS <i class="fa-solid fa-bomb"></i></label>
                            <input type="number" name="bita_ddos" id="bita_ddos" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-6 mb-3">
                            <label for="bita_conex_bloq" class="form-label fw-bold">Conexiones Bloqueadas <i class="fa-solid fa-ban"></i></label>
                            <input type="number" name="bita_conex_bloq" id="bita_conex_bloq" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-6 mb-3">
                            <label for="bita_total" class="form-label fw-bold">Total Acumulado <i class="bi bi-calculator"></i></label>
                            <input type="text" class="form-control rounded-pill" name="bita_total" id="bita_total" placeholder="Automático" readonly>
                        </div>
                    </div>

                    
                    <div class="row justify-content-center mb-3">
                        <div class="col-lg">
                            <button type="submit" id="btnGuardar" class="btn btn-lg btn-primary rounded-pill px-4"><i class="bi bi-floppy-fill me-2"></i> Guardar</button>
                        </div>
                        <div class="col-lg">
                            <button type="button" id="btnModificar" class="btn btn-lg btn-warning rounded-pill px-4"><i class="bi bi-pencil-square me-2"></i> Modificar</button>
                        </div>
                        <div class="col-lg">
                            <button type="button" id="btnCancelar" class="btn btn-lg btn-danger rounded-pill px-4"><i class="bi bi-x-circle me-2"></i> Cancelar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="col-lg justify-content-center bg-light bg-gradient rounded text-center">
            <div class="row justify-content-center">

                <div class="col-lg justify-content-center">
                    <h2 class="text-center mb-3 text-primary mt-3">Historial de Incidentes</h2>
                </div>

                <div class="row mb-3 align-items-center justify-content-between">
                    <div class="col-lg-7 mb-3 mb-lg-0">
                        <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <label for="fechaInicioBusqueda" class="form-label fw-bold me-2 mb-2 mb-md-0">Desde:</label>
                            <input type="date" id="fechaInicioBusqueda" class="form-control form-control-sm rounded-pill me-3 mb-2 mb-md-0">
                            <label for="fechaFinBusqueda" class="form-label fw-bold me-2 mb-2 mb-md-0">Hasta:</label>
                            <input type="date" id="fechaFinBusqueda" class="form-control form-control-sm rounded-pill me-3 mb-2 mb-md-0">
                            <button type="button" id="btnBuscarFecha" class="btn btn-lg btn-info rounded-pill px-3"><i class="bi bi-search me-2"></i></button>
                        </div>
                    </div>
                    <div class="col-lg-5 d-flex justify-content-lg-end align-items-center flex-wrap gap-2">
                        <label for="registrosPorPagina" class="form-label fw-bold me-2 mb-0">Mostrar:</label>
                        <select id="registrosPorPagina" class="form-select form-select-sm rounded-pill w-auto">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="ms-2">registros</span>
                        <button type="button" id="btnExportar" class="btn btn-lg btn-info rounded-pill px-4 ms-md-3 mt-2 mt-md-0"><i class="bi bi-funnel-fill"></i> Exportar</button>
                    </div>
                </div>

                <table class="table table-bordered table-hover w-100 text-center shadow" id="tablaBitacora"></table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-dark" id="exportModalLabel">Seleccionar Formato de Exportación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary rounded-pill" id="btnResumenImagen"><i class="bi bi-image me-2"></i> Ver Imagen Resumen</button>
                        <button type="button" class="btn btn-outline-danger rounded-pill" id="btnExportarPdf"><i class="bi bi-file-earmark-pdf-fill me-2"></i> Exportar a PDF</button>
                        <button type="button" class="btn btn-outline-success rounded-pill" id="btnExportarXlsx"><i class="bi bi-file-earmark-excel-fill me-2"></i> Exportar a XLSX</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver la imagen resumen -->
    <div class="modal fade" id="imagenModal" tabindex="-1" aria-labelledby="imagenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resumen de Amenazas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="imagenResumen" src="" class="img-fluid" alt="Resumen de Amenazas">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="module" src="/App_CIBER/build/js/bitacora/index.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tablaBitacora = document.getElementById('tablaBitacora');
            if (tablaBitacora) {
                tablaBitacora.addEventListener('mouseover', (event) => {
                    const target = event.target.closest('.modificar, .eliminar');
                    if (target) {
                        if (target.classList.contains('modificar')) {
                            target.setAttribute('title', 'Modificar Registro');
                        } else if (target.classList.contains('eliminar')) {
                            target.setAttribute('title', 'Eliminar Registro');
                        }
                    }
                });
            }

            const exportButton = document.getElementById('btnExportar');
            const exportModalElement = document.getElementById('exportModal');
            if (exportButton && exportModalElement) {
                const exportModal = new bootstrap.Modal(exportModalElement);
                exportButton.addEventListener('click', () => {
                    exportModal.show();
                });
            }
        });
    </script>

</body>

</html>