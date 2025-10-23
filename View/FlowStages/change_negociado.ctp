<h2 class="titleflujost">
	<?php echo $datos['ProspectiveUser']['id'] ?> - 
	<?php echo mb_strtoupper($this->Utilities->name_prospective_contact($datos['ProspectiveUser']['id'])); ?>&nbsp;
	<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
		<span class="orderst">ÓRDEN DE SERVICIO <b><?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?></b></span>
	<?php endif ?>
</h2>
<br>
<?php echo $this->Form->create('FlowStage',array('id' => 'form_negociado','enctype'=>'multipart/form-data')); ?>
	<?php 
		echo $this->Form->hidden('flujo_id',array('value' => $datos['ProspectiveUser']['id']));
		echo $this->Form->input('description',array('type'=>'textarea','label' => false,'placeholder' => 'Descripción'));
		echo $this->Form->input('document',array('type' => 'file','label' => 'Por favor adjunta el pdf con la orden de pago'));
	?>
</form>