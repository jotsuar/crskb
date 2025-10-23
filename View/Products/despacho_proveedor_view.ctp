<div class="container">
	<?php echo $this->Form->create('product',array('id' => 'form_despacho')); ?>
		<?php echo $this->Form->hidden('producto_import_id',array('value' => $producto_import_id)); ?>

		<?php echo $this->Form->input('link',array('label' => false,'placeholder' => 'Por favor ingresa el link correspondiente al despacho')); ?>

		<label>Fecha estimada de  llegada</label>
		<?php echo $this->Form->date('fecha_fin',array('class' => 'form-control','value' => !is_null($this->request->data["deadline"]) ? $this->request->data["deadline"] :  date("Y-m-d"), "readonly" => !is_null($this->request->data["deadline"]) ? true : false ));?>
		<!-- <label for="">Aplicar a todos los productos</label> -->
		<?php //echo $this->Form->checkbox('all_products',array('class' => 'form-control',"label" => "Aplicar a todos los productos"));?>
	</form>
</div>