<h1 class="text-ligth text-center"><b>Registro de Ataques</b></h1>

<div class="row justify-content-center text-center p-5">

  <div class="col-lg-3">
    <form id="formCiberAtaque" class="border bg-light bg-gradient rounded p-4 mx-auto my-5">
      <h2 class="text-primary fw-bold">Registro</h2>
      <i class="bi bi-shield-lock text-secondary" style="font-size: 4rem;"></i>
      <input type="hidden" name="ata_id" id="ata_id">

      <div class="row mt-3">
        <div class="col">
          <label for="ata_nombre" class="form-label fw-bold text-dark">Nombre del Ciberataque</label>
          <input type="text" name="ata_nombre" id="ata_nombre" class="form-control border-primary">
        </div>
      </div>

      <div class="row mt-3 mb-3">
        <div class="col">
          <label for="ata_descripcion" class="form-label fw-bold text-dark">Descripción</label>
          <input name="ata_descripcion" id="ata_descripcion" class="form-control border-primary">
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-4 mt-3">
          <button type="submit" id="btnGuardar" class="btn btn-primary w-100 btn-lg custom-btn">
            <i class="bi bi-save"></i> Guardar
          </button>
        </div>
        <div class="col-lg-4 mt-3">
          <button type="button" id="btnModificar" class="btn btn-warning w-50 btn-lg">
            <i class="bi bi-pencil-square"></i> Modificar
          </button>
        </div>
        <div class="col-lg-4 mt-3">
          <button type="button" id="btnCancelar" class="btn btn-danger w-100 btn-lg">
            <i class="bi bi-x-circle"></i> Cancelar
          </button>
        </div>
      </div>
    </form>
  </div>

  <div class="col-lg-9 border bg-light
   shadow-lg rounded mx-auto my-5 text-dark">
    <h2 class="mb-3 mt-3 fw-bold text-primary">Ciberataques</h2>
    <table class="table table-bordered table-hover w-100 text-center shadow mt-3" id="tablaCiberAtaque"></table>
  </div>

</div>


<script src="<?= asset('./build/js/ciberataque/index.js') ?>"></script>