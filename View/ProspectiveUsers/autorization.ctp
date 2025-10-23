<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
		<i class="fa fa-1x flaticon-growth"></i>
		<h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Autorización de tiempos de bloqueos por gestión de flujos</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="configFlows form">
			<?php echo $this->Form->create('ProspectiveUser',['data-parsley-validate'=>true]); ?>
				<?php
					echo $this->Form->input('user_id',['label' => 'Usuario al que le aplicará más tiempo de autorización',"options"=>$usuarios, "required" ]);
					echo $this->Form->input('time_auth', [ "label" => "Fecha y hora para autorizar", "type" => "datetime-local", "value"=>date("Y-m-d H:i:s"), "required" ]);
					echo $this->Form->input('message', [ "label" => "Mensaje de autorización", "type" => "textarea", "required" ]);
				?>
			<?php echo $this->Form->end(__('Guardar')); ?>
		</div>
	</div>
</div>

	<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	?>

