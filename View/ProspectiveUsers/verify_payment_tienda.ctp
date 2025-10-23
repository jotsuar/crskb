<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-verde big">
             <i class="fa fa-1x flaticon-money"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
		</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">VERIFICACIÓN DE PAGOS EN TIENDA</h1>
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
					<li class="activesub">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_tienda')) ?>">Verificar pagos en tienda</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_credito')) ?>">Verificar créditos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_true')) ?>">Pagos verificados</a>
					</li>
					<li>
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
		<table cellpadding="0" cellspacing="0" class='table-striped datosPendientesDespacho table-bordered'>
			<thead>
				<tr>
					<th>#</th>
					<th>Fecha</th>
					<th>Asesor</th>
					<th>Id</th>
					<th>Valor</th>
					<th>Medio / Identificador</th>
					<th class="widthmin">Cotización</th>
					<th>Foto</th>
					<th>Tipo de pago</th>
					<th>Cliente</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($flujosVerify as $value): ?>
				<tr>
					<td><?php echo $value['FlowStage']['id']; ?></td>
					<td>
						<?php echo $this->Utilities->date_castellano(h($value['FlowStage']['created'])); ?>
					</td>	
					<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
					<td>
						<?php if ($value['ProspectiveUser']['type'] > 0){ ?>
							<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action' => 'flujos?q='.$value['ProspectiveUser']['id'])) ?>" class="idflujotable flujoModal" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
								<?php echo $value['ProspectiveUser']['id'] ?>
							</a>
						<?php } else { ?>
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value['ProspectiveUser']['id'])) ?>" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>" class="idflujotable flujoModal">
								<?php echo $value['ProspectiveUser']['id'] ?>
							</a>
						<?php } ?>
					</td>
					<td>$<?php echo number_format((int)h($value['FlowStage']['valor']),0,",","."); ?>&nbsp;</td>
					<td><?php echo $value['FlowStage']['payment']; ?> / <?php echo $value["FlowStage"]["identificator"] ?></td>
					<td class="widthmin">
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
					<td class="comprobanteimgTd">
						<img datacomprobantet="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['FlowStage']['document']) ?>" src="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['FlowStage']['document']) ?>" class="reciboT" width="30px">
					</td>
					<td><b><?php echo $this->Utilities->type_pay_quotation($value['FlowStage']['type_pay']); ?></b></td>
					<td>
						<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 40,array('ellipsis' => '...','exact' => false)); ?> 
					</td>
					<td>
						<a href="javascript:void(0)" class="confirm_payment_flujo btn btn-success text-white" data-toggle="tooltip" title="Confirmar pago" data-uid="<?php echo $value['FlowStage']['id'] ?>" data-user_id="<?php echo $value['ProspectiveUser']['user_id'] ?>" data-flujo_id="<?php echo $value['ProspectiveUser']['id'] ?>"><i class="fa fa-check vtc"></i></a>

						<a href="javascript:void(0)" class="not_confirm_payment_flujo btn btn-danger text-white" data-toggle="tooltip" title="No confirmar pago" data-uid="<?php echo $value['FlowStage']['id'] ?>" data-user_id="<?php echo $value['ProspectiveUser']['user_id'] ?>" data-flujo_id="<?php echo $value['ProspectiveUser']['id'] ?>"><i class="fa fa-times vtc"></i></a>
					</td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	</div>
	</div>
</div>
<div class="popup2">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
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

<!-- Modal -->
<div class="modal fade " id="recibodeCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Ingreso de información de recibo de caja</h5>
      </div>
      <div class="modal-body" id="cuerpoRecibo">
        |
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
	        Cerrar
	    </button>
      </div>
    </div>
  </div>
</div>


<?php echo $this->Html->script("controller/prospectiveUsers/recibos.js?".rand(),			array('block' => 'AppScript'));  ?>