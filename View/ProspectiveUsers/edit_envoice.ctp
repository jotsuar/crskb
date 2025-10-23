<?php echo $this->Form->create($typeModel,array("id" => "formEditFact","type" => "file")); ?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->label('bill_date','Fecha de factura');
		echo $this->Form->text('bill_date', [ "label" => "Fecha de factura", "class"=>"form-control","type" =>"date"]);
		echo $this->Form->input('bill_user',[ "label" => "Usuario que factura", "options" =>$usuariosAsesores ]);
		echo $this->Form->input('bill_value',[ "label" => "Valor sin IVA" ]);
		echo $this->Form->input('bill_value_iva',[ "label" => "Valor con IVA" ]);
	?>
	<input type="submit" value="Guardar oportunidad de venta" class="btn btn-success pull-right" >
</form> 