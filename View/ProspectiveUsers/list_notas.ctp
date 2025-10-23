<?php if (count($datos) > 0) { ?>
	<table class="table table-striped myTable">
		<thead>
		<tr>
			<th>DESCRIPCIÓN</th>
			<th>ETAPA</th>
			<th>ASESOR</th>
			<th>FECHA</th>
			<th>
				Imagen de contacto
			</th>
		</tr>
		</thead>
		<tbody>
			<?php foreach ($datos as $value): ?>
				<tr>
					<td class="describenota"><?php echo $value['ProgresNote']['description'] ?></td>
					<td><?php echo $value['ProgresNote']['etapa'] ?></td>
					<td><?php echo $this->Utilities->find_name_lastname_adviser($value['ProgresNote']['user_id']) ?></td>
					<td><?php echo $this->Utilities->date_castellano(h($value['ProgresNote']['created'])); ?></td>
					<?php if (!empty($value["ProgresNote"]["image"])): ?>
						<td>
							<?php $ruta = $value["ProgresNote"]["image"]; ?>
							<img dataimg="<?php echo $this->Html->url('/img/flujo/contactado/'.$ruta) ?>" dataname="Gestión" src="<?php echo $this->Html->url('/img/flujo/contactado/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
						</td>
					<?php else: ?>
						<td>
							&nbsp;
						</td>
					<?php endif ?>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php } else { ?>
	<p class="text-center">Aún no se han creado novedades en este negocio</p>
<?php } ?>