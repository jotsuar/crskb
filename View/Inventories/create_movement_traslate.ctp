<div class="col-md-12">
	<div class="row spacetopbottom">
		<div class="col-md-3">
			<?php $ruta = $this->Utilities->validate_image_products($producto['Product']['img']); ?>
			<img class="img-fluid productoImagenData" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>">
		</div>
		<div class="col-md-9">
			<div class="dataproductview2">
				<strong class="text-success">Referencia: <?php echo $producto['Product']['part_number'] ?> / Marca: <?php echo $producto['Product']['brand'] ?></strong>
				<h3 class=""><?php echo $this->Text->truncate(strip_tags($producto['Product']['name']), 70,array('ellipsis' => '...','exact' => false)); ?></h3>
				<p class="descriptionview nc dnone"><?php echo $this->Text->truncate(strip_tags($producto['Product']['description']), 400,array('ellipsis' => '...','exact' => false)); ?></p>
				<p class="mb-0"><b>Inventario Medellín:</b> <?php echo $producto["ProductsLock"]["quantity"] ?></p>
				<p class="mb-0"><b>Inventario Bogotá:</b> <?php echo $producto["ProductsLock"]["quantity_bog"] ?></p>
				<p class="mb-0"><b>Inventario Transito:</b> <?php echo $producto["ProductsLock"]["quantity_back"] ?></p>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<h2 class="text-center">Datos del movimiento</h2>
	<form action="#" id="formMovivimientoProducto" method="post">
		<input type="hidden" id="productoId" name="productoId" value="<?php echo $producto["Product"]["id"] ?>">
		<input type="hidden" id="productoName" name="productoName" value="<?php echo $producto["Product"]["name"] ?>">
		<input type="hidden" id="movimientoBloqueo" name="movimientoBloqueo" value="<?php echo $producto["ProductsLock"]["id"] ?>">
		<input type="hidden" id="productoRef" name="productoRef" value="<?php echo $producto["Product"]["part_number"] ?>">
		<input type="hidden" id="productoImage" name="productoImage" value="<?php echo Router::url("/",true).'img/products/'.$producto["Product"]["img"]; ?>">
		<div class="form-group">
			<label for="type_movement">Tipo de movimiento</label>
			<select name="type_movement" id="type_movement" required="">
				<option value="">Seleccionar tipo</option>
				<?php if ($producto["ProductsLock"]["quantity"] > 0 || $producto["ProductsLock"]["quantity_bog"] > 0 || $producto["ProductsLock"]["quantity_back"] > 0 ): ?>
					<option value="TR">Traslado</option>
				<?php endif ?>
			</select>
		</div>
		<div class="form-group entrada">
			<?php $bodegas = Configure::read("BODEGAS"); unset($bodegas["ST Medellín"]); unset($bodegas["ST Bogotá"]); ?>
			<label for="bodegaEntrada">Bodega de entrada</label>
			<select name="bodegaEntrada" id="bodegaEntrada" class="entradaProducto">
				<option value="">Seleccionar bodega de entrada</option>
				<?php foreach ($bodegas as $key => $value): ?>
					<option value="<?php echo $value ?>"> <?php echo $value ?></option>
				<?php endforeach ?>
			</select>
		</div>
		<div class="form-group entrada">
			<label for="CantidadEntrada">Cantidad entrada</label>
			<input name="CantidadEntrada" id="CantidadEntrada" type="number" min="1" value="1" class="form-control entradaProducto">
		</div>
		<div class="form-group salida">
			<label for="bodegaSalida">Bodega de salida</label>
			<select name="bodegaSalida" id="bodegaSalida" class=" salidaProducto">
				<option value="">Seleccionar bodega de entrada</option>
				<?php if ($producto["ProductsLock"]["quantity"] > 0): ?>
					<option value="Medellín" data-id="MED" data-quantity="<?php echo $producto["ProductsLock"]["quantity"] ?>">Medellín</option>
				<?php endif ?>
				<?php if ($producto["ProductsLock"]["quantity_bog"] > 0): ?>
					<option value="Bogotá" data-id="BOG" data-quantity="<?php echo $producto["ProductsLock"]["quantity_bog"] ?>">Bogotá<</option>
				<?php endif ?>
				<?php if ($producto["ProductsLock"]["quantity_stm"] > 0): ?>
					<option value="Medellín" data-id="MEDST" data-quantity="<?php echo $producto["ProductsLock"]["quantity_stm"] ?>">ST Medellín</option>
				<?php endif ?>
				<?php if ($producto["ProductsLock"]["quantity_stb"] > 0): ?>
					<option value="Bogotá" data-id="BOGST" data-quantity="<?php echo $producto["ProductsLock"]["quantity_stb"] ?>">ST Bogotá</option>
				<?php endif ?>
			</select>
		</div>
		<div class="form-group salida">
			<label for="CantidadSalida">Cantidad salida</label>
			<input name="CantidadSalida" id="CantidadSalida" type="number" min="1" value="1" class="form-control salidaProducto">
		</div>
		<div class="form-group traslado">
			<label for="bodegaSalidaTraslado">Bodega de salida para el traslado</label>
			<select name="bodegaSalidaTraslado" id="bodegaSalidaTraslado" class=" trasladoProducto">
				<option value="">Seleccionar bodega de entrada</option>
				<?php if ($producto["ProductsLock"]["quantity"] > 0): ?>
					<option value="Medellín" data-id="MED" data-quantity="<?php echo $producto["ProductsLock"]["quantity"] ?>">Medellín</option>
				<?php endif ?>
				<?php if ($producto["ProductsLock"]["quantity_bog"] > 0): ?>
					<option value="Bogotá" data-id="BOG" data-quantity="<?php echo $producto["ProductsLock"]["quantity_bog"] ?>">Bogotá</option>
				<?php endif ?>
				<?php if ($producto["ProductsLock"]["quantity_back"] > 0): ?>
					<option value="Transito" data-id="TRANS" data-quantity="<?php echo $producto["ProductsLock"]["quantity_back"] ?>">Transito</option>
				<?php endif ?>
			</select>
		</div>
		<div class="form-group traslado">
			<label for="CantidadSalidaTraslado">Cantidad traslado</label>
			<input name="CantidadSalidaTraslado" id="CantidadSalidaTraslado" type="number" min="1" value="1" class="form-control trasladoProducto">
		</div>
		<div class="form-group traslado">
			<label for="bodegaEntradaTraslado">Bodega de entrada para el traslado</label>
			<select name="bodegaEntradaTraslado" id="bodegaEntradaTraslado" class="trasladoProducto">
				<option value="">Seleccionar bodega de entrada</option>
				<option value="Medellín" data-id="MED" >Medellín</option>
				<option value="Bogotá" data-id="BOG" >Bogotá</option>
				<option value="Transito" data-id="TRANS" >Transito</option>
			</select>
		</div>
		<div class="form-group mt-2">
			<input type="hidden" name="razonMovimiento" id="razonMovimiento" cols="30" rows="10" required="" value="Se traslada para realizar facturación">
		</div>
		<div class="form-group mt-4">
			<input type="submit" class="btn btn-success pull-right" value="Crear traslado">
		</div>
	</form>
</div>

