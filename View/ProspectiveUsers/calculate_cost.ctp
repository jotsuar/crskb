<?php 
	
	$costoProducto 		= $this->request->data["costo"];
	$datosProductoVenta =  $this->Utilities->getCostProductForImport($this->request->data["flujo"], $this->request->data["product"]); 
	$currency 			= $this->request->data["currency"];
	$product_id 		= $this->request->data["product"];

	$margenFinal = $this->Utilities->calculateMargen($trmActual,$this->Utilities->getProductFactor($product_id),$costoProducto,$datosProductoVenta,$this->request->data["cantidadProducto"], $currency); 

 ?>

 	<div id="<?php echo $this->request->data["uuid"] ?>" style="display: none;">
 		<?php if ($margenFinal < 30): ?>
			<a class="pointer bg-danger p-1 text-white aa">
				<?php echo $margenFinal ?>% <i class="fa fa-eye vtc"></i>
			</a>
		<?php else: ?>
			<a class="pointer bg-success p-1 text-white">
				<?php echo $margenFinal ?>% <i class="fa fa-eye vtc"></i>
			</a>
		<?php endif ?>
 	</div>

	<?php 
	 	echo $this->element("trm_import", compact("costoProducto","datosProductoVenta","currency","product_id","margenFinal")); 
	 ?>
<!-- Versión vieja -->
<?php 
// $costoProducto = $this->request->data["costo"];
// $datosProductoVenta =  $this->Utilities->getCostProductForImport($this->request->data["flujo"], $this->request->data["product"]); 
// $currency = $currency;
// $product_id = $this->request->data["product"];
?>

<?php //echo $this->element("calcular_trm", compact("costoProducto","datosProductoVenta","currency","product_id")); ?>