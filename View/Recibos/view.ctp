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
				<tr class="text-center">
					<th>Fecha recibo: </th>
					<td><?php echo date("Y-m-d",strtotime( $recibo['Recibo']['fecha_recibo'])) ?></td>
				</tr>
				<tr class="text-center">
					<th>Código Recibo: </th>
					<td><?php echo $recibo['Recibo']['numero']  ?></td>
				</tr>
				<?php if (isset($nit)): ?>
					<tr class="text-center">
						<th>Cliente: </th>
						<td>
							<b><?php echo $nit ?>  </b> - <?php echo $empresa ?>
						</td>
					</tr>
					<tr class="text-center">
						<th>Vendedor: </th>
						<td>
							<b><?php echo $empleado ?> </b>
						</td>
					</tr>
				<?php endif ?>
			</tbody>
		</table>
		<h2 class="text-center my-2"> Detalles </h2>
		<table class="table table-bordered" id="orderproveedor">
			<thead>
				<tr>
					<th class="text-center">Cuenta</th>
					<th>Concepto</th>
					<th class="text-center">Débito</th>
					<th class="text-center">Crédito</th>
					<th class="text-center">Base retención</th>
					<th class="text-center">% Ret</th>
					<th class="text-center">Valor Ret</th>
				</tr>
			</thead>
			<tbody>
				<?php $total = 0; ?>
				<?php foreach ($details["details"] as $key => $value): ?>
					<tr>
						<td class="text-center"><?php echo $value["Nombre_Cuenta"]?></td>
						<td><?php echo $value["Concepto_Detalle"] ?></td>
						<td class="text-center"><?php echo empty($value["Débito"]) ?  '' : number_format($value["Débito"]) ?>						</td>
						<td class="text-center"><?php echo empty($value["Crédito"]) ?  '' : number_format($value["Crédito"]) ?></td>
						<td class="text-center"><?php echo empty($value["Base_Retencion"]) ?  'N/A' : number_format($value["Base_Retencion"]) ?></td>
						<td class="text-right"><?php echo empty($value["Porcentaje_retención"]) ?  'N/A' : number_format($value["Porcentaje_retención"]) ?></td>						
						<td class="text-right"><?php echo empty($value["Valor_retenido"]) ?  'N/A' : number_format($value["Valor_retenido"]) ?></td>						
					</tr>
				<?php endforeach ?>
			</tbody>
			<tfooter>
				<tr>
					<th colspan="2" class="text-right">Totales</th>
					<th><?php echo number_format($recibo['Recibo']['debito']); ?></th>
					<th><?php echo number_format($recibo['Recibo']['credito']); ?></th>
				</tr>
			</tfooter>
		</table>
	</div>
</div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>
