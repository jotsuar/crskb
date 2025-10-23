<?php 
	
	 $rolesPermitidos = array(
    	"Gerente General", "Logística","Asesor Comercial"
    );

	if(!empty($productQuotation)){
		$dataQ = end($productQuotation);
	}
 ?>
<?php foreach ($productQuotation as $datos): ?>
	<?php if ($datos["Product"]["deleted"] == 1 || $datos["Product"]["state"] == 0): ?>
		<?php continue; ?>
	<?php endif ?>
	<?php 
		$idTr = time()+rand(1000,100000);
		$entregaProducto 	= $entrega;
		$producto 			= $this->Utilities->getQuantityBlock($datos["Product"]);

		$descuento = 0;

		if ($datos["Quotation"]["descuento"] > 0) {
			$descuento = $datos['QuotationsProduct']['price'] * ($datos["Quotation"]["descuento"] / 100);
		}

		$totalGeneral 	= 0;
		if (!empty($inventioWo[$datos['Product']['part_number']])) {
			foreach ($inventioWo[$datos['Product']['part_number']] as $key => $value) {
				$totalGeneral+= $value["total"];
			}
		}
		$producto["quantity_back"]   = 0;
		$producto["totalDisponible"] = ($totalGeneral+$producto["quantity_back"]) < 0 ? 0 : $totalGeneral+$producto["quantity_back"];

		// if($producto["totalDisponible"] == 0){
		// 	unset($entregaProducto["Inmediato"]);
		// 	unset($entregaProducto["1-2 días hábiles"]);
		// }

		$pos = $this->Utilities->getNameProduct($datos["Product"]["id"]);
		
		if(isset($this->request->data["money"]) && $this->request->data["money"] != "0" ){
			$currency = $this->request->data["money"] == "USD" ? "usd" : "cop";
			if($this->request->data["money"] == "COP"){
				$datos["Product"]["type"] = 0;
			}
		}elseif(!empty($header) && $header == 3){
			$currency = "usd";
		}else{			
			if( ($datos["Product"]["type"] == 0 && $datos["Product"]["currency"] == 2) || ( $pos !== false && (!empty($header) && $header != "3") ) ){
				$currency = "usd";
			}elseif($datos["Product"]["type"] == 0 || ( $pos !== false && (!empty($dataQ) && $header != 3) ) ){
				$currency = "cop";
			}elseif(strtolower($datos["Product"]["Category"]["name"]) == "servicio"){
				$currency = "cop";
				$entregaProducto = $entrega;
			}else{
				$currency =  $producto["totalDisponible"] <= 0 && $datos["Product"]["type"] == 1 ? "usd" : "cop";
			}
		}

		$currencies = [ "COP" => "COP", "USD" => "USD" ];
	?>
	<tr 
		id="<?php echo "tr_".$idTr."-".$datos["Product"]["id"] ?>" 
		data-producto="<?php echo $datos["Product"]["id"] ?>" 
		data-id="<?php echo $idTr ?>" 
		class="listado_tabla_ordenada productoAgregado_<?php echo $datos["Product"]["id"] ?>" 
		data-clase="productoAgregado_<?php echo $datos["Product"]["id"] ?>" 
		data-disponible="<?php echo $producto["totalDisponible"] ?>" 
		data-currency="<?php echo $currency ?>" 
		data-reference="<?php echo $datos["Product"]["part_number"] ?>"
	>
		<td>
			<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img']); ?>
			<img src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="40px" class="imgmin-product">
		</td>
		<td class="nameget ">
			<?php echo $datos['Product']['name'] ?>
		</td>
		<td class="descriptionget"><?php echo $this->Text->truncate(strip_tags($datos['Product']['description']), 120,array('ellipsis' => '...sigue','exact' => false)); ?></td>
		<td class="enlaceget center">
			<?php echo $this->Form->input('Moneda-'.$idTr."-".$datos["Product"]["id"],array('label' => false,'class' => 'monedasChanges Moneda-'.$idTr."-".$datos["Product"]["id"], "value" => strtoupper($currency), "readonly", "options" => $currencies,"data-clase" => 'Precio-'.$idTr."-".$datos["Product"]["id"] ));?>
		</td>
		<td class="enlaceget center">
			<?php if ((!empty($header) && $header == "3")): ?>
				<?php echo $this->Form->input('IVA-'.$idTr."-".$datos["Product"]["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datos["Product"]["id"], "value" => 0, "options" => ["0" =>"NO"] ,));?>
			<?php else: ?>
				<?php echo $this->Form->input('IVA-'.$idTr."-".$datos["Product"]["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datos["Product"]["id"], "default" => $datos['QuotationsProduct']['iva'] , "options" => ["1" => "SI","0" =>"NO"] ));?>
			<?php endif ?>
		</td>
		<td><?php echo $datos['Product']['part_number'] ?></td>
		<td>
			<?php echo $this->Form->input('Entrega-'.$idTr."-".$datos["Product"]["id"],array('label' => false, 'options' => $entregaProducto,'class' => 'Entrega-'.$idTr."-".$datos["Product"]["id"],'names' => 'Entrega-'.$datos['Product']['id'],'default' => $datos['QuotationsProduct']['delivery'] ));?>
		</td>
		<td><?php echo $datos['Product']['brand'] ?></td>
		<td class="size4">
			<?php echo $this->element("products_block",["producto" => $datos["Product"], "inventario_wo" => $inventioWo[$datos['Product']['part_number']]]) ?>
		</td>
		<td class="precio">
			<?php 

				$precio = 0;

				if (isset($totalGeneral) && $totalGeneral > 0 && $datos["Product"]["type"] == 0 && $currency == "cop") {
					$datos["Product"]["type"] = 1;
				}

				if ($currency == "cop" && $datos["Product"]["type"] == 0) {
					$precio = $datos["Product"]["purchase_price_cop"] + $datos["Product"]["aditional_cop"];
				}elseif ($currency == "cop" && $datos["Product"]["type"] == 1 ) {
					$precio = isset($inventioWo[$datos['Product']['part_number']][0]["costo"] ) ? $inventioWo[$datos['Product']['part_number']][0]["costo"] : $datos["Product"]["purchase_price_wo"];
				}elseif($currency == "usd"){
					$precio =  $datos["Product"]["purchase_price_usd"] + $datos["Product"]["aditional_usd"];
				}

			 ?>
			<?php echo $this->Form->input('Precio-'.$idTr."-".$datos["Product"]["id"],array(
				'id' 				=> 'precio_item', 
				'data-id' 			=> $datos['Product']['id'],
				'data-price' 		=> $precio, 
				'data-min' 			=> $currency == "usd" ? $datos["Product"]["Category"]["margen"]: $datos["Product"]["Category"]["margen_wo"] ,
				'data-category' 	=> $datos["Product"]["category_id"],
				'data-type' 		=> $datos["Product"]["type"],
				'data-categoryName' => $datos["Product"]["Category"]["name"],
				'class'				=> 'valoresCotizacionProductos Precio-'.$idTr."-".$datos["Product"]["id"],
				'data-uid'			=> $idTr."-".$datos["Product"]["id"],
				'value'				=> (float)$datos['QuotationsProduct']['price'] - $descuento,
				'data-trdata'		=> "calculo_".$idTr,
				'data-clasetr'		=> "productoAgregado_".$idTr,
				'data-header'		=> empty($header) ? 1 : $header,
				'data-currency'		=> $currency,
				'data-factor'		=> $datos["Product"]["Category"]["factor"],
				'data-price-cop'    => $datos["Product"]["type"] == 0 ? $datos["Product"]["purchase_price_cop"] + $datos["Product"]["aditional_cop"] : (isset($inventioWo[$datos['Product']['part_number']][0]["costo"] ) ? $inventioWo[$datos['Product']['part_number']][0]["costo"] : $datos["Product"]["purchase_price_wo"]),
				'data-price-usd'    => $datos["Product"]["purchase_price_usd"] + $datos["Product"]["aditional_usd"],
				'label' 			=> false, 
				'div' 			=> false, 
				'type' 				=> "text", 
				'names' 			=> 'Precio-'.$datos['Product']['id']
			));?>
		</td>
		<td class="cantidad">
			<?php 

			$max = false;
				if(isset($this->request->data["other"])){
					$max = $producto["totalDisponible"];
				}else{
					if($datos["Product"]["type"] == 0 || $currency == "usd"){
						$max = false;
					}elseif($producto["totalDisponible"] > 0){
						$max = $producto["totalDisponible"];
					}
				}
				$max = false;
			 ?>
			<?php echo $this->Form->input('Cantidad-'.$idTr."-".$datos["Product"]["id"],array(
				'type' 				=> "number", 
				'id' 				=> 'cantidadproduct', 
				'class'				=> 'cantidadProduct Cantidad-'.$idTr."-".$datos["Product"]["id"],
				'data-uid'			=> $idTr."-".$datos["Product"]["id"],
				'names' 			=> 'Cantidad-'.$idTr,
				'min'				=> 1,
				'value'				=> $datos['QuotationsProduct']['quantity'],
				'max'				=> $max,
				'label' 			=> false, 
				'div' 				=> false, 
			));?>
			<?php echo $this->Form->input("Margen_".$datos["Product"]["id"]."_".$currency,["type" => "hidden"]) ?>
			<input type="hidden" class="Nota-<?php echo $idTr ?>-<?php echo $datos["Product"]["id"] ?> notasProductos" name="data[Nota-<?php echo $idTr ?>-<?php echo $datos["Product"]["id"] ?>]" value="<?php echo $datos['QuotationsProduct']["note"] ?>" > 
		</td>
		<td class="subtotal">
			<?php echo number_format((float)($datos['QuotationsProduct']['price']*$datos['QuotationsProduct']['quantity']),2,",",".");?>
			
		</td>
		<td>
			<?php if (!isset($this->request->data["order"])): ?>
				<a href="#" data-id="<?php echo $idTr ?>-<?php echo $datos["Product"]["id"] ?>" class="btn btn-success mr-2 p-1 notaPrductCotizacion text-white ml-1" data-toggle="tooltip" title="" data-original-title="Agregar nota de al producto para esta cotización" style="padding-left: 4px !important;padding-right: 4px !important;">
	                <i class="fa fa-plus-square text-white vtc"></i>
	            </a>
	            <?php if ($editProducts): ?>
					<a  data-uid="<?php echo $datos['Product']['id'] ?>" class="editarProduct">
						<i class="fa fa-fw fa-pencil" data-toggle="tooltip" data-placement="right" title="Editar producto"></i>
					</a>
				<?php else: ?>
					<a  data-id="<?php echo $datos['Product']['id'] ?>" class="requestEditProduct">
						<i class="fa fa-fw fa-pencil" data-toggle="tooltip" data-placement="right" title="Solicitar Editar producto"></i>
					</a>	
				<?php endif ?>	
				<?php if (in_array(AuthComponent::user("role"), $rolesPermitidos)): ?>
		            <a href="#" data-id="<?php echo $datos["Product"]["id"] ?>" class="btn btn-incorrecto d-inline p-1 notesProduct text-white ml-4" data-toggle="tooltip" title="" data-original-title="Gestionar notas del producto">
		                <i class="fa fa-comments text-white vtc"></i>
		            </a>
		        <?php endif ?>
			<?php endif ?>
			
			<a data-uid="<?php echo $datos['Product']['id'] ?>"  class="deleteProduct" data-clase="productoAgregado_<?php echo $datos["Product"]["id"] ?>">
				<i class="fa fa-remove" data-toggle="tooltip" data-placement="right" title="Eliminar producto"></i>
			</a>
			
		</td>
	</tr>
	<tr class="productoAgregado_<?php echo $idTr ?>">
		<td colspan="13">
			
			<div class="notaPCtz_<?php echo $idTr ?>-<?php echo $datos["Product"]["id"] ?>"><?php echo $datos['QuotationsProduct']["note"] ?></div> 
		</td>
	</tr>
<?php endforeach ?>
<script type="text/javascript">
    jQuery("a").mouseenter(function (e) {             
        var posMouse = e.pageX - this.offsetLeft; 
        var textoTooltip = jQuery(this).attr("title"); 
		
        if ( typeof textoTooltip != undefined && textoTooltip != null && textoTooltip.length > 0) {
            jQuery(this).append('<div class="tooltip-os">' + textoTooltip + '</div>');
            jQuery("a > div.tooltip-os").css("left", "" + posMouse - 355 + "px");
            jQuery("a > div.tooltip-os").fadeIn(100);
        }
    });

    jQuery("a").mouseleave(function () {             
        jQuery("a > div.tooltip-os").fadeOut(100).delay(100).queue(function () {
            jQuery(this).remove();
            jQuery(this).dequeue();
        });
    });
</script>