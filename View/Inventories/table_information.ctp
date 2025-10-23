<?php if (!isset($this->request->data["productos"])): ?>
	<tr>
		<td colspan="8" class="text-center">
			No hay movimientos actualmente
		</td>
	</tr>
<?php else: ?>
	<?php foreach ($this->request->data["productos"] as $key => $value): ?>
		<tr>
			<td>
				<img style="width: 55px;" src="<?php echo $value["productoImage"] ?>" alt="<?php echo $value["productoName"] ?>">
			</td>
			<td>
				<?php echo $value["productoName"] ?>
			</td>
			<td>
				<?php echo $value["productoRef"] ?>
			</td>
			<td>
				<?php echo Configure::read("MOVEVENTS.".$value["type_movement"]); ?>
			</td>
			<td>
				<?php if ($value["type_movement"] == "RM"): ?>
					<?php echo $value["bodegaSalida"] ?>
				<?php elseif ($value["type_movement"] == "ADD"): ?>
					<?php echo $value["bodegaEntrada"] ?>
				<?php elseif ($value["type_movement"] == "TR"): ?>
					<b>Salida:</b> <?php echo $value["bodegaSalidaTraslado"] ?> <br>
					<b>Entrada:</b> <?php echo $value["bodegaEntradaTraslado"] ?>
				<?php endif ?>
			</td>
			<td>
				<?php if ($value["type_movement"] == "RM"): ?>
					<?php echo $value["CantidadSalida"] ?>
				<?php elseif ($value["type_movement"] == "ADD"): ?>
					<?php echo $value["CantidadEntrada"] ?>
				<?php elseif ($value["type_movement"] == "TR"): ?>
					<?php echo $value["CantidadSalidaTraslado"] ?> 
				<?php endif ?>
			</td>
			<td>
				<?php echo $value["razonMovimiento"] ?>
			</td>
			<td>
				<a href="" class="btn btn-danger eliminarPanel" data-id="<?php echo $value["productoId"] ?>">Eliminar</a>
			</td>
		</tr>
	<?php endforeach ?>
<?php endif ?>