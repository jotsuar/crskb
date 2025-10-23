<?php $idTr = time(); ?>
<?php 
	$copiaEntrega = $entrega;

	$rolesPermitidos = array(
    	"Gerente General", "Logística","Asesor Comercial"
    );

	$producto = $this->Utilities->getQuantityBlock($datosProducto["Product"]);

	$pos = $this->Utilities->getNameProduct($datosProducto["Product"]["id"]);
	
	if(isset($this->request->data["money"]) && $this->request->data["money"] != "0" ){
		$currency = $this->request->data["money"] == "USD" ? "usd" : "cop";
		if($this->request->data["money"] == "COP"){
			$datosProducto["Product"]["type"] = 0;
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
            <b>Nombre: </b><?php echo $datosProducto['Product']['name'] ?> <br> 
            <b>Marca: </b> <?php echo $datosProducto['Product']['brand'] ?> <br>
            <b>Referencia: </b> <?php echo $datosProducto['Product']['part_number'] ?>
			
	</td>
	<td class="descriptionget"><?php echo $this->Text->truncate(strip_tags($datosProducto['Product']['description']), 120,array('ellipsis' => '...sigue','exact' => false)); ?></td>

	<td class="enlaceget center">
		<?php echo $this->Form->input('Moneda-'.$idTr."-".$datosProducto["Product"]["id"],array('label' => false,'class' => 'Moneda-'.$idTr."-".$datosProducto["Product"]["id"], "value" => strtoupper($currency), "readonly","style" => 'width: 65px;' ));?>
	</td>
	<td class="enlaceget center">
		<?php if ((!empty($header) && $header == "3")): ?>
			<?php echo $this->Form->input('IVA-'.$idTr."-".$datosProducto["Product"]["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datosProducto["Product"]["id"], "value" => 0, "options" => ["0" =>"NO"] ,));?>
		<?php else: ?>
			<?php echo $this->Form->input('IVA-'.$idTr."-".$datosProducto["Product"]["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datosProducto["Product"]["id"], "default" => 1, "options" => ["1" => "SI","0" =>"NO"] ));?>
		<?php endif ?>
	</td>
	<td>
		<?php 

			$precio = 0;
			$precioVenta = 0;
			if (isset($totalGeneral) && $totalGeneral > 0 && $datosProducto["Product"]["type"] == 0 && $currency == "cop") {
				$datosProducto["Product"]["type"] = 1;
			}

			
			$precio = isset($inventioWo[$datosProducto['Product']['part_number']][0]["costo"] ) ? $inventioWo[$datosProducto['Product']['part_number']][0]["costo"] : $datosProducto["Product"]["purchase_price_wo"];
			$precioVenta = $precio / ( 1 - ($datosProducto["Category"]["margen_wo"]/100) );
			

			$precioVenta = round($precioVenta,2);
			$datosProducto["Product"]["price"] = $precioVenta;	
			$margenFinal = $this->Utilities->calculateMargen($trmActual,$this->Utilities->getProductFactor($datosProducto['Product']['id']),$precio,$datosProducto["Product"],1, "cop"); 

		?>
		<?php if ($margenFinal < 30): ?>
			<a class="pointer bg-danger p-1 text-white" id="<?php echo md5("TIENDA_KEB_".$datosProducto["Product"]["id"]) ?>">
				<?php echo $margenFinal ?>% 
			</a>
		<?php else: ?>
			<a class="pointer bg-success p-1 text-white" id="<?php echo md5("TIENDA_KEB_".$datosProducto["Product"]["id"]) ?>">
				<?php echo $margenFinal ?>% 
			</a>
		<?php endif ?>
			
	</td>
	<td>
		<?php echo "Inmediato" ?>
		<?php echo $this->Form->input('Entrega-'.$idTr."-".$datosProducto["Product"]["id"],array('type' => "hidden", "value"=> "Inmediato",'class' => 'Entrega-'.$idTr."-".$datosProducto["Product"]["id"],'names' => 'Entrega-'.$datosProducto['Product']['id']));?>
	</td>
	<td class="size4">
		<?php echo $this->element("products_block",["producto" => $datosProducto["Product"],"inventario_wo" => $inventioWo[$datosProducto['Product']['part_number']],"bloqueo" => false, "reserva" => isset($inventioWo["Reserva"][$datosProducto["Product"]["part_number"]]) ? $inventioWo["Reserva"][$datosProducto["Product"]["part_number"]] : null ]) ?>
	</td>
	<td class="precio">
		
		<?php echo $this->Form->input('Precio-'.$idTr."-".$datosProducto["Product"]["id"],array(
			'id' 				=> 'precio_item', 
			'data-id' 			=> $datosProducto['Product']['id'],
			'data-price' 		=> $precio, 
			'data-min' 			=> $datosProducto["Category"]["margen_wo"],
			'data-category' 	=> $datosProducto["Product"]["category_id"],
			'data-categoryName' => $datosProducto["Category"]["name"],
			'data-type' 		=> $datosProducto["Product"]["type"],
			'class'				=> 'valoresCotizacionProductos Precio-'.$idTr."-".$datosProducto["Product"]["id"],
			'data-uid'			=> $idTr."-".$datosProducto["Product"]["id"],
			'value'				=> $precioVenta > 0 ? floatval($precioVenta) : (float)$datosProducto['Product']['list_price_usd'],
			'data-trdata'		=> "calculo_".$idTr,
			'data-clasetr'		=> "productoAgregado_".$idTr,
			'data-currency'		=> $currency,
			'data-margen'		=> md5("TIENDA_KEB_".$datosProducto["Product"]["id"]),
			'data-factor'		=> $datosProducto["Category"]["factor"],
			'data-header'		=> empty($header) ? 1 : $header,
			'label' 			=> false, 
			'div' 				=> false, 
			'type' 				=> "text", 
			'names' 			=> 'Precio-'.$datosProducto['Product']['id']
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

		 ?>
		<?php echo $this->Form->input('Cantidad-'.$idTr."-".$datosProducto["Product"]["id"],array(
			'type' 				=> "number", 
			'id' 				=> 'cantidadproduct', 
			'class'				=> 'cantidadProduct Cantidad-'.$idTr."-".$datosProducto["Product"]["id"],
			'data-uid'			=> $idTr."-".$datosProducto["Product"]["id"],
			'names' 			=> 'Cantidad-'.$idTr,
			'min'				=> 1,
			'value'				=> 1,
			'max'				=> false,
			'label' 			=> false, 
			'div' 				=> false, 
		));?>
		<?php echo $this->Form->input("Margen_".$datosProducto["Product"]["id"]."_".$currency,["type" => "hidden"]) ?>
		<input type="hidden" class="Nota-<?php echo $idTr ?>-<?php echo $datosProducto["Product"]["id"] ?> notasProductos" name="data[Nota-<?php echo $idTr ?>-<?php echo $datosProducto["Product"]["id"] ?>]" > 
	</td>
	<td class="subtotal">
		<?php echo number_format((float)$precioVenta,2,",",".");?>
	</td>
	<td>
		
		<a data-uid="<?php echo $datosProducto['Product']['id'] ?>"  class="deleteProduct" data-clase="productoAgregado_<?php echo $idTr ?>">
			<i class="fa fa-remove" data-toggle="tooltip" data-placement="right" title="Eliminar producto"></i>
		</a>
		
	</td>
</tr>
