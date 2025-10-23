<?php if (isset($dataFlujo)): ?>
	<h2 class="text-center text-info">
		La cotización enviada al cliente se encuentra en proceso de validación.
	</h2>
<?php else: ?>
	<?php echo $this->Form->create('FlowStage',array('id' => 'form_cotizado','enctype'=>'multipart/form-data'));
		echo $this->Form->hidden('flujo_id',array('value' => $datos['ProspectiveUser']['id']));
		echo $this->Form->hidden('flowstage_id',array('value' => $id_flow_bussines));
		if (count($quotationList) > 0){
			echo $this->Form->input('quotation_id',array('label' => 'Por favor selecciona la cotización que se va a enviar al cliente','options' => $quotationList));
			echo $this->Form->input('copias_email',array("value" => $emails, "type" => "text"));
	?>
			<a href="" class="btn btn-info btn-sm borraEmails"> <i class="fa fa-trash vtc"></i> Eliminar copias </a> <br>
			<label class="cotiza">¿Enviar un correo electrónico con la información de la cotización al cliente?</label>
			<div class="form-check-inline">
				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1"> SI
				</label>
			</div>
			<div class="form-check-inline">
				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0"> NO
				</label>
			</div>
		<?php } else { ?>
			<p class="copiealert">Por favor crea una cotización en el CMR</p>
		<?php } ?>
		<center>
			<a class="alingicon copiealert" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'add',$datos['ProspectiveUser']['id'],$id_flow_bussines)) ?>" data-toggle="tooltip" data-placement="right" title="Hacer cotización">
					HACER COTIZACIÓN &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
			</a>
		</center>
	</form>
<?php endif ?>