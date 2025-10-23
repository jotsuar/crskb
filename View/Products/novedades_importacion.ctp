<div class="table-responsive">
	<table cellpadding="0" cellspacing="0" class='table-striped table table-bordered'>
		<thead>
			<tr>
				<th>Descripción</th>
				<th>Fecha</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!isset($novedades[0])){ ?>
				<tr>
					<td>No hay información</td>
					<td>No hay información</td>
				</tr>
			<?php } else { ?>
				<?php foreach ($novedades as $value): ?>
					<tr>
						<td><?php echo $value['News']['description'] ?></td>
						<td><?php echo $value['News']['created'] ?></td>
					</tr>
				<?php endforeach ?>
			<?php } ?>
		</tbody>
	</table>

</div>