<div class="clientsNaturals form">
	<?php echo $this->Form->create('ClientsNatural',array('data-parsley-validate'=>true,"id"=>"changeFormClientData")); ?>
		<?php
			echo $this->Form->hidden('id',array('value' => $clientsNaturals['ClientsNatural']['id']));
			echo $this->Form->hidden('flujo_id',array('value' => $flujo_id));
		?>
		<div class="row">
			<div class="col-md-6">
				<?php echo $this->Form->input('name_emp',array('label' => 'Nombre empresa','placeholder' => 'Ingresa el nombre de la empresa', "required")); ?>
			</div>			
			<div class="col-md-6">
				<?php echo $this->Form->input('nacional',array('label' => 'La empresa es nacional o internacional','options' => ["1"=>"Nacional","2" => "Internacional"] , "required")); ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('nit',array('label' => 'Nit empresa','placeholder' => 'Ingresa el nit de la empresa', "required")); ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('name',array('label' => 'Nombre contacto','placeholder' => 'Ingresa el nombre del contacto','value' => $clientsNaturals['ClientsNatural']['name'], "required")); ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('telephone',array('label' => 'Teléfono','placeholder' => 'Ingresa el teléfono del contacto','value' => !empty($clientsNaturals['ClientsNatural']['telephone']) ? $clientsNaturals['ClientsNatural']['telephone'] : $clientsNaturals['ClientsNatural']['cell_phone'], "required","readonly")); ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('cell_phone',array('label' => 'Celular','placeholder' => 'Ingresa el celular del cliente','value' => $clientsNaturals['ClientsNatural']['cell_phone'],'type' => 'number', "required","readonly")); ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('email',array('label' => 'Correo electrónico','placeholder' => 'Ingresa el correo electrónico del cliente','value' => $clientsNaturals['ClientsNatural']['email'],"type" => "email", "required", empty($clientsNaturals['ClientsNatural']['email']) ? '' : 'readonly' )); ?>
			</div>
			<div class="col-md-6">
				<?php echo $this->Form->input('type',array('label' => 'Como se debe guardar el cliente','options' => ["1"=>"Guardar solo registro jurídico y borrar natural","2" => "Conservar ambos registros"] , "required")); ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $this->Form->input('city',array('label' => 'Ciudad','placeholder' => 'Ciudad','readonly' => false,'value' => $clientsNaturals['ClientsNatural']['city'], "required")); ?>
			</div>
			<div class="col-md-6 text-center">
				<input type="submit" class="btn btn-success" value="Cambiar cliente">
			</div>
		</div>
		
	</form>
</div>