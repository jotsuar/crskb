<div class="col-md-12">
			<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>
	<div class="row">
		<div class="col-md-12">
			<div class="notes view blockwhite">
				<small class="themename"><?php echo $this->Utilities->type_note($note['Note']['type']); ?></small>

				<p><b>Nombre: </b><?php echo $this->Utilities->data_null(h($note['Note']['name'])); ?>&nbsp;</p>

				<p><b>Descripción: </b><?php echo $this->Utilities->data_null($note['Note']['description']); ?>&nbsp;</p>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp')); ?>