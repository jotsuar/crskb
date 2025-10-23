<div class="col-md-12">
	<h3 class="text-center m-t-2 m-b-2">Productos asociados <a href="" class="btn btn-info" id="addProductToCompuesto"><i class="fa fa-plus"></i> Agregar producto</a> </h3>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>
						Referenca
					</th>
					<th>
						Producto
					</th>
					<th>
						Tiempo de entrega
					</th>
					<th>
						IVA
					</th>
					<th>
						Cantidad
					</th>
					<th>
						Precio
					</th>
					<th>
						Moneda
					</th>
					<th>
						Acción
					</th>
				</tr>
			</thead>
			<tbody id="productosCotizacionData">
				<?php if (empty($productos)): ?>
					<tr>
						<td class="text-center" colspan="8">
							No hay productos asociados
						</td>
					</tr>
				<?php else: ?>
					<?php $productos = Set::sort($productos, '{n}.Producto.number', 'asc'); ?>
					<?php foreach ($productos as $key => $value): ?>
						<tr id="<?php echo $value["Producto"]["id"] ?>" data-num="<?php echo $value["Producto"]["number"] ?>"> 
							<td>
								<?php echo $value["Product"]["part_number"] ?>
							</td>
							<td>
								<?php echo $value["Product"]["name"] ?>
							</td>
							<td>
								<?php echo $value["Producto"]["delivery"] ?>
							</td>
							<td>
								<?php echo $value["Producto"]["iva"] == 1 ? "SI" : "NO"; ?>
							</td>
							<td>
								<?php echo $value["Producto"]["quantity"] ?>
							</td>
							<td>
								<?php echo strtoupper($value["Producto"]["currency"]) ?>
							</td>
							<td>
								<?php echo number_format($value["Producto"]["price"],2,",",".") ?>
							</td>
							<td>
								<a href="" data-id="<?php echo $value["Producto"]["product_id"] ?>" class="btn btn-xs btn-sm btn-danger deleteComposition">
									<i class="fa vtc fa-trash"></i>
								</a>
								<a href="" data-id="<?php echo $value["Producto"]["product_id"] ?>" class="btn btn-xs btn-sm btn-warning editProducto">
									<i class="fa vtc fa-edit"></i>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				<?php endif ?>
			</tbody>
		</table>
	</div>
</div>