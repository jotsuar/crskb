<?php echo $this->Form->create('Account',["type" => "file",'data-parsley-validate'=>true]); ?>

		<?php echo $this->Form->input('id',["type" => "hidden","value" => $account["Account"]["id"] ]); ?>
		<?php if ($account["Account"]["state"] == 0): ?>
			<?php $estados = ["1" => "En gestión" ]; ?>
			<?php echo $this->Form->input('date_gest',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
			<div class="form-group">
				<label for="">
					Fecha probable de pago
				</label>
				<?php echo $this->Form->text('date_deadline',["type" => "date", "value" => date("Y-m-d"),"label" => "Fecha probable de pago", "class" => "form-control" ]); ?>
			</div>
		<?php elseif($account["Account"]["state"] == 1): ?>
			<?php $estados = ["2" => "Pagado"]; ?>
			<?php echo $this->Form->input('notes',["label" => "Notas adicionales", "class" => "form-control" ]); ?>
			<?php echo $this->Form->input('value_payment',["label" => "Valor del pago", "class" => "form-control" ]); ?>
			<?php echo $this->Form->input('date_payment',["type" => "hidden", "value" => date("Y-m-d") ]); ?>
			<?php echo $this->Form->input('modified',["type" => "hidden", "value" => date("Y-m-d H:i:s") ]); ?>
			<?php echo $this->Form->input('document',["required"=>true,"label" => "Documento adjunto", "type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify" ]); ?>
		<?php endif ?>
	<div class="form-group">
		<?php echo $this->Form->input('state',["label" => "Estado", "options" => $estados, "class" => "form-control" ]); ?>		
	</div>

	<small class="text-danger mt-3">
		Nota: Todos los campos son requeridos
	</small>

	<div class="form-group">
		<input type="submit" class="btn btn-success btn-block" value="Actualizar cuenta de cobro">
	</div>


<?php echo $this->Form->end(); ?>