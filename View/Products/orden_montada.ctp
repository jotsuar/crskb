<div class="container">
	<?php echo $this->Form->create('orden',array('id' => 'form_orden')); ?>
		<?php echo $this->Form->hidden('producto_import_id',array('value' => $producto_import_id)); ?>

		<?php echo $this->Form->input('numero_orden',array('label' => false,'placeholder' => 'Por favor ingresa el número de orden')); ?>

		<?php echo $this->Form->input('proveedor',array('label' => false,'placeholder' => 'Por favor ingresa el proveedor')); ?>
	</form>
</div>