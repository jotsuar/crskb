<?php echo $this->Form->create('ContacsUser'); ?>
	<a id="btn_find_existencia_contacto" data-toggle="tooltip" data-placement="right" title="Comprobar que no esté creado">
		<i class="fa fa-refresh"></i>Validar existencia
	</a>
	<?php 
		echo $this->Form->hidden('empresa_id',array('value'=> $empresa_id));
		echo $this->Form->input('name',array('label' => "Nombre completo",'placeholder' => 'Nombre completo'));
		echo $this->Form->input('telephone',array('label' => "Teléfono",'placeholder' => 'Teléfono'));
		echo $this->Form->input('cell_phone',array('label' => "Celular",'placeholder' => 'Celular','type'=>'number'));
		echo $this->Form->input('email',array('label' => "Correo electrónico",'placeholder' => 'Correo electrónico',"type" => "email"));

		if(!empty($consesiones)){
			echo $this->Form->input('concession_id',array('label' => "Concesión a la que pertenece",'placeholder' => 'Seleccionar',"options"=>$consesiones, "empty"=>"Ninguna"));
		}else{
			echo $this->Form->hidden('concession_id',array('value'=> null));
		}

	?>
</form>