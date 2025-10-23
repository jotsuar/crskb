	<?php $flujoBloqueoData = isset($flujoBloqueoData) ? $flujoBloqueoData : null; $productInfo =  $this->Utilities->getQuantityBlock($producto,$flujoBloqueoData); ?>
	<div class="invdata" style="font-size: 16px;">
		<span>

			<?php 
				$totalWo 		 = 0;
				$reserva 		 = 0;
				if (isset($inventario_wo)) {
					$totalWo = 0; if (!empty($inventario_wo)) { foreach ($inventario_wo as $key => $value) { $totalWo+=$value["total"];	} }
				}
				$inventarioTotal = $totalWo + $productInfo["productData"]["transito_real"]; 
				$inventarioVenta = $inventarioTotal - $productInfo["productData"]["transito_real"] - $productInfo["totalBloqueo"] - $reserva;
				$inventarioVenta = $totalWo;
			?>

			Inv Total <i class="fa fa-plane vtc"></i> : <h4 class="numberQuantity2"> <?php echo $productInfo["productData"]["transito_real"] ?></h4> 
			<?php if ($productInfo["totalBloqueo"] > 0): ?>
				<br>
				Inv bloqueado <i class="fa fa-plane vtc"></i>  : <h4 class="numberQuantity2"> <?php echo number_format($productInfo["totalBloqueo"]) ?></h4> 
			<?php endif ?>
			<br>
			Inv Disp Venta: <h4 class="numberQuantity2"> <?php echo $inventarioVenta ?></h4>

			<?php if (true): ?>
				<a tabindex="0" class="infoInventory pointer" data-toggle="tooltip" title="Ver detalle de inventario"><i class="fa fa-1x fa-info-circle vtc2"></i></a>		

				<div id="infoProductInventory" style="display: none">
					<div class="col-md-12 p-0">
						<div class="row">
							<div class="col-md-4 p-1">
								<?php $ruta = $this->Utilities->validate_image_products($producto['img']); ?>
								<div class="imginv mb-3" style="background-image: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>)"></div>
							</div>
							<div class="col-md-8 p-0">
								<div class="dataproductview2">
									<small class="text-success">Referencia: <?php echo $producto['part_number'] ?> / Marca: <?php echo $producto['brand'] ?></small> 
									<small class=""><?php echo $this->Text->truncate(strip_tags($producto['name']), 70,array('ellipsis' => '...','exact' => false)); ?></small> 
									<!-- <small class="mb-0"><b>Inventario Medellín:</b> <?php // echo $productInfo["productData"]["quantity"] ?></small>
									<small class="mb-0"><b>Inventario Bogotá:</b> <?php // echo $productInfo["productData"]["quantity_bog"] ?></small> -->
									<small class="mb-0"><b>Inventario Transito:</b> <?php echo $productInfo["productData"]["quantity_back"] ?></small>
									<?php if (isset($inventario_wo) ): ?>
										<hr>
										<small class="text-success text-center">Inventario real WO</small>
										<?php if (empty($inventario_wo)): ?>
											<small class="mb-0" style="font-size: 12px;"><b>Inventario MED:</b> 0</small>
											<small class="mb-0" style="font-size: 12px;"><b>Inventario BOG:</b> 0</small>
										<?php else: ?>
											<?php foreach ($inventario_wo as $key => $value): ?>
												<small class="mb-0" style="font-size: 12px;"><b>Inventario <?php echo $value["bodega"] ?>:</b> <?php echo $value["total"] ?></small>
											<?php endforeach ?>
										<?php endif ?>										
									<?php endif ?>
									<?php if ( isset($reserva) && !is_null($reserva) && isset($reserva["reservas"]) ): ?>
										<small class="text-success text-center">Reservas actuales</small>
										<?php foreach ($reserva["reservas"] as $key => $value): ?>
											<small class="mb-0" style="font-size: 12px;"><b>Bodega:</b> <?php echo $value["Bodega"] ?><br>
											<b>Cliente:</b> <?php echo $value["Cliente"] ?><br>
											<b>Cantidad:</b> <?php echo $value["Cantidad"] ?><br>
											<b>Empleado:</b> <?php echo $value["Empleado"] ?></small>
											<hr>
										<?php endforeach ?>
									<?php endif ?>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<?php if (!empty($productInfo["productsLock"])): ?>
								
								<small class="text-center">
									Bloqueos actuales
								</small>
								<table class="table">
									<thead>
										<tr>
											<th><small>Flujo</small></th>
											<th><small>Detalle</small></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($productInfo["productsLock"] as $key => $value): ?>
											<tr>
												<td>
													<?php echo $this->Html->link($value["ProductsLock"]["prospective_user_id"], array('controller' => 'ProspectiveUsers', 'action' => 'index', "?" => array( "q" => $value["ProductsLock"]["prospective_user_id"] ) ), array("target" => "_blank","class" => "idflujotable m-1")); ?>
												</td>
												<td>
													<ul class="list-unstyled">
														<li><small>Medellin: <?php echo $value["ProductsLock"]["quantity"] ?></small></li>
														<li><small>Bogotá: <?php echo $value["ProductsLock"]["quantity_bog"] ?></small></li>
														<li><small>Transito: <?php echo $value["ProductsLock"]["quantity_back"] ?></small></li>
													</ul>
												</td>
											</tr>
										<?php endforeach ?>

									</tbody>
								</table>
							<?php endif ?>
						</div>
					</div>
				</div>
			<?php else: ?>
				<?php if (isset($inventario_wo) ): ?>
					<?php if (empty($inventario_wo)): ?>
						<!-- <small class="mb-0 fa-1x"><b>MED:</b> 0</small>
						<small class="mb-0 fa-1x"><b>BOG:</b> 0</small> -->
					<?php else: ?>
						<div class="border p-1">							
							<?php foreach ($inventario_wo as $key => $value): ?>
								<small class="mb-0" style="font-size: 16px;"><b><?php echo $value["bodega"] ?>:</b> <?php echo $value["total"] ?></small>
							<?php endforeach ?>
						</div>
					<?php endif ?>
					<?php if ( isset($reserva) && !is_null($reserva) ): ?>
						<small class="text-success text-center">Reservas actuales</small>
						<?php foreach ($reserva["reservas"] as $key => $value): ?>
							<small class="mb-0" style="font-size: 12px;"><b>Bodega:</b> <?php echo $value["Bodega"] ?><br>
							<b>Cliente:</b> <?php echo $value["Cliente"] ?><br>
							<b>Cantidad:</b> <?php echo $value["Cantidad"] ?><br>
							<b>Empleado:</b> <?php echo $value["Empleado"] ?></small>
							<hr>
						<?php endforeach ?>
					<?php endif ?>
					
				<?php endif ?>		
			<?php endif ?>
		</span> 
	</div>

	<style>
		.containerDetailLock {
		    height: 500px;
		    overflow-y: auto;
		    width: 600px;
		}
		.containerDetailLock .sa-info{
			display: none !important;
		}
		.containerDetailLock .text-muted{
			font-size: 16px;
		}
	</style>