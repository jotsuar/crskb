<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Editar empresa para concesion</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="concessions form">
		<?php echo $this->Form->create('Concession'); ?>
			<fieldset>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('name',['label'=>'Nombre']);
				echo $this->Form->input('description',['label'=>"Descripción"]);
				echo $this->Form->input('clients_legal_id',['label'=>'Empresa o consesión a la que pertenece','class' => 'selectTo2','empty'=>'Seleccionar']);
			?>
			</fieldset>
		<?php echo $this->Form->end(__('Guardar')); ?>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/brands/save.js?".rand(),						array('block' => 'AppScript'));
?>



