<?php echo $this->Form->create('User',array('id' => "newEditForm", "type" => "file")); ?>
	<?php 
		echo $this->Form->hidden('user_id',array('value' => $datos['User']['id'], "required"));
		echo $this->Form->input('name',array('label' => 'Nombre Completo','placeholder' => 'Nombre','value' => $datos['User']['name'], "required"));
		echo $this->Form->input('identification',array('placeholder' => 'Identificación','label' => 'Identificación','value' => $datos['User']['identification'], "required"));
		echo $this->Form->input('telephone',array('label' => 'Teléfono','placeholder' => 'Teléfono','value' => $datos['User']['telephone'], "required"));
		echo $this->Form->input('cell_phone',array('label' => 'Celular','placeholder' => 'Celular','value' => $datos['User']['cell_phone'], "required"));
		if ($datos["User"]["role"] == "Asesor Externo") {
			echo $this->Form->input('role',array('label' => 'Rol:', 'type' => "hidden",'default' => $datos['User']['role'], "required"));
		}else{
			echo $this->Form->input('role',array('label' => 'Rol:', 'options' => $roles,'default' => $datos['User']['role'], "required"));
		}
		echo $this->Form->input('img_signature',array('label' => 'Firma del usuario:', "type" => "file", "required" => false ));
		if ($datos["User"]["role"] == "Asesor Externo") {
			echo $this->Form->input('margen',array('label' => 'Margen mínimo de cotización:','value' => $datos['User']['margen'], "required"));
		}
	?>
	<div class="form-row">
		<div class="form-group col-md-11">
			<?php echo $this->Form->input('city',array('label' => false,'placeholder' => 'Ciudad','readonly' => true,'value' => $datos['User']['city'], "required" )); ?>
			<?php echo $this->Form->input('cityForm',array('label' => false,'placeholder' => 'Ciudad *', "required" => false,"style" => "display:none")); ?>
		</div>
		<div class="form-group col-md-1 text-center">
			<a class="btn_editar_ciudad" title="Editar ciudad"><i class="fa fa-1x fa-pencil"></i></a>
		</div>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-success pull-right" value="Guardar información">
	</div>
</form>
