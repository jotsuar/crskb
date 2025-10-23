<div class="col-md-12">
	<div class="prospectiveUsers form blockwhite">
		<h2>Crear prospecto</h2>
		<div class="container">
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#home">Persona Natural</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#juridico">Persona Jurídica</a>
				</li>
			</ul>	

			<div class="tab-content">
				<div id="home" class="tab-pane active"><br>
					<div id="divNatural">
						<?php echo $this->Form->create('ProspectiveUser',array('data-parsley-validate')); ?>
							<?php echo $this->Form->input('name',array('label' => 'Nombre completo','placeholder' => 'Nombre completo','required' => true));?>

							<div class="form-row"> 
								<div class="col">
									<?php echo $this->Form->input('telephone',array('label' => 'Teléfono','placeholder' => 'Teléfono','required' => true,'type'=>'number')); ?> 
									</div>
								<div class="col">
									<?php echo $this->Form->input('cell_phone',array('label' => 'Celular','placeholder' => 'Celular','required' => true,'type'=>'number'));?>
								</div>
							</div>

							<?php echo $this->Form->input('email',array('label' => 'Correo electrónico','placeholder' => 'Correo electrónico','required' => true));?>


							<div class="form-row"> 
								<div class="col">
									<?php echo $this->Form->input('city',array('label' => 'Ciudad','placeholder' => 'Ciudad','required' => true)); ?>
								</div>
								<div class="col">
									<?php echo $this->Form->input('origin',array('label' => 'Origen:', 'options' => $origen));?>
								</div>
							</div>

							<?php
								echo $this->Form->input('reason',array('label' => 'Asunto/Motivo/Solicitud/Requerimiento','placeholder' => 'Por favor ingresa un nombre para esta Solicitud o Requerimiento','required' => true));
								echo $this->Form->input('description',array('type' => 'textarea','rows'=>'3','label' => 'Comentario','placeholder' => 'Describa la solicitud o requerimiento inicial del cliente...','required' => true));
								echo $this->Form->input('user_id',array('label' => 'Asignado a:','value'=>AuthComponent::user('id'), 'options' => $usuarios));
							?>
						<?php echo $this->Form->end('Guardar Prospecto'); ?>
					</div>
				</div>

				<div id="juridico" class="tab-pane fade"><br>
					<div id="divJuridico">
						<form>
							<?php 
								echo $this->Form->input('name',array('label' => "Razón social",'placeholder' => 'Nombre de la empresa'));
								echo $this->Form->input('nit',array('label' => "Identificación",'placeholder' => 'NIT'));
							?>
							<button id="btn_guardar" type="button">Guardar Prospecto</button>
						</form>	
					</div>    	
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/add.js?".rand(),				array('block' => 'AppScript'));
?>