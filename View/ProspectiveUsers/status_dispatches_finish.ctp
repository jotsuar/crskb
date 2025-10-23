<div class="col-md-12 p-0">
<div class=" widget-panel widget-style-2 bg-aguamarina big">
         <i class="fa fa-1x flaticon-logistics-delivery-truck-and-clock"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Despachos</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-7">
				<h1 class="nameview spacebtnm">ENVIOS REALIZADOS</h1>
				<ul class="subdespachos">
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'information_dispatches')) ?>">Datos para despachar</a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'pending_dispatches')) ?>">Despachos por enviar <span></span></a>
				</li>
				<li>
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches')) ?>"> Despachos por confirmar</a>
				</li>
				<li class="activesub">
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches_finish')) ?>"> Despachos Finalizados</a>
				</li>
			</ul>
			</div>
			<div class="col-md-5">
				<h1 class="nameview spacebtnm">BUSCADOR POR FLUJOS</h1>
				<div class="input-group stylish-input-group">
				<?php if (isset($this->request->query['q'])){ ?>
					<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por flujo">
					<span class="input-group-addon btn_buscar">
		                <i class="fa fa-search"></i>
		            </span>
				<?php } else { ?>
					<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por flujo">
					<span class="input-group-addon btn_buscar">
		                <i class="fa fa-search"></i>
		            </span>
				<?php } ?>
				</div>
			</div>	
		</div>	
	</div>

	<div class="blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='table-striped datosPendientesDespacho1 table-bordered'>
				<thead>
					<tr>
						<!-- <th>#</th> -->
						<th><?php echo $this->Paginator->sort('FlowStage.prospective_users_id', 'Flujo'); ?></th>
						<th><?php echo $this->Paginator->sort('FlowStage.created', 'Fecha'); ?></th>
						<th><?php echo $this->Paginator->sort('FlowStage.conveyor', 'Transportadora'); ?></th>
						<th><?php echo $this->Paginator->sort('FlowStage.number', 'Guia'); ?></th>
						<th>Comprobante</th>
						<th>Cliente</th>
						<th class="widthmin">Cotización</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($flujoStateDepachado as $value): ?>
					<tr>
						<!-- <td><?php echo $value['FlowStage']['id']; ?></td> -->
						<td>
							<a href="<?php echo $this->Html->url(array('controller'=> 'ProspectiveUsers', 'action' => 'Index' .'?q='.$value['FlowStage']['prospective_users_id'])) ?>" class="idflujotable"><?php echo $value['FlowStage']['prospective_users_id'] ?></a>
						</td>
						<td><?php echo $this->Utilities->date_castellano2($value['FlowStage']['created']); ?></td>
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
														<a class=" listFact dropdown-item " data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>">
															<i class="fa fa-eye vtc"></i>
															Ver factura								
														</a>
													<?php endif ?>
													<?php if ($value["ProspectiveUser"]["state"] == 5 && $value["ProspectiveUser"]["origin"] == "Tienda"): ?>
														<a class="ingresoPagoTienda dropdown-item" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-id="<?php echo $value["ProspectiveUser"]["id"] ?>">
															<i class="fa fa-money vtc"></i>
															Ingresar comprobante de pago								
														</a>
													<?php endif ?>
													<?php if ($value["ProspectiveUser"]["state"] != 3): ?>
														<a class=" dropdown-item <?php echo empty($value["ProspectiveUser"]["bill_code"]) ? 'pointer': 'pointer' ?>  info_bill" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-bill="<?php echo $value["ProspectiveUser"]["bill_code"] ?>">
															<i class="fa fa-plus vtc"></i> <?php echo !empty($value["ProspectiveUser"]["bill_code"]) ? "Nueva factura" : "Ingresar factura" ?>	</a>
													<?php endif ?>
												<a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-check vtc"></i> Pedido entregado</a>


										  </div>
									</div>




						</td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
			<div class="row numberpages">
				<?php
					echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
					echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
					echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
					echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
					echo $this->Paginator->last(' >>', array('class' => 'next'), null);
				?>
				<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
			</div>
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
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
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

<!-- Modal -->
<div class="modal fade " id="modalIngresoPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Ingreso de pago por venta en tienda</h5>
      </div>
      <div class="modal-body" id="cuerpoPago">
      	<?php echo $this->Form->create('ProspectiveUser',array('id' => 'formPagoTienda','enctype'=>'multipart/form-data',"url" => array("controller" => "ProspectiveUsers","action"=>"savePaymentTienda"))); ?>
	        <div class="form-group">
	        	<?php echo $this->Form->input('flujo_id',array("readonly", "label" => "Flujo: ", "type" => "text", "required" => true )); ?>	        
	        </div>
	        <div class="form-group">
	        	<?php echo $this->Form->input('value',array("label" => "Valor del pago", "required" => true )); ?>	        
	        </div>
	        <div class="form-group">
	        	<?php echo $this->Form->input('img',array("label" => "Comprobande de pago","type" => "file", "required" => true )); ?>	        
	        </div>
	        <div class="form-group">
	        	<input type="submit" class="btn btn-success float-right" value="Guardar pago">
	        </div>
      </form>
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
