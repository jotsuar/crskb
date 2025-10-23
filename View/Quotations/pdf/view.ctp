<?php $arrayVarcharPermitido = array('','<br>'); ?>
<?php if ($datosQuation['Quotation']['header_id'] == 0) { ?>
	<?php echo $this->Html->image('header/header/'.$datosHeaders['Header']['img_big'],array('class' => 'images-header')) ?>
<?php } else { ?>
	<?php echo $this->Html->image('header/header/'.$datosHeaders['Header']['img_big'],array('class' => 'images-header')) ?>
<?php } ?>
<br>

<div class="contentmargin">
	<div class="datosPedido">
		<br>
		<h3 class="colorrojo"><?php echo $datosQuation['Quotation']['codigo'] ?>
			<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
				/ <span class="numberorderst">
					<?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?>
				</span>
			<?php endif ?>
		</h3>
		<br>
		<h3 style="color: #3c3c3c">
			<?php echo $this->Utilities->explode_city($datosUsuario['User']['city'],$datos["ProspectiveUser"]["country"]) .', '.$this->Utilities->date_castellano($datosQuation['Quotation']['created']); ?>
		</h3>
		<br><br>
		<p>Sr(a):</p>
		<br>
		

			<?php if ($datos['ProspectiveUser']['contacs_users_id'] > 0){ ?>
					<h3 style="color: #3c3c3c">
						<?php echo $this->Utilities->name_bussines($datosC['ContacsUser']['clients_legals_id']); ?> 
						<br>
						<?php echo $datosC["ContacsUser"]["nit"] ?>
						<br>
						<?php echo mb_strtoupper($this->Utilities->name_prospective($datos['ProspectiveUser']['id'])); ?>
					</h3>					
					<?php if ($datosC['ContacsUser']['telephone'] != ''): ?>
						<h3 style="color: #3c3c3c"><?php echo $datosC['ContacsUser']['telephone'] ?></h3>
					<?php endif ?>
					<?php if ($datosC['ContacsUser']['cell_phone'] != ''): ?>
						<h3 style="color: #3c3c3c"><?php echo $datosC['ContacsUser']['cell_phone'] ?></h3>
					<?php endif ?>
					<h3 style="color: #3c3c3c"><i class="fa fa-mail"></i><?php echo $datosC['ContacsUser']['email'] ?></h3>
					<h3 style="color: #3c3c3c"><?php echo $datosC['ContacsUser']['city'] ?></h3>
				<?php } else { ?>
					<h3 style="color: #3c3c3c"><?php echo mb_strtoupper($this->Utilities->name_prospective($datos['ProspectiveUser']['id'])); ?></h3>
					<?php if ($datosC['ClientsNatural']['telephone'] != ''): ?>
						<h3 style="color: #3c3c3c"><?php echo $datosC['ClientsNatural']['telephone'] ?></h3>
					<?php endif ?>
					<?php if ($datosC['ClientsNatural']['cell_phone'] != ''): ?>
						<h3 style="color: #3c3c3c"><?php echo $datosC['ClientsNatural']['cell_phone'] ?></h3>
					<?php endif ?>					
					<h3 style="color: #3c3c3c"><?php echo $datosC['ClientsNatural']['email'] ?></h3>
					<?php if ($datosC['ClientsNatural']['city'] != ''): ?>
						<h3 style="color: #3c3c3c"><?php echo $datosC['ClientsNatural']['city'] ?></h3>
					<?php endif ?>
				<?php } ?>

		<br><br><br>
		<h3 class="colorazul"><b>Asunto: </b><?php echo $datosQuation['Quotation']['name'] ?></h3>
		<br><br><br>
		<hr>

		<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
			<table cellpadding="0" cellspacing="0" class="tableproductsst">
				<tr class="text-center">
					<th colspan="6">PRODUCTO DEL CLIENTE - ORDEN DE SERVICIO <?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?></b></th>
				</tr>
				<tr class="titles-tablest">
					<td>Equipo</td>
					<td>Número de parte</td>
					<td>Serie</td>
					<td>Serial</td>
					<td>Marca</td>
				</tr>
				<?php foreach ($productClient as $valueP): ?>
					<tr>
						<td><?php echo $valueP['ProductTechnical']['equipment'] ?></td>
						<td><?php echo $valueP['ProductTechnical']['part_number'] ?></td>
						<td><?php echo $valueP['ProductTechnical']['serial_number'] ?></td>
						<td><?php echo $this->Utilities->data_null($valueP['ProductTechnical']['serial_garantia']) ?></td>
						<td><?php echo $valueP['ProductTechnical']['brand'] ?></td>
					</tr>
				<?php endforeach ?>
			</table>
			<?php echo $this->Html->image('logos-clientes-tecnico.jpg',array('class' => 'images-cliente')) ?>
		<?php endif ?>

		<?php if ($datos['ProspectiveUser']['type'] < 1): ?>
			<?php if (!in_array($datosQuation['Quotation']['notes'],$arrayVarcharPermitido)) { ?>
				<div class="contentgrafico">
					<p><?php echo $datosQuation['Quotation']['notes'] ?></p>
				</div>
			<?php } else { ?>
				<?php echo $this->Html->image('logos-clientes.jpg',array('class' => 'images-cliente')) ?>
			<?php } ?>
		<?php endif; ?>
		<hr>
	</div>
	<br>
	<br>
	<?php $totalNoIva = 0; ?>
	<?php $totalNoIvaUSD = 0; ?>
	<?php $totalIvaUSD = 0; ?>
	<?php $totalIvaCop = 0; ?>
	<?php $usdData = array(); $copData=array(); ?>
	<?php $i = 0; foreach ($datosProductos as $value): $i++; ?>
		<?php 
			if($value["QuotationsProduct"]["currency"] == "usd"){
				$usdData[] = $value["QuotationsProduct"]["id"];
			}else{
				$copData[] = $value["QuotationsProduct"]["id"];								
			}
		 ?>
		<div class="divContenedor" style="display: <?php echo in_array($value["Product"]["part_number"], [ 'S-003','SER-AE']) && $datosQuation["Quotation"]["show_ship"] == 0 ? 'none !important' : 'block' ?>;">
			<br>
			<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']);?>
			<div class="imgProduct">
				<?php echo $this->Html->image('products/'.$ruta,array('class' => 'images-product')) ?>
			</div>
			<div class="atributos">
				<p class="txtPLineaNo text-left">
					<strong class="colorVerde">
						Referencia: <?php echo $value['Product']['part_number'] ?> / Marca: <?php echo $value['Product']['brand'] ?>
					</strong>
				</p>
				<p class="txtPLineaNo text-right"><?php echo 'Producto '.$i; ?></p>
				<h3 class="colorazul" style="text-transform: uppercase;"><?php echo $this->Text->truncate(strip_tags($value['Product']['name']), 60,array('ellipsis' => '...','exact' => false)); ?></h3>
				<p class="descriptionview">
					<?php echo $this->Text->truncate(strip_tags($value['Product']['description']), 500,array('ellipsis' => '.','exact' => false)); ?>
				</p>
								
				<?php if ($value['Product']['link'] != "" ) { ?> 
					<p>
						Más información:
						<a target="_blank" href="<?php echo $this->Utilities->data_null($value['Product']['link']) ?>">Clic aquí para ver abrir el enlace</a>
					</p>
				<?php } ?>
				<div class="content-price">
					<div class="cantidad ivaPrice">Cant. <?php echo $value['QuotationsProduct']['quantity'] ?></div>
					<div class="entrega ivaPrice"><?php echo $value['QuotationsProduct']['delivery'] ?></div>


					<div class="precio ivaPrice" style="<?php echo $datosQuation['Quotation']['currency'] == "usd" ? "" : "" ?>">
						<?php if ($datosQuation["Quotation"]["show"] == 1): ?>
							<?php $precioCotizacionUnidad = explode(",", number_format((float)$value['QuotationsProduct']['price'], "2",",",".")) ?>
							$<?php echo $precioCotizacionUnidad[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionUnidad[1] ?></span>
							<?php echo $value['QuotationsProduct']['currency'] == "usd" ? " USD" : "" ?>
						<?php else: ?>
							<span style="height: 15px; color: #00b307 !important;">
								&nbsp;&nbsp;&nbsp;
								-----------------
							</span>
						<?php endif ?>

					</div>

					<div class="subtotal ivaPrice" style="<?php echo $datosQuation['Quotation']['currency'] == "usd" ? "" : "" ?>">
						<?php if ($datosQuation["Quotation"]["show"] == 1): ?>
							<?php $precioCotizacionSubtotal = explode(",", $this->Utilities->total_item_products_quotations2($value['QuotationsProduct']['quantity'],$value['QuotationsProduct']['price'])) ?>
							$<?php echo $precioCotizacionSubtotal[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionSubtotal[1] ?></span>
							<?php echo $value['QuotationsProduct']['currency'] == "usd" ? " USD" : "" ?>
							<?php echo $datos["ProspectiveUser"]["country"] == "Colombia" && $value['QuotationsProduct']['iva'] == 1? "+IVA" : "" ?>
						<?php else: ?>
							<span style="height: 15px; color: #00ce08 !important;">
								&nbsp;&nbsp;&nbsp;
								-----------------
							</span>
						<?php endif ?>						

					</div>

					<?php if ($datosQuation["Quotation"]["show_iva"] == 1 && $datosQuation["Quotation"]["show"] == 1 && $value['QuotationsProduct']['iva'] == 1): ?>

						<div class=" ivaPrice" style="width: 205px; margin-right: 0px; text-align: center; padding-top: 5px;">
							Valores IVA incluido
						</div>
						<div class="precio ivaPrice" style="border-top: 1px solid #fff;">
								
								<b>
									$<?php echo number_format($value["QuotationsProduct"]["price"]*1.19,2,",",".") ?>
								</b>
								<?php if ($value["QuotationsProduct"]["currency"] == "usd"): ?>
									<span class="uppercase">
										<?php echo $value["QuotationsProduct"]["currency"] ?> 
									</span>
								<?php endif ?>		
						</div>
						<div class="subtotal ivaPrice" style="border-top: 1px solid #fff;">
								<b>
									$<?php echo number_format( $value["QuotationsProduct"]["price"]*$value['QuotationsProduct']['quantity'] *1.19,2,",",".") ?>																				
								</b>
								<?php if ($value["QuotationsProduct"]["currency"] == "usd"): ?>
									<span class="uppercase">
										<?php echo $value["QuotationsProduct"]["currency"] ?> 
									</span>
								<?php endif ?>
						</div>
																				
					<?php endif ?>	


					<?php
						if ($value["QuotationsProduct"]["currency"] == "cop") {
							$totalNoIva += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
							if ($value["QuotationsProduct"]["iva"] == 1) {
								$totalIvaCop += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
							}
						}else{
							$totalNoIvaUSD += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
							if ($value["QuotationsProduct"]["iva"] == 1) {
								$totalIvaUSD += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
							}
						}
					?>
				</div>
			</div>
		</div>
		<?php $garantia = $this->Utilities->getGarantiaProv($value["Product"]["garantia_id"]) ?>
		<?php if (!empty($garantia)): ?>
			<div class="col-md-12">
				<hr>
				<span style="color: red;">
					NOTA: <?php echo $garantia ?>
				</span>
			</div>
		<?php endif ?>
		<?php if (!empty($value["QuotationsProduct"]["note"])): ?>
			<div>
				<hr>
				<?php echo $value["QuotationsProduct"]["note"] ?>
			</div>
		<?php endif ?>

		

	<?php endforeach ?>
	<div class="notasImagenes">
		<br><br>
		<?php $total = number_format((float)$datosQuation['Quotation']['total'], 2); ?>
		<?php if ($datos["ProspectiveUser"]["from_bot"] == 1): ?>
			<br>
			<small class="notaimg" style="color:red">
				<b>NOTA: Los precios están sujetos a verificación previa de una asesor, ya que esta cotización fue generada mediante inteligencia artificial y el precio puede variar al momento de su generación.</b>
			</small>
			<br>
			<br>
		<?php endif ?>
		<small class="notaimg" style="color:red">
			Las imágenes de producto son de referencia para ambientación fotográfica y pueden variar con respecto a las versiones disponibles
		</small>
		<br>
		<small class="notaimg" style="color:red">
			Las unidades disponibles se encuentran sujetas a la venta previa.
		</small>		
		<br>
		<small class="notaimg" style="color:red">
			En el caso de que la cotización esté realizada en Dólares Americanos el valor en Pesos se liquida en el momento de realizar la factura con la TRM del dia de facturación
		</small>

		

			


		<br>
		<br>
		<br>		
			<?php if ($totalNoIva > 0): ?>
				<?php if ($datosQuation['Quotation']['total_visible'] == 1 && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?>

					<?php $totalNoIvaParts = explode(",", number_format($totalNoIva, "2",",",".")) ?>
					<?php $ivaParts = explode(",", number_format(($totalIvaCop * 0.19), "2",",",".")) ?>
					<?php $totalIvaParts = explode(",", number_format(($totalNoIva + ($totalIvaCop * 0.19) ), "2",",",".")) ?>
					

					<br>
					<br>
					<hr>

<!-- 					<div class="pricecop">
						<small>PRODUCTOS CON PRECIO DÓLARES</small>
						<h4 class="titlemoney">Este es el precio total de los productos cotizados en moneda USD </h4>
					</div>
					<div class=" text-center">

							<div class=" pricesline colorbg1">
								<p>
									<b>Subtotal </b>
									<span class="subalign">$<?php echo $totalNoIvaParts[0] ?></span>
									<span class="simpledecimal">,<?php echo $totalNoIvaParts[1] ?></span> 
								</p>
							</div>
							<div class=" pricesline colorbg2">		
								<p>
									<b>IVA 19%</b>
									<span class="subalign">$<?php echo $ivaParts[0] ?></span> 
									<span class="simpledecimal">,<?php echo $ivaParts[1] ?></span> 
								</p>
							</div>
							<div class=" pricesline colorbg3">	
								<p>
									<b>Total con IVA </b> 
									<span class="subalign">$<?php echo $totalIvaParts[0] ?></span> 
									<span class="simpledecimal">,<?php echo $totalIvaParts[1] ?></span> 
								</p>
							</div>	
					</div> -->
									<div class="" style="display: block;width: 100%; margin-bottom: 20px;">
										<div class="">
											<small style="padding: 5px 33px;background: #ffb100; color: white;margin-bottom: 5px;display: inline-block;    border-bottom: 5px solid #e27708;">PRODUCTOS CON PRECIO EN PESOS COLOMBIANOS</small>
											<h4 style="font-size: 18px; color: #004990;font-weight: bold;">Esta cotización incluye productos cotizados en PESOS COLOMBIANOS, este es el precio total únicamente para productos en moneda COP </h4>
										</div>
										<?php $totalNoIvaParts = explode(",", number_format($totalNoIva, "2",",",".")) ?>

										<?php if (!empty($datosQuation['Quotation']['descuento']) && $datosQuation['Quotation']['descuento'] >= 1) {
											$descuentoCop = $totalNoIva * ($datosQuation['Quotation']['descuento'] / 100);
											$ivaParts = explode(",", number_format(( ($totalIvaCop - $descuentoCop) * 0.19), "2",",","."));
											$totalIvaParts = explode(",", number_format(($totalNoIva - $descuentoCop + ( ($totalIvaCop - $descuentoCop) * 0.19) ), "2",",","."));
										}else{
											$ivaParts = explode(",", number_format(($totalIvaCop * 0.19), "2",",","."));
											$totalIvaParts = explode(",", number_format(($totalNoIva + ($totalIvaCop * 0.19) ), "2",",","."));
										} ?>

										<div class="contentprice">
											<div class="pricesline colorbg1">
												<b>Subtotal</b> $<?php echo $totalNoIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalNoIvaParts[1] ?></span>
											</div>
											<?php if (!empty($datosQuation['Quotation']['descuento']) && $datosQuation['Quotation']['descuento'] >= 1): ?>
												<div class="pricesline colorbg3">
													<?php $descuentoCopParts = explode(",", number_format($descuentoCop, "2",",",".")); ?>
													<b>Descuento <?php echo $datosQuation['Quotation']['descuento'] ?>%</b> -$<?php echo $descuentoCopParts[0] ?><span class="decimales simpledecimal">,<?php echo $descuentoCopParts[1] ?></span>
												</div>  
											<?php endif ?>  
											<div class="pricesline colorbg2"><b>IVA 19%</b>  $<?php echo $ivaParts[0] ?><span class="decimales simpledecimal">,<?php echo $ivaParts[1] ?></span> </div>
											<div class="pricesline colorbg3"><b>Total IVA incluido</b>  $<?php echo $totalIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalIvaParts[1] ?></span>
											 </div>  
										 </div>
									</div>



				<?php else: ?>
					<br>
					<p>
						<span class="subalign">
						<?php echo $this->Utilities->total_visible_quotations(number_format((float)$totalNoIva, 2, ",","."),$datosQuation['Quotation']['total_visible']); ?> <?php echo $datosQuation['Quotation']['total_visible'] == "1" ? " COP" : "" ?> </span>
					</p>
				<?php endif ?>
			<?php endif ?>
			<?php if ($totalNoIvaUSD > 0): ?>
				<?php if ($datosQuation['Quotation']['total_visible'] == 1 && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?>

					<?php $totalNoIvaParts = explode(",", number_format($totalNoIvaUSD, "2",",",".")) ?>
					
					<?php if (!empty($datosQuation['Quotation']['descuento']) && $datosQuation['Quotation']['descuento'] >= 1) {
						$descuentoUsd = $totalNoIvaUSD * ($datosQuation['Quotation']['descuento'] / 100);
						$ivaParts = explode(",", number_format(( ($totalIvaUSD - $descuentoUsd) * 0.19), "2",",","."));
						$totalIvaParts = explode(",", number_format(( ($totalNoIvaUSD - $descuentoUsd) + ( ($totalIvaUSD - $descuentoUsd) * 0.19) ), "2",",","."));
					}else{
						$ivaParts = explode(",", number_format(($totalIvaUSD * 0.19), "2",",","."));
						$totalIvaParts = explode(",", number_format(($totalNoIvaUSD + ($totalIvaUSD * 0.19) ), "2",",","."));
					} ?>

					<br>
					<br>
					<br>

									<div class="" style="display: block;width: 100%; margin-bottom: 20px;">
										<div class="" >
											<small style="padding: 5px 33px; background: #004990; color: white; margin-bottom: 5px;display: inline-block;    border-bottom: 5px solid #00c4ff;">PRODUCTOS CON PRECIO EN DÓLARES</small>
											<h4 style="font-size: 18px; color: #004990;font-weight: bold;">Esta cotización incluye productos cotizados en Dólares, este es el precio total únicamente para productos en moneda USD </h4>
											<p class="mt-2">Recuerda para solicitar productos por importación debes consignar mínimo en 50% del total</p>

										</div>

										<?php $totalNoIvaParts = explode(",", number_format($totalNoIvaUSD, "2",",",".")) ?>
										
										<?php if (!empty($datosQuation['Quotation']['descuento']) && $datosQuation['Quotation']['descuento'] >= 1) {
											$descuentoUsd = $totalNoIvaUSD * ($datosQuation['Quotation']['descuento'] / 100);
											$ivaParts = explode(",", number_format(( ($totalIvaUSD - $descuentoUsd) * 0.19), "2",",","."));
											$totalIvaParts = explode(",", number_format(( ($totalNoIvaUSD - $descuentoUsd) + ( ($totalIvaUSD - $descuentoUsd) * 0.19) ), "2",",","."));
										}else{
											$ivaParts = explode(",", number_format(($totalIvaUSD * 0.19), "2",",","."));
											$totalIvaParts = explode(",", number_format(($totalNoIvaUSD + ($totalIvaUSD * 0.19) ), "2",",","."));
										} ?>
										<div class="contentprice">
											<div class="pricesline colorbg1"><b>Subtotal </b>$<?php echo $totalNoIvaParts[0] ?><span class="decimales edecimal">,<?php echo $totalNoIvaParts[1] ?></span> USD </div>
											<?php if (!empty($datosQuation['Quotation']['descuento']) && $datosQuation['Quotation']['descuento'] >= 1): ?>
												<div class="pricesline colorbg3">
													<?php $descuentoUsdParts = explode(",", number_format($descuentoUsd, "2",",",".")); ?>
													<b>Descuento <?php echo $datosQuation['Quotation']['descuento'] ?>%</b> -$<?php echo $descuentoUsdParts[0] ?><span class="decimales simpledecimal">,<?php echo $descuentoUsdParts[1] ?></span> USD
												</div>  
											<?php endif ?>
											<div class="pricesline colorbg2"><b>IVA 19%</b>  $<?php echo $ivaParts[0] ?>
											<span class="decimales edecimal">,<?php echo $ivaParts[1] ?></span> USD</div> 
											<div class="pricesline colorbg3"><b>Total IVA incluido</b>  $<?php echo $totalIvaParts[0] ?><span class="decimales edecimal">,<?php echo $totalIvaParts[1] ?></span> USD
											</div>
										</div>
									</div>
				<?php else: ?>
					<br>
					<p>
						<span class="subalign">
							<?php echo $this->Utilities->total_visible_quotations(number_format((float)$totalNoIvaUSD, 2, ",","."),$datosQuation['Quotation']['total_visible']); ?> <?php echo $datosQuation['Quotation']['total_visible'] == "1" ? " USD" : "" ?> 
						</span>
					</p>
				<?php endif ?>
			<?php endif ?>
	</div>

	<div>
		<?php $i = 0; foreach ($datosProductos as $value): $i++; ?>
		<?php if ($i % 4 == 0): ?>
			<?php if (isset($datosProductos[($i + 1)])): ?>
				<br>
				<br>
				<br>
				<br>
				<p></p>
			<?php endif ?>
		<?php endif ?>

		<?php if (isset($suggested[$value["QuotationsProduct"]["product_id"]])): ?>
			<br>
			<br>
			<br>
			<?php $valuePSuggested = $suggested[$value["QuotationsProduct"]["product_id"]]; ?>
				
				<div class="border-0 border-blues card borderRadius30" style="border: 1px #ccc solid; border-radius: 10px 10px 10px 10px; padding: 0px 0px 20px 0px">
					<div class="divContenedor" style="width: 100%; display: block; margin: 0 auto; margin-bottom: 0px !important; height:90px" >
				  		<div class="imgProduct" style="width: 110px; margin-left: 15px">
							<?php $ruta = $this->Utilities->validate_image_products($valuePSuggested['img']); ?>
			                <?php echo $this->Html->image('products/'.$ruta,array('class' => 'images-product2', 'style' => 'display: block; margin: 0 auto; width: 100px !important; height 100px !important;padding-top: 5px ' )) ?>	
				  		</div>
				  		<div class="atributos" style="width:80%">	
				  								  			
				  			<h3 class="colorazul" style="text-transform: uppercase; text-align: center;">
								Repuestos y accesorios sugeridos <br> para el producto: <?php echo $valuePSuggested["part_number"] ?> - <?php echo $valuePSuggested["name"] ?>												
							</h3>
					  	</div>
				  	</div>
			  		<?php foreach ($valuePSuggested["suggested"] as $key => $suggested): ?>
			  			<div class="divContenedor2s" style="height: 120px; margin-top: 0px !important; width: 90%; display:block; margin: 0 auto; border: 1px #000 dotted; padding-left: 20px; padding-top: 10px;">
			  				<div class="imgProduct" style="width: 90px">
			  					<?php $ruta = $this->Utilities->validate_image_products($suggested["Product"]['img']); ?>
		                        <?php echo $this->Html->image('products/'.$ruta,array('class' => 'images-product2', 'style' => 'margin-lef:25px; float: left; width: 80px; height: 80px; padding-top: 15px' )) ?>
			  				</div>
				  			<div class="atributos" style="margin-top: 0px !important; padding-top: 0px !important;">
				  				<div class="media-body order-2 order-lg-1">
				  					<p class="txtPLineaNo text-left">
										<strong class="colorVerde">
											Referencia: <?php echo $suggested["Product"]["part_number"] ?> 
											<span class="colorazul" style="text-transform: uppercase;">
											<?php echo $this->Text->truncate(strip_tags($suggested["Product"]["name"]), 200,array('ellipsis' => '...','exact' => false)); ?>												
											</span>
										</strong>
										
									</p>
									
									<p class="descriptionview" style="word-wrap: break-word !important; width: 450px; margin-bottom: 0px !important">
										<?php echo $this->Text->truncate(strip_tags($suggested['Product']['description']), 300,array('ellipsis' => '...','exact' => false)); ?>
									</p>
									<div class="content-price" style="margin-top: -20px; height: 10px;">
										<div class="cantidad ivaPrice" style="width: 120px !important; height: 13px;">
											<b><?php echo $suggested["SuggestedProduct"]["quantity"] ?> Unidad(es)</b>
										</div>
										<div class="precio ivaPrice" style="height: 13px;">
											<?php if ($currencys[$valuePSuggested["id"]] == "usd" ): ?>
			                                		<?php echo number_format($suggested["SuggestedProduct"]["price_usd"]*$suggested["SuggestedProduct"]["quantity"]) ?> USD
			                                	<?php else: ?>
			                                		<?php echo number_format( round(($suggested["SuggestedProduct"]["price_usd"]*$suggested["SuggestedProduct"]["quantity"]) * $trm_suggest) ) ?> COP
			                                	<?php endif ?>
													
													+ IVA
										</div>
									</div>
			                    </div>
				  			</div>
				  		</div>
				  	<?php endforeach ?>

				</div>
			<br><br>
			<br><br>
			<br><br>			
			<br><br>			
			<br><br>			

		<?php endif ?>
		<?php endforeach ?>
	</div>
		
	
	<hr>
	
	<?php if (!in_array($datosQuation['Quotation']['notes_description'],$arrayVarcharPermitido)) : ?>
		<p><?php echo $datosQuation['Quotation']['notes_description'] ?></p>
		<hr>
	<?php endif ?>
	<?php if (!in_array($datosQuation['Quotation']['conditions'],$arrayVarcharPermitido)) : ?>
		<div class="conditionsview condiciones_negociacion">
			<h2>CONDICIONES DE LA NEGOCIACIÓN:</h2>
			<p class="-"><?php echo $datosQuation['Quotation']['conditions'] ?></p>
		</div>
		<?php if (is_null($datosQuation['Quotation']['garantia'])): ?>
			<br>
			<br>
		<?php endif ?>		
	<?php endif ?>
	<?php if (!is_null($datosQuation['Quotation']['garantia']) && $datosQuation['Quotation']['garantia'] != false): ?>
		<div class="conditionsview condiciones_negociacion mb-0">
			<h2>Garantía general</h2>
			<div id="garantiaGeneral"><?php echo $datosQuation['Quotation']['garantia'] ?></div>.
		</div>
		<br>
		<br>
	<?php endif ?>
	<b>Cordial saludo,</b>
	<div class="datasesorview">
		<?php if (!is_null($datosUsuario["User"]["img_signature"])): ?>
			<?php echo $this->Html->image('/img/users/signatures/'.$datosUsuario["User"]["img_signature"],array('class' => 'images-footer')) ?>
		<?php else: ?>
			<b class="firmaUsuario"><?php echo mb_strtoupper($datosUsuario['User']['name']) ?></b><br>
			<b class="firmaUsuario"><?php echo $datosUsuario['User']['email'] == "ventasbogota@almacendelpintor.com" || $datosUsuario['User']['email'] == "jotsuar@gmail.com" ? "Director Comercial" : $datosUsuario['User']['role'] ?></b><br>
			<?php echo 'CEL: '.$datosUsuario['User']['cell_phone'] ?><br>
			<?php echo 'TEL: '.$datosUsuario['User']['telephone'] ?><br>
			<?php echo $datosUsuario['User']['email'] ?></p>
		<?php endif; ?>
	</div> 
	<br>
	<?php echo $this->Html->image('/img/header/miniatura/'.$datosHeaders['Header']['img_small'],array('class' => 'images-footer')) ?>
</div>