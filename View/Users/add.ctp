<div class="container">
	<div class="col-md-12">
		<div class=" widget-panel widget-style-2 bg-azul big">
		<i class="fa fa-1x flaticon-settings"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Configuraciones </h2>
	</div>
		<div class="users form blockwhite">
			<div class="col-md-12">
				<?php echo $this->Form->create('User',array('enctype'=>"multipart/form-data",'data-parsley-validate'=>true)); ?>
					<fieldset>
						<h2>Registrar asesor</h2>
						<?php
							echo $this->Form->input('name',array('placeholder' => 'Nombre completo','label' => 'Nombre completo'));
							echo $this->Form->input('identification',array('placeholder' => 'Identificación','label' => 'Identificación'));
							echo $this->Form->input('city',array('placeholder' => 'Ciudad','label' => 'Ciudad','autocomplete'=>'on','required'=>true));
							echo $this->Form->input('img',array('type' => 'file','label' => 'Foto de perfil'));
							echo $this->Form->input('cell_phone',array('placeholder' => 'Celular','label' => 'Celular'));
							echo $this->Form->input('telephone',array('placeholder' => 'Teléfono','label' => 'Teléfono'));
							echo $this->Form->input('email',array('placeholder' => 'Correo electrónico','label' => 'Correo electrónico'));
							echo $this->Form->input('role',array('label' => 'Rol:', 'options' => $roles));
						?>
					</fieldset>
				<?php echo $this->Form->end('Guardar'); ?>
			</div>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/users/add.js?".rand(),						array('block' => 'AppScript'));
?>