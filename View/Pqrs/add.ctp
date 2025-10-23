<?php if (!is_null($clienteActivo) && !AuthComponent::user("id")): ?>
	<?php echo $this->element("nav_cliente", ["cliente" => $clienteActivo, "action" => "pqrs"]); ?>
<?php endif ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo PQRS </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Crear PQRS</h2>
	</div>
	<div class="blockwhite spacebtn20">
		<?php echo $this->Form->create('Pqr',array('data-parsley-validate'=>true,"type" => "file","autocomplete" => "off")); ?>
		<div class="row">
			<div class="col-md-4">
				<?php echo $this->Form->input('email',["label" => "Correo electrónico *"]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('city',["label" => "Ciudad * ","autocomplete" => "off"]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('phone',["label" => "Teléfono"]); ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('subject',["label" => "Asunto *", "options" => Configure::read("PQRS") ]); ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('response_type',["label" => "Médio de respuesta", "options" => ["1" => "Correo electrónico", "2" => "Teléfono (Se requiere que ingrese el teléfono)"] ]); ?>
			</div>
			<div class="col-md-12">
				<?php echo $this->Form->input('description',["label" => "Descripción *","rows" => 5, "style" => "height:auto !important"]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('file1',["label" => "Archivo 1", "type" => "file", "data-parsley-fileextension" => "1" ]); ?>
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('file2',["label" => "Archivo 2", "type" => "file", "data-parsley-fileextension" => "1" ]); ?>				
			</div>
			<div class="col-md-4">
				<?php echo $this->Form->input('file3',["label" => "Archivo 3", "type" => "file", "data-parsley-fileextension" => "1" ]); ?>				
			</div>
		</div>
			<?php
				
				
				
				
				
				echo $this->Form->input('code', ["type" => "hidden","value" => date("Ymdhis")]);
				echo $this->Form->input('state',["type" => "hidden", "value" => 1]);
			?>
		<?php echo $this->Form->end(__('Enviar PQRS')); ?>
	</div>
</div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/pqrs/admin.js?".rand(),						array('block' => 'AppScript'));
?>