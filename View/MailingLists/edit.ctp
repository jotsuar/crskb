<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12">
				<h2 class="titleviewer">Editar listas de distribución</h2>
			</div>
		</div>
	</div>

	<div class=" blockwhite spacebtn20">
		<?php echo $this->Form->create('MailingList'); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('name',["label" => "Nombre","required"]);
				echo $this->Form->input('type',["label" => "Tipo de lista","required","options" => ["1" => "Whatsapp","2" => "Correos"]]);
				echo $this->Form->input('numbers',["label" => "Números / Correos","required","type" => "text"]);
			?>
		<div class="form-control">
			<?php echo $this->Form->input('archivo',["label" => "Subir CSV","required"=>false,"type" => "file"]); ?>
		</div>
		<?php echo $this->Form->end(__('Guardar')); ?>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/mailist/add.js?".rand(),    array('block' => 'AppScript'));
?>

