<!-- Modal -->
<div class="modal fade " id="modalFlujo" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close cierreModal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Datos del flujo</h5>
      </div>
      <div class="modal-body" id="cuerpoModalFlujo">
        <div class="row">
          <div class="col-md-8" id="cuerpoModalInicial"><img src="<?php echo $this->Html->url('/img/preload.gif'); ?>" id="loadajax"></div>
          <div class="col-md-4" id="resultadoDatos_"><img src="<?php echo $this->Html->url('/img/preload.gif'); ?>" id="loadajax"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cierreModal" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade " id="modalFlujoCotizacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close cierreModal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Cotización del flujo</h5>
      </div>
      <div class="modal-body heightresults" id="cuerpoModalFlujoCotizacion">
        <img src="<?php echo $this->Html->url('/img/preload.gif'); ?>" id="loadajax">
      </div>
      <div class="modal-footer">
        <?php if (isset($aprobar)): ?>
          <div id="botonesAprueba">
            
          </div>
        <?php endif ?>
        <button type="button" class="btn btn-secondary cierreModal" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalFlujoOrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close cierreModal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Órden de compra para el flujo</h5>
      </div>
      <div class="modal-body" id="cuerpoModalFlujoOrdenCompra">
        <img src="<?php echo $this->Html->url('/img/preload.gif'); ?>" id="loadajax">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cierreModal" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="modalFlujoPagos" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close cierreModal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Pago(s) asociado(s) al flujo</h5>
      </div>
      <div class="modal-body" id="cuerpoModalFlujoPagos">
        <img src="<?php echo $this->Html->url('/img/preload.gif'); ?>" id="loadajax">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cierreModal" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php 
  echo $this->Html->script("controller/prospectiveUsers/flujoView.js?".rand(),    array('block' => 'AppScript')); ?>