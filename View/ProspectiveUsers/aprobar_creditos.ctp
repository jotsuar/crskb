<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-azul big">
             <i class="fa fa-1x flaticon-money"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión</h2>
		</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">
					APROBACIÓN DE COMPRAS A CRÉDITO


				</h1> 
				
			</div>
		</div>
	</div>


	<div class="blockwhite mb-3">
		<h1 class="nameview spacebtnm">Créditos por aprobar</h1>
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='table-striped datosPendientesDespacho table-bordered'>
				<thead>
					<tr>
						<th>#</th>
						<th>Fecha de ingreso</th>
						<th>Id del flujo</th>
						<th>Asesor</th>
						<th>Valor</th>
						<th>Días</th>
						<th class="widthmin">Cotización</th>
						<th>Cliente</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($flujosVerify as $value): ?>
						<tr>
							<td><?php echo $value['FlowStage']['id'] ?></td>
							<td><?php echo $this->Utilities->date_castellano($value['FlowStage']['created']); ?></td>
							<td>
								<div class="dropdown d-inline">
								  <a class="btn btn-success btn-sm dropdown-toggle p-1 rounded idflujotable" href="#" role="button" id="dropdownMenuLink_<?php echo $value['ProspectiveUser']['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <?php echo $value['ProspectiveUser']['id'] ?>
								  </a>

								  <div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo $value['ProspectiveUser']['id'] ?>">
								    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value['ProspectiveUser']['id']); ?>">Ver flujo</a>
								    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($value['ProspectiveUser']['id']) ?>" href="#">Ver cotización</a>
								    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $value['ProspectiveUser']['id'] ?>">Ver órden de compra</a>
								  </div>
								</div>
								
							</td>
							<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
							<td>$<?php echo number_format((int)h($value['FlowStage']['valor']),0,",","."); ?>&nbsp;</td>
							<td><?php echo $this->Utilities->number_day_payment_text($value['FlowStage']['payment_day']); ?></td>
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
						<td>
							<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['FlowStage']['prospective_users_id']), 40,array('ellipsis' => '...','exact' => false)); ?> 							
						</td>
							<td>
								<a href="" data-nit="<?php echo $this->Utilities->name_prospective_dni($value['FlowStage']['prospective_users_id']) ?>" data-flujo="<?php echo $value['FlowStage']['prospective_users_id'] ?>" class="btn btn-warning verifyWo" data-uid="<?php echo $value['FlowStage']['id'] ?>" data-user_id="<?php echo $value['ProspectiveUser']['user_id'] ?>">
									Verificar cupo de credito en WO directamente
								</a>
<!-- 								<a href="javascript:void(0)" class="confirm_payment_flujo btn btn-success text-white" data-toggle="tooltip" title="Confirmar pago" data-uid="<?php echo $value['FlowStage']['id'] ?>" data-user_id="<?php echo $value['ProspectiveUser']['user_id'] ?>" data-flujo_id="<?php echo $value['ProspectiveUser']['id'] ?>"><i class="fa fa-check vtc"></i></a>

								<a href="javascript:void(0)" class="not_confirm_payment_flujo btn btn-danger text-white" data-toggle="tooltip" title="No confirmar pago" data-uid="<?php echo $value['FlowStage']['id'] ?>" data-user_id="<?php echo $value['ProspectiveUser']['user_id'] ?>" data-flujo_id="<?php echo $value['ProspectiveUser']['id'] ?>"><i class="fa fa-times vtc"></i></a>
							</td> -->
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


<div class="modal fade" id="modalClienteWo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle de credito </h2>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-12">
      			<div class="row">
      				<div class="col-md-6">
      					<div class="form-group">
      						<label for="nitCliente">Nit a consultar</label>
      						<input type="text" id="nitCliente" class="form-control" readonly>
      						<input type="hidden" id="is_validate_adm" value="1">
      					</div>
      				</div>
      				<div class="col-md-6">
      					<div class="form-group">
      						<a href="" class="btn btn-info btnSearchCustomer mt-4">Consultar en WO</a>
      					</div>
      				</div>
      			</div>
      		</div>
      		<div class="col-md-12" id="bodyClienteWo">
      			
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
echo $this->Html->script("controller/prospectiveUsers/verify_payment.js?".rand(),	array('block' => 'AppScript'));
?>

<?php echo $this->element("flujoModal"); ?>
