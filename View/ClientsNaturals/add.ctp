<div class="clientsNaturals form">
	<?php echo $this->Form->create('ClientsNatural'); ?>
		<a id="btn_find_existencia" class="natural" data-toggle="tooltip" data-placement="right" title="Comprobar que no esté creado">
			<i class="fa fa-refresh"></i>Validar existencia
		</a>
		<?php
			echo $this->Form->input('id');
			echo $this->Form->hidden('action',array('value' => 'add'));
			echo $this->Form->input('name',array('label' => 'Nombre *','placeholder' => 'Ingresa el nombre del cliente'));
			echo $this->Form->input('identification',array('label' => 'Identificación','placeholder' => 'Ingresa la Identificación del cliente.'));
			echo $this->Form->input('telephone',array('label' => 'Teléfono','placeholder' => 'Ingresa el teléfono del cliente'));
			echo $this->Form->input('cell_phone',array('label' => 'Celular','placeholder' => 'Ingresa el celular del cliente','type' => 'number'));
			echo $this->Form->input('email',array('label' => 'Correo electrónico *','placeholder' => 'Ingresa el correo electrónico del cliente',"type" => "email"));
		?>
	</form>
</div>
