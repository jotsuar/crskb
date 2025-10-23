<div class="col-md-12">
			<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>
	<div class="notes form blockwhite">
		<div class="row">
			<div class="col-md-6">
				<h2>Registrar nota</h2>
			</div>
		</div>
		<?php echo $this->Form->create('Note',array('data-parsley-validate'=>true)); ?>
			<?php
				echo $this->Form->input('name',array('label' => 'Nombre:'));
				echo $this->Form->input('description',array('label' => 'Descripción:', 'type' => 'textarea','rows'=>'3'));
				echo $this->Form->input('type',array('label' => 'Tipo de nota:', 'options' => $tipo_nota));
			?>
		<?php echo $this->Form->end('Guardar'); ?>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/notes/index.js?".rand(),						array('block' => 'AppScript'));
?>