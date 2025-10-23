<h3 class="text-center">
	Normalmente se cotizan juntos
</h3>
<div class="row">
	<?php foreach ($productsSugestions["productsFinals"] as $keySug => $valueSug): ?>
		<?php $producto = $valueSug["Product"] ?>
		<?php $productInfo =  $this->Utilities->getQuantityBlock($producto) ?>
		<div class="col-md-12">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<?php $ruta = $this->Utilities->validate_image_products($producto['img']); ?>
						<div class="imginv mb-3" style="background-image: url(<?php echo $this->Html->url('/img/products/'.$ruta) ?>)"></div>
						<a href="" data-id="<?php echo $producto["id"] ?>" class="btn btn-block btn-success addToQt">
								Agregar a la cotización
							</a>
					</div>
					<div class="col-md-12">
						<div class="dataproductview2">
							<small class="text-success">Referencia: <?php echo $producto['part_number'] ?> / Marca: <?php echo $producto['brand'] ?></small> 
							<small class=""><?php echo $this->Text->truncate(strip_tags($producto['name']), 70,array('ellipsis' => '...','exact' => false)); ?></small> 
							<small class="mb-0"><b>Inventario Transito:</b> <?php echo $productInfo["productData"]["quantity_back"] ?></small>
							<?php if (isset($productsSugestions["inventarioWo"][$producto["part_number"]]) ): ?>
								<hr>
								<small class="text-success">Inventario real WO</small>
								<?php if (empty($productsSugestions["inventarioWo"][$producto["part_number"]])): ?>
									<small class="mb-0"><b>Inventario MED:</b> 0</small>
									<small class="mb-0"><b>Inventario BOG:</b> 0</small>
								<?php else: ?>
									<?php foreach ($productsSugestions["inventarioWo"][$producto["part_number"]] as $keyWO => $valueWo): ?>
										<small class="mb-0"><b>Inventario <?php echo $valueWo["bodega"] ?>:</b> <?php echo $valueWo["total"] ?></small>
									<?php endforeach ?>
								<?php endif ?>								
							<?php endif ?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>