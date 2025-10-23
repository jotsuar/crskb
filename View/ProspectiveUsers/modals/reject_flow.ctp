<?php echo $this->Form->create('ProspectiveUser',array("id" => "formReject","type" => "file")); ?>

<div class="row p-5">
	<div class="col-md-8">
		<?php echo $this->Form->input('id',array('type' => 'hidden')); ?>
		<?php echo $this->Form->input('reject',array('type' => 'hidden','value'=>2)); ?>
		<?php echo $this->Form->input('reject_reason',array('type' => 'textarea','rows'=>'3','label' => "Detalle del rechazo",'placeholder' => 'Por favor detalla la razón del rechazo, recuerda que solo se permiten flujos provenientes de chat','required')); ?>
	</div>
	<div class="col-md-4">
		<?php echo $this->Form->input('reject_image', array("label" => "Evidencia del chat (Requerida)", "type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify",'required' )) ?>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<input type="submit" value="Enviar rechazo" class="btn btn-success float-right col-md-2">
		</div>
	</div>
</div>

</form> 