<?php $costoUsd = $cost; ?>

<?php 


	if ($header == 3) {
		$factorImport = 1;
	}elseif($currency == "usd"){
		$factorImport = $factorImport;
	}else{
		$factorImport = 1; 
	}
?>

<?php $productInfo = $this->Utilities->data_product($productoId); $boquilla = $this->Utilities->validarBoquillas($productInfo["Product"]);?>
<?php 

	$referencesAbrasivo = ['M-8','M-25','M-60'];
	$referencesAbrasivo = [];

	$isRef = false;
	if(in_array($productInfo["Product"]["part_number"], $referencesAbrasivo)){
		$isRef = true;
	}

	$blockMargen = true;

	$refMagen = ['24Y680','17D899','17E852','19D519','19D521','20B305','19F545','19F550'];

	if(in_array($productInfo["Product"]["part_number"],$refMagen)){
		$blockMargen = false;
	}


	$months = $this->Utilities->calculateMonths($productInfo["Product"]["last_change"],date("Y-m-d H:i:s"));

	$bgColor = "";
	$tooltip = "";
	$claseCosto = "";
	$gracoClass = "";

	if ($months >= 12) {
		$bgColor = "btn btn-sm btn-outline-danger";
		$tooltip = "Hace 12 meses o más no se actualiza el costo";
		$claseCosto = "spinner-grow";
		if( in_array( $productInfo["Product"]["brand_id"],[4,7,70,132,37])  ){
			$gracoClass = "graco1-class";
		}
	}elseif($months >= 6 && $months < 12){
		$tooltip = "Hace 6 meses o más no se actualiza el costo";
		$bgColor = "btn btn-sm btn-outline-warning";
		$claseCosto = "spinner-grow";
		if( in_array( $productInfo["Product"]["brand_id"],[4,7,70,132,37])  ){
			// $gracoClass = "graco-class";
		}
		if($productInfo["Product"]["brand_id"] == 37 || $productInfo["Product"]["brand_id"] == 70){
			$gracoClass = "graco1-class";
		}
	}elseif($months >= 0 && $months < 6 ){
		$tooltip = "Hace menos de 6 meses se actualizó el costo";
		$bgColor = "btn btn-sm btn-outline-success";	
		// if($productInfo["Product"]["brand_id"] == 4){
		// 	$gracoClass = "graco-class";
		// }	
		// $gracoClass = "graco-class";
	}

	if($isRef){

		// if($cantidad > 56){
		// 	$cantidad = 56;
		// }

		// $cost = $precio = 21865 * $cantidad + (25 * $cantidad * 21) + ( 25 * $cantidad  * 33 );
		// $cost += 31983;
		// $cost /= $cantidad;
		$costoUsd = $cost;
	}

?>
<?php $costo = $currency == "cop" ? $cost : $cost;  ?>
<?php
	
	$costoColombia = $currency == "cop" || ($currency == "usd" && $productInfo["Product"]["currency"] == 2 && $productInfo["Product"]["type"] == 0) ? $costo :  $costo*$factorImport; 


?>
<?php 
	$valorProducto = $valorCotizacion;
	$valorNormal   = $valorProducto;

	$margenFinal   = $valorProducto == 0 || is_null($valorProducto) ? 0 : ( ($valorProducto-$costoColombia) /$valorProducto) * 100 ; 
	$margenNormal  = $margenFinal;

	if ($discount > 0) {
		$valorProducto = $valorProducto - ($valorProducto * ($discount / 100) );
		$margenFinal   = $valorProducto == 0 || is_null($valorProducto) ? 0 : ( ($valorProducto-$costoColombia) /$valorProducto) * 100 ; 
	}

?>
<?php $pos = $this->Utilities->getNameProduct($productoId);?>
<?php 
if(AuthComponent::user("email") == "ventas@kebco.co" || $header == 3 ) { 
	$min = 15; 
}else { 
	if (AuthComponent::user("role") == "Asesor Externo") {
		$min = AuthComponent::user("margen");
	}else{
		if ($typeCost == "WO") {
			$min = isset($productInfo["Category"]["margen_wo"]) ? $productInfo["Category"]["margen_wo"] : 30;
		}else{
			$min = isset($productInfo["Category"]["margen"]) ? $productInfo["Category"]["margen"] : 35;
		}		
	}
} ?>
<td colspan="13" class="nohover">
	<div class="col-md-12 show_data">
	<div class="row">
		<?php 

			$clase 		 = "fondo-verde";
			$claseNormal = "text-success";

			if (floatval($margenFinal+0.5) < $min && $currency == "usd" && $blockMargen) {
				$clase = "AAfondo-rojoAA";
			}elseif (floatval($margenFinal+0.5) < $min && $currency == "cop" && $blockMargen) {
				$clase = "error_wo text-danger";
				$clase = "AAfondo-rojoAA";
			}

			if ($discount > 0) {
				if (floatval($margenNormal+0.5) < $min && $currency == "usd" && $blockMargen) {
					$claseNormal = "text-danger";
				}elseif (floatval($margenNormal+0.5) < $min && $currency == "cop" && $blockMargen) {
					$claseNormal = "text-danger";
					$claseNormal = "text-danger";
				}
			}

			$costoCero = "";

			if(strtolower($categoryName) == "servicio" && $costo <= 0){
				$costoCero = "";
			}elseif ($costo <= 0 && $pos !== false) {
				$costoCero = "";
			}elseif ($costo <= 0 && $type == 0) {
				$costoCero = "costo-cero";
			}elseif ($costo <= 0) {
				$costoCero = "costo-cero";
			}

		 ?>
		<div class="col-md-12 text-center margendatablock <?php echo $clase ?> <?php echo $costoCero ?>" data-product="<?php echo $productoId ?>" data-margen="<?php echo $clase == "fondo-rojo" ? 0 : 1 ?>" data-currency="<?php echo $currency ?>"> 
			<?php if ($discount > 0): ?>
				<h2 class="font-weight-bold margenes <?php echo $claseNormal ?>">
					<!-- <b class="<?php echo $claseNormal ?>">Margen sin descuento: </b> -->
					<!-- <span class="<?php echo $claseNormal ?>"><?php  echo number_format($margenNormal,"2",".",",") ?> %</span> -->
				</h2>
			<?php endif ?>
			<h2 class="font-weight-bold margenes" id="Margen-<?php echo $idTr ?>" data-margen="<?php echo round($margenFinal,2) ?>">
				<!-- <b>Margen final del producto: </b> -->
				<!-- <?php  echo number_format($margenFinal,"2",".",",") ?> % -->
			</h2>
		</div>		
		<div class="col-md-12 text-center fila1block">
			<span><b>Categoría: </b> <?php echo $categoryName ?> </span>
			<!-- <span><b>Margen mín: </b> <?php echo $min ?>% </span> -->
			<!-- <span><b>Factor: </b> <?php echo $factorImport ?>% </span> -->
			<span>
				<b>TRM actual: </b> $<?php echo $trmActual ?> 
				<a href="#" class="text-secondary ml-2 cambioTRM" data-toggle="tooltip" title="" data-original-title="Solicitar cambio de TRM">
					<i class="fa fa-edit vtc"></i>
				</a> 
			</span>	
			<span>
				<span class="<?php echo $bgColor ?> <?php echo $gracoClass ?>" data-toggle="tooltip" title="<?php echo $tooltip ?>" style="padding-left: 4px !important;    padding-right: 4px !important; ">
					
					<b>Costo <span class="<?php echo $typeCost ?> <?php echo $claseCosto ?> p-1" style="<?php echo $claseCosto != '' ? 'border-radius: 0px;    width: auto;    height: auto;    animation: spinner-grow 1.5s ease-in-out infinite; line-height: 18px !important;' : '' ?>"> <?php echo $typeCost == "REPOSICION" ? "USD" : $typeCost ?></span> </b>

					&nbsp;
				<!-- 	$<?php echo number_format($costoUsd,2,",",".") ?> <i class="fa fa-info vtc"></i> 

					<?php if ($typeCost == "WO" && isset($realCost) && $realCost > 0 && $costo > $realCost && $productInfo["Category"]["show_cost"] == 1 ): ?>
						<br>
						<hr>
						<b>Costo real WO:</b> <?php echo number_format($realCost) ?>
					<?php endif ?> -->
				</span>
				<!-- <a href="#" class="text-primary cambioCosto" data-id="<?php echo $productoId ?>" data-toggle="tooltip" title="" data-original-title="Solicitar cambio de costo" data-field="<?php echo $currency == "usd" ? "purchase_price_usd" : $type == 0 ? "purchase_price_cop" : "purchase_price_wo" ?>">
					<i class="fa fa-edit vtc"></i>
				</a>  -->
			</span>
			<?php if ($currency == "cop"): ?>					
				<!-- <span><b>Costo <?php echo $currency == "usd" ? "USD" : "COP" ?> </b> <?php echo number_format($costo,2, ",",".") ?> </span> -->
			<?php endif ?>
			<!-- <span><b>Costo final <?php echo $currency == "usd" ? "(COSTO*FACTOR)" : "COP" ?>: </b> <?php echo number_format($costoColombia,2, ",",".") ?> </span> -->
			$<?php $valorProducto = $valorProducto == 0 || is_null($valorProducto) ? 0 : $valorProducto; ?>
			<?php if ($discount == 0): ?>
			<span><b>Valor cotizado: </b> <?php echo number_format($valorProducto ,2, ",",".") ?></span>
			<?php else: ?>
			<span>
				<strike><b>Valor cotizado: </b> <?php echo number_format($valorProducto ,2, ",",".") ?></strike> <br>
				<b>Valor final con descuento: </b> <?php echo number_format($valorProducto ,2, ",",".") ?>
			</span>

			<?php endif ?>
		</div>

	</div>
	</div>
</td>

<style>
	.REPOSICION{
		background: #85BB65;
		color: #fff;
	}
	.WO{
		background: #1f88a9;
		color: #fff;
	}

	.CRM{
		background: #004990;
		color: #fff;
	}
</style>