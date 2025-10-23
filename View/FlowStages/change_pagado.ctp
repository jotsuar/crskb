<h2 class="titleflujost">
	<?php echo $datos['ProspectiveUser']['id'] ?> - 
	<?php echo mb_strtoupper($this->Utilities->name_prospective_contact($datos['ProspectiveUser']['id'])); ?>&nbsp;
	<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
		<span class="orderst">ÓRDEN DE SERVICIO <b><?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?></b></span>
	<?php endif ?>
</h2>
<?php if (isset($cotizacion_usd)): ?>
	<div class="container">
		<h3 class="notemodal">Se detectó que en la cotización productos cotizados en USD</h3>
		<div class="col-md-12 text-center mb-3">
			<a href="<?php echo $this->Html->url(array("controller" => "quotations", "action" => "view", $this->Utilities->encryptString($cotizacion))) ?>" class="btn btn-success" target="_blank">
				Ver cotización
			</a>
		</div>
		<p class="text-center">Se debe hacer una conversión de dolares a pesos para poder procesar el pago</p>
		<hr>
		<div class="row text-center">
			<div class="col-md-12">
				<h3 class="notemodal">Procesar cambio con el trm del día de pago: </h3>
			</div>
			<div class="col-md-4">
				<select name="trmDia" id="trmDia">
					<option value="">Seleccionar</option>
					<?php for ($day=0; $day <= 14; $day++) : ?>
						<option value="<?php echo $this->Utilities->getDayTrm( date("Y-m-d",strtotime("- $day day")), $ajuste, $trmActual )[1]; ?>">
							<?php echo $this->Utilities->getDayTrm( date("Y-m-d",strtotime("- $day day")), $ajuste, $trmActual )[0]; ?>
						</option>				
					<?php endfor; ?>
				</select>
			</div>
			<div class="col-md-4">
				<input type="number" name="trmDiaCustom" id="trmDiaCustom" min="0" class="form-control" placeholder="Otro valor">
			</div>
			<div class="col-md-4">
				<a href="#" class="btn btn-warning btn-block" id="btnProcesarCambioDolar" data-flujo="<?php echo $flujo_id ?>" data-quotation="<?php echo $cotizacion ?>">
					Procesar cambio
				</a>
			</div>
			

		</div>
	</div>
	<style>
		#btn_guardar_pagado{
			display: none;
		}
	</style>
<?php else: ?>
	<?php echo $this->Form->create('FlowStage',array('id' => 'form_pagado','enctype'=>'multipart/form-data')); ?>
		<?php if ($count_pago < 1): ?>
			<div class="border col-md-12 mt py-2 text-monospace mb-2" style="margin-top: -1.5%;">
				<div class="row">
					<div class="border-blue border-right col-md-4 text-center">
						<b>Subtotal:</b> $<?php echo number_format($valorQuotation,2,",",".") ?>
					</div>
					<div class="border-blue border-right col-md-4 text-center">
						<b>IVA:</b> $<?php echo number_format($ivaData,2,",",".") ?>
					</div>
					<div class="col-md-4 text-center">
						<b>Total a pagar:</b> $<?php echo number_format($valorQuotation+$ivaData,2,",",".") ?>
					</div>
					<div class="col-md-12">
						<hr>
						<?php $total90 = ($totalCop+$totalParaIva)*0.9; ?>
						<h4 class="text-center <?php echo $totalPagado >= $total90 ? 'text-success' : 'text-danger' ?>" style="font-size: 1rem;">
							Pagado y aprobado actualmente: $<?php echo number_format($totalPagado,2,",",".") ?>
							<?php if (isset($datosPagado)): ?>
								<hr>
								¿Se detectó un pago anteriormente aprobado por: $<?php echo number_format($datosPagado["FlowStage"]["valor"]) ?> deseas aplicarlo? 
								<a href="<?php echo $this->Html->url(["controller"=>"FlowStages","action"=>"applyPayment",$datosPagado["FlowStage"]["id"]]) ?>" id="<?php echo $datosPagado["FlowStage"]["id"] ?>" class="btn btn-info btn-applyPayment">Aplicar</a>
							<?php endif ?>
						</h4>
					</div>
				</div>
			</div>
			<label class="cotiza">Selecciona el tipo de pago</label>
			<br>
			<div class="form-check-inline">
				<label class="form-check-label">
					<input class="form-check-input checkPago" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0"> <?php echo 'Total con iva, del '.Configure::read('variables.iva').'%' ?>
				</label>
			</div>
			<div class="form-check-inline">
				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="4"> Total sin iva
				</label>
			</div>
			<div class="form-check-inline" >
				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="1"> Total con Retención
				</label>
			</div>
			<?php if ($totalUsdOriginal > 1500000 || !empty($autorization_info) ): ?>				
				<div class="form-check-inline" data-minval="<?php echo !empty($autorization_info) ? $autorization_info["Autorization"]["valor"] : round(($totalUsdCop+($totalUsdOriginal/2))*1.19) ?>" data-col="<?php echo $totalUsdCop ?>" id="abonoLabelDiv" data-valueusd="<?php echo $totalUsdOriginal ?>">
					<label class="form-check-label">
						<input class="form-check-input" data-valueusd="<?php echo $totalUsdOriginal ?>" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="2"> Abono 
						<small class="text-danger">
							Debe ser mínimo de $<?php echo number_format(!empty($autorization_info) ? $autorization_info["Autorization"]["valor"] : round(($totalUsdCop+($totalUsdOriginal/2))*1.19)) ?>
						</small>
					</label>
				</div>
				<div class="form-check-inline" data-minval="<?php echo !empty($autorization_info) ? $autorization_info["Autorization"]["valor"] : round(($totalUsdCop+($totalUsdOriginal/2))) ?>" data-col="<?php echo $totalUsdCop ?>" id="abonoLabelDivNoIva" data-valueusd="<?php echo $totalUsdOriginal ?>">
					<label class="form-check-label">
						<input class="form-check-input" data-valueusd="<?php echo $totalUsdOriginal ?>" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="6"> Abono sin IVA
						<small class="text-danger">
							Debe ser mínimo de $<?php echo number_format(!empty($autorization_info) ? $autorization_info["Autorization"]["valor"] : round(($totalUsdCop+($totalUsdOriginal/2)))) ?>
						</small>
					</label>
				</div>
			<?php endif ?>
			<div class="form-check-inline">
				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="3"> A crédito con iva
				</label>
			</div>
			<div class="form-check-inline">
				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="5"> A crédito sin iva
				</label>
			</div>
			<br>
		<?php endif ?>
		<?php 
			echo $this->Form->hidden('flujo_id',array('value' => $datos['ProspectiveUser']['id']));
			echo $this->Form->hidden('valorQuotation',array('value' => $valorQuotation));
			echo $this->Form->hidden('totalParaIva',array('value' => $totalParaIva));
			
		?>
		<label id="labelIdPago" class="idVP">Número de Voucher o ID de transacción del pago</label>
		<?php
			echo $this->Form->input('identificator',array('label' => false,'placeholder' => 'Número de Voucher o ID de transacción del pago','div'=>'mb-0 form-group', 'class' => 'form-control idVP' ));
		?>
		<small class="text-danger idVP">
			Este número se comparara con la imagen que subas, si es efectivo se debe subir el id del recibo de caja o factura generado
		</small>
		<br>
		<br>
		<label id="labelIdValorPagado">¿Cuánto dinero pagó el cliente?</label>
		<?php
			echo $this->Form->input('valor',array('label' => false,'placeholder' => '¿Cuánto dinero pagó el cliente?'));
		?>
		<div class="dias_credito"></div>
		<div class="type_payu">
			<?php
				echo $this->Form->input('payment',array('label' => 'Medio de pago','options' => $medios));
				echo $this->Form->hidden('discount_datafono',array('value' => 0));
			?>
			<span class="text-danger mb-5 font16" id="datafonoText" >Recuerda que para pago por datáfono debes indicarle al cliente que debe pagar el 3% adcional por comision en el banco o modificar la cotización agregando dicho valor. De lo contrario se te descontará la suma de: <strong>$<span id="SumaDatafono" style="animation: spinner-grow 5s ease-in-out infinite;"></span> de las comisiones </strong> </span>
			<?php
				echo $this->Form->input('img',array('type' => 'file','label' => 'Por favor adjunta una imagen del comprobante pago','class'=>'form-control','div'=>'form-group mt-2 mb-2'));
			?>
		</div>
		<p class="copiealert mt-2">
			Recuerda que cuando proceses este flujo a “pagado”, se generará una alerta para que el área de contabilidad verifique y apruebe este pago con el comprobante que estas adjuntando
		</p>
		<?php if ($count_pago < 1): ?>
			<a class="alingicon d-inline-block " data-uid="<?php echo $datos['ProspectiveUser']['id'] ?>" data-flow_stage="<?php echo $idFlowstage ?>" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'add',$datos['ProspectiveUser']['id'],$idFlowstage,'4')) ?>" data-toggle="tooltip" data-placement="right" title="Hacer cotización">
				HACER UNA NUEVA COTIZACIÓN &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
			</a>
			<?php if (empty($datos["ProspectiveUser"]["bill_code"]) &&  (in_array(AuthComponent::user("role"),["Gerente General","Logística"]) || AuthComponent::user("email") == "ventas2@almacendelpintor.com" )   ): ?>
				
				<a class="btn btn-warning d-inline-block quotationWO" data-flujo="<?php echo $datos['ProspectiveUser']['id'] ?>" data-qt="<?php echo $idFlowstage ?>" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'from_wo',$datos['ProspectiveUser']['id'],$idFlowstage)) ?>" data-toggle="tooltip" data-placement="right" title="Hacer cotización">
					VALIDAR DESDE WO FACTURA WO <i class="fa-1x fa fa-arrow-circle-o-right"></i>
				</a>
			<?php endif ?>
		<?php endif; ?>
	</form>
<?php endif ?>