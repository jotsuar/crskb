<div class="container">
	<?php echo $this->Form->create('product',array('id' => 'form_novedad')); ?>
		<?php echo $this->Form->hidden('producto_import_id',array('value' => $producto_import_id)); ?>
		<div class="form-group controlheight">
			<?php echo $this->Form->input('description',array('label' => false,'type' => 'textarea','rows'=>'3','placeholder' => 'Por favor ingresa la novedad')); ?>
		</div>
	</form>
</div>