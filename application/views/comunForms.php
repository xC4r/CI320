
<!-- Modal Forms -->
<div class="modal fade" tabindex="-1" id='confirmForm' role="dialog" aria-labelledby="ConfirmlLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" data-toggle="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" id='progressForm' data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="ConfirmlLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="progress">
          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" id='expiredSesion' role="dialog" aria-labelledby="ConfirmlLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Sesion Expirada</h5>
      </div>
      <div class="modal-body">Tu sesion ha expirado. Por favor, ingresa de nuevo para continuar.</div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" data-toggle="modal" onclick='location.reload();'>Aceptar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" id='alertForm' role="dialog" aria-labelledby="ConfirmlLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Alerta</h5>
      </div>
      <div class="modal-body">...</div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" data-toggle="modal" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>
