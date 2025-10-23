<?php foreach ($pagos as $key => $value): ?>
	<p class="resetmargin">
		<b>Valor: </b>$<?php echo number_format((int)h($value['FlowStage']['valor']),0,",","."); ?> 
		<?php echo $this->Utilities->type_pay_quotation($value['FlowStage']['type_pay']); ?> 
		a través de 
		<?php echo $value['FlowStage']['payment']; ?>
	</p>

	<?php if ($value['FlowStage']['type_pay'] == 2): ?>
		<div class="stylebtn">
			<a href="javascript:void(0)" class="find_payments_flujo" data-toggle="tooltip" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" title="Pagos realizados">Ver más pagos<i class="fa fa-question" aria-hidden="true"></i></a>
		</div>
	<?php endif ?>
	<?php 
		$estados_credito = array('3','5');
		if (!in_array($value['FlowStage']['type_pay'],$estados_credito)){
	?>
		<div class="Comprobanteacep imgbuy">
			<img datacomprobante="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['FlowStage']['document']) ?>" src="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['FlowStage']['document']) ?>" class="comprobanteimg" width="0px">
			Comprobante de pago &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
		</div>
	<?php } else { ?>
		<b>Número de días para el crédito: </b><?php echo $this->Utilities->number_day_payment_text($value['FlowStage']['payment_day']); ?><br>
	<?php } ?>

	<div class="statushop"><b>Estado del pago: </b><?php echo $this->Utilities->check_state_flow_stage($value['FlowStage']['state']) ?></div>
	<?php if ($value['FlowStage']['state'] == 4): ?>
		<b>Mótivo del rechazo: </b><?php echo $value['FlowStage']['payment_false_description']; ?>
	<?php endif ?>
	<?php if ($datosProspecto['ProspectiveUser']['state_flow'] < Configure::read('variables.control_flujo.flujo_cancelado')): ?>
		<?php if ($this->Utilities->validate_new_quotation($idLatestRegystri,$value['FlowStage']['id'],$value['FlowStage']['state'],$datosProspecto['ProspectiveUser']['id'])): ?>			
			Nota: No se ha completado la totalidad de pago.		
		<?php endif ?>
	<?php endif ?>
<?php endforeach ?>