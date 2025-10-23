<h2>
	Datos cotizaciones y ventas de la referencia <?php echo $datosQuotation2023["Product"]["part_number"] ?>
</h2>

<div class="row">
	<div class="col-md-12">
		<?php echo $datosQuotation2023['Product']['name'] ?>
	</div>
	<div class="col-md-2">
		
		<?php $ruta = $this->Utilities->validate_image_products($datosQuotation2023['Product']['img']); ?>
			<img class="img-fluid productoImagenData" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>">
	</div>
	<div class="col-md-5 pt-3">
		<b>Precio Promedio Cotizado 2023: </b> $<?php echo number_format($promedioCotizaciones2023) ?> <br>
		<b>Cantidad Cotizada 2023: </b> <?php echo number_format($datosQuotation2023["0"]["total"]) ?> <br>



		<b>Precio Promedio vendido 2023: </b> $<?php echo number_format($promedio2023) ?> <br>
		<b>Cantidad vendida 2023: </b> <?php echo number_format($conteo) ?> <br>
		<hr>
		<p class="text-info"><b>Efectividad: </b> <?php echo round($conteo>0 && $conteo/$datosQuotation2023["0"]["total"] ? $conteo/$datosQuotation2023["0"]["total"] : 0,2)*100 ?> % </p>
	</div>
	<div class="col-md-5 pt-3">
		<b>Precio Promedio Cotizado 2024: </b> $<?php echo number_format($promedioCotizaciones2024) ?> <br>
		<b>Cantidad Cotizada 2024: </b> <?php echo number_format($datosQuotation2024["0"]["total"]) ?> <br>



		<b>Precio Promedio vendido 2024: </b> $<?php echo number_format($promedio2024) ?> <br>
		<b>Cantidad vendida 2024: </b> <?php echo number_format($conteo_2024) ?> <br>
		<hr>
		<p class="text-info"><b>Efectividad: </b> <?php echo round($conteo_2024 > 0 && $datosQuotation2024["0"]["total"] ? $conteo_2024/$datosQuotation2024["0"]["total"] : 0,2)*100 ?> % </p>
	</div>
</div>