<?php echo $this->Form->create('ContacsUser'); ?>
	<?php
		echo $this->Form->hidden('id_contact',array('value'=>$contacto_id));
		echo $this->Form->hidden('flujo_id',array('value'=> $flujo_id));
		echo $this->Form->input('name',array('label' => "Nombre completo",'placeholder' => 'Nombre completo','value' => $datos['ContacsUser']['name']));
	?>
	<div class="form-row"> 
		<div class="col">
			<?php echo $this->Form->input('cell_phone',array('label' => "Celular",'placeholder' => 'Celular','type'=>'number','value' => $datos['ContacsUser']['cell_phone'])); ?>
		</div>
		<div class="col">
			<?php echo $this->Form->input('telephone',array('label' => "Teléfono",'placeholder' => 'Teléfono','value' => isset($dataWo->Teléfonos) ? $dataWo->Teléfonos : $datos['ContacsUser']['telephone'])); ?>
		</div>
	</div>
	<?php echo $this->Form->input('email',array('label' => 'Correo electrónico','placeholder' => 'Ingresa el correo electrónico del cliente','value' => $datos['ContacsUser']['email'],"type" => "email")); ?>
	<?php 
	
		if(!empty($consesiones)){
			echo $this->Form->input('concession_id',array('label' => "Concesión a la que pertenece",'placeholder' => 'Seleccionar',"options"=>$consesiones, "empty"=>"Ninguna"));
		}else{
			echo $this->Form->hidden('concession_id',array('value'=> null));
		}

	 ?>
	<div class="form-row">
		<div class="form-group col-md-11">
			<?php echo $this->Form->input('city',array('label' => "Ciudad",'placeholder' => 'Ciudad','readonly' => true,'value' => $datos['ContacsUser']['city'])); ?>
		</div>
		<div class="form-group col-md-1 text-center">
			<a class="btn_editar_ciudad_1" title="Editar ciudad"><i class="fa fa-1x fa-pencil"></i></a>
		</div>
	</div>
</form>