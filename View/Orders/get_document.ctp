
<?php if (!empty($data["productos_factura"])): ?>
	


<?php foreach ($data["productos_factura"] as $datos): ?>
	<?php 
		$idTr = time()+rand(1000,100000);
		$entregaProducto 	= ["Inmediato" => "Inmediato"];
		$producto 			= $this->Utilities->getQuantityBlock($datos->Product);

		$totalGeneral 	= 0;
		
		$currency = "cop";
	?>
	<tr 
		id="<?php echo "tr_".$idTr."-".$datos->Product["id"] ?>" 
		data-producto="<?php echo $datos->Product["id"] ?>" 
		data-id="<?php echo $idTr ?>" 
		class="listado_tabla_ordenada productoAgregado_<?php echo $datos->Product["id"] ?>" 
		data-clase="productoAgregado_<?php echo $datos->Product["id"] ?>" 
		data-disponible="<?php echo $producto["totalDisponible"] ?>" 
		data-currency="<?php echo $currency ?>" 
		data-reference="<?php echo $datos->Product["part_number"] ?>"
	>
		<td>
			<?php $ruta = $this->Utilities->validate_image_products($datos->Product['img']); ?>
			<img src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="40px" class="imgmin-product">
		</td>
		<td class="nameget ">
			<?php echo $datos->Descripción ?>
		</td>
		<td class="descriptionget"><?php echo $this->Text->truncate(strip_tags($datos->Product['description']), 120,array('ellipsis' => '...sigue','exact' => false)); ?></td>
		<td class="enlaceget center">
			<?php echo $this->Form->input('Moneda-'.$idTr."-".$datos->Product["id"],array('label' => false,'class' => 'monedasChanges Moneda-'.$idTr."-".$datos->Product["id"], "value" => strtoupper($currency), "readonly", "data-clase" => 'Precio-'.$idTr."-".$datos->Product["id"] ));?>
		</td>
		<td class="enlaceget center">
			<?php if ((!empty($header) && $header == "3")): ?>
				<?php echo $this->Form->input('IVA-'.$idTr."-".$datos->Product["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datos->Product["id"], "value" => 0, "options" => ["0" =>"NO"] ,));?>
			<?php else: ?>

				<?php 
					if ($datos->Iva == ".190000") {
						$ivaOptions = ["1" => "SI"];
					}else{
						$ivaOptions = ["0" => "NO"];						
					}

				 ?>

				<?php echo $this->Form->input('IVA-'.$idTr."-".$datos->Product["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datos->Product["id"],"options" => $ivaOptions ));?>
			<?php endif ?>
		</td>
		<td><?php echo $datos->Product['part_number'] ?></td>
		<td>
			<?php echo $this->Form->input('Entrega-'.$idTr."-".$datos->Product["id"],array('label' => false, 'options' => $entregaProducto,'class' => 'Entrega-'.$idTr."-".$datos->Product["id"],'names' => 'Entrega-'.$datos->Product['id'], ));?>
		</td>
		<td><?php echo $datos->Product['brand'] ?></td>
		<td class="size4">
			<?php echo $this->element("products_block",["producto" => $datos->Product, "inventario_wo" => [] ]) ?>
		</td>
		<td class="precio">
			<?php 

				$precio = 0;

				if (isset($totalGeneral) && $totalGeneral > 0 && $datos["Product"]["type"] == 0 && $currency == "cop") {
					$datos->Product["type"] = 1;
				}

				if ($currency == "cop" && $datos->Product["type"] == 0) {
					$precio = $datos->Product["purchase_price_cop"] + $datos->Product["aditional_cop"];
				}elseif ($currency == "cop" && $datos->Product["type"] == 1 ) {
					$precio = isset($inventioWo[$datos->Product['part_number']][0]["costo"] ) ? $inventioWo[$datos->Product['part_number']][0]["costo"] : $datos->Product["purchase_price_wo"];
				}elseif($currency == "usd"){
					$precio =  $datos->Product["purchase_price_usd"] + $datos->Product["aditional_usd"];
				}

			 ?>
			<?php echo $this->Form->input('Precio-'.$idTr."-".$datos->Product["id"],array(
				'id' 				=> 'precio_item', 
				'data-id' 			=> $datos->Product['id'],
				'data-price' 		=> $precio, 
				'data-category' 	=> $datos->Product["category_id"],
				'data-type' 		=> $datos->Product["type"],
				'class'				=> 'valoresCotizacionProductos Precio-'.$idTr."-".$datos->Product["id"],
				'data-uid'			=> $idTr."-".$datos->Product["id"],
				'value'				=> (float)round($datos->Precio,2),
				'data-trdata'		=> "calculo_".$idTr,
				'data-clasetr'		=> "productoAgregado_".$idTr,
				'data-header'		=> empty($header) ? 1 : $header,
				'data-currency'		=> $currency,
				'data-price-cop'    => $datos->Product["type"] == 0 ? $datos->Product["purchase_price_cop"] + $datos->Product["aditional_cop"] : (isset($inventioWo[$datos->Product['part_number']][0]["costo"] ) ? $inventioWo[$datos->Product['part_number']][0]["costo"] : $datos->Product["purchase_price_wo"]),
				'data-price-usd'    => $datos->Product["purchase_price_usd"] + $datos->Product["aditional_usd"],
				'label' 			=> false, 
				'div' 				=> false, 
				'type' 				=> "text", 
				'names' 			=> 'Precio-'.$datos->Product['id'],
				"readonly"			=> true
			));?>
		</td>
		<td class="cantidad">
			<?php 

			$max = false;
				if(isset($this->request->data["other"])){
					$max = $producto["totalDisponible"];
				}else{
					if($datos->Product["type"] == 0 || $currency == "usd"){
						$max = false;
					}elseif($producto["totalDisponible"] > 0){
						$max = $producto["totalDisponible"];
					}
				}

			 ?>
			<?php echo $this->Form->input('Cantidad-'.$idTr."-".$datos->Product["id"],array(
				'type' 				=> "number", 
				'id' 				=> 'cantidadproduct', 
				'class'				=> 'cantidadProduct Cantidad-'.$idTr."-".$datos->Product["id"],
				'data-uid'			=> $idTr."-".$datos->Product["id"],
				'names' 			=> 'Cantidad-'.$idTr,
				'min'				=> 1,
				'value'				=> intval($datos->Cantidad),
				'label' 			=> false, 
				'div' 				=> false, 
				'readonly'			=> true
			));?>
			<?php echo $this->Form->input("Margen_".$datos->Product["id"]."_".$currency,["type" => "hidden"]) ?>
			<input type="hidden" class="Nota-<?php echo $idTr ?>-<?php echo $datos->Product["id"] ?> notasProductos" name="data[Nota-<?php echo $idTr ?>-<?php echo $datos->Product["id"] ?>]" value="" > 
		</td>
		<td class="subtotal">
			<?php echo number_format((float)(round($datos->Precio,2)*$datos->Cantidad),2,",",".");?>
			
		</td>
		<td>
		</td>
	</tr>
<?php endforeach ?>
<?php endif ?>