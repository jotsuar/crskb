<div class="col-md-12 p-0">
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1 class="nameview">PANEL PRINCIPAL DE COMPRAS</h1>
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'flujos_import')) ?>" class="pull-right btn btn-info">					
					<span class="d-block"><i class="fa flaticon-growth ml-0"></i> Flujos con importaciones en proceso</span>
				</a>
			</div>
		</div>
	</div>
	<div class="blockwhite-import my-3 spacebtn20">	
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<h2 class="titlemenuline">Flujos con importaciones en proceso</h2>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<?php if (empty($datos)): ?>
			<div class="blockwhite-import spacebtn20">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2>No hay flujos con importación en proceso</h2>
					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="contenttableresponsive">
				<table class="table-striped tblProcesoSolicitud table table-bordered">
					<thead class="titlestab">
						<tr>
							<th>
								Flujo
							</th>
							<th>
								Importación
							</th>
							<th>
								Asesor
							</th>
							<th>
								Fecha solicitud
							</th>
							<th>
								Fecha límite
							</th>
							<th>
								Acciones
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($datos as $key => $value): ?>
							<tr>
								<td>
									<div class="dropdown d-inline">
										  <a class="btn btn-success btn-sm dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($value["ProspectiveUser"]["id"]) ?>_<?php echo md5($value["Import"]["id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										   <?php echo $value["ProspectiveUser"]["id"] ?>
										  </a>
										<div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo md5($value["ProspectiveUser"]["id"]) ?>_<?php echo md5($value["Import"]["id"]) ?>">
										    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $value["ProspectiveUser"]["id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["ProspectiveUser"]["id"]); ?>">Ver flujo</a>
										    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" href="#">Ver cotización</a>
										    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $value["ProspectiveUser"]["id"] ?>">Ver órden de compra</a>
										</div>
									</div>
								</td>
								<td>
									<?php echo $value["Import"]["code_import"] ?>
								</td>
								<td>
									<?php echo $value["User"]["name"] ?>
								</td>
								<td>
									<?php echo $value["ImportRequestsDetail"]["created"] ?>
								</td>
								<td>
									<?php echo $value["ImportRequestsDetail"]["deadline"] ?>
								</td>
								<td>
									<a href="<?php echo $this->Html->url(array('controller' => 'Products','action' => 'products_import', $this->Utilities->encryptString($value['Import']['id']))) ?>" class="btn btn-outline-success" data-toggle="tooltip" title="Ver detalle">
										Detalle importación
									<i class="fa fa-fw fa-eye vtc"></i>
								</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		<?php endif ?>
	</div>
</div>

<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp')); ?>