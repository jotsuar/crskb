<?php 
	if(is_null($datosProductoVenta)){
		$precioVenta = $currency == "cop" ? $costoProducto / 0.65 : (($costoProducto*$trmActual) * 1.1) / 0.65; 
		$cantidadVenta = $cantidadProducto; 
	}else{
		$cantidadVenta = $datosProductoVenta["quantity"]; 
		$precioVenta = $datosProductoVenta["price"];
	}

	if(isset($datosProductoVenta["header"]) && $datosProductoVenta["header"] == 3){
		$unitarioPesos = $costoProducto;
	}else{
		$unitarioPesos = $currency == "cop" ? $costoProducto :  $costoProducto * $trmActual;
	}

 ?>
<div class="stylegeneralbox ">
<div class="col-md-12">
<div class="row">
	<?php if (is_null($datosProductoVenta)): ?>
		<div class="col-md-12 m-2">
			<p class="text-danger">
				<b>Nota: </b> Este producto no se ha vendido, se calcula en base al costo del producto <?php echo $currency != "cop" ? "en USD (Costo * TRM ) * 1.1" : "" ?> / 0.65.
			</p>
		</div>
	<?php endif ?>
	<div class="col-md-3 col-xs-6 mt-1">
		<div>	
			<h2><?php echo number_format($precioVenta, 2, ",",".") ?></h2>
			<p>Valor en que se vendío el producto</p> 
		</div>
	</div>
	<div class="col-md-3 col-xs-6 mt-1 ">
		<div>
			<h2><?php echo $cantidadVenta ?></h2> 
			<p>Cantidad vendida del producto</p> 
		</div>
	</div>
	<div class="col-md-3 col-xs-6 mt-1 ">
		<div>
			<h2><?php $valorFinal = $precioVenta*$cantidadVenta;
			 echo number_format($valorFinal, 2, ",","."); ?></h2>
			<p>Valor final del producto</p>
		</div>
	</div> 
	<input type="hidden" class="valorFinal" value="<?php echo $precioVenta*$cantidadVenta ?>">

	<div class="col-md-3 col-xs-6 mt-1 ">
		<div>
			<h2><?php  echo number_format($unitarioPesos,"2",".",".") ?></h2>
			<p>Costo del producto unitario producto en pesos</p>
		</div>
	</div>
	<div class="col-md-3 col-xs-6 mt-1 ">
		<div>
			<h2><?php $totalCostoPesos = $unitarioPesos * $cantidadVenta; echo number_format($totalCostoPesos,"2",".",".") ?></h2>
			<p>Costo total de <?php echo $cantidadVenta ?> producto(s) en pesos </p>
		</div>
	</div>
	<div class="col-md-3 col-xs-6 mt-1 ">
		<div>
			<?php 
				if(isset($datosProductoVenta["header"]) && $datosProductoVenta["header"] == 3){
					$finalImportacion = $totalCostoPesos;
				}else{
					$finalImportacion = $currency == "cop" ? $totalCostoPesos : $totalCostoPesos * $this->Utilities->getProductFactor($product_id); 
				}
			?>
			<h2> <?php  echo number_format($finalImportacion,"2",".",".") ?></h2>
		<p>
			<?php if ($currency == "cop"): ?>
				Costo final en pesos:  
			 <?php else: ?>
				Costo final con factor de importación (<?php echo $this->Utilities->getProductFactor($product_id) ?>) en pesos:  
			 <?php endif ?> 
		</p>
		</div>
	</div>
	<div class="col-md-6 ">
		<div>
			<h2><?php $margenFinal = $valorFinal == 0 ? 0 : ( ($valorFinal-$finalImportacion) /$valorFinal) * 100 ; ?> 
			<span class="font-weight-bold <?php echo $margenFinal < 30 ? "letra-rojo" : "letra-verde" ?>">
				<?php  echo number_format($margenFinal,"2",".",",") ?> %
			</span>	</h2>
		<p>Margen final luego de calcular costos: </p>
		</div>
	</div>
</div>
</div>
</div>
