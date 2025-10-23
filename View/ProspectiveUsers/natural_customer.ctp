<div class="col-md-12">
	<div class="users index blockwhite spacebtn">
		<h2>Personas Naturales</h2>
		<table cellpadding="0" cellspacing="0" class='naturalperson table-striped table-bordered'>
			<thead>
				<tr>
					<th>Nombre completo</th>
					<th>Teléfono</th>
					<th>Celular</th>
					<th>Ciudad</th>
					<th>Correo electrónico</th>
					<th>Origen</th>
					<th>Fecha de registro</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($clients as $user): ?>
				<tr>
					<td><?php echo h($user['ProspectiveUser']['name']); ?>&nbsp;</td>
					<td><?php echo h($user['ProspectiveUser']['telephone']); ?>&nbsp;</td>
					<td><?php echo h($user['ProspectiveUser']['cell_phone']); ?>&nbsp;</td>
					<td><?php echo h($user['ProspectiveUser']['city']); ?>&nbsp;</td>
					<td><?php echo h($user['ProspectiveUser']['email']); ?>&nbsp;</td>
					<td><?php echo h($user['ProspectiveUser']['origin']); ?>&nbsp;</td>
					<td><?php echo h($user['ProspectiveUser']['created']); ?>&nbsp;</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>