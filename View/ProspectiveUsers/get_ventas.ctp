<h5 class="text-center text-success">
	Producto: <?php echo $producto["name"] ?> - <?php echo $producto["part_number"] ?>
</h5>

<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Fecha de venta</th>
				<th>Cantidad</th>
				<th>Factura</th>
				<th>Cliente</th>
				<th>Vendedor</th>
				<th>Ver factura</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($ventas as $key => $value): ?>
				<tr>
					<td>
						<?php echo $this->Utilities->date_castellano($value['Wo']['Fecha']); ?>
					</td>
					<td>
						<?php echo $value["Wo"]["Cantidad"] ?>
					</td>
					<td>
						<?php echo $value["Wo"]["prefijo"] ?> <?php echo $value["Wo"]["Numero_Documento"] ?>
					</td>
					<td>
						<?php echo $value["Wo"]["Tercero"] ?> 
					</td>
					<td>
						<?php echo $value["Wo"]["Empleado_Vendedor"] ?> 
					</td>
					<td>
						<a href="javascript:void(0)" class="btn-info btn-primary facturaData" data-prefijo="<?php echo $value["Wo"]["prefijo"] ?>" data-numero="<?php echo $value["Wo"]["Numero_Documento"] ?>">
							Ver factura <i class="fa fa-file vtc"></i>
						</a>	
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>