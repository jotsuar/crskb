<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-aguamarina big">
         <i class="fa fa-1x flaticon-logistics-delivery-truck-and-clock"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Despachos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
			<?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
				<a href="<?php echo $this->Html->url(array('controller'=>'conveyors','action'=>'index')) ?>" class="btn btn-info pull-right">
		            Gestión de transportadoras
		        </a>
			<?php endif ?>
			<h1 class="nameview spacebtnm">ENVÍOS REALIZADOS PENDIENTES DE CONFIRMAR RECIBIDO</h1>
			<ul class="subdespachos">
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'information_dispatches')) ?>">Flujos pendientes y parciales de despacho</a>
				</li>
				<li class="activesub">
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches')) ?>"> Despachos por confirmar</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>"> Despachos enviados / Finalizados</a>
				</li>
			</ul> 
	</div>
	<div class="blockwhite p-2">
		<div class="contenttableresponsive bg-gris p-2">

			<table cellpadding="0" cellspacing="0" class='table-striped datosPendientesDespacho table-bordered'>
				<thead>
					<tr>
						<th>#</th>
						<th>Flujo</th>
						<th>Fecha de envío</th>
						<th>Transportadora</th>
						<th>Guía</th>
						<th>Comprobante</th>
						<th>Cliente</th>
						<th>Cotización</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($flujoStateDepachado as $value): ?>
					<tr>
						<td><?php echo $value['FlowStage']['id']; ?></td>
						<td>
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'Index?q='.$value['FlowStage']['prospective_users_id'])) ?>" class="idflujotable">
								<?php echo $value['FlowStage']['prospective_users_id'] ?>
							</a>
						</td>
						<td><?php echo $this->Utilities->date_castellano($value['FlowStage']['created']); ?></td>
						<td><?php echo $value['FlowStage']['conveyor']; ?></td>
						<td><?php echo $value['FlowStage']['number']; ?></td>
						<?php if ($value['FlowStage']['document'] != ''){ ?>
							<td class="comprobanteimgTd">
								<img datacomprobantet="<?php echo $this->Html->url('/img/flujo/despachado/'.$value['FlowStage']['document']) ?>" src="<?php echo $this->Html->url('/img/flujo/despachado/'.$value['FlowStage']['document']) ?>" class="reciboT" width="30px">
							</td>
						<?php } else { ?>
							<td>No se adjuntó</td>
						<?php } ?>
						<td class="nameuppercase">						
							<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['FlowStage']['prospective_users_id']), 40,array('ellipsis' => '...','exact' => false)); ?> 	
						</td>
						 <td class="widthmin">
							<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id']))) { ?>
								<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id'])) ?>">
									<?php echo $this->Utilities->find_name_document_quotation_send($value['FlowStage']['prospective_users_id']) ?>
								</a>
							<?php } else { ?>
								<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id'])))) ?>">
									<?php echo $this->Utilities->find_name_document_quotation_send($value['FlowStage']['prospective_users_id']) ?>
								</a>
							<?php } ?>
						</td> 					
						<td class="">
		    					<div class="dropdown d-inline styledrop ">
									  <a class="btn btn-outline-secondary dropdown-toggle p-1 rounded" href="#" role="button" id="gruposproducts<?php echo $value['FlowStage']['id'] ?>" data-toggle="dropdown" aria-expanded="false">Ver  								  
									  </a>

									  <div class="dropdown-menu" aria-labelledby="gruposproducts<?php echo $value['FlowStage']['id'] ?>">

										
										<?php if (!empty($value["ProspectiveUser"]["bill_code"])): ?>
											<a class="dropdown-item pointer listFact" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>">
												<i class="fa fa-eye vtc"></i>
												Ver factura								
											</a>
										<?php endif ?>
										<?php if ($value["ProspectiveUser"]["state"] != 3): ?>							
											<a class="dropdown-item <?php echo empty($value["ProspectiveUser"]["bill_code"]) ? 'pointer': 'pointer' ?> info_bill" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-bill="<?php echo $value["ProspectiveUser"]["bill_code"] ?>">
												<i class="fa fa-pencil vtc"></i>
												<?php echo !empty($value["ProspectiveUser"]["bill_code"]) ? "Nueva factura" : "Ingresar factura" ?>	
											</a>
										<?php endif ?>

										<a href="javascript:void(0)" class="dropdown-item btn_confirm_entrega" data-uid="<?php echo $value['FlowStage']['prospective_users_id'] ?>"><i class="fa fa-check vtc"></i>	Confirmar recibido</a>

					  				</div>
								</div>

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


<!-- Modal -->
<div class="modal fade " id="modalBillInformation" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document" style="max-width: 85% !important;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Datos de factura de venta</h5>
      </div>
      <div class="modal-body" id="cuerpoBill">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalBillList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Listado de facturas ingresadas</h5>
      </div>
      <div class="modal-body" id="cuerpoBillList">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/verify_payment.js?".rand(),	array('block' => 'AppScript'));
?>

<?php 
  echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); ?>
