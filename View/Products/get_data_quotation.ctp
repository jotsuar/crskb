<?php $idTr = time(); ?>
<?php 

	if(!isset($datosProducto["Product"]["id"])){
		return "";
	}

	if(!is_null($datosProducto["Product"]["margen_wo"]) && $datosProducto["Product"]["margen_wo"] > 0 ){
		$datosProducto["Category"]["margen_wo"] = $datosProducto["Product"]["margen_wo"];
	}

	if(!is_null($datosProducto["Product"]["margen_usd"]) && $datosProducto["Product"]["margen_usd"] > 0 ){
		$datosProducto["Category"]["margen"] = $datosProducto["Product"]["margen_usd"];
	}

	if(!is_null($datosProducto["Product"]["factor"]) && $datosProducto["Product"]["factor"] > 0 ){
		$datosProducto["Category"]["factor"] = $datosProducto["Product"]["factor"];
	}

	$abrasivoInfo = $this->Utilities->get_data_abrasivo($datosProducto["Product"]["part_number"]);
	$isRef 		  = $abrasivoInfo["isRef"];

	$copiaEntrega = $entrega;

	$inter 		  = false;

	$rolesPermitidos = array(
    	"Gerente General", "Logística","Asesor Comercial"
    );

	$producto = $this->Utilities->getQuantityBlock($datosProducto["Product"]);
	if (!isset($noChange)) {
		$producto["quantity_back"]   = 0;
		$producto["totalDisponible"] = ($totalGeneral+$producto["quantity_back"]) < 0 ? 0 : $totalGeneral+$producto["quantity_back"] ;
	}
	

	if(!empty($header) && $header == "3"){
		$producto["totalDisponible"] = 0;
		$inter = true;
	}elseif($cantidadBloqueo){
		$producto["totalDisponible"] = 0;
	}elseif ($cantidadCotiza !== false) {
		$producto["totalDisponible"] = $cantidadCotiza;
	}

	if($producto["totalDisponible"] == 0 && !isset($noChange)){
		unset($entrega["Inmediato"]);
		unset($entrega["1-2 días hábiles"]);
	}

	if($datosProducto["Product"]["type"] == 0){
		$datosProducto["Product"]["delivery_min"] = '2-3 días hábiles';
	}else{
		$datosProducto["Product"]["delivery_min"] = '20-30 días hábiles';		
	}


	if($producto["totalDisponible"] ==  0 && $datosProducto["Product"]["delivery_min"] != null ){

		$copyEntrega = array_values($entrega);
		$entrega = [];
		$entregaDefault = $datosProducto["Product"]["delivery_min"];

		$keyVal = array_search($datosProducto["Product"]["delivery_min"], $copyEntrega);

		foreach ($copyEntrega as $key => $value) {
			$entrega[] = [
				"name" => $value,
				"value" => $value,
				// "disabled" => $key < $keyVal,
				// "data-dis" => $key < $keyVal ? 1:0,
			];
		}

	}else{
		$entregaDefault = array_values($entrega)[0];
	}

	$pos = $this->Utilities->getNameProduct($datosProducto["Product"]["id"]);
	
	if(isset($this->request->data["money"]) && $this->request->data["money"] != "0" ){
		$currency = $this->request->data["money"] == "USD" ? "usd" : "cop";
		if($this->request->data["money"] == "COP"){
			// $datosProducto["Product"]["type"] = 0;
		}
	}elseif(!empty($header) && $header == 3){
		$currency = "usd";
	}else{
		if( ($datosProducto["Product"]["type"] == 0 && $datosProducto["Product"]["currency"] == 2) || ( $pos !== false && (!empty($header) && $header != "3") ) ){
			$currency = "usd";
		}elseif($datosProducto["Product"]["type"] == 0 || ( $pos !== false && (!empty($header) && $header != "3") ) ){
			$currency = "cop";
		}elseif(strtolower($datosProducto["Category"]["name"]) == "servicio"){
			$currency = "cop";
			$entrega = $copiaEntrega;
		}else{
			$currency =  $producto["totalDisponible"] <= 0 && $datosProducto["Product"]["type"] == 1  ? "usd" : "cop";
		}
	}
 ?>
<tr 
	id="<?php echo "tr_".$idTr."-".$datosProducto["Product"]["id"] ?>" 
	data-producto="<?php echo $datosProducto["Product"]["id"] ?>" 
	data-id="<?php echo $idTr ?>" 
	class="listado_tabla_ordenada productoAgregado_<?php echo $idTr ?> listadoProductos" 
	data-clase="productoAgregado_<?php echo $idTr ?>" 
	data-disponible="<?php echo $producto["totalDisponible"] ?>" 
	data-currency="<?php echo $currency ?>" 
	data-reference="<?php echo $datosProducto["Product"]["part_number"] ?>"
>
	<td>
		<?php $ruta = $this->Utilities->validate_image_products($datosProducto['Product']['img']); ?>
		<img class="minprods" minprod="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="65px" >
	</td>
	<td class="nameget <?php echo !is_null($datosProducto["Product"]["notes"]) ? "nota" : "" ?>">
		<?php if (!is_null($datosProducto["Product"]["notes"])): ?>
            <div class="notaproduct">
            <span class="triangle"></span>
            <span class="flechagiro">|</span>
            <div class="text_nota">
                <small class="creadornota"><b></b></small>
                <?php echo $datosProducto["Product"]["notes"] ?>
                <small class="datenota"></small>
            </div>
            </div>
            <?php echo $datosProducto['Product']['name'] ?>
        <?php else: ?>
            <?php echo $datosProducto['Product']['name'] ?>
        <?php endif ?>
			
	</td>
	<td class="descriptionget"><?php echo $this->Text->truncate(strip_tags($datosProducto['Product']['description']), 120,array('ellipsis' => '...sigue','exact' => false)); ?></td>

	<td class="enlaceget center">
		<?php 
			$optionsCurrency = array('label' => false,'class' => 'Moneda-'.$idTr."-".$datosProducto["Product"]["id"], "value" => strtoupper($currency), "readonly","style" => $type == 0 ? 'width: 80px;' : 'width: 65px;' );

			if($type == 0){
				$optionsCurrency["options"] = ["USD" => "USD","COP" => "COP" ];
			}
		?>
		<?php echo $this->Form->input('Moneda-'.$idTr."-".$datosProducto["Product"]["id"],$optionsCurrency);?>
	</td>
	<td class="enlaceget center">
		<?php if ((!empty($header) && $header == "3")): ?>
			<?php echo $this->Form->input('IVA-'.$idTr."-".$datosProducto["Product"]["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datosProducto["Product"]["id"], "value" => 0, "options" => ["0" =>"NO"] ,));?>
		<?php else: ?>
			<?php echo $this->Form->input('IVA-'.$idTr."-".$datosProducto["Product"]["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datosProducto["Product"]["id"], "default" => 1, "options" => ["1" => "SI","0" =>"NO"] ));?>
		<?php endif ?>
	</td>
	<td class="tds-references" data-qty='Cantidad-<?php echo $idTr."-".$datosProducto["Product"]["id"] ?>' data-ref="<?php echo $datosProducto['Product']['part_number'] ?>" ><?php echo $datosProducto['Product']['part_number'] ?></td>
	<td>
		<?php echo $this->Form->input('Entrega-'.$idTr."-".$datosProducto["Product"]["id"],array('label' => false, 'options' => $entrega,'class' => 'Entrega-'.$idTr."-".$datosProducto["Product"]["id"], "value" => $entregaDefault ,'names' => 'Entrega-'.$datosProducto['Product']['id']));?>
	</td>
	<td><?php echo $datosProducto['Product']['brand'] ?></td>
	<td class="size4">
		<?php echo $this->element("products_block",["producto" => $datosProducto["Product"],"inventario_wo" => $inventioWo[$datosProducto['Product']['part_number']], "reserva" => isset($inventioWo["Reserva"][$datosProducto["Product"]["part_number"]]) ? $inventioWo["Reserva"][$datosProducto["Product"]["part_number"]] : null]) ?>
	</td>
	<td class="precio">
		<?php 

			$precio = 0;
			$precioVenta = 0;
			if ( (isset($totalGeneral) && $totalGeneral > 0 && $datosProducto["Product"]["type"] == 0 && $currency == "cop")  && ( isset($this->request->data["money"]) && $this->request->data["money"] != "0" ) ) {
				$datosProducto["Product"]["type"] = 1;
			}

			$typeCost = "CRM";

			$realCost = 0;

			if ($currency == "cop" && $datosProducto["Product"]["type"] == 0 ) {
				$precio = isset($costos[$datosProducto["Product"]["part_number"]]) ? $costos[$datosProducto["Product"]["part_number"]]*1 : $datosProducto["Product"]["purchase_price_cop"] + $datosProducto["Product"]["aditional_cop"];
				$precioVenta = $precio / ( 1 - ($datosProducto["Category"]["margen_wo"]/100) );
				$typeCost = "CRM";
			}elseif ($currency == "cop" && $datosProducto["Product"]["type"] == 1 ) {

				// $precio = isset($inventioWo[$datosProducto['Product']['part_number']][0]["costo"] ) ? $inventioWo[$datosProducto['Product']['part_number']][0]["costo"] : $datosProducto["Product"]["purchase_price_wo"];
				// $precioVenta = $precio / ( 1 - ($datosProducto["Category"]["margen_wo"]/100) );
				// 
				$costo 	  = $datosProducto["Product"]["purchase_price_wo"];
				$costoRep = round($datosProducto["Category"]["factor"] * ($datosProducto["Product"]["purchase_price_usd"] * ($trmActual*1.05) ),2);

				$realCost = isset($costos[$datosProducto["Product"]["part_number"]]) ? $costos[$datosProducto["Product"]["part_number"]] :  0;

				if (isset($costos[$datosProducto["Product"]["part_number"]])
					// && ($costos[$datosProducto["Product"]["part_number"]] * 1) >=  $costoRep 
				) {

					if($producto["totalDisponible"] <= 0){
						$precio 	 = $costoRep;
						$typeCost 	 = "REPOSICION";
						$precioVenta = round($precio / ( 1 - (($datosProducto["Category"]["margen"])/100) ));
					}else{
						$precio = $costos[$datosProducto["Product"]["part_number"]]*1;
						// $precio 	 = 1.1 * $datosProducto["Product"]["purchase_price_usd"] * $trmActual;
						$precioVenta = round($precio / ( 1 - (($datosProducto["Category"]["margen_wo"])/100) ));
						$typeCost 	 = "WO";
					}

				}else{
					$precio = $costoRep;
					$typeCost 	 = "REPOSICION";
					$precioVenta = round($precio / ( 1 - (($datosProducto["Category"]["margen"])/100) ));
				}

			}elseif($currency == "usd"){
				$precio =  $datosProducto["Product"]["purchase_price_usd"] + $datosProducto["Product"]["aditional_usd"];
				if($inter){
					$precioVenta = $precio / 0.8;
				}else{
					$precioVenta = $precio / ( 1 - (($datosProducto["Category"]["margen"])/100) );
					$precioVenta = $precioVenta * $datosProducto["Category"]["factor"];
				}
				$typeCost = "REPOSICION";
			}

			$precioVenta = round($precioVenta,2);
			$priceInput = $precioVenta > 0 ? floatval($precioVenta) : (float)$datosProducto['Product']['list_price_usd'];

			if (AuthComponent::user("role") == "Asesor Externo") {
				$datosProducto["Category"]["margen"] 	= AuthComponent::user("margen");
				$datosProducto["Category"]["margen_wo"] = AuthComponent::user("margen");
			}

			if($isRef){
				$precioVenta = $abrasivoInfo["unitPrice"];
				$precio = $abrasivoInfo["precio"];

			}

			if($datosProducto["Product"]["fixed_price"] > 0 && $typeCost == 'WO'){
				$precioVenta = $datosProducto["Product"]["fixed_price"] ;
			}elseif($datosProducto["Product"]["fixed_price"] > 0 && $typeCost == 'CRM'){
				$precioVenta = $datosProducto["Product"]["fixed_price"] ;
			}elseif($datosProducto["Product"]["fixed_cop"] > 0 && $typeCost == 'CRM'){
				$precioVenta = $datosProducto["Product"]["fixed_cop"] ;
			}elseif($datosProducto["Product"]["fixed_usd"] > 0 && $typeCost == 'REPOSICION' && $currency == 'usd'){
				$precioVenta = $datosProducto["Product"]["fixed_usd"] ;
			}elseif($datosProducto["Product"]["fixed_cop"] > 0 && $typeCost == 'REPOSICION' && $currency == 'cop'){
				$precioVenta = $datosProducto["Product"]["fixed_cop"] ;
			}

			

		 ?>
		<?php echo $this->Form->input('Precio-'.$idTr."-".$datosProducto["Product"]["id"],array(
			'id' 				=> 'precio_item', 
			'data-id' 			=> $datosProducto['Product']['id'],
			'data-price' 		=> $precio, 
			'data-min' 			=> $currency == "usd" ? $datosProducto["Category"]["margen"]: $datosProducto["Category"]["margen_wo"],
			'data-category' 	=> $datosProducto["Product"]["category_id"],
			'data-categoryName' => $datosProducto["Category"]["name"],
			'data-type' 		=> $datosProducto["Product"]["type"],
			'class'				=> 'valoresCotizacionProductos Precio-'.$idTr."-".$datosProducto["Product"]["id"],
			'data-uid'			=> $idTr."-".$datosProducto["Product"]["id"],
			'value'				=> $precioVenta > 0 ? floatval($precioVenta) : (float)$datosProducto['Product']['list_price_usd'],
			'data-trdata'		=> "calculo_".$idTr,
			'data-clasetr'		=> "productoAgregado_".$idTr,
			'data-currency'		=> $currency,
			'data-typeCost'	    => $typeCost,
			'data-realCost'	    => $realCost,
			'data-factor'		=> $datosProducto["Category"]["factor"],
			'data-header'		=> empty($header) ? 1 : $header,
			'label' 			=> false, 
			'div' 				=> false, 
			'type' 				=> AuthComponent::user("role") == "Asesor Externo" ? "number" : "text", 
			'min' 				=> AuthComponent::user("role") == "Asesor Externo" ? $priceInput : false, 
			'names' 			=> 'Precio-'.$datosProducto['Product']['id'],
			'readonly' 			=> ( $datosProducto["Product"]["free_price"] == 1 || strtolower($datosProducto['Category']["name"]) == "servicio" ||  in_array($datosProducto["Product"]["part_number"], ['M-8','M-25','M-60']) || in_array(AuthComponent::user("role"), ["Gerente General","Logística"] )) ? false : "readonly" ,
			'data-ref' 			=> $isRef ? $abrasivoInfo["typeAbrasivo"] : 0,
			'data-fixed'		=> $datosProducto["Product"]["fixed_price"],
			'data-cantidad'     => 'Cantidad-'.$idTr."-".$datosProducto["Product"]["id"],
		));?>
		<?php echo $this->Form->input('Costo-'.$idTr."-".$datosProducto["Product"]["id"],array(
			'value'				=> $precio,
			'type'				=> 'hidden'
		));?>


	</td>
	<td class="cantidad">
		<?php 

			$max = false;
			if(isset($this->request->data["other"])){
				$max = $producto["totalDisponible"];
			}else{
				if($datosProducto["Product"]["type"] == 0 || $currency == "usd"){
					$max = false;
				}elseif($producto["totalDisponible"] > 0){
					$max = $producto["totalDisponible"];
				}
			}

			if($type == 0){
				$max = false;
			}

		 ?>
		<?php echo $this->Form->input('Cantidad-'.$idTr."-".$datosProducto["Product"]["id"],array(
			'type' 				=> "number", 
			'id' 				=> 'cantidadproduct', 
			'class'				=> 'cantidadProduct Cantidad-'.$idTr."-".$datosProducto["Product"]["id"],
			'data-uid'			=> $idTr."-".$datosProducto["Product"]["id"],
			'names' 			=> 'Cantidad-'.$idTr,
			'data-entrega'		=> 'Entrega-'.$idTr."-".$datosProducto["Product"]["id"],
			'data-modify'		=> $producto["totalDisponible"] ==  0 && $datosProducto["Product"]["quantity_min"] > -1 && $datosProducto["Product"]["delivery_min"] != null ? 1 : 0,
			'data-min'			=> $producto["totalDisponible"] ==  0 && $datosProducto["Product"]["quantity_min"] > -1 && $datosProducto["Product"]["delivery_min"] != null ? $datosProducto["Product"]["quantity_min"] : 0,
			'min'				=> $isRef ? 25 : 1,
			'value'				=> $isRef ? 25 : 1 ,
			'step'				=> $isRef ? 25 : 1 ,
			'data-ref'			=> $isRef ? 1 : 0,
			'data-reftype'		=> $isRef ? $abrasivoInfo["typeAbrasivo"] : 0,
			'data-price'		=> 'Precio-'.$idTr."-".$datosProducto["Product"]["id"],
			'max'				=> $max,
			'label' 			=> false, 
			'div' 				=> false,

		));?>
		<?php if ($producto["totalDisponible"] ==  0 && $datosProducto["Product"]["quantity_min"] > -1 && $datosProducto["Product"]["delivery_min"] != null): ?>
			<br><small class="text-danger">Min: <?php echo $datosProducto["Product"]["quantity_min"] ?></small>
		<?php endif ?>
		<?php echo $this->Form->input("Margen_".$datosProducto["Product"]["id"]."_".$currency,["type" => "hidden"]) ?>
		<input type="hidden" class="Nota-<?php echo $idTr ?>-<?php echo $datosProducto["Product"]["id"] ?> notasProductos" name="data[Nota-<?php echo $idTr ?>-<?php echo $datosProducto["Product"]["id"] ?>]" > 
		<?php echo $this->Form->input('Cotiza-'.$idTr."-".$datosProducto["Product"]["id"],array('label' => false,'class' => 'Cotiza-'.$idTr."-".$datosProducto["Product"]["id"], "value" => strtoupper($typeCost), "type"=>"hidden" ));?>
	</td>
	<td class="subtotal">
		<?php echo number_format((float)$precioVenta,2,",",".");?>
	</td>
	<td>
		<?php if (!isset($this->request->data["noValidate"])): ?>
			
		 	<a href="#" data-id="<?php echo $idTr ?>-<?php echo $datosProducto["Product"]["id"] ?>" class="btn btn-success mr-2 p-1 notaPrductCotizacion text-white ml-1" data-toggle="tooltip" title="" data-original-title="Agregar nota de al producto para esta cotización" style="padding-left: 4px !important;padding-right: 4px !important;">
                <i class="fa fa-plus-square text-white vtc"></i>
            </a>
            <?php if (!isset($this->request->data["other"])): ?>	
				<?php if ($editProducts): ?>
					<a  data-uid="<?php echo $datosProducto['Product']['id'] ?>" class="editarProduct">
						<i class="fa fa-fw fa-pencil" data-toggle="tooltip" data-placement="right" title="Editar producto"></i>
					</a>						
				<?php else: ?>
					<a  data-id="<?php echo $datosProducto['Product']['id'] ?>" class="requestEditProduct">
						<i class="fa fa-fw fa-pencil" data-toggle="tooltip" data-placement="right" title="Solicitar Editar producto"></i>
					</a>	
				<?php endif ?>		
			<?php endif ?>
			<?php if (in_array(AuthComponent::user("role"), $rolesPermitidos)): ?>
	            <a href="#" data-id="<?php echo $datosProducto["Product"]["id"] ?>" class="btn btn-incorrecto d-inline p-1 notesProduct text-white ml-4" data-toggle="tooltip" title="" data-original-title="Gestionar notas del producto">
	                <i class="fa fa-comments text-white vtc"></i>
	            </a>
	        <?php endif ?>
		<?php endif ?>
		<a data-uid="<?php echo $datosProducto['Product']['id'] ?>"  class="deleteProduct" data-clase="productoAgregado_<?php echo $idTr ?>">
			<i class="fa fa-remove" data-toggle="tooltip" data-placement="right" title="Eliminar producto"></i>
		</a>

		<?php if (!empty($productsSugestions["productsFinals"]) && !isset($this->request->data["noValidate"])): ?>
			<a data-uid="<?php echo $datosProducto['Product']['id'] ?>"  class="btn btn-warning viewSugesstions" data-clase="productoAgregado_<?php echo $idTr ?>">
				<i class="fa fa-info" data-toggle="tooltip" data-placement="right" title="Ver sugerencia de cotización"></i>
			</a>
		<?php endif ?>
	</td>
</tr>
<tr class="productoAgregado_<?php echo $idTr ?>">
	<td colspan="13">
		<input type="hidden" class="N">
		<div class="notaPCtz_<?php echo $idTr ?>-<?php echo $datosProducto["Product"]["id"] ?>"></div> 
	</td>
</tr>
<tr class="productoAgregado_<?php echo $idTr ?>">
	<td colspan="13">
		<div style="display:none" class="suggestions_<?php echo $datosProducto["Product"]["id"] ?>">
			<?php echo $this->element("suggestions",["productsSugestions" => $productsSugestions ]) ?>
		</div>
	</td>
</tr>
<?php if (!isset($this->request->data["other"])): ?>
	<?php $margen = $currency == "cop" ? $datosProducto["Category"]["margen_wo"] : $datosProducto["Category"]["margen"]; ?>
	<tr class="productoAgregado_<?php echo $idTr ?> productosCalculados" id="calculo_<?php echo $idTr ?>">
		<td colspan="12">
			<div class="col-md-12">
			<div class="row">
				<div class="col-sm-4 col-md-4">
					<b>Categoría del producto: </b> <?php echo $datosProducto["Category"]["name"] ?> <br>
					<b>Margen mínimo necesario para la categoría: </b> <?php echo $margen ?>% <br>
					<b>Factor de importación: </b> <?php echo $factorImport ?>% <br>
					<b>TRM actual: </b> <?php echo $trmActual ?>% <br>
				</div>
				<div class="col-sm-4 col-md-4">
					<?php $costo = $currency == "cop" ? $datosProducto["Product"]["purchase_price_wo"] : $datosProducto["Product"]["purchase_price_usd"]; ?>
					<?php $costoColombia = $currency == "cop" ? $costo : $costo*$factorImport; ?>
					<?php $valorProducto = $datosProducto["Product"]["list_price_usd"]; ?>
			
					<b>Precio cotizado del producto:</span> </b> <?php echo number_format($valorProducto,2, ",",".") ?> <br>
				</div>
				<div class="col-sm-4 col-md-4">
			
				</div>
			</div>
			</div>
		</td>
	</tr>
<?php endif ?>

<script type="text/javascript">
    // jQuery("a").mouseenter(function (e) {
    //     var posMouse = e.pageX - this.offsetLeft;
    //     var textoTooltip = jQuery(this).attr("title");
    //     if (textoTooltip.length > 0) {
    //         jQuery(this).append('<div class="tooltip-os">' + textoTooltip + '</div>');
    //         jQuery("a > div.tooltip-os").css("left", "" + posMouse - 355 + "px");
    //         jQuery("a > div.tooltip-os").fadeIn(100);
    //     }
    // });

    // jQuery("a").mouseleave(function () {             
    //     jQuery("a > div.tooltip-os").fadeOut(100).delay(100).queue(function () {
    //         jQuery(this).remove();
    //         jQuery(this).dequeue();
    //         calcular_total();
    //     });
    // });
</script>