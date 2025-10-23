<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-aguamarina big">
         <i class="fa fa-1x flaticon-logistics-delivery-truck-and-clock"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Despachos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<h1 class="nameview spacebtnm">FLUJOS PENDIENTES DE DESPACHO (<?php echo $this->Utilities->count_pending_dispatches(); ?>)</h1>
			<ul class="subdespachos">
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'information_dispatches')) ?>">Datos para despachar</a>
				</li>
				<li class="activesub">
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'pending_dispatches')) ?>">Despachos por enviar <span></span></a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches')) ?>"> Despachos por confirmar</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches_finish')) ?>"> Despachos Finalizados</a>
				</li>
			</ul>

	</div>
	<div class="blockwhite">
		<div class="contenttableresponsive">
			
			<table cellpadding="0" cellspacing="0" class='table-striped datosPendientesDespacho table-bordered'>
				<thead>
					<tr>
						<th>#</th>
						<th>Flujo</th>
						<th>Fecha</th>
						<th>Asesor</th>
						<th>Cliente</th>
						<th>Recibe</th>
						<th>Dirección</th>
						<th>Ciudad</th>
						<th>Flete</th>
						<th class="size3">Acción</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($pendingDispatches as $value): ?>
					<tr>
						<td><?php echo $value['ProspectiveUser']['user_id'] ?></td>
						<td>
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'Index?q='.$value['ProspectiveUser']['id'])) ?>" class="idflujotable">
								<?php echo $value['ProspectiveUser']['id'] ?>
							</a>
						</td>
						<td><?php echo $this->Utilities->date_castellano3(h($value['FlowStage']['created'])); ?></td>
						<td class="nameuppercase"><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
						<td class="nameuppercase">
							<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 40,array('ellipsis' => '...','exact' => false)); ?> 	
						</td>
						<td class="nameuppercase"><?php echo $value['FlowStage']['contact']; ?></td>
						<td>
							<?php echo $value['FlowStage']['address']; ?> (<?php echo $value['FlowStage']['additional_information']; ?>)
						</td>
						<td><?php echo $value['FlowStage']['city']; ?></td>
						<td><?php echo $value['FlowStage']['flete']; ?></td>
						<td>
							

										
							<?php if (!empty($value["ProspectiveUser"]["bill_code"])): ?>
								<a class="listFact btn btn-warning " data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>">
									Facturas 								
								</a>
							<?php endif ?>
								<?php if ($value["ProspectiveUser"]["state"] != 3): ?>
									<span><?php echo $this->Utilities->paint_state_despacho($value['ProspectiveUser']['state'],$value['ProspectiveUser']['id'],$value['ProspectiveUser']['state_flow']); ?>
										
									</span>

									<a class=" btn btn-success text-white <?php echo empty($value["ProspectiveUser"]["bill_code"]) ? 'pointer': 'pointer' ?>  info_bill" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-bill="<?php echo $value["ProspectiveUser"]["bill_code"] ?>">
										<?php echo !empty($value["ProspectiveUser"]["bill_code"]) ? "Nueva factura" : "Ingresar factura" ?>	</a>

								<?php endif ?>
				
						</td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),							array('block' => 'jqueryApp')); 
	echo $this->Html->script("controller/prospectiveUsers/pending_dispatches.js?".rand(),	array('block' => 'AppScript'));
	echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); 
?>

<!-- Modal -->
<div class="modal fade " id="modalBillInformation" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document" >
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

<?php echo $this->element("address"); ?>


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