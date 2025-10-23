<div class="table-responsive">
	<?php if (empty($data)): ?>
		<h2 class="text-center text-warning mt-4">
			No se encontró información sobre cupo de crédito
		</h2>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">						
					<a href="javascript:void(0)" class="confirm_payment_flujo btn btn-success text-white btn-block" data-toggle="tooltip" title="Confirmar pago" data-uid="<?php echo $flow ?>" data-user_id="<?php echo $user ?>" data-flujo_id="<?php echo $flujo ?>">
						Aprobar
						<i class="fa fa-check vtc"></i>
					</a>


								
				</div>
				<div class="col-md-6">
					<a href="javascript:void(0)" class="not_confirm_payment_flujo btn btn-danger text-white btn-block" data-toggle="tooltip" title="No confirmar pago" data-uid="<?php echo $flow ?>" data-user_id="<?php echo $user ?>" data-flujo_id="<?php echo $flujo ?>">
						Rechazar <i class="fa fa-times vtc"></i>
					</a>
				</div>
			</div>
		</div>
	<?php else: ?>
		<table class="table table-hovered mt-4">
			<tr>
				<th colspan="2" class="text-center">
					Cliente: <?php echo $data->Nombres_terceros ?>
				</th>
			</tr>
			<tr>
				<th>
					Cupo crédito: 
				</th>
				<td>
					<?php echo number_format(floatval($data->CupoCredito),2,",",".") ?>
				</td>
			</tr>
			<tr>
				<th>
					Saldo o deuda actual: 
				</th>
				<td>
					<?php echo number_format(floatval($data->Saldo),2,",",".") ?>
				</td>
			</tr>
			<tr>
				<th>
					Cupo final (Cupo crédito - Saldo)
				</th>
				<td>
					<?php echo number_format(floatval($data->CupoCredito-$data->Saldo),2,",",".") ?>
				</td>
			</tr>
		</table>
		<?php 

			$permitido = true;

		?>
		<?php if (isset($data->details) && !empty($data->details)): ?>
			<div class="col-md-12" style="height: 300px; overflow-y: auto;">
				
			
				<h2 class="my-2 text-center text-info">
					Detalle de saldos
				</h2>
				<table class="table table hovered">
					<thead>
						<tr>
							<th>Documento</th>
							<th>Factura</th>
							<th>Fecha Factura</th>
							<th>Saldo</th>
							<th>Fecha de vencimiento</th>
							<th>DIAS VENCIDOS</th>
						</tr>
					<tbody>
						<?php foreach ($data->details as $key => $value): ?>
							<tr class="<?php echo $value->DIAS > 30 ? 'text-danger' : '' ?>">
								<td><?php echo $value->Documento ?></td>
								<td><?php echo $value->prefijo ?> <?php echo $value->DocumentoNúmero ?></td>
								<td><?php echo date("Y-m-d",strtotime($value->Fecha)) ?></td>
								<td><?php echo number_format(floatval($value->Saldo),2,",",".") ?></td>
								<td><?php echo date("Y-m-d",strtotime($value->Vencimiento)) ?></td>
								<td><?php echo $value->DIAS; if($value->DIAS > 30) { $permitido = false; } ?></td>
							</tr>
						<?php endforeach ?>
					</tbody>
					</thead>
				</table>
			</div>
		<?php endif ?>

		<?php if (isset($flujo) && !is_null($flujo) && $flujo != 0): ?>
			
		

			<div class="col-md-12">
				<div class="row">
					<?php if ($is_validate_adm == 1): ?>
						<div class="col-md-12 my-3">
							<h3 class="text-danger text-center">
								El cliente cuenta con facturas vencidas con más de 30 días. No se recomienda aprobar el pago
							</h3>
						</div>
					<?php endif ?>
					<div class="col-md-6">
						<?php if ($permitido || $is_validate_adm == 1): ?>							
							<a href="javascript:void(0)" class="confirm_payment_flujo btn btn-success text-white btn-block" data-toggle="tooltip" title="Confirmar pago" data-uid="<?php echo $flow ?>" data-user_id="<?php echo $user ?>" data-flujo_id="<?php echo $flujo ?>">
								Aprobar
								<i class="fa fa-check vtc"></i>
							</a>
						<?php else: ?>
							<h3 class="text-danger">
								El cliente cuenta con facturas vencidas con más de 30 días. No es posible aprobar el pago
							</h3>
						<?php endif ?>

									
					</div>
					<div class="col-md-6">
						<a href="javascript:void(0)" class="not_confirm_payment_flujo btn btn-danger text-white btn-block" data-toggle="tooltip" title="No confirmar pago" data-uid="<?php echo $flow ?>" data-user_id="<?php echo $user ?>" data-flujo_id="<?php echo $flujo ?>">
							Rechazar <i class="fa fa-times vtc"></i>
						</a>
					</div>
				</div>
			</div>
		<?php endif ?>
	<?php endif ?>
</div>