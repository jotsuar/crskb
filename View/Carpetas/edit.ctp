<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-green big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">BIBLIOTECA CRM KEBCO SAS </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Edición de categorías</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<?php echo $this->Form->create('Carpeta',array('data-parsley-validate'=>true,)); ?>
		<?php
			echo $this->Form->input('id');
			echo $this->Form->input('name', ["label" => "Nombre" ]);
			echo $this->Form->input('carpeta_id', ["label" => "Carpeta Principal", "options" => $carpetas, "empty" => "Seleccionar" ]);
			echo $this->Form->input('description', ["label" => "Descripción" ]);
			echo $this->Form->input('user_id',["value" => AuthComponent::user("id"),"type" => "hidden"]);
		?>		
		<?php echo $this->Form->end(__('Guardar')); ?>	
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>
