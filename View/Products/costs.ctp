<div class="row">
	<div class="col md-12">
		<h3>Historial de cambios de costo para el producto: <br>
			<?php echo $cambios[0]["Product"]["part_number"] ?> - <?php echo $cambios[0]["Product"]["name"] ?>
		</h3>
	</div>
	<div class="col-md-12">
		<table class="table table-hovered">
			<thead>
				<tr>
					<th>
						Usuario
					</th>
					<th>
						Costo antes
					</th>
					<th>
						Costo despúes
					</th>
					<th>
						Fecha modificación
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($cambios as $key => $value): ?>
					<tr>
						<td><?php echo $value["User"]["name"] ?></td>
						<td><?php echo $value["Cost"]["pre_purchase_price_usd"] ?></td>
						<td><?php echo $value["Cost"]["purchase_price_usd"] ?></td>
						<td><?php echo $value["Cost"]["created"] ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>