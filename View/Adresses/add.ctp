<?php echo $this->Form->create('Adress',array('id' => 'formAdminAddress','enctype'=>'multipart/form-data')); ?>
<?php 
		echo $this->Form->hidden('id');
	echo $this->Form->hidden('clients_legal_id');
		echo $this->Form->hidden('clients_natural_id');

 ?>
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input("name", array("label" => "Nombre de quien recibe el envío" ,"Placeholder" => "Ingresa el nombre del destinatario" ,"required", )) ?>
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input("city", array("label" => "Ciudad" ,"required", )) ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="form-group">
				<?php echo $this->Form->input("address", array("label" => "Dirección" ,"required","Placeholder" => "Ingresa la dirección exacta del destino" )) ?>
			</div>
		</div>
		<div class="col-md-12 col-sm-12">
			<div class="form-group">
				<?php echo $this->Form->input("address_detail", array("label" => "Más información de la dirección" , "Placeholder" => "Por favor ingresa más detalles que faciliten llegar a la dirección, nombre de calles, edificios, interiores, unidades residenciales, etc." ,)) ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input("phone", array("label" => "Teléfono" ,"Placeholder" => "Ingresa el número de contacto del destinatario" ,"required", )) ?>
			</div>
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-group">
				<?php echo $this->Form->input("phone_two", array("label" => "Otro teléfono","Placeholder" => "Ingresa otro número de contacto", "required" => false )) ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<input type="submit" id="btnFormSubmit" value="Guardar información" class="btn btn-success pull-right mt-3">
		</div>
	</div>
	
</form>

