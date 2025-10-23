<div class="container">
	<?php echo $this->Form->create('product',array('id' => 'form_amerimpex')); ?>
		<?php echo $this->Form->hidden('producto_import_id',array('value' => $producto_import_id)); ?>

		<?php echo $this->Form->input('numero_guia',array('label' => false,'placeholder' => 'Por favor ingresa el número guia')); ?>

		<?php echo $this->Form->input('transportadora',array('label' => false,'placeholder' => 'Por favor ingresa la transportadora')); ?>
		<label for="">Aplicar a todos los productos</label>
		<?php echo $this->Form->checkbox('all_products',array('class' => 'form-control',"label" => "Aplicar a todos los productos"));?>
	</form>
</div>