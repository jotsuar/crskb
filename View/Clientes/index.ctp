<?php echo $this->element("nav_cliente", ["cliente" => $cliente, "action" => "index"]); ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de clientes CRM </h2>
	</div>

	<div class="templates index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class="table tabletemplates table-striped table-bordered responsive">
				<thead>
					<tr>
						<th>
							Código
						</th>
						<th>
							Asesor Kebco
						</th>
						<th>
							Tipo de pago
						</th>
						<th>
							Fecha de creación
						</th>
						<th>
							Acciones
						</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($orders)): ?>
						<tr>
							<td class="text-center" colspan="5">
								No hay órdenes generadas a tu nombre.
							</td>
						</tr>
					<?php else: ?>
						<?php foreach ($orders as $key => $order): ?>
							<tr>
								<td>
									<?php echo $order["Order"]["prefijo"] ?> <?php echo $order["Order"]["code"] ?>
								</td>
								<td>
									<?php echo $order["User"]["name"] ?>
								</td>
								<td>
									<?php echo Configure::read("PAYMENT_TYPE.".$order["Order"]["payment_type"]) ?>
								</td>
								<td>
									<?php echo $this->Utilities->date_castellano($order['Order']['created']); ?>
								</td>
								<td>
									<a class="btn btn-info btn-xs btn-sm" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($order['Order']['id']))) ?>" >
										<i class="fa vtc fa-eye"></i> Detalle
				            		</a>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
