<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">INGRESO DE RECIBOS DE CAJA</h1>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<?php echo $this->Form->create('Receipt'); ?>
              <div class="row">
                <div class="col-md-11">
                  <select class="form-control" id="flujoFactBuscaListReceipt" placeholder="Escriba el número de flujo" aria-label="Escriba el número de flujo" aria-describedby="basic-addon2"></select>
                </div>
                <div class="col-md-1">
                  <button class="btn btn-outline-secondary btn-block" type="button" id="buttonSearchList">Buscar</button>
                </div>
              </div>
            <div class="col-md-12">
              <div id="listadoRecibosActuales"></div>
            </div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade " id="recibodeCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Ingreso de información de recibo de caja</h5>
      </div>
      <div class="modal-body" id="cuerpoRecibo">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
	        Cerrar
	    </button>
      </div>
    </div>
  </div>
</div>

<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>

<?php 	echo $this->Html->script("controller/prospectiveUsers/recibos.js?".rand(),			array('block' => 'AppScript'));  ?>