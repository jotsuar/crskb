<div class="contenttableresponsive">
	<table cellpadding="0" cellspacing="0" class='table_resultados table-striped table-bordered'>
		<thead>
			<tr>
				<th>Número</th>
				<th>Prospecto</th>
				<th>Tipo de solicitud</th>
				<th>Medio</th>
				<th>Estado actual</th>
				<th>Flujo</th>
				<th>Asignado a:</th>
				<th>Ciudad</th>
			</tr>
		</thead>
		<tbody>
			<?php $num = 1; ?>
			<?php foreach ($datos as $value): ?>
				<tr>
					<td><?php echo $num; $num++; ?></td>
					<td><?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 50,array('ellipsis' => '...','exact' => false)); ?> </td>
					<td><?php echo $this->Text->truncate($value['ProspectiveUser']['description'], 150,array('ellipsis' => '...','exact' => false)); ?> </td>
					<td><?php echo $value['ProspectiveUser']['origin'] ?></td>
					<td><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']) ?></td>
					<td>
						<div class="dropdown d-inline styledrop">
							<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($value["ProspectiveUser"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?php echo $value["ProspectiveUser"]["id"] ?>
							</a>

							<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($value["ProspectiveUser"]["id"]) ?>_<?php echo md5($value["ProspectiveUser"]["id"]) ?>">
								<a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $value["ProspectiveUser"]["id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["ProspectiveUser"]["id"]); ?>">Ver flujo</a>
								<?php if ($value['ProspectiveUser']['state_flow'] >= 3): ?>
									<?php if (!is_null($this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]))): ?>
										
										<a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($value["ProspectiveUser"]["id"]) ?>" href="#">Ver cotización</a>
									<?php endif ?>
									<?php if ($value['ProspectiveUser']['state_flow'] >= 4): ?>
										
									<a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $value["ProspectiveUser"]["id"] ?>">Ver órden de compra</a>
									<?php endif ?>
								<?php endif ?>
							</div>
						</div>
					</td>
					<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']); ?></td>
					<td>
						<?php echo $this->Text->truncate($this->Utilities->city_prospective($value['ProspectiveUser']['id']), 35,array('ellipsis' => '.','exact' => false)); ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php echo $this->Form->input('flujo_reporte',array('label' => 'Reporte para exportar', 'options' => $options_reporte)); ?>
	<a class="btnExportar">Exportar <i class="fa fa-file"></i></a>
	<div class="preloadExportar"></div>
	<form action="exportFileExcel" method="post" target="_blank" id="FormularioExportacion">
	    <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
	    <input type="hidden" id="tipo_exportacion" name="tipo_exportacion" />
  </form>
</div>

<?php echo $this->element("flujoModal"); ?>