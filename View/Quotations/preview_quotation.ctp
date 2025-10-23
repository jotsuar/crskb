<?php $arrayVarcharPermitido = array('','<br>'); ?>
<div class="container">
	<div class="col-md-12" id="cotizacionview">

		<div class="blockwhite shadowstrong">
			<?php if ($datosHeaders['Header']['id'] == 1) { ?>
				<img src="<?php echo $this->Html->url('/img/header/header/'.$datosHeaders['Header']['img_big']) ?>" class="img-fluid normal">
			<?php } else { ?>
				<img src="<?php echo $this->Html->url('/img/header/header/'.$datosHeaders['Header']['img_big']) ?>" class="img-fluid normal">
			<?php } ?>
				
			<div class="contentmargin">
				<h3 class="colorrojo"><?php echo $codigo ?>
					<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
						/ <span class="numberorderst"><?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?></span>
					<?php endif ?>
				</h3>
				<h3 class="colorgris">
					<?php echo $this->Utilities->explode_city($datosUsuario['User']['city'],$datos["ProspectiveUser"]["country"]) .', '.$this->Utilities->date_castellano(date('Y-m-d')); ?>
				</h3>
				<br>
				<p class="colorgris">Sr(a):</p>
				<h3 class="colorgris"><?php echo mb_strtoupper($this->Utilities->name_prospective($datos['ProspectiveUser']['id'])); ?></h3>
				<?php if ($datos['ProspectiveUser']['contacs_users_id'] > 0){ ?>
					<h3 class="colorgris"><?php echo $this->Utilities->name_bussines($datosC['ContacsUser']['clients_legals_id']); ?></h3>
					<?php if ($datosC['ContacsUser']['telephone'] != ''): ?>
						<h3 class="colorgris"><?php echo $datosC['ContacsUser']['telephone'] ?></h3>
					<?php endif ?>
					<?php if ($datosC['ContacsUser']['cell_phone'] != ''): ?>
						<h3 class="colorgris"><?php echo $datosC['ContacsUser']['cell_phone'] ?></h3>
					<?php endif ?>
					<h3 class="colorgris"><?php echo $datosC['ContacsUser']['email'] ?></h3>
					<h3 class="colorgris"><?php echo $datosC['ContacsUser']['city'] ?></h3>
				<?php } else { ?>
					<?php if ($datosC['ClientsNatural']['telephone'] != ''): ?>
						<h3 class="colorgris"><?php echo $datosC['ClientsNatural']['telephone'] ?></h3>
					<?php endif ?>
					<?php if ($datosC['ClientsNatural']['cell_phone'] != ''): ?>
						<h3 class="colorgris"><?php echo $datosC['ClientsNatural']['cell_phone'] ?></h3>
					<?php endif ?>
					<h3 class="colorgris"><?php echo $datosC['ClientsNatural']['email'] ?></h3>
					<h3 class="colorgris"><?php echo $datosC['ClientsNatural']['city']?></h3>
				<?php } ?>
				<br>
				<h3 class="colorazul"><b>Asunto: </b><?php echo $asunto ?></h3>
				<br>

				<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
					<div class="producstorderst">
						<table cellpadding="0" cellspacing="0" class="table-striped table-bordered tableproductsst" >
							<tr class="text-center">
								<th colspan="6">PRODUCTO DEL CLIENTE - ORDEN DE SERVICIO <?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?></b></th>
							</tr>
							<tr class="titles-tablest">
								<td>Equipo</td>
								<td>Número de parte</td>
								<td>Serie</td>
								<td>Serial</td>
								<td>Marca</td>s
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
					</div>
					<br>
				<?php endif ?>
				<div class="contentgrafico">
				<?php if (!in_array($notes,$arrayVarcharPermitido)) : ?>
					<hr>
					<p class="controllerimg"><?php echo $notes ?></p>
					<hr>
				<?php endif ?>
				</div>
				<div class="productoscotizados">
					<br>
					<br>
					<?php $totalNoIva = 0; ?>
					<?php $totalNoIvaUSD = 0; ?>
					<?php $totalIvaUSD = 0; ?>
					<?php $totalIvaCop = 0; ?>
					<?php foreach ($datosProductos as $value): ?>
						<br>
						<div class="row">
							<div class="col-md-12">
								<div class="row mbrowproduct">
									<div class="col-md-3">
										<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']); ?>
										<div class="imgproductview" style="background-image: url(<?php echo $this->Html->url('/img/products/'.$ruta)?>);">
										</div>
									</div>
									<div class="col-md-9">
										<div class="dataproductview h-150">
											<div class="h-150 justify-content-center">
												<div>
													<strong class="text-success">Referencia: <?php echo $value['Product']['part_number'] ?> / Marca: <?php echo $value['Product']['brand'] ?></strong>
													<h3 class=""><?php echo $this->Text->truncate(strip_tags($value['Product']['name']), 70,array('ellipsis' => '...','exact' => false)); ?></h3>
													<p class="descriptionview">
														<?php echo $this->Text->truncate(strip_tags($value['Product']['description']), 500,array('ellipsis' => '...','exact' => false)); ?>
													</p>

													<?php if ($value['Product']['link'] != "" ) { ?> 
														<span>
															Más información:
															<a target="_blank" href="<?php echo $this->Utilities->data_null($value['Product']['link']) ?>">Clic aquí para ver abrir el enlace</a>
														</span>
													<?php } ?>

													<div class="row nomr">
														<div class="col-md-2 pdnone">
															<p class="cantidadquote" style="height: 40px">
																Cantidad:  <b><?php echo $value['QuotationsProduct']['quantity'] ?></b>
															</p>
														</div>	
														<div class="col-md-4 pdnone">
															<p class="priceview" style="height: 40px">
																Entrega:  <b><?php echo $value['QuotationsProduct']['delivery'] ?></b>
															</p>
														</div>

														<div class="col-md-3 pdnone">
															<p class="entregat" style="height: 40px">

																<?php $precioCotizacionUnidad = explode(",", number_format((float)$value['QuotationsProduct']['price'], "2",",",".")) ?><?php echo strtolower($value['QuotationsProduct']['currency']) == "usd" ? " USD" : "" ?> 
																<b>
																$<?php echo $precioCotizacionUnidad[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionUnidad[1] ?></span></b> 

															</p>
														</div>

														<div class="col-md-3 pdnone">
															<p class="subtotalquote" style="height: 40px">

																<?php $precioCotizacionSubtotal = explode(",", $this->Utilities->total_item_products_quotations2($value['QuotationsProduct']['quantity'],$value['QuotationsProduct']['price'])) ?>
																<?php echo strtolower($value['QuotationsProduct']['currency']) == "usd" ? " USD" : "" ?>
																 <b> 
																$<?php echo $precioCotizacionSubtotal[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionSubtotal[1] ?></span> <?php echo $datos["ProspectiveUser"]["country"] == "Colombia" && $value["QuotationsProduct"]["iva"] == 1 ? "+IVA" : "" ?>
																</b> 																
															</p>
														</div>	
														<?php 
															if($value["QuotationsProduct"]["currency"] == "COP"){
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
														<?php if ($radio_option_iva == 1): ?>

															<div class="col-md-12 pdnone">	
																<div class="row mx-0">
																	<div class="col-md-6 pt-2 text-center uppercase">
																		<b>
																			Valores IVA incluido
																		</b>
																	</div>
																	<div class="col-md-3 pdnone">
																		<p class="entregat border-top" style="margin-bottom: 35px !important; margin-top: 0px !important">
																			<?php if ($value["QuotationsProduct"]["currency"] == "USD"): ?>
																				<span>
																					<?php echo $value["QuotationsProduct"]["currency"] ?> 
																				</span>
																			<?php endif ?>
																			<b>
																				$<?php echo number_format($value["QuotationsProduct"]["price"]*1.19,2,",",".") ?>
																			</b>																			
																		</p>
																	</div>
																	<div class="col-md-3 pdnone">
																		<p class="subtotalquote border-top" style="margin-bottom: 35px !important; margin-top: 0px !important">
																			<?php if ($value["QuotationsProduct"]["currency"] == "USD"): ?>
																				<span>
																					<?php echo $value["QuotationsProduct"]["currency"] ?> 
																				</span>
																			<?php endif ?>
																			<b>
																				$<?php echo number_format( $value["QuotationsProduct"]["price"]*$value['QuotationsProduct']['quantity'] *1.19,2,",",".") ?>																				
																			</b>
																			 																			
																		</p>
																	</div>
																</div>
															</div>
																													
														<?php endif ?>														
													</div>	
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
							<?php $garantia = $this->Utilities->getGarantiaProv($value["Product"]["brand_id"]) ?>
							<?php if (!empty($garantia)): ?>
								<div class="col-md-12">
									<hr>
									<span class="text-danger">
										NOTA: <?php echo $garantia ?>
									</span>
								</div>
							<?php endif ?>
							<?php if (!empty($value['QuotationsProduct']['note'])): ?>
								<div class="col-md-12">
									<hr>
									<?php echo $value['QuotationsProduct']['note'] ?>
								</div>
							<?php endif ?>
						</div>
						<hr>
					<?php endforeach ?>
					<div class="notasImagenes">
						<small class="notaimg">
							Las imágenes de producto son de referencia para ambientación fotográfica y pueden variar con respecto a las versiones disponibles
						</small>
						<small class="notaimg">
							Las unidades disponibles se encuentran sujetas a la venta previa.
						</small>
						<br>
						<br>
						<small class="notaimg">
							En el caso de que la cotización esté realizada en Dólares americanos el valor en Pesos se liquida en el momento de realizar la factura con la TRM del dia de facturación
						</small>						
						<br>
						<?php if ($totalNoIva > 0): ?>
							
							<h4 class="totalcotiza mt-5 ">
								<?php if ($radio_option == 1 && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?> 
							</h4>
								<div class="divprice mt-5">
										<img src="https://crm.kebco.co/img/inventario.png" style="
										    float: left;
										    width: 178px;
										">										
										<div class="pricecop2">
											<small>PRODUCTOS CON PRECIO EN PESOS COLOMBIANOS</small>
											<h4 class="titlemoney">Esta cotización incluye productos cotizados en PESOS COLOMBIANOS, este es el precio total únicamente para productos en moneda COP </h4>
										</div>
										<?php $totalNoIvaParts = explode(",", number_format($totalNoIva, "2",",",".")) ?>

										<?php if (!empty($descuento) && $descuento >= 1) {
											$descuentoCop = $totalNoIva * ($descuento / 100);
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
											<?php if (!empty($descuento) && $descuento >= 1): ?>
												<div class="pricesline colorbg3">
													<?php $descuentoCopParts = explode(",", number_format($descuentoCop, "2",",",".")); ?>
													<b>Descuento <?php echo $descuento ?>%</b> -$<?php echo $descuentoCopParts[0] ?><span class="decimales simpledecimal">,<?php echo $descuentoCopParts[1] ?></span>
												</div>  
											<?php endif ?>
											<div class="pricesline colorbg2"><b>IVA 19%</b>  $<?php echo $ivaParts[0] ?><span class="decimales simpledecimal">,<?php echo $ivaParts[1] ?></span> </div>
											<div class="pricesline colorbg3"><b>Total IVA incluido</b>  $<?php echo $totalIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalIvaParts[1] ?></span>
											 </div>  
										 </div>
									</div>

							<?php else: ?>
								<?php echo $this->Utilities->total_visible_quotations(number_format((float)$totalNoIva, 2, ",","."),$radio_option); ?> <?php echo $radio_option == 1 ? "COP" : "" ?>
							<?php endif ?>
								
						<?php endif ?>
							<br>
							<?php if ($totalNoIvaUSD > 0): ?>
								<?php if ($radio_option == 1 && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?> 
									<div class="divprice">
										<img src="https://crm.kebco.co/img/importacion.png" style="
										    float: left;
										    width: 178px;
										">
										<div class="pricecop">
											<small>PRODUCTOS CON PRECIO EN DÓLARES</small>
											<h4 class="titlemoney">Esta cotización incluye productos cotizados en Dólares, este es el precio total únicamente para productos en moneda USD </h4>
											<p class="mt-2">Recuerda para solicitar productos por importación debes consignar mínimo en 50% del total</p>

										</div>

										<?php $totalNoIvaParts = explode(",", number_format($totalNoIvaUSD, "2",",",".")) ?>

										<?php if (!empty($descuento) && $descuento >= 1) {
											$descuentoUsd = $totalNoIvaUSD * ($descuento / 100);
											$ivaParts = explode(",", number_format(( ($totalIvaUSD - $descuentoUsd) * 0.19), "2",",","."));
											$totalIvaParts = explode(",", number_format(( ($totalNoIvaUSD - $descuentoUsd) + ( ($totalIvaUSD - $descuentoUsd) * 0.19) ), "2",",","."));
										}else{
											$ivaParts = explode(",", number_format(($totalIvaUSD * 0.19), "2",",","."));
											$totalIvaParts = explode(",", number_format(($totalNoIvaUSD + ($totalIvaUSD * 0.19) ), "2",",","."));
										} ?>

										<div class="contentprice">
											<div class="pricesline colorbg1"><b>Subtotal </b>$<?php echo $totalNoIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalNoIvaParts[1] ?></span> USD </div>
											<?php if (!empty($descuento) && $descuento >= 1): ?>
												<div class="pricesline colorbg3">
													<?php $descuentoUsdParts = explode(",", number_format($descuentoUsd, "2",",",".")); ?>
													<b>Descuento <?php echo $descuento ?>%</b> -$<?php echo $descuentoUsdParts[0] ?><span class="decimales simpledecimal">,<?php echo $descuentoUsdParts[1] ?></span> USD
												</div>  
											<?php endif ?>
											<div class="pricesline colorbg2"><b>IVA 19%</b>  $<?php echo $ivaParts[0] ?><span class="decimales simpledecimal">,<?php echo $ivaParts[1] ?></span> USD</div> 
											<div class="pricesline colorbg3"><b>Total IVA incluido</b>  $<?php echo $totalIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalIvaParts[1] ?></span> USD
											</div>
										</div>
									</div>

								<?php else: ?>
									<?php echo $this->Utilities->total_visible_quotations(number_format((float)$totalNoIvaUSD, 2, ",","."),$radio_option); ?> <?php echo $radio_option == 1 ? " USD" : "" ?>
								<?php endif ?>
								
						<?php endif ?>
							</div>

						
					</div>
					<br>
					<hr>
				</div>


				<?php if (!in_array($notes_descriptiva,$arrayVarcharPermitido)) : ?>
					<p><?php echo $notes_descriptiva ?></p>
					<hr>
				<?php endif ?>
				

				<?php if ($conditions != ''): ?>
					<div class="conditionsview condiciones_negociacion mb-0">
						<p><?php echo $conditions ?></p>.
					</div>
				<?php endif ?>
				<?php if (!is_null($garantiaGeneral) && $garantiaGeneral != false): ?>
					<div class="conditionsview condiciones_negociacion mb-0">
						<h2>Garantía general</h2>
						<div id="garantiaGeneral"><?php echo $garantiaGeneral ?></div>.
					</div>
				<?php endif ?>

				<b>Cordial saludo,</b>
				<div class="datasesorview">
					<?php if (!is_null($datosUsuario["User"]["img_signature"]) && $datos["ProspectiveUser"]["country"] == "Colombia" ): ?>
						<img src="<?php echo $this->Html->url('/img/users/signatures/'.$datosUsuario["User"]["img_signature"]) ?>" class="img-fluid w-100">
					<?php else: ?>
						<b class="firmaUsuario"><?php echo mb_strtoupper($datosUsuario['User']['name']) ?></b><br>
						<b class="firmaUsuario">
							<?php 
								if($datosUsuario['User']['email'] == "ventasbogota@almacendelpintor.com"){
									echo "Director Comercial";
								}elseif($datosUsuario['User']['email'] == "ventas3@kebco.co"){
									echo "Asesor Comercial";
								}else{
									echo $datosUsuario['User']['role'];
								}
							?>								
						</b><br>
						<?php if ($datos["ProspectiveUser"]["country"] == "Colombia"): ?>
							<?php echo 'CEL: '.$datosUsuario['User']['cell_phone'] ?><br>
							<?php echo 'TEL: '.$datosUsuario['User']['telephone'] ?><br>
						<?php endif ?>
						<?php echo $datosUsuario['User']['email'] ?></p>
					<?php endif ?>
				</div> 
			</div>

			<img src="<?php echo $this->Html->url('/img/header/miniatura/'.$datosHeaders['Header']['img_small']) ?>" class="img-fluid">
<!-- 			<footer>
				<div class="siguenos-redessociales">
					<div class="basegray"></div>
					<div class="contentcenter">
						<span>SÍGUENOS EN REDES SOCIALES</span>
						<ul class="list-inline dpinline">
							<li class="list-inline-item">
								<i class="fa fa-facebook"></i>
								<span>almacendelpintor</span>
							</li>
							<li class="list-inline-item">
								<i class="fa fa-instagram"></i>
								<span>almacendelpintor</span>
							</li>
							<li class="list-inline-item">
								<i class="fa fa-youtube-play"></i>
								<span>almacendelpintor</span>
							</li>
							<li class="list-inline-item">
								<i class="fa fa-whatsapp"></i>
								<span>301 448 5566</span>
							</li>															
						</ul>
					</div>
				</div>
				<div class="col-md-12">
					<div class="row datasedes">
						<div class="col-md-4 text-center">
							<p><b>KEBCO S.A.S. NIT: 900412283-0</b></p>
							<p>ventas@almacendelpintor.com</p>
						</div>
						<div class="col-md-4 text-center">
							<p><b>OFICINA PRINCIPAL MEDELLÍN</b></p>
							<p>Calle 10 # 52A - 18 Int. 104 Centro Integral la 10</p>
							<p>Teléfono (4) 448 5566</p>
						</div>
						<div class="col-md-4 text-center">
							<p><b>OFICINA BOGOTÁ</b></p>
							<p>Av. Calle 26 # 85D - 55 LE 25 C.E. Dorado Plaza</p>
							<p>Teléfono (4) 448 5566</p>
						</div>										
					</div>				
				</div>				
			</footer> -->
		</div>
	</div>	
</div>