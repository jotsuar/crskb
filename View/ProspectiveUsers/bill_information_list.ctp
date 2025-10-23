<?php if ( !empty($datos) && isset($this->request->data["modalIngreso"])): ?>
	<div id="formularioIngresoFact"></div>
	<div id="divGeneralIngreso">
<?php endif ?>
<h2 class="text-center text-info">
	Facturas ingresadas para el flujo: <?php echo empty($datos) ? "Flujo no encontrado" : $datos["ProspectiveUser"]["id"]; ?>
	<?php if ( !empty($datos) && isset($this->request->data["modalIngreso"]) && in_array($datos["ProspectiveUser"]["state_flow"], [5,6,8] ) && in_array($last["FlowStage"]["state_flow"], ["Despachado","Datos Despacho","Pagado"])  ): ?>
		<a class="btn btn-success text-white pointer <?php echo empty($datos["ProspectiveUser"]["bill_code"]) ? 'pointer': 'pointer' ?>  info_bill2" data-uid="<?php echo $datos['ProspectiveUser']['id'] ?>" data-bill="<?php echo $datos["ProspectiveUser"]["bill_code"] ?>">
			<i class="fa fa-plus vtc"></i> <?php echo !empty($datos["ProspectiveUser"]["bill_code"]) ? "Nueva factura" : "Ingresar factura" ?>	
		</a>
	<?php else: ?>
		<?php if ( !empty($datos) && isset($this->request->data["modalIngreso"]) && !in_array($last["FlowStage"]["state_flow"], ["Despachado","Datos Despacho"]) && !in_array($datos["ProspectiveUser"]["state_flow"], [5,6,8]   ) ): ?>
			<br>
			<span class="text-center text-danger">
				No es posible ingresar factura ya que el flujo no está en estado despachado 
			</span>
		<?php endif ?>
	<?php endif ?>
</h2>
<div class="table-responsive mt-2">
	<table class="table table-hovered">
		<thead>
			<tr>
				<th>Código</th>
				<th>Total sin iva</th>
				<th>Total con iva</th>
				<th>Fecha factura</th>
				<th>Usuario</th>
				<th>Archivo</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($datos) && !empty($datos["ProspectiveUser"]["bill_code"]) ): ?>
				
				<tr>
					<td><?php echo $datos["ProspectiveUser"]["bill_code"] ?></td>
					<td><?php echo number_format($datos["ProspectiveUser"]["bill_value"], 2, ",",".") ?></td>
					<td><?php echo number_format($datos["ProspectiveUser"]["bill_value_iva"], 2, ",",".") ?></td>
					<td><?php echo $datos["ProspectiveUser"]["bill_date"] ?></td>
					<td><?php echo $this->Utilities->find_name_adviser($datos["ProspectiveUser"]["bill_user"]) ?></td>
					<td>
						<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$datos["ProspectiveUser"]["bill_file"] ) ?>" target="blank" class="btn-info btn-primary <?php echo is_null($datos["ProspectiveUser"]["bill_file"]) ? "mostradDatos" : "" ?>" data-id="KEB_<?php echo md5($datos["ProspectiveUser"]["bill_code"]) ?>">
							Ver factura <i class="fa fa-file vtc"></i>
						</a>										
					</td>
				</tr>
			<?php endif ?>
			<?php foreach ($otrasFacturas as $key => $value): ?>
				<tr>
					<td><?php echo $value["Salesinvoice"]["bill_code"] ?></td>
					<td><?php echo number_format($value["Salesinvoice"]["bill_value"], 2, ",",".") ?></td>
					<td><?php echo number_format($value["Salesinvoice"]["bill_value_iva"], 2, ",",".") ?></td>
					<td><?php echo $value["Salesinvoice"]["bill_date"] ?></td>
					<td><?php echo $this->Utilities->find_name_adviser($value["Salesinvoice"]["user_id"]) ?></td>
					<td>
						<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$value["Salesinvoice"]["bill_file"] ) ?>" target="blank" class="btn-info btn-primary <?php echo is_null($value["Salesinvoice"]["bill_file"]) ? "mostradDatos" : "" ?>" data-id="KEB_<?php echo md5($value["Salesinvoice"]["bill_code"]) ?>">
							Ver factura <i class="fa fa-file vtc"></i>
						</a>										
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
<?php if (!empty($datos)): ?>
	
	<?php if (is_null($datos["ProspectiveUser"]["bill_file"])): ?>

		<div id="KEB_<?php echo md5($datos["ProspectiveUser"]["bill_code"]) ?>" class="table-responsive" style="display: none;">
			<?php $factValue = (array) json_decode($datos["ProspectiveUser"]["bill_text"]); ?>
			<?php echo $this->element("vistaFacturaWo", ["factValue" => $factValue]); ?>
		</div>

	<?php endif ?>
<?php endif ?>

<?php foreach ($otrasFacturas as $key => $value): ?>
	<?php if (is_null($value["Salesinvoice"]["bill_file"])): ?>
		<div id="KEB_<?php echo md5($value["Salesinvoice"]["bill_code"]) ?>" class="table-responsive" style="display: none;">
			<?php $factValue = (array) json_decode($value["Salesinvoice"]["bill_text"]); ?>
			<?php echo $this->element("vistaFacturaWo", ["factValue" => $factValue]); ?>
		</div>
	<?php endif ?>
<?php endforeach ?>

<?php if ( !empty($datos) && isset($this->request->data["modalIngreso"])): ?>
	</div>
<?php endif ?>