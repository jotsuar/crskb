<?php 	
	$rolesPriceImport = array(
		Configure::read('variables.roles_usuarios.Logística'),
		Configure::read('variables.roles_usuarios.Gerente General')
	);
	$validRole = in_array(AuthComponent::user("role"), $rolesPriceImport) ? true : false;
	$totalValueFinal = 0;
?>
<div class="col-md-12 p-0">
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1 class="nameview">PANEL PRINCIPAL DE COMPRAS</h1>
			</div>
		</div>
	</div>

	<ul class="subpagos2">
		<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
		<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
			<li class="impblock-color1">
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands')) ?>"><i class="fa fa-list-alt vtc d-xs-none"></i><span> Pedidos a Proveedores</span> </a>
			</li>
			<li class="impblock-color2">
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'import_ventas')) ?>"><i class="fa fa-dropbox vtc d-xs-none"></i><span> Reposición de Inventario</span> </a>
			</li>
		<?php endif ?>	
		<li class="impblock-color3">
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'add_import')) ?>"><i class="fa fa-cart-plus vtc d-xs-none"></i><span> Crear solicitud Interna</span> </a>
		</li>	

		<li class="impblock-color4">
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>"><i class="fa fa-spinner vtc d-xs-none"></i><span> <b><?php echo $this->Utilities->count_importaciones_revisiones(); ?> </b> Solicitudes Pendientes</span> </a>
		</li>
		<li class="impblock-color5">
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_approved')) ?>"> <i class="fa fa-check vtc d-xs-none"></i> <span><b><?php echo $this->Utilities->count_imports_approved(); ?></b> Solicitudes en proceso</span></a>
		</li>
		<li class="impblock-color6">
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_rejected')) ?>"> <i class="fa fa-remove vtc d-xs-none"></i><span> <b><?php echo $this->Utilities->count_imports_rejected(); ?></b> Solicitudes Rechazadas</span></a>
		</li>
		<li class="impblock-color8">
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'import_finalizadas')) ?>"><i class="fa fa-external-link vtc d-xs-none"></i> <span><b><?php echo $this->Utilities->count_imports_process(); ?></b> Solicitudes Finalizadas</span></a>
		</li>		
	</ul>

	<div class="blockwhite spacebtn20">
		<div class="searchimport">
			<h2>Buscador de Compras</h2>
			<div class="input-group">
				<input type="search" id="txt_buscador" class="form-control" placeholder="Buscador por Código o Descripción" value="<?php echo isset($this->request->query["q"]) ? $this->request->query["q"] : "" ?>">
				<span class="input-group-addon btn_buscar">
	                <i class="fa fa-search"></i>
	            </span>
			</div>
		</div>
		<div class="contenttableresponsive deleteelementsdatatable">
			<table class="table-striped tableCotizacionesEnviadas table-bordered ">
				<thead class="titlestab">
					<tr>
						<th>Código solicitud</th>
						<th>Fecha de la solicitud</th>
						<th>Asesor que solicita</th>
						<th class="size7">Descripción de la solicitud</th>
						<th>Cotización</th>
						<th>Flujos</th>
						<th class="text-center size1">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($importacionesCotizacion as $value): ?>
						<?php
						$quotations_products_id = $this->Utilities->get_quotations_products_id($value['Import']['id']);
						$datosQuationsProduct 	= $this->Utilities->get_data_quotations_product($quotations_products_id);
						$flujo_id 				= empty($datosQuationsProduct['QuotationsProduct']['quotation_id']) ? "" : $this->Utilities->find_flujoid_quotationid($datosQuationsProduct['QuotationsProduct']['quotation_id']);
						?>
						<tr>
							<td><?php echo $value['Import']['code_import'] ?></td>
							<td><?php echo $this->Utilities->date_castellano($value['Import']['created']); ?></td>
							<td><?php echo $this->Utilities->find_name_lastname_adviser($value['Import']['user_id']); ?></td>
							
							<td><?php echo $value["Import"]["description"]; ?></td>

								<td>
							<?php if (!empty($datosQuationsProduct['QuotationsProduct']['quotation_id'])): ?>
									<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($flujo_id))) { ?>
										<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($flujo_id)) ?>">
											<?php echo $this->Utilities->find_name_document_quotation_send($flujo_id) ?>
										</a>
									<?php } else { ?>
										<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($this->Utilities->find_id_document_quotation_send($flujo_id)))) ?>">
											<?php echo $this->Utilities->find_name_document_quotation_send($flujo_id) ?>
										</a>
									<?php } ?>

							<?php else: ?>
								</td>
							<?php endif ?>
							<td>
								<?php $flujos = $this->Utilities->extratctFlujos($value); ?>
								<?php if ($flujos): ?>
									<?php foreach ($flujos as $keyPU => $valuePU): ?>
										<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$valuePU)) ?>" class="idflujotable m-1" target="_blank">
											<?php echo $valuePU ?>
										</a>
									<?php endforeach ?>
								<?php endif ?>
							</td>
							<td class="text-center">
								<a class="btn btn-outline-success" href="<?php echo $this->Html->url(array('controller' => 'Products','action' => 'products_import', $this->Utilities->encryptString($value['Import']['id']))) ?>" class="btn btn-info" data-toggle="tooltip" title="Ver productos">
									<i class="fa fa-fw fa-eye vtc"></i>
								</a>
								<?php if (AuthComponent::user("role") == "Gerente General"): ?>	
									<button type="button" class="btn btn-outline-primary" data-uid="<?php echo $this->Utilities->encryptString($value['ImportRequest']['id']) ?>" id="pdfGenerate" data-toggle="tooltip" title="Visualizar OC en .pdf" data-original-title="Visualizar OC en .pdf">
									   <i class="fa fa-file-text-o vtc"></i>
									</button>
								<?php endif ?>
								<?php if ($validRole): ?>			
									<button type="button" class="btn btn-outline-secondary" data-uid="<?php echo $this->Utilities->encryptString($value['ImportRequest']['id']) ?>" id="pdfGenerateDetail" data-toggle="tooltip" title="Visualizar OC con detalles de entrega" data-original-title="Visualizar OC con detalles de entrega">
										    <i class="fa fa-file-pdf-o vtc"></i>
									</button>
								<?php endif ?>
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

<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),							array('block' => 'jqueryApp'));
echo $this->Html->script("controller/import/search.js?".rand(),		array('block' => 'AppScript'));
echo $this->Html->script("controller/prospectiveUsers/imports_revisions.js?".rand(),		array('block' => 'AppScript'));
?>