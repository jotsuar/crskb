<div class="col-md-12 p-0">
	<h3 class="text-center m-t-2 m-b-2">Productos asociados <a href="" class="btn btn-info" data-id="<?php echo $id ?>" data-action="<?php echo $action ?>" id="addProductToCompuesto"><i class="fa fa-plus"></i> Agregar producto</a> </h3>
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
						Acción
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($compuestos)): ?>
					<tr>
						<td class="text-center" colspan="4">
							No hay productos asociados
						</td>
					</tr>
				<?php else: ?>
					<?php foreach ($compuestos as $key => $value): ?>
						<tr>
							<td>
								<?php echo $value["Product"]["part_number"] ?>
								<?php echo $this->Form->input('Composition.product_id.'.$value["Composition"]["id"], array('label'=>false,'div'=>false, "value" => $value["Composition"]["product_id"], "type" => "hidden", "class" => "compuestoIngrediente" )); ?>
								<?php echo $this->Form->input('Composition.product.'.$value["Composition"]["id"], array('label'=>false,'div'=>false, "value" => $id, "type" => "hidden", "class" => "compuestoIngrediente" )); ?>
							</td>
							<td>
								<?php echo $value["Product"]["name"] ?>
							</td>
							<td>
								<a href="" data-id="<?php echo $value["Composition"]["product_id"] ?>" class="btn btn-danger deleteComposition">
									<i class="fa fa-trash"></i>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				<?php endif ?>
			</tbody>
		</table>
	</div>
</div>