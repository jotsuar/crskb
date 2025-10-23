<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
		<i class="fa fa-1x flaticon-growth"></i>
		<h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Configuración de bloqueos no gestión de solicitudes de despacho/facturación</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="configFlows form">
			<?php echo $this->Form->create('ConfigFlow'); ?>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('hours_shipping', [ "label" => "Horas para gestionar solicitud luego de creada" ]);
					echo $this->Form->input('user_shipping', [ "label" => "Usuario asignado para el despacho que tendra el bloqueo", "options" => $users ]);
				?>

				<div class="form-group mb-5">
					<label for="configFlowsMaxHourShipping">Máxima hora para solicitar facturación</label>
					<?php	
						echo $this->Form->text('max_hour_envoyce',[ "label" => "Máxima hora para solicitar despacho / Facturación", "type" => "time", "class" => "form-control mb-3" ]);
					?>
				</div>
				<div class="form-group mb-5">
					<label for="configFlowsMaxHourEnvoice">Máxima hora para solicitar despacho</label>
					<?php	
						echo $this->Form->text('max_hour_shipping',[ "label" => "Máxima hora para solicitar despacho / Facturación", "type" => "time", "class" => "form-control mb-3" ]);
					?>
				</div>
				<div class="form-group mb-5">
					<label for="configFlowsMaxHourEnvoice">Máxima hora para solicitar remisión</label>
					<?php	
						echo $this->Form->text('max_hour_remission',[ "label" => "Máxima hora para solicitar remisión", "type" => "time", "class" => "form-control mb-3" ]);
					?>
				</div>
				<div class="form-group mb-5">
					<label for="configFlowsMaxHourShippingSat">Máxima hora para solicitar despacho y facturación el día sábado</label>
					<?php	
						echo $this->Form->text('max_hour_shipping_sat',[ "label" => "Máxima hora para solicitar despacho el día sábado", "type" => "time", "class" => "form-control mb-3" ]);
					?>
				</div>
				<div class="form-group mb-5">
					<label for="configFlowsMaxHourShippingSat">Máxima hora para solicitar despacho y facturación el día sábado</label>
					<?php	
						echo $this->Form->text('max_hour_envoyce_sat',[ "label" => "Máxima hora para solicitar Facturación el día sábado", "type" => "time", "class" => "form-control mb-3" ]);
					?>
				</div>
			<?php echo $this->Form->end(__('Guardar')); ?>
		</div>
	</div>
</div>

	<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	?>

