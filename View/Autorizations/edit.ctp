<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Editar autorización de abonos</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="brands form">
			<?php echo $this->Form->create('Autorization'); ?>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('prospective_user_id',['label' => 'Flujo', "type" => 'number' ]);
					echo $this->Form->input('valor');
				?>
			<?php echo $this->Form->end(__('Guardar')); ?>
		</div>
	</div>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),	array('block' => 'jqueryApp'));	
?>

