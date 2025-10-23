<div class="col-md-12">
		<div class=" widget-panel widget-style-2 bg-verde big">
             <i class="fa fa-1x flaticon-money"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
		</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">HISTORIAL DE TODOS LOS PAGOS RECHAZADOS</h1>
			</div>
		</div>
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 mb-3">
				<h2>TIPOS DE PAGOS</h2>
				<ul class="subpagos-box">
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment')) ?>">Verificar Pagos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_tienda')) ?>">Verificar pagos en tienda</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_credito')) ?>">Verificar créditos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_true')) ?>">Pagos verificados</a>
					</li>
					<li class="activesub">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_false')) ?>">Pagos rechazados</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payments_payments')) ?>">Verificación total de abonos</a>
					</li>

				</ul>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<h2>INFORMES DE TESORERÍA</h2>
				<ul class="subpagos-box2">
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas')) ?>"><b>1-</b> Informe de ventas</a>
					</li>	
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas_tienda')) ?>"><b>2-</b> Informe de ventas en tienda</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones')) ?>"><b>3-</b> Informe de Comisiones</a>
					</li>					
				</ul>
			</div>
		</div>
	</div>


	<div class="blockwhite">
		<div class="contenttableresponsive">
			<h1 class="nameview spacebtnm">Pagos rechazados</h1>
			<table cellpadding="0" cellspacing="0" class='table-striped datosPendientesDespacho table-bordered'>
				<thead>
					<tr>
						<th>#</th>
						<th>Fecha</th>					
						<th>Asesor</th>
						<th>Flujo</th>
						<th>Cliente</th>
						<th>Valor</th>
						<th>Medio de pago / Identifiador</th>
						<th>Motivo del rechazo</th>
						<th class="widthmin15">Cotización</th>
						<th>Imagen</th>
						<th>Tipo de pago</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($paymentFalse as $value): ?>
						<tr>
							<td><?php echo $value['FlowStage']['id']; ?></td>
							<td>
								<?php echo $this->Utilities->date_castellano($value['FlowStage']['modified']); ?>
							</td>					
							<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
							<td>
								<?php if ($value['ProspectiveUser']['type'] > 0){ ?>
									<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action' => 'flujos?q='.$value['ProspectiveUser']['id'])) ?>" class="idflujotable flujoModal" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value['ProspectiveUser']['id'] ?>
									</a>
								<?php } else { ?>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value['ProspectiveUser']['id'])) ?>" class="idflujotable flujoModal" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value['ProspectiveUser']['id'] ?>
									</a>
								<?php } ?>
							</td>
							<td>
								<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 40,array('ellipsis' => '...','exact' => false)); ?> 
							</td>
							<td>$<?php echo number_format((int)h($value['FlowStage']['valor']),0,",","."); ?>&nbsp;</td>
							<td><?php echo $value['FlowStage']['payment']; ?> / <?php echo $value["FlowStage"]["identificator"] ?> </td>
							<td><?php echo $value['FlowStage']['payment_false_description']; ?></td>
							<td class="widthmin15">
								<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['ProspectiveUser']['id']))) { ?>
									<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['ProspectiveUser']['id'])) ?>">
										<?php echo $this->Utilities->find_name_document_quotation_send($value['ProspectiveUser']['id']) ?>
									</a>
								<?php } else { ?>
									<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($this->Utilities->find_id_document_quotation_send($value['ProspectiveUser']['id'])))) ?>">
										<?php echo $this->Utilities->find_name_document_quotation_send($value['ProspectiveUser']['id']) ?>
									</a>
								<?php } ?>
							</td>
							<?php if ($value['FlowStage']['document'] == 'Crédito'){ ?>
								<td></td>
							<?php } else { ?>
								<td class="comprobanteimgTd">
									<img datacomprobantet="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['FlowStage']['document']) ?>" src="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['FlowStage']['document']) ?>" class="reciboT" width="30px">
								</td>
							<?php } ?>
							<td><b><?php echo $this->Utilities->type_pay_quotation($value['FlowStage']['type_pay']); ?></b></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="popup2">
	<span class="cierra2"> <i class="fa fa-remove"></i> </span>
	<div class="contentpopup">
		<img src="" class="img-product" alt="">
	</div>
</div>
<div class="fondo"></div>

<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
echo $this->Html->script("controller/prospectiveUsers/verify_payment.js?".rand(),	array('block' => 'AppScript'));
?>


<?php echo $this->element("flujoModal"); ?>