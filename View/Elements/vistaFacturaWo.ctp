<div class="row">
	<div class="col-md-6 pl-5">
		<img src="<?php echo $this->Html->url("/files/logoProveedor.png") ?>" alt="Logo Proveedor" class="img-fluid mb-2 w-75">

		<h2 class="text-mobile"><b> <?php echo "Linea Gratuita"  ?>  <?php echo Configure::read("COMPANY.CALL_FREE_NUMBER") ?></b></h2>
	</div>
	<div class="col-md-6">
		<h3 class="strongtittle spacetop text-mobile"><?php echo Configure::read("COMPANY.NAME") ?></h3>
		<h3 class="strongtittle text-mobile"><?php echo Configure::read("COMPANY.NIT") ?></h3>
		<h3 class="text-mobile"><b><?php echo Configure::read("COMPANY.ADDRESS") ?></b></h3>
		<h3 class="text-mobile"><b><?php echo Configure::read("COMPANY.TELCOMPANY") ?></b></h3>
	</div>
	<div class="col-md-12 mt-4 table-responsive">
		<table class="table table-bordered" id="orderproveedor">
			<tbody>
				<?php $noIva = 0; $Iva = 0; ?>
				<tr class="text-center">
					<th>Fecha factura: </th>
					<td><?php echo $factValue["datos_factura"]->Fecha ?></td>
				</tr>
				<tr class="text-center">
					<th>Código factura: </th>
					<td><?php echo $factValue["datos_factura"]->prefijo ?> <?php echo $factValue["datos_factura"]->Id ?></td>
				</tr>
				<?php if (isset($factValue["datos_factura"]->Identificacion)): ?>
					<tr class="text-center">
						<th>Cliente: </th>
						<td>
							<b><?php echo $factValue["datos_factura"]->Identificacion ?>  </b> - <?php echo $factValue["datos_factura"]->Cliente ?>
						</td>
					</tr>
					<tr class="text-center">
						<th>Vendedor: </th>
						<td>
							<b><?php echo $factValue["datos_factura"]->IdVendedor ?> </b> - <?php echo $factValue["datos_factura"]->NombreVendedor ?> 
						</td>
					</tr>
				<?php endif ?>
				<?php if (isset($factValue["valores_factura"])): ?>
					
					<?php foreach ($factValue["valores_factura"] as $key => $value): ?>
						<?php if (!is_null($value->IdClasificación)): ?>
							<?php $noIva = $value->Crédito; ?>
						<?php else: ?>
							<?php $Iva = $value->Débito; ?>
						<?php endif ?>
					<?php endforeach ?>
				<?php endif ?>
			
				<?php if (isset($valores)): ?>
					<tr class="text-center">
						<th colspan="2">
							<h2 class="text-center my-2"> Ganancias y costos </h2>
							
						</th>
					</tr>
					<tr class="text-center">
						<th>Valor factura: </th>
						<td>$<?php echo number_format($noIva) ?></td>
					</tr>
					<tr class="text-center">
						<th>
							Costo factura: 
						</th>
						<td>
							$<?php echo number_format($valores["costo_factura"]) ?>
						</td>
					</tr>
					<tr class="text-center">
						<th>
							Utilidad en pesos
						</th>
						<td>
							$<?php echo number_format($valores["utilidad_factura"]) ?>
						</td>
					</tr>
					<tr class="text-center">
						<th>
							Utilidad neta %
						</th>
						<td>
							<?php echo round($valores["utilidad_porcentual"],2); ?> %
						</td>
					</tr>
				<?php endif ?>
			</tbody>
		</table>
		<h2 class="text-center my-2"> Productos </h2>
		<table class="table table-bordered" id="orderproveedor">
			<thead>
				<tr>
					<th class="text-center">Referencia</th>
					<th>Producto</th>
					<th class="text-center">Cantidad</th>
					<th class="text-center">Bodega</th>
					<th class="text-center">IVA</th>
					<th class="text-center">Precio Unit</th>
					<th class="text-center">Valor Total</th>
				</tr>
			</thead>
			<tbody>
				<?php $total = 0; ?>
				<?php foreach ($factValue["productos_factura"] as $key => $value): ?>
					<tr>
						<td class="text-center"><?php echo $value->CódigoInventario ?></td>
						<td><?php echo $value->Descripción ?></td>
						<td class="text-center"><?php echo intVal($value->Cantidad) ?>						</td>
						<td class="text-center"><?php echo $value->Bodega ?></td>
						<td class="text-center"><?php echo $value->Iva*100 ?>%</td>
						<td class="text-right">$<?php $total+=$value->Precio; echo number_format(round($value->Precio,2),2,",",".") ?></td>						
						<td class="text-right">$<?php $total+=$value->Precio; echo number_format(round($value->Precio*intVal($value->Cantidad),2),2,",",".") ?></td>						
					</tr>
				<?php endforeach ?>
				<tr>
					<th colspan="6" class="text-right">Subtotal:</th>
					<th class="text-right"><?php echo number_format(round($noIva,2),2,",",".") ?></th>
				</tr>
				<tr>
					<th colspan="6" class="text-right">IVA:</th>
					<th class="text-right"><?php echo number_format( ($Iva - $noIva) ,2,",",".") ?></th>
				</tr>
				<tr>
					<th colspan="6" class="text-right">TOTAL:</th>
					<th class="text-right"><?php echo number_format( ($Iva) ,2,",",".") ?></th>
				</tr>
			</tbody>
		</table>
	</div>
</div>