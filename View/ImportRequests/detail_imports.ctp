<div class="card-body">
	<div class="card-text">
		<ul class="p-0">
			<li class="mb-3 pl-0 mgb">
				<b>Razón de la importación</b>: <?php echo $request["ImportRequestsDetail"]["description"] ?>
			</li>
			<li>
				<b>Fecha de solicitud: </b><?php echo $request["ImportRequestsDetail"]['created'] ?>
			</li>
		</ul>
		
	</div>
	<div class="contenttableresponsive mt-3">
	<table class="table-striped table-bordered myTable">
		<thead>
			<tr>
				<th>Imagen</th>
				<th>Producto</th>
				<th class="size2"># Parte</th>
				<th class="size2">Marca</th>
				<th class="size">Inventario actual</th>							
				<th class="size">Cant. solicitada</th>
				<th class="size">
					Costo USD
				</th>
				<th>
					Costo total
				</th>
			</tr>
		</thead>
		<?php $totalQuantity = 0; ?>
		<?php $costTotal = 0; ?>
		<tbody class="os3">
			<?php foreach ($request["Product"] as $idProduct => $value): ?>
				<tr class="os1">
					<td>
						<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
						<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="55px" height="55px" class="imgmin-product">
					</td>
					<td class="<?php echo !is_null($value["notes"]) ? "nota" : "" ?>">
						<?php if (!is_null($value["notes"])): ?>
							<div class="notaproduct">
								<span class="triangle"></span>
								<span class="flechagiro">|</span>
								<div class="text_nota">
									<small class="creadornota"><b></b></small>
									<?php echo $value["notes"] ?>
									<small class="datenota"></small>
								</div>
							</div>
							<p class="cantd"> <?php echo $value["name"] ?>
						<?php else: ?>
							<p class="cantd"> <?php echo $value["name"] ?>
						<?php endif ?>
					</td>
					<td>
						<?php echo $value["part_number"]?>
					</td>
					<td>
						<?php echo $value["brand"]?>
					</td>
					<td class="controlquantity">
						<?php echo $this->element("products_block",["producto" => $value, "inventario_wo" => $inventioWo[$value['part_number']],"bloqueo" => false, "no_show_total" => true, "reserva" => isset($inventioWo["Reserva"][$value['part_number']]) ? $inventioWo["Reserva"][$value['part_number']] : null ]) ?>
					</td>
					
					<td>
						<?php echo $value["ImportRequestsDetailsProduct"]["quantity"]; $totalQuantity+= $value["ImportRequestsDetailsProduct"]["quantity"] ?>
					</td>
					<td>
						$ <?php echo $value["purchase_price_usd"]; $costTotal += ($value["ImportRequestsDetailsProduct"]["quantity"] * $value["purchase_price_usd"]) ?>
					</td>
					<td>
						$ <?php echo ($value["ImportRequestsDetailsProduct"]["quantity"] * $value["purchase_price_usd"]) ?>
					</td>
				</tr>
				<?php if (isset($value["compositions"])): ?>
					<tr>
						<td colspan="10">
							<div class="col-md-12 p-4">
								<h3 class="text-center">
									Producto compuesto por las siguientes referencias:
								</h3>
								<div class="row mt-2">
									<?php foreach ($value["compositions"] as $keyComp => $valueComp): ?>
										<div class="p-2 col-md-4 border">
											<p class="mb-0">
												<b>Referencia: </b> <?php echo $valueComp["Product"]["part_number"] ?>
											</p>
											<p class="mb-0">
												<b>Producto: </b> <?php echo $valueComp["Product"]["name"] ?>
											</p>
										</div>
									<?php endforeach ?>
								</div>
							</div>
						</td>
					</tr>
				<?php endif ?>
			<?php endforeach ?>
			<tr class="bg-blue" style="background-color: #004990 !important">
				<td style="border: none !important; font-size: 1.5rem !important;" colspan="4">
					<span class="float-right">
						Cantidad total:												
					</span>
				</td>
				<td style="border: none !important; font-size: 1.5rem !important;">
					<span><?php echo $totalQuantity ?></span>
				</td>
				<td style="border: none !important; font-size: 1.5rem !important;">
					<span>Costo total:</span>
				</td>
				<td style="border: none !important; font-size: 1.5rem !important;" colspan="2">
					<span>$ <?php echo number_format($costTotal, 2,".",",") ?></h1>
				</td>
			</tr>
		</tbody>
	</table>
	</div>
</div>