<h2>
	Cambiar referencia actual: <?php echo $productInfo["Product"]["part_number"] ?>
</h2>

<div class="row">
	<div class="col-md-3">
		<?php $ruta = $this->Utilities->validate_image_products($productInfo['Product']['img']); ?>
			<img class="img-fluid productoImagenData" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>">
	</div>
	<div class="col-md-7 pt-3">
		<b>Precio: </b> $<?php echo number_format($productInfo["OrdersProduct"]["price"]) ?> <br>
		<b>Cantidad: </b> <?php echo number_format($productInfo["OrdersProduct"]["quantity"]) ?> <br>
	</div>
	<div class="col-md-12">
		<?php echo $this->Form->create('OrdersProduct'); ?>
			<div class="form-group">
				<?php echo $this->Form->hidden('id',["value" => $productInfo["OrdersProduct"]["id"] ]); ?>
				<?php echo $this->Form->input('product_id',["label" => "Producto a cambiar", "options" => $references, "class" => "form-control", "value" => $productInfo["OrdersProduct"]["product_id"] ]); ?>
			</div>
			<div class="form-group">
				<?php echo $this->Form->input('quantity',["label" => "Cantidad",  "class" => "form-control", "value" => $productInfo["OrdersProduct"]["quantity"], "min" => $productInfo["OrdersProduct"]["quantity"] ]); ?>
				<input type="submit" class="btn btn-success btn-block" value="Cambiar referencia">
			</div>
		</form>
	</div>
	<div class="col-md-8 mx-auto p-2 text-center">
		<b class="text-danger">
			Solo se cambiará la referencia del producto el precio sigue siendo el mismo.
		</b>
	</div>
</div>