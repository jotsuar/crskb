<div class="databussiness contenttableresponsive">
	<table class="table table-striped table-bordered">
		<tr>
			<th>Flujo</th>
			<th>Código</th>
			<th>Asesor</th>
			<th>Fecha de inicio</th>
			<th>Fecha de fin</th>
			<th>Ver</th>
		</tr>
		<tbody>
		<?php foreach ($servicio_tecnico as $value): ?>
		<tr>
			<td><?php echo $value['ProspectiveUser']['id'] == 0 ? "Sin flujo" : $value['ProspectiveUser']['id'] ?></td>
			<td><?php echo $value["TechnicalService"]["code"] ?></td>
			<td><?php echo $value['User']['name'] ?></td>
			<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['created']) ?></td>
			<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['date_end']) ?></td>
			<td>
				<?php if ($value['ProspectiveUser']['id'] == 0): ?>
					N/A
				<?php else: ?>
					<a class="btn btn-primary" target="_blank" href="<?php echo Router::url('/', true).'TechnicalServices/flujos?q='.$value['ProspectiveUser']['id'] ?>">
						<i class="fa fa-file-text fa-x"></i>
					</a>
				<?php endif ?>
				
			</td>
		</tr>
		<?php endforeach ?>
		</tbody>
	</table>
</div>