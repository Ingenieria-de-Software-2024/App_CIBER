<div class="row justify-content-center p-3">
  <h1 class="text-light mb-3 text-center"><b>Bitácora</b></h1>

  <div class="col-lg-6 justify-content-center">
    <div class="row justify-content-center">
      <div class="col-lg justify-content-center">

        <form id="formBitacora" class="border bg-light bg-gradient text-dark shadow-lg rounded p-3 text-center">
          <h1 class="text-center mb-4 text-primary">Registro</h1>

          <input type="hidden" name="bita_id" id="bita_id">

          <div class="row mb-4">

            <div class="col-lg mx-auto justify-content-center">
              <label for="bita_fecha" class="form-label fw-bold">Fecha <i class="bi bi-calendar-event"></i></label>
              <input type="date" name="bita_fecha" id="bita_fecha" class="form-control rounded-pill">
            </div>

            <div class="col-lg mx-auto justify-content-center">
              <label for="bita_malware" class="form-label fw-bold">Malware <i class="fa-solid fa-skull"></i></label>
              <input type="number" name="bita_malware" id="bita_malware" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
            </div>

            <div class="col-lg">
              <label for="bita_phishing" class="form-label fw-bold">Phishing <i class="fa-solid fa-bangladeshi-taka-sign"></i></label>
              <input type="number" name="bita_phishing" id="bita_phishing" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
            </div>

            <div class="col-lg">
              <label for="bita_coman_cont" class="form-label fw-bold">Comando y Control <i class="fa-solid fa-folder-open"></i></label>
              <input type="number" name="bita_coman_cont" id="bita_coman_cont" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
            </div>

          </div>

          <div class="row mb-4">

            <div class="col-lg">
              <label for="bita_cryptomineria" class="form-label fw-bold">Cryptominería <i class="fa-solid fa-money-bill"></i></label>
              <input type="number" name="bita_cryptomineria" id="bita_cryptomineria" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
            </div>

            <div class="col-lg">
              <label for="bita_ddos" class="form-label fw-bold">Denegación de Servicio <i class="fa-solid fa-magnifying-glass"></i></label>
              <input type="number" name="bita_ddos" id="bita_ddos" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
            </div>

            <div class="col-lg">
              <label for="bita_conex_bloq" class="form-label fw-bold">Conexiones Bloqueados <i class="fa-solid fa-magnifying-glass"></i></label>
              <input type="number" name="bita_conex_bloq" id="bita_conex_bloq" class="form-control rounded-pill" placeholder="Ingrese la Cantidad">
            </div>

          </div>

          <div class="row mb-4">
            <div class="col-lg-6 mx-auto">
              <label for="bita_total" class="form-label fw-bold">Total <i class="bi bi-calculator"></i></label>
              <input type="number" name="bita_total" id="bita_total" class="form-control rounded-pill" readonly>
            </div>
          </div>

          <div class="row mb-5 text-center justify-content-center">

            <div class="col-lg-3">
              <button type="submit" id="btnGuardar" class="btn btn-lg btn-primary w-100 rounded-pill"><i class="bi bi-floppy-fill"></i> Guardar</button>
            </div>

            <div class="col-lg-3">
              <button type="button" id="btnModificar" class="btn btn-lg btn-warning w-100 rounded-pill"><i class="bi bi-pencil-square me-2"></i> Modificar</button>
            </div>

            <div class="col-lg-3">
              <button type="button" id="btnCancelar" class="btn btn-lg btn-danger w-100 rounded-pill"><i class="bi bi-x-circle me-2"></i> Cancelar</button>
            </div>

            <div class="col-lg-3">
              <button type="button" id="btnPdf" class="btn btn-lg btn-secondary w-100 rounded-pill"><i class="bi bi-file-earmark-pdf-fill"></i> Pdf</button>
            </div>
          </div>

        </form>

      </div>
    </div>

  </div>

  <div class="col-lg-6 justify-content-center bg-light bg-gradient rounded p-3">
    <h1 class="text-center mb-3 text-primary">Registro</h1>

    <table class="table table-bordered table-hover w-100 text-center shadow" id="tablaBitacora"></table>
    
  </div>
</div>

<script src="<?= asset('build/js/bitacora/index.js') ?>"></script>


