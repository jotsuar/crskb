<div class="col-md-12">
	<h2 class="titleviewer">Facturas asociadas a Medellín</h2>
</div>
<div class="table-responsive">
	<table class="table table-bordered <?php echo empty($datos["medellin"]) ? "" : "datosPendientesDespacho" ?>  table-hovered">
		<thead>
			<tr>						
				<th>Flujo</th>
				<th>Estado del flujo</th>
				<th>Cliente</th>
				<th>Vendedor</th>
				<th>Código Cotización</th>
				<th>Total productos sin facturar</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>						
			<?php if (empty($datos["medellin"])): ?>
				<tr>
					<td colspan="8" class="text-center">
						<p class="text-danger mb-0">No existen registros sin facturar</p>
					</td>
				</tr>
			<?php else: ?>
				<?php foreach ($datos["medellin"] as $key => $value): ?>
					<tr>
						<td>
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
								<?php echo $value["ProspectiveUser"]["id"] ?>
							</a>
						</td>
						<td>
							<?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?>
						</td>
						<td class="text-uppercase"><?php echo $this->Utilities->name_prospective_contact($value['ProspectiveUser']["id"]) ?></td>
						<td>
							<?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["user_id"]); ?>
						</td>
						<td><?php echo $value["Quotation"]["codigo"] ?></td>
						<td class="text-uppercase"><?php echo $value["0"]["total"] ?></td>
						<td> 
							<div class="dropdown d-inline styledrop ">
								<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($value["Quotation"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   Acciones
								</a>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($value["Quotation"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>">
								    <a class="dropdown-item idflujotable flujoModal modalFlujoClick" href="#" data-uid="<?php echo $value["ProspectiveUser"]["id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["ProspectiveUser"]["id"]); ?>">Ver flujo</a>
								    <a class="dropdown-item getQuotationId modalFlujoClick" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" href="#">Ver cotización</a>
								    <a class="dropdown-item getOrderCompra modalFlujoClick" href="#" data-flujo="<?php echo $value["ProspectiveUser"]["id"] ?>">Ver órden de compra</a>
								</div>
							</div>
						</td>
					</tr>
				<?php endforeach ?>
			<?php endif ?>
		</tbody>
	</table>
</div>

<div class="col-md-12 mt-4">
			<h2 class="titleviewer">Facturas asociadas a Bogotá</h2>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered <?php echo empty($datos["bogota"]) ? "" : "datosPendientesDespacho" ?>  table-hovered">
				<thead>
					<tr>						
						<th>Flujo</th>
						<th>Estado del flujo</th>
						<th>Cliente</th>
						<th>Vendedor</th>
						<th>Código Cotización</th>
						<th>Total productos sin facturar</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($datos["bogota"])): ?>
						<tr>
							<td colspan="8" class="text-center">
								<p class="text-danger mb-0">No existen registros sin facturar</p>
							</td>
						</tr>
					<?php else: ?>
						<?php foreach ($datos["bogota"] as $key => $value): ?>
							<tr>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value["ProspectiveUser"]["id"] ?>
									</a>
								</td>
								<td>
									<?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?>
								</td>
								<td class="text-uppercase"><?php echo $this->Utilities->name_prospective_contact($value['ProspectiveUser']["id"]) ?></td>
								<td>
									<?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["user_id"]); ?>
								</td>
								<td><?php echo $value["Quotation"]["codigo"] ?></td>
								<td class="text-uppercase"><?php echo $value["0"]["total"] ?></td>
								<td> 
									<div class="dropdown d-inline styledrop ">
										<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($value["Quotation"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										   Acciones
										</a>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($value["Quotation"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>">
										    <a class="dropdown-item idflujotable flujoModal modalFlujoClick" href="#" data-uid="<?php echo $value["ProspectiveUser"]["id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["ProspectiveUser"]["id"]); ?>">Ver flujo</a>
										    <a class="dropdown-item getQuotationId modalFlujoClick" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" href="#">Ver cotización</a>
										    <a class="dropdown-item getOrderCompra modalFlujoClick" href="#" data-flujo="<?php echo $value["ProspectiveUser"]["id"] ?>">Ver órden de compra</a>
										</div>
									</div>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>