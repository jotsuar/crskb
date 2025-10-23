<div class="databussiness contenttableresponsive">
	<table class="table table-striped table-bordered">
		<tr>
			<th>Origen</th>
			<th>Requerimiento</th>
			<th>Asesor</th>
			<th>Fecha</th>
			<th>Ver</th>
		</tr>
		<tbody>
		<?php foreach ($requerimientos_cliente as $value): ?>
		<tr>
			<td><?php echo $value['ProspectiveUser']['origin'] ?></td>
			<td><?php echo $this->Utilities->find_reason_prospective($value['ProspectiveUser']['id']) ?></td>
			<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
			<td><?php echo $this->Utilities->date_castellano($value['ProspectiveUser']['created']) ?></td>
			<td>
				<a class="btn btn-primary" href="<?php echo Router::url('/', true).'prospectiveUsers/index?q='.$value['ProspectiveUser']['id'] ?>">
					<i class="fa fa-file-text fa-x"></i>
				</a>
			</td>
		</tr>
		<?php endforeach ?>
		</tbody>
	</table>
</div>