<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
		<i class="fa fa-1x flaticon-growth"></i>
		<h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Configuración de bloqueos por gestión de flujos</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="configFlows form">
			<?php echo $this->Form->create('ConfigFlow'); ?>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('hours_contact', [ "label" => "Horas para contactar luego de asignar el flujo" ]);
					echo $this->Form->input('hours_quotation',[ "label" => "Horas para cotizar luego de contactar el flujo" ]);
					echo $this->Form->input('flows_cntact',[ "label" => "Número de flujos en estado asignado para bloquear el asesor" ]);
					echo $this->Form->input('flows_quotation',[ "label" => "Número de flujos en estado contactado para bloquear el asesor" ]);
					echo $this->Form->input('flows_deleted',[ "label" => "Número de flujos máximo para solicitar eliminación" ]);
					echo $this->Form->input('flows_no_gests',[ "label" => "Número de flujos máximo sin gestión en cotizado para bloquear" ]);
				?>
			<?php echo $this->Form->end(__('Guardar')); ?>
		</div>
	</div>
</div>

	<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	?>

