<div class="table-responsive">
	<h3>
		Seleccionar productos
	</h3>
	<table class="table-bordered table <?php echo empty($otherProducts) ? "" : "tblProcesoSolicitud" ?>">
		<thead>
			<th>Imagen</th>
			<th><?php echo 'Referencia'; ?></th>
			<th><?php echo 'Producto'; ?></th>
			<th><?php echo 'Categoría'; ?></th>
			<th><?php echo __("Inventario actual") ?></th>
			<th class="text-center">
				<?php echo 'Costo reposición'; ?><br>
				<?php echo 'Costo WO'; ?>
			</th>
			<th class="sizeprecio">Precio</th>
			<th class="">Seleccionar</th>
		</thead>
		<tbody>
			<?php if (!empty($otherProducts )): ?>								
				<?php foreach ($otherProducts as $key => $product): ?>
					<tr>
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
						<td style="max-width: 220px !important">
							<?php 
								$categorias = $categoriesData[$product["Product"]["category_id"]]; 
							 	$grupos = explode("-->",$categorias);
							 	$num = 1;
							 ?>
							 
	    					<ul class="list-unstyled">
								<?php foreach ($grupos as $key => $value): ?>
									<li> <b>Grupo <?php echo $num; $num++; ?>:</b> <?php echo $value; ?> </li>
								<?php endforeach ?>
							</ul>



						</td>

						<td class="size11">
							<?php echo $this->element("products_block",["producto" => $product["Product"], "inventario_wo" => $partsData[$product["Product"]["part_number"]] ,"reserva" => isset($partsData["Reserva"][$product["Product"]["part_number"]]) ? $partsData["Reserva"][$product["Product"]["part_number"]] : null ]) ?>
						</td>


						<td class="text-center resetpd">

							<div class="price-purchase_price_usd">
								$<?php echo h($product['Product']['purchase_price_usd']); ?> USD
							</div>
							 
							<div class="price-purchase_price_wo">
								$<?php echo h($product['Product']['purchase_price_wo']); ?> COP
							</div>

						</td>				
						<td>$<?php echo number_format((int)h($product['Product']['list_price_usd']),0,",","."); ?>&nbsp;</td>
						<td>

							<a href="" class="btn btn-success selectOtherProduct" data-id="<?php echo $product['Product']['id'] ?>" data-request="<?php echo $requestId ?>">
								<i class="fa fa-check vtc"></i>
							</a>
						</td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr>
					<td colspan="8" class="text-center">
						No se encuentran más productos para agregar de esta marca
					</td>
				</tr>
			<?php endif ?>
		</tbody>
	</table>
</div>