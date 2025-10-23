<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">Solicitud de compras nacionales desde logística</h1>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<?php echo $this->Form->create('Import'); ?>

				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
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

		<div class="contenttableresponsive">
			<table class="table-striped tableCotizacionesEnviadas table-bordered">
				<thead class="titlestab">
					<tr>
						<th><?php echo $this->Paginator->sort('Import.internacional', 'Origen'); ?></th>
						<th><?php echo $this->Paginator->sort('Import.code_import', 'Código'); ?></th>
						<th><?php echo $this->Paginator->sort('ImportRequest.brand_id', 'Marca'); ?></th>
						<th><?php echo $this->Paginator->sort('ImportRequest.brand_id', 'Proveedor'); ?></th>
						<th><?php echo $this->Paginator->sort('ImportRequest.brand_id', 'Estado'); ?></th>
						<th><?php echo $this->Paginator->sort('Import.created', 'Fecha'); ?></th>
						<th class="size2">Descripción</th>
						<th>Solicita</th>
						<th>Flujos</th>
						<th class="text-center">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($importaciones as $value): ?>
						<tr>
							<td><?php echo $value['Import']['internacional'] == 1 ? "Internacional" : "Nacional"; ?></td>
							<td><?php echo $value['Import']['code_import'] ?></td>
							<td>
								<?php echo isset($value['ImportRequest']['brand_id']) && !is_null($value['ImportRequest']['brand_id']) ? $this->Utilities->getInfoByBrand($value['ImportRequest']['brand_id'])["brand"]["Brand"]["name"] : "" ?> <?php echo $value['ImportRequest']['brand_id'] == $value["Brand"]["id"] ? "" : " - ".$value["Brand"]["name"] ?> 
							</td>
							<td>
								<?php echo isset($value['ImportRequest']['brand_id']) && !is_null($value['ImportRequest']['brand_id']) ? $this->Utilities->getInfoByBrand($value['ImportRequest']['brand_id'])["brand"]["Brand"]["provider"] : "" ?>
							</td>
							<td>
								<?php echo $value["Import"]["state"] == '3' ? 'Pendiente de aprobación' : 'Aprobado y en curso'; ?>
							</td>
							<td><?php echo $this->Utilities->date_castellano($value['Import']['created']); ?></td>
							<td><?php echo $value['Import']['description'] ?></td>
							<td><?php echo $value['User']['name'] ?></td>
							<td>
								<?php $flujos = $this->Utilities->extratctFlujos($value); ?>
								<?php if ($flujos): ?>
									<?php foreach ($flujos as $keyPU => $valuePU): ?>
										
										<div class="dropdown d-inline">
										  <a class="btn btn-success btn-sm dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($valuePU) ?>_<?php echo md5($value["Import"]["id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										   <?php echo $valuePU ?>
										  </a>

										  <div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo md5($valuePU) ?>_<?php echo md5($value["Import"]["id"]) ?>">
										    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $valuePU ?>" data-type="<?php echo $this->Utilities->getTypeProspective($valuePU); ?>">Ver flujo</a>
										    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($valuePU) ?>" href="#">Ver cotización</a>
										    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $valuePU ?>">Ver órden de compra</a>
										  </div>
										</div>
									<?php endforeach ?>
								<?php endif ?>
							</td>
							<td class="text-center">
				<!-- 				<a class="btn btn-outline-success" href="<?php //echo $this->Html->url(array('controller' => 'Products','action' => 'products_import', $this->Utilities->encryptString($value['Import']['id']))) ?>" data-toggle="tooltip" title="Ver productos">
									<i class="fa vtc fa-eye"></i>
								</a>		 -->						
								
												
											<button type="button" class="btn btn-outline-primary" data-uid="<?php echo $this->Utilities->encryptString($value['ImportRequest']['id']) ?>" id="pdfGenerate" data-toggle="tooltip" title="Visualizar OC en .pdf" data-original-title="Visualizar OC en .pdf">
												   <i class="fa fa-file-text-o vtc"></i>
												</button>
											<button type="button" class="btn btn-outline-secondary" data-uid="<?php echo $this->Utilities->encryptString($value['ImportRequest']['id']) ?>" id="pdfGenerateDetail" data-toggle="tooltip" title="Visualizar OC con detalles de entrega" data-original-title="Visualizar OC con detalles de entrega">
													    <i class="fa fa-file-pdf-o vtc"></i>
											</button>

											<?php if ($value["Import"]["payed"] == 0): ?>
												<a class="btn btn-outline-success changeState" data-id="<?php echo $this->Utilities->encryptString($value['Import']['id']) ?>" data-controller="ImportRequests" data-action="change_payed" href="<?php echo $this->Html->url(array('controller' => 'ImportRequests','action' => 'change_payed', $this->Utilities->encryptString($value['Import']['id']))) ?>" data-toggle="tooltip" title="Marcar como pagado">
													<i class="fa vtc fa-money"></i> Marcar como pagado
												</a>		 
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

<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
echo $this->Html->script("controller/prospectiveUsers/imports_revisions.js?".rand(),		array('block' => 'AppScript'));
	echo $this->Html->script("controller/import/search.js?".rand(),		array('block' => 'AppScript'));
?>
