<div class="row">
	<div class="col-md-4 ">
		<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img']); ?>
		<img class="img-fluid imgmin-product" dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($datos['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>">
	</div>
	<div class="col-md-8">
		<div class="data_product_imp">
			<h4 class="">						
				 <?php echo $datos["Product"]["name"] ?>
					<span>- <?php echo $datos["Product"]["part_number"] ?></span>						
			</h4> 
			<div class="row">
				<form action="#" method="post" id="productoEmpresaForm">
					<div class="col-md-12 alingnbox">
						<b>Cantidad solicitada:</b> <?php echo $datos["ImportProduct"]["quantity"] ?>
						<input type="hidden" name="product_import" value="<?php echo $productImport ?>">
					</div>
					<hr>
					<?php $interna = 0; ?>
					<?php foreach ($details as $key => $value): ?>
						<?php if ( $value["ImportRequestsDetail"]["type_request"] == 2): ?>
							<div class="col-md-12 alingnbox">
								<div class="form-group">
									<label for="cantidadLlego">Cantidad que llega por solicitud interna de <?php echo $this->Utilities->find_name_adviser($value["ImportRequestsDetail"]["user_id"]) ?>: </label>
									<input type="number" id="cantidadLLego" value="<?php echo $value["ImportProductsDetail"]["quantity"] - $value["ImportProductsDetail"]["quantity_final"] ?>" min="<?php echo count($details) == 1 ? "0" : "0" ?>" max="<?php echo $value["ImportProductsDetail"]["quantity"] - $value["ImportProductsDetail"]["quantity_final"]  ?>" class="form-control" name="data[cantidad_interna][<?php echo $value["ImportProductsDetail"]["id"] ?>]">
								
								</div>
							</div>
						<?php elseif($value["ImportRequestsDetail"]["type_request"] == 3 || $value["ImportRequestsDetail"]["type_request"] == 4): ?>
							<div class="col-md-12 alingnbox">
								<div class="form-group">
									<label for="cantidadLlego">Cantidad que llega para reposición: </label>
									<input type="number" id="cantidadLLego" name="data[cantidat_transito][<?php echo $value["ImportProductsDetail"]["id"] ?>]" value="<?php echo $value["ImportProductsDetail"]["quantity"] - $value["ImportProductsDetail"]["quantity_final"] ?>" min="<?php echo count($details) == 1 ? "0" : "0" ?>" max="<?php echo $value["ImportProductsDetail"]["quantity"] - $value["ImportProductsDetail"]["quantity_final"]  ?>" class="form-control">
								</div>							
						</div>
						<?php elseif(!empty($value["ImportProductsDetail"]["flujo"]) || $value["ImportProductsDetail"]["flujo"] != "0"): ?>
							<div class="col-md-12 alingnbox">
								<div class="form-group">
									<label for="cantidadLlego">Cantidad que llega para el flujo: <?php echo $value["ImportProductsDetail"]["flujo"] ?></label>
									<input type="number" id="cantidadLLego" value="<?php echo $value["ImportProductsDetail"]["quantity"] - $value["ImportProductsDetail"]["quantity_final"] ?>" min="<?php echo count($details) == 1 ? "1" : "0" ?>" max="<?php echo $value["ImportProductsDetail"]["quantity"] - $value["ImportProductsDetail"]["quantity_final"]  ?>" class="form-control" name="data[cantidad_flujo][<?php echo $value["ImportProductsDetail"]["id"] ?>]">
								</div>							
							</div>
						<?php endif ?>

					<?php endforeach ?>
					<div class="col-md-12 alingnbox">
								<div class="form-group">
									<label for="cantidadLlego">Cantidad que adicional que llega: </label>
									<input type="number" id="cantidadLLego" value="0" min="0" max="" class="form-control" name="data[cantidad_adicional][<?php echo $datos["Product"]["id"] ?>]">
								
								</div>
							</div>
					<div class="col-md-12 alingnbox">
						<div class="form-group">
							<label for="envio_mensaje">Enviar correo electrónico</label>
							<select name="data[envio_mensaje]" id="envio_mensaje" required>
								<option value="">Seleccionar</option>
								<option value="1">Si</option>
								<option value="0">No</option>
							</select> 
						</div>
					</div>
					<div class="col-md-12 alingnbox mt-3">
							<div class="form-group">
								<input type="submit" class="btn btn-success" value="Guardar información producto">
							</div>
							
						</div>
				</form>
			</div>
		</div>
	</div>
</div>