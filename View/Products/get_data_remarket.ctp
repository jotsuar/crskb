<h2 class="text-center mb-2">
	Cotizaciones encontradas <?php echo $this->request->data["type"] == "venta" ? "con ventas" : "" ?>  - <?php echo count($cotizaciones) ?>
</h2>

<div class="table-responsive" style="max-height: <?php echo $this->request->data["ht"]; ?>px; overflow-y: auto; ">
	<table class="table table-bordered table-hovered" id="tablaProd" >
		<thead class="backblue">
			<th>Cliente</th>
			<?php if (isset($this->request->data["is_report"])): ?>
				<th>Ciudad</th>
				<th>Email</th>
				<th>Teléfono</th>
				<th>Asesor</th>
			<?php endif ?>
			<th>Cotización</th>
			<th>Fecha</th>
			<?php if (!isset($this->request->data["is_report"])): ?>
				<th>Estado Cotización</th>
			<?php endif ?>
			<th>Estado flujo</th>
			<th>Flujo</th>
			<?php if (!isset($this->request->data["is_report"])): ?>
				
				<th class="text-center">
					Seleccionar 
					<?php if (!empty($cotizaciones)): ?>
						<input type="checkbox" class="seleccionTotal checkbox" value="1">
					<?php endif ?>
				</th>
			<?php else: ?>
				<th>
					Precio
				</th>
			<?php endif ?>
		</thead>
		<tbody>
			<?php if (empty($cotizaciones)): ?>
				<tr>
					<td colspan="7">Tu búsqueda no arrojó cotizaciones</td>
				</tr>
			<?php else: ?>
				<?php foreach ($cotizaciones as $key => $value): ?>
					<tr>
						<td class="uppercase">
							<?php if (!isset($this->request->data["is_report"])): ?>
								<?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['Quotation']['prospective_users_id']), !isset($this->request->data["is_report"]) ? 50 : 200,array('ellipsis' => '...','exact' => false)); ?>
							<?php else: ?>
								<?php echo $value["Customer"]["name"] ?>
							<?php endif ?>
						</td>
						<?php if (isset($this->request->data["is_report"])): ?>
							<td><?php echo $value["Customer"]["city"] ?></td>
							<td><?php echo $value["Customer"]["email"] ?></td>
							<td><?php echo $value["Customer"]["cell_phone"] ?></td>
							<td><?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["user_id"]) ?></td>
						<?php endif ?>
						<td>
							<a class="btn btn-secondary btn-min getQuotationId modalFlujoClick" data-quotation="<?php echo $value["Quotation"]["id"] ?>" href="#">
								<?php echo $value['Quotation']['codigo'] ?>
							</a>
							
						</td>
						<td><?php echo $value['Quotation']['created'] ?></td>
						<?php if (!isset($this->request->data["is_report"])): ?>
							<td>
								<?php echo $this->Utilities->find_stateFlow_quotation($value['Quotation']['prospective_users_id'],$value['Quotation']['id']) ?>
							</td>
						<?php endif ?>
						<td>
							<?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']) ?>
						</td>
						<td>
							<a class="btn btn-success btn-min idflujotable flujoModal modalFlujoClick" href="#" data-uid="<?php echo $value["Quotation"]["prospective_users_id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value["Quotation"]["prospective_users_id"]); ?>">
								<?php echo $value['Quotation']['prospective_users_id'] ?>
							</a>
							
						</td>
						<?php if (!isset($this->request->data["is_report"])): ?>
							<td class="text-center">
								<?php $clientData = $this->Utilities->get_clientData($value['Quotation']['prospective_users_id']); ?>
								<input type="checkbox" class="seleccionBasica checkbox" data-id="<?php echo $clientData["id"]; ?>" data-type="<?php echo $clientData["type"]; ?>" value="<?php echo $value["Quotation"]["prospective_users_id"]; ?>" >
							</td>
						<?php else: ?>
							<td>
								<?php foreach ($value["QuotationsProduct"] as $key => $valueProd): ?>
								<?php if (in_array($valueProd["product_id"], $this->request->data["products"])): ?>
									<?php echo count($this->request->data["products"]) == 1 ? number_format($valueProd["price"]) : '<b>'.$this->Utilities->getRefProd($valueProd["product_id"]).':</b> '.number_format($valueProd["price"])." <br>"; ?>
								<?php endif ?>
							<?php endforeach ?>
							</td>
						<?php endif ?>
					</tr>
				<?php endforeach ?>
			<?php endif ?>
		</tbody>
	</table>
	
	

</div>
<div class="p4 mt-3" id="btnContinue" style="display: none">
	<a href="#" class="btn btn-warning float-right" id="continuarEnvio">Continuar</a>
</div>