<?php if (!empty($productInfo)): ?>
	<div class="table-responsive">
		<h2 class="text-center nameview">
			Órden final de los productos <br>
			<small class="text-center">Mover en el órden deseado</small>
		</h2>
		<div class="contenttableresponsive">
		<table class="table-striped table-bordered myTable">
			<thead>

				<th>
					Imagen
				</th>
				<th>
					Producto
				</th>
				<th>
					Número de parte
				</th>
				<th>
					Costo Unitario
				</th>
				<th>
					Cantidad final
				</th>
			</thead>
			<tbody id="total_products_<?php echo $productInfo["brand"] ?>">
				<?php $totalFinal = 0; ?>
				<?php foreach ($products as $key => $value): ?>

					<tr id="<?php echo $value["id"] ?>">
						<td>
							<?php $ruta = $this->Utilities->validate_image_products($value["Product"]['img']); ?>
							<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value["Product"]['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="40px" height="40px" class="imgmin-product">
						</td>
						<td class="<?php echo !is_null($value["Product"]["notes"]) ? "nota" : "" ?>">
							<?php if (!is_null($value["Product"]["notes"])): ?>
					            <div class="notaproduct">
					            <span class="triangle"></span>
					            <span class="flechagiro">|</span>
					            <div class="text_nota">
					                <small class="creadornota"><b></b></small>
					                <?php echo $value["Product"]["notes"] ?>
					                <small class="datenota"></small>
					            </div>
					            </div>
					            <?php echo $value["Product"]['name'] ?>
					        <?php else: ?>
					            <?php echo $value["Product"]['name'] ?>
					        <?php endif ?>
							
						</td>
						<td>
							<?php echo $value["Product"]['part_number'] ?>
						</td>
						<td>
							$ <?php echo round($value["cost"],2) ?>
						</td>
						<td>
							<?php echo $value["quantity"] ?>
						</td>
						<?php $totalFinal += (round($value["cost"],2)*$value["quantity"]) ?>
					</tr>
					
				<?php endforeach;  ?>
				
			</tbody>
		</table>

		<div class="row mx-0">
			<div class="col-md-12 p-3 text-right ">
				<h2 class="text-azul mr-5 font22">
					Valor final orden: <b>$<?php echo number_format($totalFinal,2) ?></b>
				</h2>
			</div>
		</div>

		</div>
	</div>
<?php endif ?>
