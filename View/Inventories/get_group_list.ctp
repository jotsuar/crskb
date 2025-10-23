<div class="contenttableresponsive">
	<table cellpadding="0" cellspacing="0" class='myTable table-striped table-bordered'>
		<thead>
			<tr>
				<th>Imagen</th>
				<th><?php echo 'Referencia'; ?></th>
				<th><?php echo 'Producto'; ?></th>
				
				<th><?php echo __("Inventario actual") ?></th>
				<th class="text-center">
					<?php echo 'Costo reposición'; ?><br>
					<?php echo 'Costo WO'; ?>
				</th>
				<th ><?php echo 'Marca'; ?></th>
				<th><?php echo 'Bodega'; ?></th>
				<th><?php echo 'Motivo salida'; ?></th>
				<th><?php echo 'Cantidad'; ?></th>
				<th>Usuario / Fecha de movimiento</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($products)): ?>
				<?php foreach ($products as $key => $product): ?>
					<?php $dataProducto = $this->Utilities->getQuantityBlock($product["Product"]); ?>
					<tr id="trMove_<?php echo $product["Inventory"]["id"] ?>">
						<td>
							<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
							<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($product['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
						</td>
						<td><?php echo h($product['Product']['part_number']); ?>&nbsp;</td>
						<td class="sizeprod nameuppercase <?php echo !is_null($product["Product"]["notes"]) ? "nota" : "" ?>">
							<?php if (!is_null($product["Product"]["notes"])): ?>
					            <div class="notaproduct">
					            <span class="triangle"></span>
					            <span class="flechagiro">|</span>
					            <div class="text_nota">
					                <small class="creadornota"><b></b></small>
					                <?php echo $product["Product"]["notes"] ?>
					                <small class="datenota"></small>
					            </div>
					            </div>
					            <?php echo $this->Text->truncate(h($product['Product']['name']), 45,array('ellipsis' => '...','exact' => false)); ?>&nbsp;
					        <?php else: ?>
					            <?php echo $this->Text->truncate(h($product['Product']['name']), 45,array('ellipsis' => '...','exact' => false)); ?>&nbsp;
					        <?php endif ?>
						</td>
						

						<td class="size11">
							<?php echo $this->element("products_block",["producto" => $product["Product"]]) ?>
						</td>


						<td class="text-center resetpd">

							<div class="price-purchase_price_usd">
								$<?php echo h($product['Product']['purchase_price_usd']); ?> USD
							</div>
							 
							<div class="price-purchase_price_wo">
								$<?php echo h($product['Product']['purchase_price_wo']); ?> COP
							</div>

						</td>								
						<td><?php echo h($product['Product']['brand']); ?>&nbsp;</td>
						<td class="sizeprod ">
							<?php echo $product["Inventory"]["warehouse"] ?>
						</td>
						<td class="sizeprod ">
							<?php echo $product["Inventory"]["reason"] ?>
						</td>
						<td class="sizeprod ">
							<?php echo $product["Inventory"]["quantity"] ?>
						</td>
						<td>
							<?php if (!is_null($product["Inventory"]["user_id"])): ?>
								<?php echo $this->Utilities->find_name_adviser($product["Inventory"]["user_id"]) ?> / <?php echo h($product['Inventory']['created']); ?>
							<?php endif ?>
						</td>
					</tr>
				<?php endforeach ?>

			<?php else: ?>
				<tr>
					<td colspan="12" class="text-center">
						No hay salidas de  productos en el momento
					</td>
				</tr>
			<?php endif ?>
		</tbody>
	</table>
</div>