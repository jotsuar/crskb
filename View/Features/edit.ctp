
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Editar característica</h2>
			</div>
			<div class="col-md-6 text-right">				
				<a href="<?php echo $this->Html->url(array('controller'=>'features_values','action'=>'add',$this->Utilities->encryptString($this->request->data["Feature"]["id"]))) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nuevo valor</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'features','action'=>'index')) ?>" class="crearclientej"><i class="fa fa-1x fa-list"></i> <span>Listar características</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'features_values','action'=>'index',$this->Utilities->encryptString($this->request->data["Feature"]["id"]))) ?>" class="crearclientej"><i class="fa fa-1x fa-list"></i> <span>Listar valores</span></a>
			</div>
		</div>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="brands form">
		<?php echo $this->Form->create('Feature',array('data-parsley-validate'=>true,)); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('name',array("label" => "Nombre característica","required"));
			?>
			<div class="form-group">
				<input type="submit" value="Guardar y listar" class="btn btn-success">
				<input type="submit" value="Guardar y crear otra característica" class="btn btn-primary" name="other">
			</div>
		<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
?>
