<?php echo $this->Form->create('Shipping',["type" => "file",'data-parsley-validate'=>true]); ?>

	<div class="form-group">
		<?php echo $this->Form->input('id'); ?>
		<?php echo $this->Form->input('note',["label" => "Nota de la gestión ( se incluye la anterior )"]); ?>
	</div>
	<div class="form-group">
		<?php echo $this->Form->input('document_add',["required"=>false,"label" => "Documento adicional", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify", ]); ?>
	</div>
	<small class="text-danger mt-3">
		Nota: Todos los campos son requeridos
	</small>

	<div class="form-group">
		<input type="submit" class="btn btn-success btn-block" value="Actualizar despacho">
	</div>


<?php echo $this->Form->end(); ?>