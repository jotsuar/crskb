<?php $arrayVarcharPermitido = array('','<br>'); ?>
<?php if (AuthComponent::user('id') && !isset($this->request->data["modal"])): ?>	
	<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-azulclaro big">
		     <i class="fa fa-1x flaticon-growth"></i>
		    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
		</div>
	</div>
<?php endif ?>
<div class="container p-0 containerCRM">
	<div class="col-md-12 p-0" id="cotizacionview">

		<?php if (AuthComponent::user('id')) { ?>
	  		<?php if ($datos['ProspectiveUser']['type'] > 0){ ?>
	  			<a href="<?php echo $this->Html->url(array('controller'=> 'TechnicalServices', 'action' => 'flujos?q='.$datosQuation['Quotation']['prospective_users_id'])) ?>" id="idflujo">
	    			ID FLUJO <?php echo $datosQuation['Quotation']['prospective_users_id'] ?>
	    		</a>
	  		<?php } else { ?>
	  			<a href="<?php echo $this->Html->url(array('controller'=> 'ProspectiveUsers', 'action' => 'index?q='.$datosQuation['Quotation']['prospective_users_id'])) ?>" id="idflujo">
	    			ID FLUJO <?php echo $datosQuation['Quotation']['prospective_users_id'] ?>
	    		</a>
	  		<?php } ?>
	  		<a href="<?php echo $this->Html->url(array('controller'=> 'ProspectiveUsers', 'action' => 'duplicate',$datosQuation['Quotation']['prospective_users_id'],$datosQuation["Quotation"]["id"],$datosFlowStage["FlowStage"]["id"] )) ?>" class="btn btn-info duplicate">
	    			Crear flujo a partir de esta cotización
	    		</a>
		<?php } ?>
		<?php if (isset($reenviar)): ?> 
			<a class="btn btn-warning ml-lg-2 mr-lg-2 p-2 reenviarCot" data-uid="<?php echo $flujoDataId ?>" data-flowstages="<?php echo $flowData; ?>" data-toggle="tooltip" data-placement="right" title="Reenviar cotización de nuevo al correo electrónico">
				Reenviar cotización &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
			</a>
		<?php endif ?>

		

		<?php if (!AuthComponent::user("id") && isset($edata) && $datos["ProspectiveUser"]["state_flow"] <= 3): ?>
			<?php echo $this->Html->link("Enviar comentario", array('action' => 'comentario', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf commentQuotation', "data-toggle"=>"modal","data-target"=>"#modalComentario")); ?>

			<?php echo $this->Html->link("Reenviar cotización", array('action' => 'comentario', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf replayQuotation', "data-toggle"=>"modal","data-target"=>"#modalReenvio")); ?>

			<?php echo $this->Html->link("Aprobar cotización", array('action' => 'aprovee', $this->Utilities->encryptString($datosQuation['Quotation']['id']) ),array('class' => 'btnDownloadPdf aproveeQuotation', "data-toggle"=>"modal","data-target"=>"#modalAprove")); ?> 
		<?php endif ?>
		<?php echo $this->Html->link("Exportar a PDF", array('action' => 'view', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf','target'=>'_blank')); ?>
		<?php if (AuthComponent::user("id")): ?>
			<div class="form-check form-check-inline float-right classViewMargen">
				
				<label for="viewMargen" class="form-check-label mr-3">
					Ver margen de ganancia
				</label>
				<input type="checkbox" data-toggle="toggle" name="viewMargen" value="1" id="viewMargen" data-on="SI" data-off="NO" data-onstyle="success" data-offstyle="danger" <?php echo AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Asesor Comercial" ? "checked" : "" ?>>
			</div>
		<?php endif ?>

		
		
		<div class="blockwhite">
			<?php if (AuthComponent::user('id') && !isset($this->request->data["modal"])): ?>	
				<div class="col-md-12">
					<a href="/" data-id="<?php echo $this->Utilities->encryptString($datosQuation["Quotation"]["id"]); ?>" class="btb btn-primary float-right mt-2 mb-2 datosProforma">
						Generar Proforma <i class="fa fa-file vtc"></i>
					</a>
				</div>
			<?php endif ?>
			<?php if ($datosQuation['Quotation']['header_id'] == 0) { ?>
				<img src="<?php echo $this->Html->url('/img/header/header/'.$datosHeaders['Header']['img_big']) ?>" class="img-fluid normal">
			<?php } else { ?>
				<img src="<?php echo $this->Html->url('/img/header/header/'.$datosHeaders['Header']['img_big']) ?>" class="img-fluid normal">
			<?php } ?>
				
			<div class="contentmargin">
				<h3 class="colorrojo"><?php echo $datosQuation['Quotation']['codigo'] ?>
					<?php if ($datos['ProspectiveUser']['type'] > 0): ?>
						/ <span class="numberorderst"><?php echo $this->Utilities->consult_cod_service($datos['ProspectiveUser']['type']) ?></span>
					<?php endif ?>
				</h3>
				<h3>
					<?php echo $this->Utilities->explode_city($datosUsuario['User']['city'], $datos["ProspectiveUser"]["country"]) .', '.$this->Utilities->date_castellano($datosQuation['Quotation']['created']); ?>
				</h3>
				<br>
				<p>Sr(a):</p>
				<h3><?php echo mb_strtoupper($this->Utilities->name_prospective($datos['ProspectiveUser']['id'])); ?></h3>
				<?php if ($datos['ProspectiveUser']['contacs_users_id'] > 0){ ?>
					<h3><?php echo $this->Utilities->name_bussines($datosC['ContacsUser']['clients_legals_id']); ?></h3>
					<?php if ($datosC['ContacsUser']['telephone'] != ''): ?>
						<h3><?php echo $datosC['ContacsUser']['telephone'] ?></h3>
					<?php endif ?>
					<?php if ($datosC['ContacsUser']['cell_phone'] != ''): ?>
						<h3><?php echo $datosC['ContacsUser']['cell_phone'] ?></h3>
					<?php endif ?>
					<h3><?php echo $datosC['ContacsUser']['email'] ?></h3>
					<h3><?php echo $datosC['ContacsUser']['city'] ?></h3>
				<?php } else { ?>
					<?php if ($datosC['ClientsNatural']['telephone'] != ''): ?>
						<h3><?php echo $datosC['ClientsNatural']['telephone'] ?></h3>
					<?php endif ?>
					<?php if ($datosC['ClientsNatural']['cell_phone'] != ''): ?>
						<h3><?php echo $datosC['ClientsNatural']['cell_phone'] ?></h3>
					<?php endif ?>
					<h3><?php echo $datosC['ClientsNatural']['email'] ?></h3>
					<h3><?php echo $datosC['ClientsNatural']['city'] ?></h3>
				<?php } ?>
				<br>
				<h3 class="colorazul"><b>Asunto: </b><?php echo $datosQuation['Quotation']['name'] ?></h3>
				<br>

				<?php if (AuthComponent::user('id') &&  !empty($datosQuation["Quotation"]["customer_note"])): ?>	
				<div class="col-md-12 p-0">
					<h3 class="text-center text-info">Texto que fue enviado al cliente en el correo</h3>

					<div class="border border-blue pl-2 pt-2">
						<?php echo $datosQuation["Quotation"]["customer_note"] ?>
					</div>

				</div>
				<br>
			<?php endif ?>

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
					</div>
					<br>
				<?php endif ?>
				<div class="contentgrafico">
				<?php if (!in_array($datosQuation['Quotation']['notes'],$arrayVarcharPermitido)) : ?>
					<hr>
					<p><?php echo $datosQuation['Quotation']['notes'] ?></p>
					<hr>
				<?php endif ?>
				</div>
				<div class="productoscotizados">
					<br>
					<br>
					<?php $usdData = array(); $copData=array(); ?>
					<?php $totalNoIva = 0; ?>
					<?php $totalNoIvaUSD = 0; ?>
					<?php foreach ($datosProductos as $value): ?>
						<?php
							if(isset($this->request->data["modal"]) && $value["QuotationsProduct"]["change"] == 1){
								$value["QuotationsProduct"]["currency"] = "usd";
								$value["QuotationsProduct"]["price"]	= $value["QuotationsProduct"]["price"] / $value["QuotationsProduct"]["trm_change"]; 
							}
						 ?>
						<?php 
							if($value["QuotationsProduct"]["currency"] == "usd"){
								$usdData[] = $value["QuotationsProduct"]["id"];
							}else{
								$copData[] = $value["QuotationsProduct"]["id"];								
							}
						 ?>
						<br>
						<div class="row">
							<div class="col-md-12">
								<div class="row mbrowproduct">
									<div class="col-md-3">
										<?php 
											$imgsUrls = [];

											if (!isset($this->request->data["modal"])) {											

												if(!is_null($value['Product']['img'])){
													$ruta = $this->Utilities->validate_image_products($value['Product']['img']);
													$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
												}
												if(!is_null($value['Product']['img2'])){
													$ruta = $this->Utilities->validate_image_products($value['Product']['img2']);
													$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
												}
												if(!is_null($value['Product']['img3'])){
													$ruta = $this->Utilities->validate_image_products($value['Product']['img3']);
													$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
												}
												if(!is_null($value['Product']['img4'])){
													$ruta = $this->Utilities->validate_image_products($value['Product']['img4']);
													$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
												}
												if(!is_null($value['Product']['img5'])){
													$ruta = $this->Utilities->validate_image_products($value['Product']['img5']);
													$imgsUrls[] = $this->Html->url('/img/products/'.$ruta);
												}
											}
										 ?>
										<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']); ?>
										<div class="imgproductview" style="background-image: url(<?php echo $this->Html->url('/img/products/'.$ruta)?>); cursor: pointer;">
											<div class="buttonsMore float-md-right">												
												<i class="fa fa-2x fa-image"></i>
												<i class="fa fa-1x fa-plus-circle"></i>
											</div>
										</div>
										<?php if (!empty($imgsUrls)): ?>											
											<div class="gallery" style="display:none">
												<?php foreach ($imgsUrls as $keyUrl => $valueUrl): ?>
													<a id="<?php echo $keyUrl == 0 ? "firstImg" : "" ?>" href="<?php echo $valueUrl ?>" alt="hola">
														<img src="<?php echo $valueUrl ?>" alt="<?php echo $value["Product"]["name"] ?>">
													</a>
												<?php endforeach ?>
												<?php if (!empty($value["Product"]["url_video"])): ?>
													<a href="<?php echo $value["Product"]["url_video"] ?>"></a>
												<?php endif ?>
											</div>
										<?php endif ?>
									</div>
									<div class="col-md-9">
										<div class="dataproductview h-150">
											<div class="h-150 justify-content-center">
												<div>
													<?php if (AuthComponent::user("role") == "Gerente General" && AuthComponent::user("email") == "jotsuar@gmail.com"): ?>
														<a href="<?php echo $this->Html->url(["controller" => "quotations_products","action" => "edit", $value["QuotationsProduct"]["id"], $this->Utilities->encryptString($datosQuation["Quotation"]["id"])] ) ?>" class="btn btn-info">
															<i class="fa fa-pencil"></i>
														</a>
													<?php endif ?>
													<strong class="text-success">Referencia: <?php echo $value['Product']['part_number'] ?> / Marca: <?php echo $value["Product"]["type"] == 0 ? "Kebco" : $value['Product']['brand'] ?></strong>
													<h3 class=""><?php echo $this->Text->truncate(strip_tags($value['Product']['name']), 70,array('ellipsis' => '...','exact' => false)); ?></h3>
													<p class="descriptionview">
														<?php echo $this->Text->truncate(strip_tags($value['Product']['description']), 500,array('ellipsis' => '...','exact' => false)); ?>
													</p>

													<?php if ($value['Product']['link'] != "" ) { ?> 
														<p>
															Más información:
															<a target="_blank" href="<?php echo $this->Utilities->data_null($value['Product']['link']) ?>">Clic aquí para ver abrir el enlace</a>
														</p>														
													<?php } ?>
													<?php if ($value['QuotationsProduct']['currency'] == "usd" && $value['QuotationsProduct']['currency'] !== "Inmediato" ) {?> 
														<div class="disponibilidadproducto ">
															<span class="avisocotizs"><img src="https://crm.kebco.co/img/sello0.png"></span>
														</div>
													<?php } ?>
													<div class="row nomr avisocotiz">
														<div class="col-md-2 pdnone">
															<p class="cantidadquote" style="margin-bottom: 0px !important">
																Cant.  <b><?php echo $value['QuotationsProduct']['quantity'] ?></b>
															</p>
														</div>	
														<div class="col-md-4 pdnone">
															<p class="priceview" style="margin-bottom: 0px !important">
																Entrega:  <b><?php echo $value['QuotationsProduct']['delivery'] ?></b>
															</p>
														</div>

														<div class="col-md-3 pdnone">
															<p class="entregat" style="margin-bottom: 0px !important">
																<?php $precioCotizacionUnidad = explode(",", number_format((float)$value['QuotationsProduct']['price'], "2",",",".")) ?>
																<b>
																$<?php echo $precioCotizacionUnidad[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionUnidad[1] ?></span></b> 
																<?php echo $value['QuotationsProduct']['currency'] == "usd" ? " USD" : "" ?>
															</p>
														</div>

														<div class="col-md-3 pdnone">
															<p class="subtotalquote" style="margin-bottom: 0px !important">
																<?php $precioCotizacionSubtotal = explode(",", $this->Utilities->total_item_products_quotations2($value['QuotationsProduct']['quantity'],$value['QuotationsProduct']['price'])) ?>
																 <b> 
																$<?php echo $precioCotizacionSubtotal[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionSubtotal[1] ?></span>
																</b> 
																<?php echo $value['QuotationsProduct']['currency'] == "usd" ? " USD" : "" ?>
																<?php echo $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1? "+IVA" : "" ?>
															</p>
															<?php 

																if ($value["QuotationsProduct"]["currency"] == "cop") {
																	$totalNoIva += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
																}else{
																	$totalNoIvaUSD += ( $value['QuotationsProduct']['quantity'] *$value['QuotationsProduct']['price'] );
																}

															 ?>
														</div>	
														<?php if ($value["QuotationsProduct"]["change"] == 1 && AuthComponent::user("id")): ?>
															<div class="col-md-12 pdnone">	
																<div class="row mx-0">
																	<div class="col-md-6 pt-2 text-center uppercase">
																		<b>
																			Valores originalmente cotizados
																		</b>
																	</div>
																	<div class="col-md-3 pdnone">
																		<p class="entregat border-top" style="margin-bottom: 35px !important; margin-top: 0px !important">
																			<?php echo number_format($value["QuotationsProduct"]["original_price"],2,",",".") ?>
																			<span>
																				USD 
																			</span> 																			
																		</p>
																	</div>
																	<div class="col-md-3 pdnone">
																		<p class="subtotalquote border-top" style="margin-bottom: 35px !important; margin-top: 0px !important">
																			<?php echo number_format(($value["QuotationsProduct"]["original_price"]*$value['QuotationsProduct']['quantity']),2,",",".") ?>
																			<span>
																				USD 
																			</span> 
																			<?php echo $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1? "+IVA" : "" ?>
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
							<?php if (!empty($value["QuotationsProduct"]["note"])): ?>
								<div class="col-md-12 no-padding">
									<hr>
									<?php echo $value["QuotationsProduct"]["note"] ?>
								</div>
							<?php endif ?>
						
						<div class="col-md-3 mt-4 valoresTRM" style="display: <?php echo AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Asesor Comercial"  ? "block" : "none" ?>">
							<span class="flechagiroprices">|</span>
							<div class="row">
								<?php 
									$factorVisible = $value["Product"]["Category"]["factor"];

									 if($value["QuotationsProduct"]["currency"] == "usd"){
									 	$costoUsd = $value["Product"]["purchase_price_usd"];
									 	$margnMin = $value["Product"]["Category"]["margen"];
									 }elseif ($value["Product"]["type"] == 0) {
									 	$costoUsd = $value["Product"]["purchase_price_cop"];
									 	$margnMin = $value["Product"]["Category"]["margen_wo"];
									 }else{
									 	$factorVisible = 1;
									 	$costoUsd = $value["Product"]["purchase_price_wo"];
									 	$margnMin = $value["Product"]["Category"]["margen_wo"];
									 }
									 $costo = $costoUsd;
									 if($datosQuation["Quotation"]["header_id"] == 3){
									 	$factorVisible = 1;
									 }
								?>

								<?php if($datosUsuario['User']['email'] == "ventas@kebco.co" ) { $margnMin = 20; } ?>

								<?php 

									if ($datosQuation['Quotation']["header_id"] == 3 || ($value["QuotationsProduct"]["currency"] == "usd" && $value["Product"]["type"] == 0 && $value["Product"]["currency"] == 2) ) {
										$costoColombia = $costo;
									}elseif($value["QuotationsProduct"]["currency"] == "usd"){
										$costoColombia = $costo * $factorVisible;
									}else{
										$costoColombia = $costo; 
									}
								?>

								<?php $valorProducto = $value["QuotationsProduct"]["price"]; ?>
								<?php $margenFinal 	 = $valorProducto == 0 ? 0 : ( ($valorProducto-$costoColombia) /$valorProducto) * 100 ; ?>

								<?php $margenValue = $value["QuotationsProduct"]["currency"] == "usd" ? $value["Product"]["Category"]["margen"] : $value["Product"]["Category"]["margen_wo"]?>

								<?php if($datosUsuario['User']['email'] == "ventas@kebco.co" ) { $margenValue = 20; } ?>

								<div class="col-md-12 margenblock text-center <?php echo $margenFinal < $margenValue ? "fondo-rojo" : "fondo-verde" ?>">
									<h2 class="margenes">
										Margen final <?php  echo number_format($margenFinal,"2",".",",") ?> %
									</h2>		
								</div>

								<div class="scrolldataprices">
									<div class="col-md-12 text-center fila1">
										<span><b>Categoría: </b> <?php echo $value["Product"]["Category"]["name"] ?></span>
										<span>
											<b>Margen mín: </b> <?php echo $margnMin ?>%
											<b>Factor: </b> <?php echo $factorVisible ?>%
										</span>
										<span>
											<b>TRM actual: </b> <?php echo $trmActual ?> 
											<a href="#" class="text-secondary ml-2 cambioTRM" data-toggle="tooltip" title="" data-original-title="Solicitar cambio de TRM">
												<i class="fa fa-edit vtc"></i>
											</a> 
										</span>
									</div>
									<div class="col-md-12 text-center fila2">
										<span><b>Costo <?php echo $value["QuotationsProduct"]["currency"] == "usd" ? "USD" :"COP"?>: </b><?php echo number_format($costo,2,",",".") ?> 
										<a href="#" class="text-primary cambioCosto" data-id="<?php echo $value["Product"]["id"] ?>" data-toggle="tooltip" title="" data-original-title="Solicitar cambio de costo">
											<i class="fa fa-edit vtc"></i>
										</a> </span>
										<?php if ($value['QuotationsProduct']["currency"] == "cop"): ?>					
											<span><b>Costo COP: </b> <?php echo number_format($costo,2, ",",".") ?> </span>
										<?php endif ?>
										<span><b>Costo final: </b> <?php echo number_format($costoColombia,2, ",",".") ?></span>
										<span><b>Precio cotizado: </b> <?php echo number_format($valorProducto,2, ",",".") ?> </span>
									</div>
								</div>

							</div>

							

						</div>
						</div>
						</div>
						<hr>
					<?php endforeach ?>
					<div class="notasImagenes">
						<?php $total = number_format((int)$datosQuation['Quotation']['total'], 2, ',', '.'); ?>
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
						<div class="totalcotiza mt-5 ">
							<?php if ($totalNoIva > 0): ?>
								<?php if ($datosQuation['Quotation']['total_visible'] && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?>
									
									<div class="divprice">
										<img src="https://crm.kebco.co/img/inventario.png" style="
										    float: left;
										    width: 178px;
										">										
										<div class="pricecop2">
											<small>PRODUCTOS CON PRECIO EN PESOS COLOMBIANOS</small>
											<h4 class="titlemoney">Esta cotización incluye productos cotizados en PESOS COLOMBIANOS, este es el precio total únicamente para productos en moneda COP </h4>
										</div>
										<?php $totalNoIvaParts = explode(",", number_format($totalNoIva, "2",",",".")) ?>
										<?php $ivaParts = explode(",", number_format(($totalNoIva * 0.19), "2",",",".")) ?>
										<?php $totalIvaParts = explode(",", number_format(($totalNoIva * 1.19), "2",",",".")) ?>
										<div class="contentprice">
											<div class="pricesline colorbg1">
												<b>Subtotal</b> $<?php echo $totalNoIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalNoIvaParts[1] ?></span>
											</div>  
											<div class="pricesline colorbg2"><b>IVA 19%</b>  $<?php echo $ivaParts[0] ?><span class="decimales simpledecimal">,<?php echo $ivaParts[1] ?></span> </div>
											<div class="pricesline colorbg3"><b>Total IVA incluido</b>  $<?php echo $totalIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalIvaParts[1] ?></span>
											 </div>  
										 </div>
									</div>

								<?php else: ?>
									<?php echo $this->Utilities->total_visible_quotations(number_format((float)$totalNoIva, 2, ",","."),$datosQuation['Quotation']['total_visible']); ?>  <?php echo $datosQuation['Quotation']['total_visible'] == 1 ? "COP" : "" ?> 

								<?php endif ?>
							<?php endif ?>

							<?php if ($totalNoIvaUSD > 0): ?>
								<?php if ($datosQuation['Quotation']['total_visible'] && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?>

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
										<?php $ivaParts = explode(",", number_format(($totalNoIvaUSD * 0.19), "2",",",".")) ?>
										<?php $totalIvaParts = explode(",", number_format(($totalNoIvaUSD * 1.19), "2",",",".")) ?>
										<div class="contentprice">
											<div class="pricesline colorbg1"><b>Subtotal </b>$<?php echo $totalNoIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalNoIvaParts[1] ?></span> USD </div>
											<div class="pricesline colorbg2"><b>IVA 19%</b>  $<?php echo $ivaParts[0] ?><span class="decimales simpledecimal">,<?php echo $ivaParts[1] ?></span> USD</div> 
											<div class="pricesline colorbg3"><b>Total IVA incluido</b>  $<?php echo $totalIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalIvaParts[1] ?></span> USD
											</div>
										</div>
									</div>
								<?php else: ?>
									<?php echo $this->Utilities->total_visible_quotations(number_format((float)$totalNoIvaUSD, 2, ",","."),$datosQuation['Quotation']['total_visible']); ?> <?php echo $datosQuation['Quotation']['total_visible'] == 1 ? " USD" : "" ?>
								<?php endif ?>
							<?php endif ?>
						</div>
					</div>
					<br>
					<hr>
				</div>
				<br>
				<div class="notesdescrip">
				<?php if (!in_array($datosQuation['Quotation']['notes_description'],$arrayVarcharPermitido)) : ?>
					<p class="mt-3"><?php echo $datosQuation['Quotation']['notes_description'] ?></p>
					<hr>
				<?php endif ?>
				</div>

				<?php if ($datosQuation['Quotation']['conditions'] != ''): ?>
					<div class="conditionsview condiciones_negociacion mt-2">
						<h2>CONDICIONES DE LA NEGOCIACIÓN:</h2>
						<p><?php echo $datosQuation['Quotation']['conditions'] ?></p>.
					</div>
				<?php endif ?>

				<b>Cordial saludo,</b>
				<div class="datasesorview">
					<?php if (!is_null($datosUsuario["User"]["img_signature"])): ?>
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
						</b>
						<br>
						<?php echo 'CEL: '.$datosUsuario['User']['cell_phone'] ?><br>
						<?php echo 'TEL: '.$datosUsuario['User']['telephone'] ?><br>
						<?php echo $datosUsuario['User']['email'] ?></p>
					<?php endif ?>
				</div> 
			</div>
			<img src="<?php echo $this->Html->url('/img/header/miniatura/'.$datosHeaders['Header']['img_small']) ?>" class="img-fluid">
			<!-- <footer>
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
			<?php if (!is_null($datosQuation['Quotation']['date_view'])): ?>
				 <div class="table-responsive mt-4">
				 	<h2 class="text-center mt-2 mb-2">Datos de correo</h2>
				 	<table class="table table-hovered">
				 		<thead>
				 			<tr class="text-center">
				 				<th>Fecha y hora de apertura</th>				 				
				 			</tr>
				 		</thead>
				 		<tbody>
				 			<tr class="text-center">
				 				<td><?php echo $datosQuation['Quotation']['date_view'] ?></td>
				 			</tr>
				 		</tbody>
				 	</table>
				 </div>
			<?php endif ?>
		</div>
		
	</div>	
</div>

<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
?>

<?php if (isset($edata)): ?>
	<script>
		var edata = "<?php echo $edata ?>";
		var flujo = "<?php echo $datos['ProspectiveUser']['id'] ?>";
	</script>
	
	<?php echo $this->Html->script("controller/quotations/actions.js?".rand(),			array('block' => 'AppScript'));  ?>
<?php endif ?>

<?php if (!isset($this->request->data["modal"])): ?>
	
<div class="modal fade " id="modalComentario" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Comentar cotización</h5>
      </div>
      <div class="modal-body" id="cuerpoCotizacion">
      	<form action="#" method="post" id="formComent">      		
			<div class="form-group">
				<label for="correoPrincipal">Por favor escriba su correo electrónico:</label>
				<input type="email" id="correoPrincipal" name="correoPrincipal" value="<?php echo isset($edata) ? $this->Utilities->desencriptarCadena($edata) : "" ?>" required="" class="form-control">
				<input type="hidden" id="flujo" name="flujo" required="" value="<?php echo $datos['ProspectiveUser']['id'] ?>" class="form-control">
				<input type="hidden" id="qt" name="quotation" required="" value="<?php echo $this->Utilities->encryptString($datosQuation['Quotation']['id']) ?>" class="form-control">
			</div>
			<div class="form-group">
				<label for="comentarioQt">Por favor escriba el comentario sobre la cotización: </label>
				<textarea name="comentarioQt" id="comentarioQt" cols="30" rows="10" class="form-control"></textarea>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success float-right mt-3" value="Enviar comentario" >
			</div>
      	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="modalReenvio" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Reenviar cotización</h5>
      </div>
      <div class="modal-body" id="cuerpoCotizacion">
      	<form action="#" method="post" id="formReenvio">      		
			<div class="form-group">
				<label for="correoPrincipal">Por favor escriba su correo electrónico:</label>
				<input type="email" id="correoPrincipal" name="correoPrincipal" value="<?php echo isset($edata) ? $this->Utilities->desencriptarCadena($edata) : "" ?>" required="" class="form-control">
				<input type="hidden" id="flujo" name="flujo" required="" value="<?php echo $datos['ProspectiveUser']['id'] ?>" class="form-control">
				<input type="hidden" id="qt" name="quotation" required="" value="<?php echo $this->Utilities->encryptString($datosQuation['Quotation']['id']) ?>" class="form-control">
			</div>
			<div class="form-group">
				<label for="nombrePersona">Por favor escriba el nombre de la persona que se le enviará la cotización</label>
				<input type="text" id="nombrePersona" name="nombrePersona" required="" class="form-control">
			</div>
			<div class="form-group">
				<label for="correoPersona">Por favor escriba el correo electrónico de la persona que se le enviará la cotización</label>
				<input type="email" id="correoPersona" name="correoPersona" required="" class="form-control">
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success float-right mt-3" value="Enviar comentario" >
			</div>
      	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="modalAprove" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Aprobar cotización</h5>
      </div>
      <div class="modal-body" id="cuerpoCotizacion">
      	<form action="#" method="post" id="formAprovee" enctype="multipart/form-data">      		
			<div class="form-group">
				<label for="correoPrincipal">Por favor escriba su correo electrónico:</label>
				<input type="email" id="correoPrincipal" name="correoPrincipal" value="<?php echo isset($edata) ? $this->Utilities->desencriptarCadena($edata) : "" ?>" required="" class="form-control">
				<input type="hidden" id="flujo" name="flujo" required="" value="<?php echo $datos['ProspectiveUser']['id'] ?>" class="form-control">
				<input type="hidden" id="qt" name="quotation" required="" value="<?php echo $this->Utilities->encryptString($datosQuation['Quotation']['id']) ?>" class="form-control">
			</div>
			<div class="form-group">
				<label for="comentarioCotizacion">Por favor escriba un comentario para la aprobación de la cotización</label>
				<textarea name="comentarioCotizacion" id="comentarioCotizacion" cols="30" rows="30" class="form-control" required=""></textarea>
			</div>
			<div class="form-group">
				<label for="archivoOrden">Por favor suba el archivo para la órden de compra (PDF) </label>
				<input type="file" id="archivoOrden" name="archivoOrden" class="form-control">
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success float-right mt-3" value="Aprobar cotización" >
			</div>
      	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalNewFactura" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Gestión de datos para proforma</h5>
      </div>
      <div class="modal-body" id="cuerpoFacturacionProforma">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalDuplicate" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Crear flujo a partir de cotización</h5>
      </div>
      <div class="modal-body">
      	<div id="cuerpoDuplicate"></div>
        <div class="cuerpoContactoCliente"></div>
        <div id="ingresoCliente"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php echo $this->element("address"); ?>
<?php endif ?>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<?php 

echo $this->Html->script("controller/quotations/facturas.js?".rand(),			array('block' => 'AppScript')); 
echo $this->Html->script("magnificpopup.js?".rand(),			array('block' => 'AppScript')); 

echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
 ?>
