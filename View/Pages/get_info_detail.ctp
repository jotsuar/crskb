<div class="row">
	<div class="col-md-12" id="divListado">
		
		<div class="table-responsive">
			<table class="table-hovered table">
				<thead>
					<tr>
						<th>
							Fecha
						</th>
						<th class="p-0">
							Factura
						</th>
						<th class="p-0">
							Flujo
						</th>
						
						<th class="p-0" style="max-width: 350px !important">
							Cliente
						</th>
						<th class="p-0" style="max-width: 350px !important">
							Empleado
						</th>
						<th class="p-0">
							Valor
						</th>
						<th>
							Ver detalle
						</th>
					</tr>
				</thead>
				<?php foreach ($lastDocuments as $key => $value): ?>
					<tr>
						<?php $strPos = strpos($value["Factura"], "DMC"); ?>
						<th>
							<?php echo date("Y-m-d",strtotime($value["Fecha"])) ?>
						</th>
						<th class="p-0">
							<?php echo $value["Factura"] ?>
						</th>

						<th class="p-0">
							<?php if ($strPos === false): ?>
								<?php echo is_null($value["Personalizado5"]) ? "No asignado" : $value["Personalizado5"] ?>
							<?php endif ?>
						</th>
						
						<td class="p-0" style="max-width: 350px !important">
							<?php echo $value["Nombre"] ?>
						</td>
						<td class="p-0" style="max-width: 350px !important">
							<?php echo $value["NombreVendedor"] ?>
						</td>
						<td class="p-0">
							<?php $strPos = strpos($value["Factura"], "DMC"); ?>
							<?php if ($strPos === false): ?>
								<i class="fa fa-plus vtc"></i>
							<?php else: ?>
								<i class="fa fa-minus vtc"></i>
							<?php endif ?>
							<?php
								
								$totalVD = $strPos === false ? $value["Total_Venta"] : $value["Total_Descuentos"]; ?>
							$ <?php echo number_format(intval($totalVD),"2",".",",") ?>
						</td>
						<td>
							<a href="javascript:void(0)" class="btn btn-info btn-sm vewInfoCode" data-code="<?php echo $value["Factura"] ?>">
								Ver detalle
							</a>
						</td>
					</tr>
				<?php endforeach ?>
			</table>
		</div>

	</div>
	<div class="col-md-12" id="divDetalle">
		<div class="row">
			<div class="col-md-12">
				<a href="javascript:void(0)" class="btn btn-info btn-sm" id="regresaInfoDetalle"> <i class="fa fa-arrow-left vtc"></i> Regresar </a>
			</div>
			<div class="col-md-12" id="facturaDetalleWO">
				
			</div>
		</div>
	</div>
</div>
