
<?php if (isset($this->request->data["header"])): ?>
	
	<?php 
	
		 $rolesPermitidos = array(
	    	"Gerente General", "Logística","Asesor Comercial"
	    );

		if(!empty($productQuotation)){
			$dataQ = end($productQuotation);
		}
	 ?>
	<?php foreach ($productTemplate as $datos): ?>
		<?php if ($datos["Product"]["deleted"] == 1 || $datos["Product"]["state"] == 0): ?>
			<?php continue; ?>
		<?php endif ?>
		<?php 
			$idTr = time()+rand(1000,100000);
			$entregaProducto 	= $entrega;
			$producto 			= $this->Utilities->getQuantityBlock($datos["Product"]);
			$totalGeneral 	= 0;
			if (!empty($inventioWo[$datos['Product']['part_number']])) {
				foreach ($inventioWo[$datos['Product']['part_number']] as $key => $value) {
					$totalGeneral+= $value["total"];
				}
			}
			$producto["quantity_back"]   = 0;
			$producto["totalDisponible"] = ($totalGeneral+$producto["quantity_back"]) < 0 ? 0 : $totalGeneral+$producto["quantity_back"];
			if($producto["totalDisponible"] == 0){
				unset($entregaProducto["Inmediato"]);
				unset($entregaProducto["1-2 días hábiles"]);
			}

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
		?>
		<tr 
			id="<?php echo "tr_".$idTr."-".$datos["Product"]["id"] ?>" 
			data-producto="<?php echo $datos["Product"]["id"] ?>" 
			data-id="<?php echo $idTr ?>" class="listado_tabla_ordenada productoAgregado_<?php echo $datos["Product"]["id"] ?>" 
			data-clase="productoAgregado_<?php echo $datos["Product"]["id"] ?>" 
			data-disponible="<?php echo $producto["totalDisponible"] ?>" 
			data-currency="<?php echo $currency ?>" 
			data-reference="<?php echo $datos["Product"]["part_number"] ?>"
		>
			<td>
				<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img']); ?>
				<img src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="40px" class="imgmin-product">
			</td>
			<td class="nameget <?php echo !is_null($datos["Product"]["notes"]) ? "nota" : "" ?>">
				<?php if (!is_null($datos["Product"]["notes"])): ?>
		            <div class="notaproduct">
			            <span class="triangle"></span>
			            <span class="flechagiro">|</span>
			            <div class="text_nota">
			                <small class="creadornota"><b></b></small>
			                <?php echo $datos["Product"]["notes"] ?>
			                <small class="datenota"></small>
			            </div>
		            </div>
		            <?php echo $datos['Product']['name'] ?>
		        <?php else: ?>
		            <?php echo $datos['Product']['name'] ?>
		        <?php endif ?>
			</td>
			<td class="descriptionget"><?php echo $this->Text->truncate(strip_tags($datos['Product']['description']), 120,array('ellipsis' => '...sigue','exact' => false)); ?></td>
			<td class="enlaceget center">
				<?php echo $this->Form->input('Moneda-'.$idTr."-".$datos["Product"]["id"],array('label' => false,'class' => 'Moneda-'.$idTr."-".$datos["Product"]["id"], "value" => strtoupper($currency), "readonly"));?>
			</td>
			<td class="enlaceget center">
			<?php if ((!empty($header) && $header == "3")): ?>
				<?php echo $this->Form->input('IVA-'.$idTr."-".$datos["Product"]["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datos["Product"]["id"], "value" => 0, "options" => ["0" =>"NO"] ,));?>
			<?php else: ?>
				<?php echo $this->Form->input('IVA-'.$idTr."-".$datos["Product"]["id"],array('label' => false,'class' => 'IVA-'.$idTr."-".$datos["Product"]["id"], "default" => 1 , "options" => ["1" => "SI","0" =>"NO"] ));?>
			<?php endif ?>
		</td>
			<td><?php echo $datos['Product']['part_number'] ?></td>
			<td>
				<?php echo $this->Form->input('Entrega-'.$idTr."-".$datos["Product"]["id"],array('label' => false, 'options' => $entregaProducto,'class' => 'Entrega-'.$idTr."-".$datos["Product"]["id"],'names' => 'Entrega-'.$datos['Product']['id'] ));?>
			</td>
			<td><?php echo $datos['Product']['brand'] ?></td>
			<td class="size4">
				<?php echo $this->element("products_block",["producto" => $datos["Product"], "inventario_wo" => $inventioWo[$datos['Product']['part_number']], "reserva" => isset($inventioWo["Reserva"][$datos["Product"]["part_number"]]) ? $inventioWo["Reserva"][$datos["Product"]["part_number"]] : null]) ?>
			</td>
			<td class="precio">
				<?php 

				$precio = 0;
				$precioVenta = 0;
				if ( (isset($totalGeneral) && $totalGeneral > 0 && $datos["Product"]["type"] == 0 && $currency == "cop") && (isset($this->request->data["money"]) && $this->request->data["money"] != "0") ) {
					$datos["Product"]["type"] = 1;
				}
				$typeCost = "CRM";
				if ($currency == "cop" && $datos["Product"]["type"] == 0) {
					$precio = $datos["Product"]["purchase_price_cop"] + $datos["Product"]["aditional_cop"];
					$precioVenta = $precio / ( 1 - ($datos["Product"]["Category"]["margen_wo"]/100) );
					$typeCost 	= "CRM";
				}elseif ($currency == "cop" && $datos["Product"]["type"] == 1 ) {
					// $precio = isset($inventioWo[$datos['Product']['part_number']][0]["costo"] ) ? $inventioWo[$datos['Product']['part_number']][0]["costo"] : $datos["Product"]["purchase_price_wo"];
					// $precioVenta = $precio / ( 1 - ($datos["Product"]["Category"]["margen_wo"]/100) );

					// $precio 	 = 1.05 * $datos["Product"]["purchase_price_usd"] * $trmActual;
					// 
					
					$costo 	  = $datos["Product"]["purchase_price_wo"];
					$costoRep = round($datos["Product"]["Category"]["factor"] * ($datos["Product"]["purchase_price_usd"] * $trmActual),2);

					if (isset($costos[$datos["Product"]["part_number"]]) && ($costos[$datos["Product"]["part_number"]] * 1) >=  $costoRep ) {
						$precio = $costos[$datos["Product"]["part_number"]]*1;
					}else{
						$precio = $costoRep;
					}

					$precioVenta = $precio / ( 1 - ($datos["Product"]["Category"]["margen_wo"]/100) );
					$typeCost 	 = "WO";
				}elseif($currency == "usd"){
					$precio =  $datos["Product"]["purchase_price_usd"] + $datos["Product"]["aditional_usd"];
					$precioVenta = $precio / ( 1 - ($datos["Product"]["Category"]["margen"]/100) );
					$precioVenta = $precioVenta * $datos["Product"]["Category"]["factor"];
					$typeCost 	= "REPOSICION";
				}
				$precioVenta = round($precioVenta,2);
			 ?>
				<?php echo $this->Form->input('Precio-'.$idTr."-".$datos["Product"]["id"],array(
					'id' 				=> 'precio_item', 
					'data-id' 			=> $datos['Product']['id'],
					'data-price' 		=> $precio, 
					'data-min' 			=> $currency == "usd" ? $datos["Product"]["Category"]["margen"]: $datos["Product"]["Category"]["margen_wo"],
					'data-category' 	=> $datos["Product"]["category_id"],
					'data-type' 		=> $datos["Product"]["type"],
					'data-categoryName' => $datos["Product"]["Category"]["name"],
					'class'				=> 'valoresCotizacionProductos Precio-'.$idTr."-".$datos["Product"]["id"],
					'data-uid'			=> $idTr."-".$datos["Product"]["id"],
					'value'				=> (float)$precioVenta,
					'data-trdata'		=> "calculo_".$idTr,
					'data-clasetr'		=> "productoAgregado_".$idTr,
					'data-header'		=> empty($header) ? 1 : $header,
					'data-currency'		=> $currency,
					'data-factor'		=> $datos["Product"]["Category"]["factor"],
					'data-typeCost'	    => $typeCost,
					'label' 			=> false, 
					'div' 				=> false, 
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

				 ?>
				<?php echo $this->Form->input('Cantidad-'.$idTr."-".$datos["Product"]["id"],array(
					'type' 				=> "number", 
					'id' 				=> 'cantidadproduct', 
					'class'				=> 'cantidadProduct Cantidad-'.$idTr."-".$datos["Product"]["id"],
					'data-uid'			=> $idTr."-".$datos["Product"]["id"],
					'names' 			=> 'Cantidad-'.$idTr,
					'min'				=> 1,
					'value'				=> 1,
					'max'				=> $max,
					'label' 			=> false, 
					'div' 				=> false, 
				));?>
				<?php echo $this->Form->input("Margen_".$datos["Product"]["id"]."_".$currency,["type" => "hidden"]) ?>
				<input type="hidden" class="Nota-<?php echo $idTr ?>-<?php echo $datos["Product"]["id"] ?> notasProductos" name="data[Nota-<?php echo $idTr ?>-<?php echo $datos["Product"]["id"] ?>]" >
				<?php echo $this->Form->input('Cotiza-'.$idTr."-".$datos["Product"]["id"],array('label' => false,'class' => 'Cotiza-'.$idTr."-".$datos["Product"]["id"], "value" => strtoupper($typeCost), "type"=>"hidden" ));?>
			</td>
			<td class="subtotal">
				<?php echo number_format($precioVenta,2,",",".");?>				
			</td>
			<td>
				 <a href="#" data-id="<?php echo $idTr ?>-<?php echo $datos["Product"]["id"] ?>" class="btn btn-success mr-2 p-1 notaPrductCotizacion text-white ml-1" data-toggle="tooltip" title="" data-original-title="Agregar nota de al producto para esta cotización" style="padding-left: 4px !important;padding-right: 4px !important;">
	                <i class="fa fa-plus-square text-white vtc"></i>
	            </a>
				<a data-uid="<?php echo $datos['Product']['id'] ?>"  class="deleteProduct" data-clase="productoAgregado_<?php echo $datos["Product"]["id"] ?>">
					<i class="fa fa-remove" data-toggle="tooltip" data-placement="right" title="Eliminar producto"></i>
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
		        <?php if (!empty($datos["productsSugestions"]["productsFinals"])): ?>
					<a data-uid="<?php echo $datos['Product']['id'] ?>"  class="btn btn-warning viewSugesstions" data-clase="productoAgregado_<?php echo $idTr ?>">
						!<i class="fa fa-info" data-toggle="tooltip" data-placement="right" title="Ver sugerencia de cotización"></i>
					</a>
				<?php endif ?>
			</td>
		</tr>
		<tr class="productoAgregado_<?php echo $idTr ?>">
			<td colspan="12">				
				<div class="notaPCtz_<?php echo $idTr ?>-<?php echo $datos["Product"]["id"] ?>"></div> 
			</td>
		</tr>
		<tr class="productoAgregado_<?php echo $idTr ?>">
			<td colspan="13">
				<div style="display:none" class="suggestions_<?php echo $datos["Product"]["id"] ?>">
					<?php echo $this->element("suggestions",["productsSugestions" => $datos["productsSugestions"] ]) ?>
				</div>
			</td>
		</tr>
		<tr class="productoAgregado_<?php echo $datos["Product"]["id"] ?> productosCalculados" id="calculo_<?php echo $idTr ?>">
			<td colspan="13">
				<div class="col-md-12 find-products_cotizacion">
				<div class="row">
					<div class="col-sm-4 col-md-4">
						<b>Categoría del producto: </b> <?php echo $datos["Product"]["Category"]["name"] ?> <br>
						<b>Margen mínimo necesario para la categoría: </b> <?php echo $datos["Product"]["Category"]["margen"] ?>% <br>
						<b>Factor de importación: </b> <?php echo $factorImport ?>% <br>
						<b>TRM actual: </b> <?php echo $trmActual ?>% <br>
					</div>
					<div class="col-sm-4 col-md-4">
						<?php $costo = $currency == "cop" ? $datos["Product"]["purchase_price_usd"]*$trmActual : $datos["Product"]["purchase_price_usd"]; ?>
						<?php $costoColombia = $costo*$factorImport; ?>
						<?php $valorProducto = $datos["Product"]["list_price_usd"]; ?>
						<b>Costo del producto: </b> <?php echo number_format($costo,2, ",",".") ?> <br>
						<b>Costo final del producto: </b> <?php echo number_format($costoColombia,2, ",",".") ?> <br>
						<b>Precio cotizado del producto:</span> </b> <?php echo number_format($valorProducto,2, ",",".") ?> <br>
					</div>
					<div class="col-sm-4 col-md-4">
						<?php $margenFinal = $valorProducto == 0 ? 0 : ( ($valorProducto-$costoColombia) /$valorProducto) * 100 ; ?> 
						<b>Margen final del producto: </b>
						<span class="font-weight-bold margenes <?php echo $margenFinal < $datos["Product"]["Category"]["margen"] ? "letra-rojo" : "letra-verde" ?> <?php echo $costo <= 0 ? "costo-cero" : "" ?>">
							<?php  echo number_format($margenFinal,"2",".",",") ?> %
						</span><br>
					</div>
				</div>
				</div>
			</td>
		</tr>
	<?php endforeach ?>

<?php else: ?>


<?php foreach ($productTemplate as $datos): ?>
	<tr id="<?php echo "tr_".$datos['Product']['id'] ?>" class="listado_tabla_ordenada">
		<td>
			<?php $ruta = $this->Utilities->validate_image_products($datos['Product']['img']); ?>
			<img src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>"  width="40px" class="imgmin-product">
		</td>
		<td class="nameget"><?php echo $datos['Product']['name'] ?></td>
		<td class="descriptionget"><?php echo $this->Text->truncate(strip_tags($datos['Product']['description']), 120,array('ellipsis' => '...sigue','exact' => false)); ?></td>
		<td class="enlaceget center">
		<a target="_blank" href="<?php echo $this->Utilities->data_null($datos['Product']['link'])?>" title="<?php echo $this->Utilities->data_null($datos['Product']['link'])?>" class="tooltip_elemento ">Ver</a>
		</td>
		<td><?php echo $datos['Product']['part_number'] ?></td>
		<td><?php echo $this->Form->input('delivery',array('label' => false, 'id' => '', 'options' => $entrega,'class' => 'Entrega-'.$datos['Product']['id'],'name' => 'Entrega-'.$datos['Product']['id']));?></td>
		<td><?php echo $datos['Product']['brand'] ?></td>
		<td class="precio">
			<input type="text" id="precio_item" class="valoresCotizacionProductos <?php echo "Precio-".$datos['Product']['id'] ?>" name="<?php echo "Precio-".$datos['Product']['id'] ?>" data-uid="<?php echo $datos['Product']['id'] ?>"  value="<?php echo (float)$datos['Product']['list_price_usd'];?>">
		</td>
		<td class="cantidad">
			<input type="number" id="cantidadproduct" class="cantidadProduct <?php echo "Cantidad-".$datos['Product']['id'] ?>" data-uid="<?php echo $datos['Product']['id'] ?>" name="<?php echo "Cantidad-".$datos['Product']['id'] ?>" min="1" value="1">
		</td>
		<td class="subtotal">
			<?php echo number_format((float)$datos['Product']['list_price_usd'],2,",",".");?>
		</td>
		<td>
			<a data-uid="<?php echo $datos['Product']['id'] ?>"  class="deleteProduct">
				<i class="fa fa-remove" data-toggle="tooltip" data-placement="right" title="Eliminar producto"></i>
			</a>
			<a  data-uid="<?php echo $datos['Product']['id'] ?>" class="editarProduct">
				<i class="fa fa-fw fa-pencil" data-toggle="tooltip" data-placement="right" title="Editar producto"></i>
			</a>
		</td>
	</tr>
<?php endforeach ?>


<?php endif ?>
