
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-rojo big">
         <i class="fa fa-1x flaticon-settings-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Servicio Técnico</h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Editar accesorio</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="brands form">
		<?php echo $this->Form->create('Aditional',array('data-parsley-validate'=>true,)); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('accesorio',array("label" => "Nombre Accesorio","required"));
				echo $this->Form->input('user_id',array("label" => false, "type"=>"hidden",));
			?>
		<?php echo $this->Form->end(__('Guardar')); ?>
		</div>
	</div>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
?>
