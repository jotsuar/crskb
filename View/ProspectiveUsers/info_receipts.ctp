<div class="contentdataRecibos">
	<?php $totalNoIvaOtras = 0;	$totalIvaOtras = 0; ?>
	<?php if (!empty($valorQuotation)): ?>
		<p>Precio de cotización con iva: <b> $ <?php echo number_format(($valorQuotation * 1.19), 2, ".",",") ?> </b></p>
	<?php endif ?>
	<?php if (!empty($valorQuotation)): ?>
		<p>Precio de cotización sin iva: <b> $ <?php echo number_format($valorQuotation, 2, ".",",") ?></b></p>
	<?php endif ?>
	<?php if (!empty($datosProspecto["ProspectiveUser"]["bill_value"])): ?>
		<?php if(!empty($otrasFacturas)){
			
			foreach ($otrasFacturas as $key => $value) {
				$totalNoIvaOtras+=$value["Salesinvoice"]["bill_value"];
				$totalIvaOtras+=$value["Salesinvoice"]["bill_value_iva"];
			}
		} ?>
		<p>Precio de factura sin iva: <b> $ <?php echo number_format( ($datosProspecto["ProspectiveUser"]["bill_value"] + $totalNoIvaOtras), 2, ".",",") ?> </b></p>
	<?php endif ?>
	<?php if (!empty($datosProspecto["ProspectiveUser"]["bill_value_iva"])): ?>
		<p>Precio de factura con iva: <b> $ <?php echo number_format(($datosProspecto["ProspectiveUser"]["bill_value_iva"] + $totalIvaOtras), 2, ".",",") ?></b></p>
	<?php endif ?>

	<p>Total ingresado en recibos de caja <b>$ <?php echo number_format($totalActual, 2, ".",",") ?></b></p>
	<?php if ($totalActual >= 0): ?>
		<p>Saldo por ingresar en recibos de caja <b>$ <?php echo number_format( (($valorQuotation * 1.19) - $totalActual) , 2, ".",",") ?></b></p>
	<?php endif ?>
</div>
<?php if (!empty($recibosCaja)): ?>
	<h4 class="text-center titlespace">Recibos de caja registrados</h4>
	<div class="text-center">
		<a href="#" data-recibo="<?php echo $datosProspecto["ProspectiveUser"]["id"] ?>" class="text-center nuevoReciboBtn"> 
			<i class="fa fa-plus vtc"></i> Ingresar nuevo recibo 
		</a>						
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Código</th>
					<th>Factura </th>
					<th>Sin iva</th>
					<th>Con iva</th>
					<th>F. recibo</th>
					<th>Usuario</th>
					<th>RETEFUENTE</th>
					<th>RETEIVA</th>
					<th>Otras retenc.</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($recibosCaja as $key => $value): ?>
					<tr>
						<td><?php echo $value["Receipt"]["code"] ?></td>
						<td><?php echo $this->Utilities->getCodeBill($datosProspecto["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"]); ?></td>
						<td><?php echo number_format($value["Receipt"]["total_iva"],2,".",",") ?></td>
						<td><?php echo number_format($value["Receipt"]["total"],2,".",",") ?></td>
						<td><?php echo $value["Receipt"]["date_receipt"] ?></td>
						<td>
							<?php $texto = explode(" ", $value['User']['name']); if (isset($texto[1])) {$texto = $texto[0];}
								echo $texto;?>
						</td>
						<td><?php echo $value["Receipt"]["retefuente"] == 1 ? "Si" : "No" ?></td>
						<td><?php echo $value["Receipt"]["reteiva"] == 1 ? "Si" : "No"?></td>
						<td><?php echo $value["Receipt"]["otras"] == 1 ? "Si" : "No" ?></td>
						<td>
							<a href="" class="btn btn-info btnEditRecipe" data-id="<?php echo $value["Receipt"]["id"] ?>">
								<i class="fa fa-edit vtc"></i>
							</a>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<p class="text-center text-uppercase noRecibos">No se han ingresado recibos de caja</p>
	<div class="text-center">
		<a href="#" data-recibo="<?php echo $datosProspecto["ProspectiveUser"]["id"] ?>" class="text-center nuevoReciboBtn"> 
			<i class="fa fa-plus vtc"></i> Ingresar nuevo recibo 
		</a>						
	</div>
<?php endif ?>				
