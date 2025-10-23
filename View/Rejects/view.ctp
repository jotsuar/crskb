<?php $validRole = true; ?>
<div class="table-responsive">
			<hr>
			<h2 class="text-center mt-3 mb-3">Detalles de solicitud</h2>

			<table class="table table-striped table-border text-center mb-0">
				<thead>
					<th>Tipo de solicitud</th>
					<th>Razón</th>
					<th>Flujo</th>
					<th>Cotizador</th>
					<th>Cliente</th>
					<th class="size2">Cantidad</th>
					<th>Fecha</th>
					<th>Entrega</th>
					<th class="size9">Tiempo restante</th>
				</thead>
				<tbody>
					<?php foreach ($detalles as $keyDetail => $valueDetail): ?>
						<tr>
							<td>
								<?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$valueDetail["ImportRequestsDetail"]["type_request"]) ?> 
							</td>
							<td>
								<?php echo $valueDetail["ImportRequestsDetail"]["description"] ?>
							</td>
							<td>
								<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>
									<div class="dropdown d-inline styledrop">
										<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?>
										</a>

										<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ?>">
											<a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($valueDetail["ImportRequestsDetail"]["prospective_user_id"]); ?>">Ver flujo</a>
											<a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ?>" href="#">Ver cotización</a>
											<a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?>">Ver órden de compra</a>
										</div>
									</div>
								<?php endif; ?>
							</td>
							<td>
								<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>

									<?php echo $this->Utilities->get_user_flujo($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ?>

								<?php endif; ?>
							</td>
							<td>
								<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>
									<?php echo $this->Text->truncate($this->Utilities->name_prospective($valueDetail['ImportRequestsDetail']['prospective_user_id']), 30,array('ellipsis' => '...','exact' => false)); ?> <?php endif; ?>
							</td>
							<td>

								<?php echo $valueDetail['ImportProductsDetail']['quantity']	 ?>
							</td>
							<td>
								<?php $fecha = $valueDetail["ImportRequestsDetail"]["created"]; ?>
								<?php echo $this->Utilities->date_castellano2($fecha); ?>
							</td>
							<td>
								<?php echo $valueDetail["ImportProductsDetail"]["delivery"] ?>
							</td>

							<td>
								<?php if (!empty($valueDetail["ImportProductsDetail"]["delivery"])): ?>
									<?php $fecha = $this->Utilities->calculateFechaFinalEntrega($fecha,Configure::read("variables.entregaProductValues.".$valueDetail["ImportProductsDetail"]["delivery"]));
									$dataDay = $this->Utilities->getClassDate($fecha); ?>
									<?php if ($dataDay == 0): ?>
										<span class="bg-danger text-white">
											Se entrega hoy
										</span>
										<?php elseif($dataDay > 0): ?>
											<span class="bg-danger text-white">
												Retraso de <?php echo $dataDay ?> día(s) <?php echo date("Y-m-d",strtotime("-".$dataDay." day")) ?>
											</span>
											<?php elseif($dataDay <= -5): ?>
												<span class="bg-success text-white">Se entrega en  <?php echo abs($dataDay) ?> día(s) <?php echo date("Y-m-d",strtotime("+".abs($dataDay)." day")) ?></span>
												<?php else: ?>
													<span class="bg-warning text-white">Se entrega en <?php echo abs($dataDay) ?> día(s) <?php echo date("Y-m-d",strtotime("+".abs($dataDay)." day")) ?></span>
												<?php endif ?>
											<?php endif ?>
							</td>

						</tr>
												
						<?php endforeach ?>

					</tbody>
			</table>
		</div>

<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp'));

echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript'));
 ?>