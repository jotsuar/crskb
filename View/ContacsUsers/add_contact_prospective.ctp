<?php if (!isset($this->request->data["modal"])): ?>	
	<a href="#" class="returnContactProspective"> <i class="fa fa-arrow-left"></i> Regresar</a>
<?php endif ?>
<h2 class="text center text-info mb-2">
		Agregar contacto 
</h2>
<?php echo $this->Form->create('ContacsUser',array("id" => "contactoProspectiveUserNew")); ?>
	<a id="btn_find_existencia_contacto" data-toggle="tooltip" data-placement="right" title="Comprobar que no esté creado">
		<i class="fa fa-refresh"></i>Validar existencia
	</a>
	<?php 
		echo $this->Form->hidden('empresa_id',array('value'=> $empresa_id));
		echo $this->Form->input('name',array('label' => "Nombre completo",'placeholder' => 'Nombre completo',"required"));
		echo $this->Form->input('telephone',array('label' => "Teléfono",'placeholder' => 'Teléfono'));
		echo $this->Form->input('cell_phone',array('label' => "Celular",'placeholder' => 'Celular','type'=>'number'));
		echo $this->Form->input('city',array('label' => "Ciudad",'autocomplete' => "off",'placeholder' => 'Ciudad',"id"=>"CityContactProspective","required"));
		echo $this->Form->input('email',array('label' => "Correo electrónico",'placeholder' => 'Correo electrónico',"type" => "email","required"));
		if(!empty($consesiones)){
			echo $this->Form->input('concession_id',array('label' => "Concesión a la que pertenece",'placeholder' => 'Seleccionar',"options"=>$consesiones, "empty"=>"Ninguna"));
		}else{
			echo $this->Form->hidden('concession_id',array('value'=> null));
		}
	?>
	<input type="submit" value="Guardar contacto" class="btn btn-success pull-right" >
</form>