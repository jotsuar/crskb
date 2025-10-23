<div class="contenedor_tabla">
	<span class="avatar botonExcel" style="background-image: url('<?php echo $this->Html->url('/files/export_file/excel.png'); ?>')"></span>
	<?php echo 'Número de registros: '.count($datos) ?>
	<!-- <?php echo $this->element('sql_dump'); ?> -->
	<table id="exportar_archivo" border="2px">
		<thead>
			<tr>
				<th>Cliente</th>
				<th>Medio</th>
				<th>ID FLUJO</th>
				<th>Asesor</th>
				<th>Ciudad</th>
				<?php switch ($type_reporte) { 
					case '1': ?>
						<th>Fecha de ingreso del flujo</th>
        			<?php break; ?>
        			<?php case '2': ?>
						<th>Fecha de registro del cliente</th>
        			<?php break; ?>
        			<?php case '3': ?>
        				<th>Fecha del contacto</th>
        			<?php break; ?>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($datos as $value): ?>
				<tr>
					<td><?php echo $this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']); ?> </td>
					<td><?php echo $value['ProspectiveUser']['origin'] ?></td>
					<td><?php echo $value['ProspectiveUser']['id'] ?></td>
					<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']); ?></td>
					<td><?php echo $this->Utilities->city_prospective($value['ProspectiveUser']['id']) ?></td>
					<?php switch ($type_reporte) { 
						case '1': ?>
							<td><?php echo $value['ProspectiveUser']['created'] ?></td>
	        			<?php break; ?>
	        			<?php case '2': ?>
							<td><?php echo $value['ClientsNatural']['created'] ?></td>
	        			<?php break; ?>
	        			<?php case '3': ?>
	        				<td><?php echo $value['ContacsUser']['created'] ?></td>
	        			<?php break; ?>
					<?php } ?>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>