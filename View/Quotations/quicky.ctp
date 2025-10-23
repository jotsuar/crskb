<?php $arrayVarcharPermitido = array('','<br>'); ?>
<?php if (AuthComponent::user('id') && !isset($this->request->data["modal"])): ?>	
	<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-azulclaro big">
		     <i class="fa fa-1x flaticon-growth"></i>
		    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
		</div>
	</div>
<?php endif ?>
<?php if (!AuthComponent::user("id") && $notPermitido ): ?>
	<div class="container p-0 containerCRM">
		<div class="col-md-12 p-0" id="cotizacionview">
			<div class="blockwhite">
				<h1 class="text-center">
					No esta permitida la visualización de esta cotización
				</h1>
			</div>
		</div>
	</div>
<?php else: ?>


<div class="container p-0 containerCRM">
	<div class="col-md-12 p-0" id="cotizacionview">

		<?php if (AuthComponent::user('id')) { ?>
	  		<a href="<?php echo $this->Html->url(array('controller'=> 'ProspectiveUsers', 'action' => 'duplicate',$datosQuation['Quotation']['prospective_users_id'],$datosQuation["Quotation"]["id"],$datosQuation["Quotation"]["flow_stage_id"] )) ?>" class="btn btn-info duplicate">
	    			Crear flujo a partir de esta cotización
	    		</a>
		<?php } ?>

		

		<?php if (!AuthComponent::user("id") && isset($edata) && $datos["ProspectiveUser"]["state_flow"] <= 3): ?>
			<?php echo $this->Html->link("Enviar comentario", array('action' => 'comentario', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf commentQuotation', "data-toggle"=>"modal","data-target"=>"#modalComentario")); ?>

			<?php echo $this->Html->link("Reenviar cotización", array('action' => 'comentario', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf replayQuotation', "data-toggle"=>"modal","data-target"=>"#modalReenvio")); ?>

			<?php echo $this->Html->link("Aprobar cotización", array('action' => 'aprovee', $this->Utilities->encryptString($datosQuation['Quotation']['id']) ),array('class' => 'btnDownloadPdf aproveeQuotation', "data-toggle"=>"modal","data-target"=>"#modalAprove")); ?> 
		<?php endif ?>
		<?php if ( (AuthComponent::user("id") && AuthComponent::user("role") != "Gerente General" && !$notPermitido) || (AuthComponent::user("id") && !$notPermitido ) ): ?>
			
			<?php echo $this->Html->link("Exportar a PDF", array('action' => 'view', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf','target'=>'_blank')); ?>
		<?php elseif(!AuthComponent::user("id")): ?>
			<?php // echo $this->Html->link("Exportar a PDF", array('action' => 'view', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf','target'=>'_blank')); ?>
		<?php endif ?>
		<?php if ($notPermitido): ?>
			<style media="print">
				.containerCRM, .blockwhite, *, a,span,h1,h2{
					background: red !important;
					color: red !important;
				}
			</style>
		<?php endif ?>
		<?php if (AuthComponent::user("id")): ?>
			<div class="form-check form-check-inline float-right classViewMargen">
				
				<label for="viewMargen" class="form-check-label mr-3">
					Ver margen de ganancia
				</label>
				<input type="checkbox" data-toggle="toggle" name="viewMargen" value="1" id="viewMargen" data-on="SI" data-off="NO" data-onstyle="success" data-offstyle="danger" <?php echo AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Asesor Comercial" ? "checked" : "" ?>>
			</div>
		<?php endif ?>

		
		
		<div class="blockwhite">
			<?php if ($datosQuation['Quotation']['header_id'] == 0) { ?>
				<img src="<?php echo $this->Html->url('/img/header/header/'.$datosHeaders['Header']['img_big']) ?>" class="img-fluid normal" style="max-width: 95% !important; height: auto !important; padding-left: 50px; ">
			<?php } else { ?>
				<img src="<?php echo $this->Html->url('/img/header/header/'.$datosHeaders['Header']['img_big']) ?>" class="img-fluid normal" style="max-width: 95% !important; height: auto !important; padding-left: 50px; ">
			<?php } ?>
				
			<div class="contentmargin">
				<h3 class="colorrojo"><?php echo $datosQuation['Quotation']['codigo'] ?>	
				</h3>
				<h3>
					<?php echo $this->Utilities->explode_city($datosUsuario['User']['city'], 'Colombia') .', '.$this->Utilities->date_castellano($datosQuation['Quotation']['created']); ?>
				</h3>
				<br>
				<p>Sr(a):</p>
				
				<?php if (!empty($datosC) && !is_null($datosQuation["Quotation"]["contacs_user_id"])){ ?>
					<h3><?php echo mb_strtoupper($datosC['ContacsUser']["name"]); ?></h3>
					<h3><?php echo $this->Utilities->name_bussines($datosC['ContacsUser']['clients_legals_id']); ?></h3>
					<?php if ($datosC['ContacsUser']['telephone'] != ''): ?>
						<h3><?php echo $datosC['ContacsUser']['telephone'] ?></h3>
					<?php endif ?>
					<?php if ($datosC['ContacsUser']['cell_phone'] != ''): ?>
						<h3><?php echo $datosC['ContacsUser']['cell_phone'] ?></h3>
					<?php endif ?>
					<h3><?php echo $datosC['ContacsUser']['email'] ?></h3>
					<h3><?php echo $datosC['ContacsUser']['city'] ?></h3>
				<?php } elseif(!empty($datosC) && !is_null($datosQuation["Quotation"]["clients_natural_id"])) { ?>
					<h3><?php echo mb_strtoupper($datosC['ClientsNatural']["name"]); ?></h3>
					<?php if ($datosC['ClientsNatural']['telephone'] != ''): ?>
						<h3><?php echo $datosC['ClientsNatural']['telephone'] ?></h3>
					<?php endif ?>
					<?php if ($datosC['ClientsNatural']['cell_phone'] != ''): ?>
						<h3><?php echo $datosC['ClientsNatural']['cell_phone'] ?></h3>
					<?php endif ?>					
					<h3><?php echo $datosC['ClientsNatural']['email'] ?></h3>
					<?php if ($datosC['ClientsNatural']['city'] != ''): ?>
						<h3><?php echo $datosC['ClientsNatural']['city'] ?></h3>
					<?php endif ?>
				<?php }else { ?>
					Cliente
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
					<?php $totalIvaUSD = 0; ?>
					<?php $totalIvaCop = 0; ?>
					<?php foreach ($datosProductos as $value): ?>
						<?php if (isset($this->request->data["biiled"]) && $value["QuotationsProduct"]["biiled"] != 1 && !$mostrarTodos): ?>
							<?php continue; ?>
						<?php endif ?>
						<?php
							if(isset($this->request->data["modal"]) && $value["QuotationsProduct"]["change"] == 1){
								$value["QuotationsProduct"]["currency"] = "usd";
								$value["QuotationsProduct"]["price"]	= $value["QuotationsProduct"]["price"] / $value["QuotationsProduct"]["trm_change"]; 
							}
						 ?>
						<?php 
							$boquilla = $this->Utilities->validarBoquillas($value["Product"]);
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
										<div class="imgproductview" style="background-image: url(<?php echo $this->Html->url('/img/products/'.$ruta)?>); ">
											
										</div>
										<?php if ($imgsUrls > 1 || !empty($value["Product"]["url_video"]) ): ?>
											<div class="buttonsMore float-md-right pointer d-block position-static p-1" >												Ver galería
												<i class="fa vtc fa-image"></i>
												<i class="fa vtc fa-ellipsis-v"></i>
											</div>
										<?php endif ?>
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

													<?php if ($value['Product']['manual_1'] != "" ) { ?> 
														<p>
															<i class="bg-red fa fa-file-pdf-o fa-2x vtc"></i> Manual 1:
															<a target="_blank" href="<?php echo $this->Html->url("/files/products/".$value["Product"]["manual_1"]) ?>">Clic aquí para ver el manual</a>
														</p>														
													<?php } ?>

													<?php if ($value['Product']['manual_2'] != "" ) { ?> 
														<p>
															<i class="bg-red fa fa-file-pdf-o fa-2x vtc"></i> Manual 2:
															<a target="_blank" href="<?php echo $this->Html->url("/files/products/".$value["Product"]["manual_2"]) ?>">Clic aquí para ver el manual</a>
														</p>														
													<?php } ?>

													<?php if ($value['Product']['manual_3'] != "" ) { ?> 
														<p>
															<i class="bg-red fa fa-file-pdf-o fa-2x vtc"></i> Manual 3:
															<a target="_blank" href="<?php echo $this->Html->url("/files/products/".$value["Product"]["manual_3"]) ?>">Clic aquí para ver el manual</a>
														</p>														
													<?php } ?>

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
															<?php if ($datosQuation["Quotation"]["show"] == 1): ?>
																
																<p class="entregat" style="margin-bottom: 0px !important">
																	<?php $precioCotizacionUnidad = explode(",", number_format((float)$value['QuotationsProduct']['price'], "2",",",".")) ?>
																	
																	<b>
																	<?php if ($datosQuation["Quotation"]["show_iva"] == 1): ?>P.U.<?php endif ?>
																	$<?php echo $precioCotizacionUnidad[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionUnidad[1] ?></span></b> 
																	<?php echo $value['QuotationsProduct']['currency'] == "usd" ? " USD" : "" ?>
																	<?php echo $value['QuotationsProduct']['iva'] == 1? "+IVA" : "" ?>
																</p>
															<?php else: ?>
																<p class="entregat" style="margin-bottom: 0px !important">
																	&nbsp;&nbsp;&nbsp;
																</p>
															<?php endif ?>
														</div>

														<div class="col-md-3 pdnone">
															<?php if ($datosQuation["Quotation"]["show"] == 1): ?>
																<p class="subtotalquote" style="margin-bottom: 0px !important">
																	<?php $precioCotizacionSubtotal = explode(",", $this->Utilities->total_item_products_quotations2($value['QuotationsProduct']['quantity'],$value['QuotationsProduct']['price'])) ?>
																	 <b> 
																	$<?php echo $precioCotizacionSubtotal[0] ?><span class="decimales simpledecimal">,<?php echo $precioCotizacionSubtotal[1] ?></span>
																	</b> 
																	<?php echo $value['QuotationsProduct']['currency'] == "usd" ? " USD" : "" ?>
																	<?php echo  $value['QuotationsProduct']['iva'] == 1? "+IVA" : "" ?>
																</p>
															<?php else: ?>
																<p class="entregat" style="margin-bottom: 0px !important">
																	&nbsp;&nbsp;&nbsp;
																</p>
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
														<?php if ($datosQuation["Quotation"]["show_iva"] == 1 && $datosQuation["Quotation"]["show"] == 1 && $value['QuotationsProduct']['iva'] == 1): ?>

															<div class="col-md-12 pdnone">	
																<div class="row mx-0">
																	<div class="col-md-6 pt-2 text-center uppercase">
																		<b>
																			Valores IVA incluido
																		</b>
																	</div>
																	<div class="col-md-3 pdnone">
																		<p class="entregat border-top" style="margin-bottom: 35px !important; margin-top: 0px !important">
																			
																			<b>
																				P.U.
																				$<?php echo number_format($value["QuotationsProduct"]["price"]*1.19,2,",",".") ?>
																			</b>
																			<?php if ($value["QuotationsProduct"]["currency"] == "usd"): ?>
																				<span class="uppercase">
																					<?php echo $value["QuotationsProduct"]["currency"] ?> 
																				</span>
																			<?php endif ?>																			
																		</p>
																	</div>
																	<div class="col-md-3 pdnone">
																		<p class="subtotalquote border-top" style="margin-bottom: 35px !important; margin-top: 0px !important">
																			
																			<b>
																				$<?php echo number_format( $value["QuotationsProduct"]["price"]*$value['QuotationsProduct']['quantity'] *1.19,2,",",".") ?>																				
																			</b>
																			<?php if ($value["QuotationsProduct"]["currency"] == "usd"): ?>
																				<span class="uppercase">
																					<?php echo $value["QuotationsProduct"]["currency"] ?> 
																				</span>
																			<?php endif ?>
																			 																			
																		</p>
																	</div>
																</div>
															</div>
																													
														<?php endif ?>		
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
																			<?php echo $datos["ProspectiveUser"]["country"] == "Colombia" && $value['QuotationsProduct']['iva'] == 1? "+IVA" : "" ?>
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
							<?php $garantia = $this->Utilities->getGarantiaProv($value["Product"]["garantia_id"]) ?>
							<?php if (!empty($garantia)): ?>
								<div class="col-md-12">
									<hr>
									<span class="text-danger">
										NOTA: <?php echo $garantia ?>
									</span>
								</div>
							<?php endif ?>
							<?php if (!empty($value["QuotationsProduct"]["note"])): ?>
								<div class="col-md-12 no-padding">
									<hr>
									<?php echo $value["QuotationsProduct"]["note"] ?>
								</div>
							<?php endif ?>
						
						<div class="<?php echo !isset($this->request->data["doce"]) ? "col-md-3" : "col-md-12" ?> mt-4 valoresTRM" style="display: <?php echo AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Asesor Comercial"  ? "block" : "none" ?>">
							<span class="flechagiroprices">|</span>
							<div class="row">
								<?php 
									$factorVisible = $value["Product"]["Category"]["factor"];

									 if($value["QuotationsProduct"]["currency"] == "usd"){
									 	$costoUsd = !is_null($value["QuotationsProduct"]["purchase_price_usd"]) ? $value["QuotationsProduct"]["purchase_price_usd"] : $value["Product"]["purchase_price_usd"];
									 	$margnMin = $value["Product"]["Category"]["margen"];
									 }elseif ($value["Product"]["type"] == 0) {
									 	$costoUsd = !is_null($value["QuotationsProduct"]["purchase_price_cop"]) ? $value["QuotationsProduct"]["purchase_price_cop"] : $value["Product"]["purchase_price_cop"];
									 	$margnMin = $value["Product"]["Category"]["margen_wo"];
									 }else{
									 	// $factorVisible = 1;
                                        // $costoUsd = !is_null($value["QuotationsProduct"]["purchase_price_wo"]) ? $value["QuotationsProduct"]["purchase_price_wo"] : $value["Product"]["purchase_price_wo"];
									 	if (!is_null($trmDay)) {
									 		$factorVisible = $value["Product"]["Category"]["factor"];
									 		$costoUsd = $value["QuotationsProduct"]["purchase_price_usd"] * $trmDay;
									 		$costoUsd *= $value["Product"]["Category"]["factor"];	
									 		if (is_null($costoUsd) || $costoUsd == 0 || $costoUsd < $value["QuotationsProduct"]["purchase_price_wo"] ) {
									 			$costoUsd = !is_null($value["QuotationsProduct"]["purchase_price_wo"]) ? $value["QuotationsProduct"]["purchase_price_wo"] : $value["Product"]["purchase_price_wo"];
									 			$factorVisible = 1;
									 		}
									 	}else{
									 		$factorVisible = 1;
									 		$costoUsd = !is_null($value["QuotationsProduct"]["purchase_price_wo"]) ? $value["QuotationsProduct"]["purchase_price_wo"] : $value["Product"]["purchase_price_wo"];
									 	}
									 	$margnMin = $value["Product"]["Category"]["margen_wo"];

									 }
									 $costo = $costoUsd;
									 if($datosQuation["Quotation"]["header_id"] == 3){
									 	$factorVisible = 1;
									 }
								?>

								<?php if($datosUsuario['User']['email'] == "ventas@kebco.co" || $datosQuation['Quotation']["header_id"] == 3 ) { $margnMin = 20; } ?>

								<?php 

									if ($datosQuation['Quotation']["header_id"] == 3 || ($value["QuotationsProduct"]["currency"] == "usd" && $value["Product"]["type"] == 0 && $value["Product"]["currency"] == 2) ) {
										$costoColombia = $costo;
									}elseif($value["QuotationsProduct"]["currency"] == "usd"){
										$costoColombia = $costo * $factorVisible;
									}else{
										$costoColombia = $costo; 
									}
								?>

								<?php 
									$valorProducto = $value["QuotationsProduct"]["price"]; 
									$valorNormal   = $valorProducto;
								?>
								<?php 
									$margenFinal 	 = $valorProducto == 0 ? 0 : ( ($valorProducto-$costoColombia) /$valorProducto) * 100 ; 
									$margenNormal 	 = $margenFinal;
									$discount 		 = $datosQuation['Quotation']["descuento"];
									if ($discount > 0) {
										$valorProducto = $valorProducto - ($valorProducto * ($discount / 100) );
										$margenFinal 	 = $valorProducto == 0 ? 0 : ( ($valorProducto-$costoColombia) /$valorProducto) * 100 ; 
									}
								?>

								<?php $margenValue = $value["QuotationsProduct"]["currency"] == "usd" ? $value["Product"]["Category"]["margen"] : $value["Product"]["Category"]["margen_wo"]?>

								<?php if($datosUsuario['User']['email'] == "ventas@kebco.co" || $datosQuation['Quotation']["header_id"] == 3 ) { $margenValue = 20;  } ?>

								<div class="col-md-12 margenblock text-center <?php echo $margenFinal < $margenValue ? "fondo-rojo" : "fondo-verde" ?>">
									<h2 class="margenes">
										<?php if ($discount > 0): ?>
											<?php $claseNormal = $margenNormal < $margenValue ? "" : "text-success" ?>
											<span>
												<b class="<?php echo $claseNormal ?>">Margen sin descuento: </b>
												<span class="<?php echo $claseNormal ?>"><?php  echo number_format($margenNormal,"2",".",",") ?> %</span>
											</span>	
										<?php endif ?>
										<b>Margen final </b><?php  echo number_format($margenFinal,"2",".",",") ?> %
									</h2>		
								</div>

								<div class="scrolldataprices <?php echo AuthComponent::user("role") == "Asesor Externo" ? "d-none" : "" ?>">
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
										<?php if ($discount > 0): ?>
											<span><strike><b>Precio cotizado: </b> <?php echo number_format($valorNormal,2, ",",".") ?> </strike></span>
											<span><b>Precio cotizado final: </b> <?php echo number_format($valorProducto,2, ",",".") ?> </span>
										<?php else: ?>
											<span><b>Precio cotizado: </b> <?php echo number_format($valorProducto,2, ",",".") ?> </span>
										<?php endif ?>
										
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
								<?php if (($datosQuation['Quotation']['total_visible'] || isset($this->request->data["biiled"])) && $iva == 1): ?>
									
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
									<?php echo $this->Utilities->total_visible_quotations(number_format((float)$totalNoIva, 2, ",","."),$datosQuation['Quotation']['total_visible']); ?>  <?php echo $datosQuation['Quotation']['total_visible'] == 1 ? "COP" : "" ?> 

								<?php endif ?>
							<?php endif ?>

							<?php if ($totalNoIvaUSD > 0): ?>
								<?php if (($datosQuation['Quotation']['total_visible'] || isset($this->request->data["biiled"]) )  && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?>

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

										<?php if (!empty($datosQuation['Quotation']['descuento']) && $datosQuation['Quotation']['descuento'] >= 1) {
											$descuentoUsd = $totalNoIvaUSD * ($datosQuation['Quotation']['descuento'] / 100);
											$ivaParts = explode(",", number_format(( ($totalIvaUSD - $descuentoUsd) * 0.19), "2",",","."));
											$totalIvaParts = explode(",", number_format(( ($totalNoIvaUSD - $descuentoUsd) + ( ($totalIvaUSD - $descuentoUsd) * 0.19) ), "2",",","."));
										}else{
											$ivaParts = explode(",", number_format(($totalIvaUSD * 0.19), "2",",","."));
											$totalIvaParts = explode(",", number_format(($totalNoIvaUSD + ($totalIvaUSD * 0.19) ), "2",",","."));
										} ?>

										<div class="contentprice">
											<div class="pricesline colorbg1"><b>Subtotal </b>$<?php echo $totalNoIvaParts[0] ?><span class="decimales simpledecimal">,<?php echo $totalNoIvaParts[1] ?></span> USD </div>

											<?php if (!empty($datosQuation['Quotation']['descuento']) && $datosQuation['Quotation']['descuento'] >= 1): ?>
												<div class="pricesline colorbg3">
													<?php $descuentoUsdParts = explode(",", number_format($descuentoUsd, "2",",",".")); ?>
													<b>Descuento <?php echo $datosQuation['Quotation']['descuento'] ?>%</b> -$<?php echo $descuentoUsdParts[0] ?><span class="decimales simpledecimal">,<?php echo $descuentoUsdParts[1] ?></span> USD
												</div>  
											<?php endif ?>

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
						<?php if (!empty($suggested)): ?>

							<?php foreach ($suggested as $productIDSuggested => $value): ?>
								
								<div class="border-0 border-blues card borderRadius30">
								  <div class="border-blue border-left card-header pl-4 px-3 py-0" style="border-radius: 30px 30px 0px 0px; background-image: url(<?php echo $this->Html->url('/img/cot_fondo.png')?>);">
								  	<div class="row" style="margin-right: -18px;">
								  		<div class="col-md-12  font-weight-lighter font22 pt-2 text-center text-white pb-2" style="border-radius: 0px 30px 0px 0px;">
								  			¡Dale un vistaso a nuestos <br>
								    		<span class="font-weight-bolder" style="color: #ffaf08 !important; font-weight: bold !important;">
								    			ACCESESORIOS OPCIONALES!
								    		</span>  <br> <?php echo $value["part_number"] ?> - <?php echo $value["name"] ?>
								  		</div>
								  	</div>
								  	
								  </div>
								  <div class="card-body border border-blues py-0" style="    padding-left: 0px !important;   padding-top: 0px !important;  padding-right: 0px !important;    margin-top: -2px; border-radius: 0px 0px 30px 30px; ">
								  	<ul class="px-3 row pt-2 pl-4">
								  		<?php foreach ($value["suggested"] as $key => $suggested): ?>
								  			<li class="list-group-item py-0 col-mdMiddle mx-2 my-2 borderRadius30">
							                    <!-- Custom content-->
							                    <div class="media align-items-lg-center flex-column flex-lg-row p-3 px-4 ">
							                        <div class="media-body order-2 order-lg-1">
							                            <h5 class="mt-0 font-weight-bold mb-2 font16">
							                            	<?php echo $suggested["Product"]["part_number"] ?> - <?php echo $suggested["Product"]["name"] ?> 

							                            </h5>
							                            <p class="font-italic text-muted mb-0 small font-weight-bold">
								                            <?php echo $this->Text->truncate(strip_tags($suggested['Product']['description']), 250,array('ellipsis' => '...','exact' => false)); ?>
								                        </p>
							                            <div class="d-flex align-items-center justify-content-between mt-1">
							                                <h6 class="font-weight-bold my-2">
							                                	<b><?php echo $suggested["SuggestedProduct"]["quantity"] ?> Unidad(es)</b> <br>
							                                	<span class=" text-success">
							                                		
							                                	<?php if ($currencys[$value["id"]] == "usd" ): ?>
							                                		<?php echo number_format($suggested["SuggestedProduct"]["price_usd"]*$suggested["SuggestedProduct"]["quantity"]) ?> USD
							                                	<?php else: ?>
							                                		<?php echo number_format( round( ($suggested["SuggestedProduct"]["price_usd"]*$suggested["SuggestedProduct"]["quantity"]) * $trm_suggest) ) ?> COP
							                                	<?php endif ?>
 																
 																+ IVA

							                                	</span>
							                                </h6>
							                            </div>
							                        </div>
							                        <?php $ruta = $this->Utilities->validate_image_products($suggested["Product"]['img']); ?>
							                        <img src="<?php echo $this->Html->url('/img/products/'.$ruta)?>" alt="Generic placeholder image" width="125" class="order-1 ml-3 order-lg-2">
							                    </div>
								  		<?php endforeach ?>
								  	</ul>
								  </div>
								</div>

							<?php endforeach ?>
							
							

						<?php endif ?>
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
					<div class="conditionsview condiciones_negociacion mt-2 mb-0">
						<h2>CONDICIONES DE LA NEGOCIACIÓN:</h2>
						<p><?php echo $datosQuation['Quotation']['conditions'] ?></p>.
					</div>
				<?php endif ?>

				<?php if (!is_null($datosQuation['Quotation']['garantia']) && $datosQuation['Quotation']['garantia'] != false): ?>
					<div class="conditionsview condiciones_negociacion mb-0">
						<h2>Garantía general</h2>
						<div id="garantiaGeneral"><?php echo $datosQuation['Quotation']['garantia'] ?></div>.
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
			<img src="<?php echo $this->Html->url('/img/header/miniatura/'.$datosHeaders['Header']['img_small']) ?>" class="img-fluid" style="max-width: 95% !important; height: auto !important; padding-left: 50px;">
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

<?php endif ?>

<?php if (AuthComponent::user("role") == "Asesor Externo"): ?>
	<style>
		.fila1block{
			display: none !important;
		}
	</style>
<?php endif ?>

<style>
	.border-blue {
    	border-color: #004990 !important;
	}
	@media screen and (min-width: 768px) {
		.col-mdMiddle {
		    -ms-flex: 0 0 48%;
		    flex: 0 0 48%;
		    max-width: 48%;
		}
	}
	.col-mdMiddle{
	    position: relative;
	    width: 100%;
	    padding-right: 15px;
	    padding-left: 15px;
	}
	.borderRadius30{
		border-radius: 30px 30px 30px 30px !important;
	}
	.list-group-item, .card-body.border {

	    border: 1px solid rgb(0 0 0 / 50%) !important;
	}
</style>

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
    

<?php if (isset($this->request->query["from_gest"])): ?>
	<?php $this->start('variablesAppScript');  ?>
	<script>
		const FROM_GEST = 2;
	</script>
	<?php $this->end();?>

<?php endif ?>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<?php 

echo $this->Html->script("controller/quotations/facturas.js?".rand(),			array('block' => 'AppScript')); 
echo $this->Html->script("magnificpopup.js?".rand(),			array('block' => 'AppScript')); 

echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
 ?>
