<div class="clientsLegals form">
	<?php echo $this->Form->create('ClientsLegal'); ?>
		<a id="btn_find_existencia_juridico" data-placement="right" data-toggle="tooltip" title="Comprobar que no exita el cliente">
			<i class="fa fa-refresh"></i>Validar existencia
		</a>
		<?php echo $this->Form->input('name',array('label' => "Nombre",'placeholder' => 'Nombre'));?>
		<?php
			echo $this->Form->input('nit',array('label' => "NIT",'placeholder' => 'NIT'));
			echo $this->Form->hidden('client_id');
		?>
	</form>
</div>