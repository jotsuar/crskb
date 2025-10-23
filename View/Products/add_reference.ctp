<div class="col-md-12">
	<div class="row spacetopbottom">
		<div class="col-md-3">
			<?php $ruta = $this->Utilities->validate_image_products($producto['Product']['img']); ?>
			<img class="img-fluid productoImagenData" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>">
		</div>
		<div class="col-md-9">
			<div class="dataproductview2">
				<?php $productInfoDataset =  $this->Utilities->getQuantityBlock($producto["Product"]); ?>
				<strong class="text-success">Referencia: <?php echo $producto['Product']['part_number'] ?> / Marca: <?php echo $producto['Product']['brand'] ?></strong>
				<h3 class=""><?php echo $this->Text->truncate(strip_tags($producto['Product']['name']), 70,array('ellipsis' => '...','exact' => false)); ?></h3>
				<p class="descriptionview nc dnone"><?php echo $this->Text->truncate(strip_tags($producto['Product']['description']), 400,array('ellipsis' => '...','exact' => false)); ?></p>
				<div>
					<?php echo $this->element("products_block",["producto" => $producto["Product"],"inventario_wo" => $partsData[$producto["Product"]["part_number"]]]) ?>
				</div>		
			</div>
		</div>
	</div>
	<div class="row spacetopbottom">
		<div class="col-md-12">
			<form id="addReferenceForm" method="POST">
				<div class="form-group">
					<label for="quantity">Cantidad</label>
					<input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
				</div>
				<div class="form-group">
					<label for="<?php echo $this->request->data["currency"] == "cop" ? "purchase_price_cop" : "purchase_price_usd" ?>" >Costo del producto</label>
					<input type="number" name="<?php echo $this->request->data["currency"] == "cop" ? "purchase_price_cop" : "purchase_price_usd" ?>" id="<?php echo $this->request->data["currency"] == "cop" ? "purchase_price_cop" : "purchase_price_usd" ?>" step="any" class="form-control" min="1" value="<?php echo $this->request->data["currency"] == "cop" ? $producto["Product"]["purchase_price_cop"] : $producto["Product"]["purchase_price_usd"] ?>" required >
				</div>
				<div class="form-group mt-4">
					<?php foreach ($this->request->data as $key => $value): ?>
						<input type="hidden" name="<?php echo $key ?>" value="<?php echo $value ?>">
					<?php endforeach ?>
					<input type="submit" class="btn btn-success" value="Agregar referencia">
				</div>
			</form>
		</div>
	</div>
</div>