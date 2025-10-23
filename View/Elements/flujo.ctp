<!-- Modal para registrar un requerimiento -->
<div class="modal fade" id="agregarFlujoModal" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_requerimiento_label">Registrar nueva oportunidad de venta</h2>
      </div>

      <div class="modal-body" id="cuerpoFlujoNuevo">

        <div class="cuerpoFlujoAdd"></div>
        <div class="cuerpoContactoCliente"></div>
        <div id="ingresoCliente"></div>
        
      </div>

      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>
    </div>
  </div>
</div>

<?php echo $this->Html->script("controller/customers/flujo.js?".time(),     array('block' => 'AppScript'));  ?>