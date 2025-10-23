<?php $arrayVarcharPermitido = array('','<br>'); ?>
<?php if (AuthComponent::user('id') && !isset($this->request->data["modal"])): ?>	
	<div class="col-md-12 p-0 classnoprint">
		<div class=" widget-panel widget-style-2 bg-azulclaro big ">
		     <i class="fa fa-1x flaticon-growth"></i>
		    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
		</div>
	</div>
<?php endif ?>
<?php if (!AuthComponent::user("id") && $notPermitido ): ?>
	<div class="container p-0 containerCRM classnoprint">
		<div class="col-md-12 p-0 classnoprint" id="cotizacionview">
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

		<?php if (AuthComponent::user('id') ) { ?>
	  		<?php if ($datos['ProspectiveUser']['type'] > 0){ ?>
	  			<a href="<?php echo $this->Html->url(array('controller'=> 'TechnicalServices', 'action' => 'flujos?q='.$datosQuation['Quotation']['prospective_users_id'])) ?>" id="idflujo">
	    			ID FLUJO <?php echo $datosQuation['Quotation']['prospective_users_id'] ?>
	    		</a>
	  		<?php } else { ?>
	  			<a href="<?php echo $this->Html->url(array('controller'=> 'ProspectiveUsers', 'action' => 'index?q='.$datosQuation['Quotation']['prospective_users_id'])) ?>" id="idflujo">
	    			ID FLUJO <?php echo $datosQuation['Quotation']['prospective_users_id'] ?>
	    		</a>
	  		<?php } ?>
	  		<?php if (!isset($this->request->data["modal"])): ?>
	  			
	  			<a href="<?php echo $this->Html->url(array('controller'=> 'ProspectiveUsers', 'action' => 'duplicate',$datosQuation['Quotation']['prospective_users_id'],$datosQuation["Quotation"]["id"],$datosFlowStage["FlowStage"]["id"] )) ?>" class="btn btn-info duplicate">
	    			Crear flujo a partir de esta cotización
	    		</a>
	  		<?php endif ?>
		<?php } ?>
		<?php if (isset($reenviar) && !isset($this->request->data["modal"])): ?> 
			<a class="btn btn-warning ml-lg-2 mr-lg-2 p-2 reenviarCot" data-uid="<?php echo $flujoDataId ?>" data-flowstages="<?php echo $flowData; ?>" data-toggle="tooltip" data-placement="right" title="Reenviar cotización de nuevo al correo electrónico">
				Reenviar cotización &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
			</a>
		<?php endif ?>

		

		<?php if (!AuthComponent::user("id") && isset($edata) && $datos["ProspectiveUser"]["state_flow"] <= 3): ?>
			<?php echo $this->Html->link("Enviar comentario", array('action' => 'comentario', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf classnoprint commentQuotation', "data-toggle"=>"modal","data-target"=>"#modalComentario")); ?>

			<?php echo $this->Html->link("Reenviar cotización", array('action' => 'comentario', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf classnoprint replayQuotation', "data-toggle"=>"modal","data-target"=>"#modalReenvio")); ?>

			<?php echo $this->Html->link("Aprobar cotización", array('action' => 'aprovee', $this->Utilities->encryptString($datosQuation['Quotation']['id']) ),array('class' => 'btnDownloadPdf classnoprint aproveeQuotation', "data-toggle"=>"modal","data-target"=>"#modalAprove")); ?> 
		<?php endif ?>
		<?php if ( (AuthComponent::user("id") && AuthComponent::user("role") != "Gerente General" && !$notPermitido) || (AuthComponent::user("id") && !$notPermitido ) ): ?>
			
			<?php echo $this->Html->link("Exportar a PDF", array('action' => 'view', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf classnoprint','targets'=>'_blank', 'onclick' => 'window.print()' )); ?>
		<?php elseif(!AuthComponent::user("id")): ?>
			<?php echo $this->Html->link("Exportar a PDF", array('action' => 'view', $this->Utilities->encryptString($datosQuation['Quotation']['id']), 'ext' => 'pdf'),array('class' => 'btnDownloadPdf classnoprint','targets'=>'_blank', 'onclick' => 'window.print()' )); ?>
		<?php endif ?>
		<?php if ($notPermitido): ?>
			<style media="print">
				.containerCRM, .blockwhite, *, a,span,h1,h2{
					background: red !important;
					color: red !important;
				}
			</style>
		<?php endif ?>
		<?php if (AuthComponent::user("id") && in_array(AuthComponent::user("role") ,["Logística","Gerente General"] ) ): ?>
			<div class="form-check form-check-inline float-right classViewMargen classnoprint">
				
				<label for="viewMargen" class="form-check-label mr-3">
					Ver margen de ganancia
				</label>
				<input type="checkbox" data-toggle="toggle" name="viewMargen" value="1" id="viewMargen" data-on="SI" data-off="NO" data-onstyle="success" data-offstyle="danger" <?php echo AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Asesor Comercial" ? "checked" : "" ?>>
			</div>
		<?php endif ?>

		
		
		<div class="blockwhite">
			<?php if (AuthComponent::user('id') && !isset($this->request->data["modal"]) && !$notPermitido): ?>	
				<div class="col-md-12 classnoprint">
					<a href="/" data-id="<?php echo $this->Utilities->encryptString($datosQuation["Quotation"]["id"]); ?>" class="btb btn-primary float-right mt-2 mb-2 datosProforma">
						Generar Proforma <i class="fa fa-file vtc"></i>
					</a>
				</div>
			<?php endif ?>
			<?php if ($datosQuation['Quotation']['header_id'] == 0) { ?>
				<img src="<?php echo $this->Html->url('/img/header/header/'.$datosHeaders['Header']['img_big']) ?>" class="img-fluid normal1" style="max-width: 95% !important; height: 180px !important; padding-left: 50px; ">
			<?php } else { ?>
				<img src="<?php echo $this->Html->url('/img/header/header/'.$datosHeaders['Header']['img_big']) ?>" class="img-fluid normal1" style="max-width: 95% !important; height: 180px !important; padding-left: 50px; ">
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
				
				<?php if ($datos['ProspectiveUser']['contacs_users_id'] > 0){ ?>
					<h3>
						<?php echo $this->Utilities->name_bussines($datosC['ContacsUser']['clients_legals_id']); ?> 
						<br>
						<?php echo $datosC["ContacsUser"]["nit"] ?>
						<br>
						<?php echo mb_strtoupper($this->Utilities->name_prospective($datos['ProspectiveUser']['id'])); ?>
					</h3>					
					<?php if ($datosC['ContacsUser']['telephone'] != ''): ?>
						<h3><?php echo $datosC['ContacsUser']['telephone'] ?></h3>
					<?php endif ?>
					<?php if ($datosC['ContacsUser']['cell_phone'] != ''): ?>
						<h3><?php echo $datosC['ContacsUser']['cell_phone'] ?></h3>
					<?php endif ?>
					<h3><i class="fa fa-mail"></i><?php echo $datosC['ContacsUser']['email'] ?></h3>
					<h3><?php echo $datosC['ContacsUser']['city'] ?></h3>
				<?php } else { ?>
					<h3><?php echo mb_strtoupper($this->Utilities->name_prospective($datos['ProspectiveUser']['id'])); ?></h3>
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
				<?php } ?>
				<br>
				<h3 class="colorazul"><b>Asunto: </b><?php echo $datosQuation['Quotation']['name'] ?></h3>
				<br>

				<?php if (AuthComponent::user('id') &&  !empty($datosQuation["Quotation"]["customer_note"])): ?>	
				<div class="col-md-12 p-0 classnoprint">
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
				<div class="contentgrafico mb-4">
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
							if(isset($this->request->data["modal"]) && strpos($this->request->referer(), 'shippings/view/') === false && $value["QuotationsProduct"]["change"] == 1){
								// $value["QuotationsProduct"]["currency"] = "usd";
								// $value["QuotationsProduct"]["price"]	= $value["QuotationsProduct"]["price"] / $value["QuotationsProduct"]["trm_change"]; 
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
						<div class="row" style="display: <?php echo in_array($value["Product"]["part_number"], [ 'S-003']) && $datosQuation["Quotation"]["show_ship"] == 0 && !AuthComponent::user("id") ? 'none !important' : 'block' ?>;">
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
										<?php if ( !isset($this->request->data["modal"]) && ($imgsUrls > 1 || !empty($value["Product"]["url_video"]) )): ?>
											<a target="_blank" href="<?php echo $this->Html->url(["controller"=>"quotations","action"=>"detail_product",$this->Utilities->encryptString($datosQuation["Quotation"]["id"]),$this->Utilities->encryptString($value["QuotationsProduct"]["id"])]) ?>" class="float-md-right pointer d-block position-static p-1 btn btn-primary btn-block detail_product">Ver detalle</a>
											<!-- <div class="buttonsMore float-md-right pointer d-block position-static p-1" >												Ver galería
												<i class="fa vtc fa-image"></i>
												<i class="fa vtc fa-ellipsis-v"></i>
											</div> -->
										<?php endif ?>
										<?php if (!empty($imgsUrls)): ?>											
											<!-- <div class="gallery" style="display:none"> -->
												<!-- <?php //foreach ($imgsUrls as $keyUrl => $valueUrl): ?> -->
													<!-- <a id="<?php //echo $keyUrl == 0 ? "firstImg" : "" ?>" href="<?php //echo $valueUrl ?>" alt="hola"> -->
														<!-- <img src="<?php //echo $valueUrl ?>" alt="<?php //echo $value["Product"]["name"] ?>"> -->
													<!-- </a> -->
												<?php //endforeach ?>
												<?php //if (!empty($value["Product"]["url_video"])): ?>
													<!-- <a href="<?php //echo $value["Product"]["url_video"] ?>"></a> -->
												<?php //endif ?>
											<!-- </div> -->
										<?php endif ?>
									</div>
									<div class="col-md-9">
										<div class="dataproductview <?php echo strlen($value["Product"]["description"]) > 350 ? 'h-1501' : 'h-150' ?>">
											<div class="<?php echo strlen($value["Product"]["description"]) > 350 ? 'h-1501' : 'h-150' ?> justify-content-center">
												<div>
													<?php if (AuthComponent::user("role") == "Gerente General" && AuthComponent::user("email") == "jotsuar@gmail.com"): ?>
														<a href="<?php echo $this->Html->url(["controller" => "quotations_products","action" => "edit", $value["QuotationsProduct"]["id"], $this->Utilities->encryptString($datosQuation["Quotation"]["id"])] ) ?>" class="btn btn-info">
															<i class="fa fa-pencil"></i>
														</a>
													<?php endif ?>
													<strong class="text-success">
														Referencia: <?php echo $value['Product']['part_number'] ?> / Marca: <?php echo $value["Product"]["type"] == 0 ? "Kebco" : $value['Product']['brand'] ?>
														<?php if ($value["QuotationsProduct"]["currency"] == 'cop'): ?>
															<?php if (!isset($this->request->data["modal"])): ?>
																
															<a style="display:nones" target="_blank" href="<?php echo $this->html->url(["controller"=>"quotations","action"=>"action_payment_qtprod",$this->Utilities->encryptString($value["QuotationsProduct"]["id"]),$this->Utilities->encryptString($datosQuation["Quotation"]["id"])]) ?>" class="btn btn-sm btn-outline-primary float-right">Pagar Producto 
																	<img src="data:image/png;base64,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" alt="">
																</a>
															<?php endif ?>
														<?php endif ?>
													</strong>
													<h3 class=""><?php echo $this->Text->truncate(strip_tags($value['Product']['name']), 70,array('ellipsis' => '...','exact' => false)); ?> </h3>
													<p class="descriptionview" <?php echo $value["Product"]["id"] ?>>

														<?php if ( strlen($value['Product']['description']) <= 500 ): ?>
															<?php echo $this->Text->truncate(strip_tags($value['Product']['description'],'<br>'), 500,array('ellipsis' => '...','exact' => false)); ?>
														<?php else: ?>
															<?php $position = strpos($value['Product']['description'],'especificaciones técnicas') ?>
															<?php $position2 = strpos($value['Product']['description'],'Especificaciones técnicas') ?>
															<?php echo $this->Text->truncate(( str_replace('especificaciones técnicas', 'Datos técnicos', $value['Product']['description']) ), 24500,array('ellipsis' => '...','exact' => false)); ?>
															<?php if ($position === false && $position2 === false): ?>
																<?php //echo $this->Text->truncate(strip_tags($value['Product']['description'],'<br>'), 24500,array('ellipsis' => '...','exact' => false)); ?>
															<?php else: ?>
																
															<?php endif ?>
														<?php endif ?>

														

														

														
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
															<a target="_blank" href="<?php echo $this->Utilities->data_null($value['Product']['link']) ?>">Clic aquí para ver más información</a>
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
																Entrega:  <b style="<?php echo $value['QuotationsProduct']['delivery'] == 'Consultar Disponibilidad' ? 'font-size: 12px;' : '' ?>" ><?php echo $value['QuotationsProduct']['delivery'] ?></b>
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
																	<?php echo $datos["ProspectiveUser"]["country"] == "Colombia" && $value['QuotationsProduct']['iva'] == 1? "+IVA" : "" ?>
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
																	<?php echo $datos["ProspectiveUser"]["country"] == "Colombia" && $value['QuotationsProduct']['iva'] == 1? "+IVA" : "" ?>
																</p>
															<?php else: ?>
																<p class="entregat" style="margin-bottom: 0px !important">
																	&nbsp;&nbsp;&nbsp;
																</p>
															<?php endif ?>
															<?php 

																if ($datosQuation["Quotation"]["header_id"] == 3 || $datosQuation["Quotation"]["show_ship"] == 1  || (!in_array($value["Product"]["part_number"],['S-003']) && $datosQuation["Quotation"]["show_ship"] == 0) ) {
																	
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
								<div class="col-md-12 no-break">
									<hr>
									<span class="text-danger">
										NOTA: <?php echo $garantia ?>
									</span>
								</div>
							<?php endif ?>
							<?php if (!empty($value["QuotationsProduct"]["note"])): ?>
								<div class="col-md-12 no-padding no-break">
									<hr>
									<?php echo $value["QuotationsProduct"]["note"] ?>
								</div>
							<?php endif ?>
						<?php if (AuthComponent::user("id") && in_array(AuthComponent::user("role") ,["Logística","Gerente General"] ) ): ?>
							<div class="<?php echo !isset($this->request->data["doce"]) ? "col-md-3" : "col-md-12" ?> mt-4 valoresTRM" style="display: <?php echo AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Asesor Comercial"  ? "block" : "none" ?>">
								<span class="flechagiroprices">|</span>
								<div class="row">
									<?php 

										if(!is_null($value["Product"]["margen_wo"]) && $value["Product"]["margen_wo"] > 0 ){
											$value["Product"]["Category"]["margen_wo"] = $value["Product"]["margen_wo"];
										}

										if(!is_null($value["Product"]["margen_usd"]) && $value["Product"]["margen_usd"] > 0 ){
											$value["Product"]["Category"]["margen"] = $value["Product"]["margen_usd"];
										}

										if(!is_null($value["Product"]["factor"]) && $value["Product"]["factor"] > 0 ){
											$value["Product"]["Category"]["factor"] = $value["Product"]["factor"];
										}

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
										 	if (!is_null($trmDay) && $datosQuation["Quotation"]["created"] < '2025-02-01' ) {
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

									<div class="col-md-12 margenblock text-center <?php echo round($margenFinal) ?> <?php echo $margnMin ?> <?php echo round($margenFinal) < $margnMin ? "fondo-rojo" : "fondo-verde" ?>" >
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
						<?php endif ?>
						</div>
						</div>
						<hr>
					<?php endforeach ?>
					<div class="notasImagenes no-break">
						<?php $total = number_format((int)$datosQuation['Quotation']['total'], 2, ',', '.'); ?>
						<?php if ($datos["ProspectiveUser"]["from_bot"] == 1): ?>

							<br>
							<small class="notaimg text-danger">
								<b>ADVERTENCIA<i class="fa fa-x fa-warning" style="    animation: spinner-grow 1s ease-in-out infinite;"></i>: Los precios están sujetos a verificación previa de una asesor, ya que esta cotización fue generada mediante inteligencia artificial y el precio puede variar al momento de su generación.</b>
							</small>
							<br>
							<br>
						<?php endif ?>
						<small class="notaimg text-danger">
							Las imágenes de producto son de referencia para ambientación fotográfica y pueden variar con respecto a las versiones disponibles
						</small>
						<small class="notaimg text-danger">
							Las unidades disponibles se encuentran sujetas a la venta previa.
						</small>
						<br>
						<small class="notaimg text-danger">
							En el caso de que la cotización esté realizada en Dólares americanos el valor en Pesos se liquida en el momento de realizar la factura con la TRM del dia de facturación
						</small>
						<br>

						

						<div class="totalcotiza mt-5 ">
							<?php if ($totalNoIva > 0): ?>
								<?php if (($datosQuation['Quotation']['total_visible'] || isset($this->request->data["biiled"])) && $datos["ProspectiveUser"]["country"] == "Colombia" && $iva == 1): ?>
									
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
											<?php if ($datos['ProspectiveUser']['contacs_users_id'] > 0 && $totalNoIva > 500000): ?>
												<div class="pricesline colorbg3">
													<span class="border p-1">														
														<span class="text-danger">NOTA: </span>
														<b>En caso de que aplique retención</b>  $<?php echo number_format(round($totalNoIva*0.975+($totalIvaCop) * 0.19,2))  ?><span class="decimales simpledecimal">,<?php echo $totalIvaParts[1] ?></span>
													</span>
											 </div> 
											<?php endif ?>
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

							<?php if (!isset($this->request->data["modal"]) && $datosQuation['Quotation']['total_visible'] && $datos["ProspectiveUser"]["country"] == "Colombia" ): ?>
								<div class="divprice text-center" data-usdcop="<?php echo $totalUsdOriginal ?>">									
									<a style="display:nones" target="_blank" href="<?php echo $this->html->url(["controller"=>"quotations","action"=>"action_payment_quotation",$this->Utilities->encryptString($datosQuation["Quotation"]["id"])]) ?>" class="btn btn-outline-primary btn-sm text-center w-75">Pagar Cotización 
										<img src="data:image/png;base64,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" alt="">
									</a>
									<?php if ($totalUsdOriginal > 1500000): ?>
										
									
									<a style="display:nones" target="_blank" href="<?php echo $this->html->url(["controller"=>"quotations","action"=>"action_payment_quotation",$this->Utilities->encryptString($datosQuation["Quotation"]["id"]), "abono" ]) ?>" class="btn btn-outline-primary btn-sm text-center w-75">Pagar con abono de: $<?php echo number_format(round(($totalUsdCop+($totalUsdOriginal/2))*1.19)) ?>
										<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAB9lJREFUSEvFlntwVOUZh59z2bO33BNCQhJy4ZIQhBBRO7GDotPiCNjSVkstxXGqRccyXjuleNfpYNW2KjNI7ai1olbUUQeKdoqKgdKCIAKBYAmYGCDAJiSbTXZz9ly+r3POJiCKVcc/PDNndufb/c7z/t7zve/vVfiGLuXrcO996SWj4FiWcuONs9Nf9TlfGry1tX3eWTVlr3V09XDgUA9dsThJ0yEU0BgzKofxlcWMzo9u7E9YN02sLt75RYF8Ibi3P7l2MGXOXbNxD2uaW9h7MEZ/ygIkKCpSCBRFISuiM6lyFHNmTGbezAaStlXXUFP2388L4HPBm7e3jZtcW9625t1dyoqXN7K3vRsjEMQwAmiqhqIOb5USgUS4Asu2MNMm48oK+OUVMzi/ofr1qRMqfnAm+BnBzdv3TZlYWbr7D6ve5i9rtyClRigcQlV1UHQURQXF26qgIJHSux2kdBHSwUqncWyL+bPO5oYrLtw/rbai9tPwM4KP9/TLe1au5bk3txONRtB1A1QdRdNRlQCKovnp9cBeyqUUPlQKBylsPwjXtokPDDJv5hSWXP3dfY11VfWfhH8G3HG0950nX22+6NHnNxCNRFADBoqqo6oBVNXwP/HBqg/PqHVPgoWwMnBh4zo28USCa75/PgvnND3V1DDu2hH4aeA3N30wU8HZsPCeFxBSwzCCKFoAPKinVDPQvO9aRrWfbpEBeymWbhopLIRwEK6NdC1sxyJtDrF8yZU01JZfe3Zd1VMe/DRwvPfIP5Y/8dgl9786RGFuFE330htAUQ10PUQgYKBpBo7UAC/tCsJLs/DSbCNEGuF6YAvp2j5cCJtUMsn4ikKeX7boSP24svLTwOvXr8/NywvFH125jNaeCo4O5ftAVdPJCkcpKcr333XaUelOOLhoKFLFFQ6up07aKNLyYY5t4vpwCxXXX4sP9LNy6VVMr6+8unFS9V9PKpZSipuXPaG8tfEtFpyTYMW2abhK0D/JBbk5NIwvIxgMcWLA5uOYSTzpkBMNkx3WUHCxHZOevoSvODeioWuCoaEhTvT147gWyeQgF59Xx8u/XzwQCQVzToIHkwNy4S1L6TzaS1m+yntHy0AJoGkBqsuLaZpS7asP6DqxvjQdx5OMHZ1DQXYQpCCeTNL60XHKisIU54VBukRDOltbDtByoBPLMsmKBnj/ufsoLS7wa8K/2js75fVLbiWsC/b0jOFYMpuAplCUn83cGfV+Le9s66ZidC4NE0qJhIK0Heqjpb0b13FJmiYTy/Mozg2yp72LnhP9zJhWTTio8MzazQyZKYbSKZqfvIOmhtpT4D379spb7/419SUOWSGV/xwuwZUaeUWVNDZ8i1feaaX96ABVYwqZ8+0JTKgo5MDhfvYfOoHjuLR1xvjhzDoQNh+0dZFKpmisHYOmClat+5ff0RKJAV577BbmXXTeKfCO3bvk4jvuIBoJUVOYpjTbZldsFDlFdYytbuTpde8zZAmm1JRy7uSxdHUnMAydsKGSnx3Gsl2qSnLp7ksQiw/4Jz0nGmT73oPs/LDdL7F4Xx+vPHIrl89qOgXe2nJA3nXfYhoqbFpjheSEBO92jmVGYx2NdVU88uImNFVn5vTxVIzO57XmfaTSNroqmV5XxgXTqsiOGLz01g7aDsXQED5sYDCJbWdquz8R582Vt3PpjMZT4I8Od8vbll7HuPwe9vUUsyeWx6Cby08vOYdZTfW8ve2Af7A0TSPWN4iQCnnZYVxXEA0FOHy8l/LiXFRV0pdIoWvQcSTGey1tmOm0X8+ubbHlbw8wdWLlKbBppeWPbrib3mO7uWxqigeba8nJymb+rHN9+/uwI4ZhGMR6B+kfTFM2OpfsSMhvmQODpq/SC6CyNA9dU1BUwfGeOPs7urAti7RlUlmSx7bVD5OTFTkFTqfTdz2+avX9q1Y/Q0lBNps+LqSspIj5s5pYs3GXb/4hI4hEQVW9Pq0OWwS4vicL3xq90vKNUtoI6aIIb90hkUjwix9/hzsXXf5h5ZjiSae1zO6uFvnA725m+aZSQkGdovwcptfVsGVPO0nTRvd79CctMVOKvlEow1Ah/EDwerffSl1cx8JxbN74052ke4+EZs+enT4NbA6daP7jn5+94PYVG8jKy0XXPaMwsGw5bAoZR/LMIWOLw5fvUJnbVyw9sIsQLgiXRH8/l1/axHMP/YqQEfA3fsYWd+/vlFfe9iCtHx3xvdh7v5pqDMMyKR4ZAob1Zsagk/Bh5V4ArouZHmJUQTavL/8Nsb5k/dwLp+87I7i3L7GqeVvLzxbd+zh9A0nCoagPzwA95sgQ8Elb91R63pxRmonDg5p+91tx1/WcNX7sunOn1s49ox+PLKZtW77w92aWPPy0XzrRcBhFGzZ/P1zVV5mZP4bjP6nYAwtSpkk4FGDZTQupqShdfdlF5/3k/04gIz+appVc/+8dkdsfe5aWvQcxIhH0gOfBnhdnkCPvSfp4iZASx3awUklqasr57U0L2d/eNefexQve+FIz18if2jqONCqauuOJF9fx7Otvc/xYN+hGJgBv0vRSL8ERLo7rgGWRV1TAgtkXUF4yau3S6+Z/7yuPt5/e8PM7H72mYWLVk//cvIPWg52ciA9g245/8vNzs5hUXcHFTQ0kU+bq+2+86rS0ngn+hQP950X8dde/MfD/AAfkxUyXHbg8AAAAAElFTkSuQmCC" alt="">
									</a>
									<?php endif ?>
								</div>
							<?php endif ?>

						</div>
						<?php if (!empty($suggested) && false): ?>

							<?php foreach ($suggested as $productIDSuggested => $value): ?>
								
								<div class="border-0 border-blues card borderRadius30 w-100 mb-5 no-break">
								  <div class="border-blue border-left card-header pl-4 px-3 py-0" style="border-radius: 30px 30px 0px 0px; background-image: url(<?php echo $this->Html->url('/img/cot_fondo.png')?>);">
								  	<div class="row" style="margin-right: -18px;">
								  		<div class="col-md-12  font-weight-lighter font22 pt-2 text-center text-white pb-2" style="border-radius: 0px 30px 0px 0px;">
								  			¡Dale un vistaso a nuestos <br>
								    		<span class="font-weight-bolder" style="color: #ffaf08 !important; font-weight: bold !important;">
								    			ACCESESORIOS OPCIONALES!
								    		</span>  <br> <?php echo $value["part_number"] ?> - <?php echo $value["name"] ?> 
								    		<?php if (!empty($value["aditional"])): ?>
								    			| <?php echo $value["aditional"]["part_number"] ?> - <?php echo $value["aditional"]["name"] ?> 
								    		<?php endif ?>
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
				<div class="notesdescrip no-break">
				<?php if (!in_array($datosQuation['Quotation']['notes_description'],$arrayVarcharPermitido)) : ?>
					<p class="mt-3"><?php echo $datosQuation['Quotation']['notes_description'] ?></p>
					<hr>
				<?php endif ?>
				</div>

				<?php if ($datosQuation['Quotation']['conditions'] != ''): ?>
					<div class="conditionsview condiciones_negociacion mt-2 mb-0 no-break">
						<h2>CONDICIONES DE LA NEGOCIACIÓN:</h2>
						<p><?php echo $datosQuation['Quotation']['conditions'] ?></p>.
					</div>
				<?php endif ?>

				<?php if (!is_null($datosQuation['Quotation']['garantia']) && $datosQuation['Quotation']['garantia'] != false): ?>
					<div class="conditionsview condiciones_negociacion mb-0 no-break">
						<h2>Garantía general</h2>
						<div id="garantiaGeneral"><?php echo $datosQuation['Quotation']['garantia'] ?></div>.
					</div>
				<?php endif ?>

				<b>Cordial saludo,</b>
				<div class="datasesorview no-break">
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
						</b>
						<br>
						<?php if ($datos["ProspectiveUser"]["country"] == "Colombia"): ?>
							<?php echo 'CEL: '.$datosUsuario['User']['cell_phone'] ?><br>
							<?php echo 'TEL: '.$datosUsuario['User']['telephone'] ?><br>							
						<?php endif ?>
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
				 <div class="table-responsive mt-4 classnoprint">
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
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js'),				array('block' => 'jqueryApp'));
?>

<?php if (isset($edata)): ?>
	<script>
		var edata = "<?php echo $edata ?>";
		var flujo = "<?php echo $datos['ProspectiveUser']['id'] ?>";
	</script>
	
	<?php echo $this->Html->script("controller/quotations/actions.js",			array('block' => 'AppScript'));  ?>
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
<!-- Modal -->
<div class="modal fade " id="modal_datos_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg4 modal-dialog-scrollable  modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Detalle de producto</h5>
      </div>
      <div class="modal-body" id="body_modal_datos_product">

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

	echo $this->Html->script("controller/quotations/facturas.js",			array('block' => 'AppScript')); 
	echo $this->Html->script("magnificpopup.js",			array('block' => 'AppScript')); 

	echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

	echo $this->Html->script("lib/jquery.zoom.js",			array('block' => 'AppScript')); 
	echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
?>

 <script>
 	const FLUJO_ACTUAL = '<?php echo $datos["ProspectiveUser"]["id"]; ?>';
 </script>


<style media="print">
	<?php if ( (AuthComponent::user("id") && AuthComponent::user("role") != "Gerente General" && !$notPermitido) || (AuthComponent::user("id") && !$notPermitido ) ): ?>
	<?php elseif($notPermitido): ?>
		body{
			display: none !important;
		}

	<?php endif ?>
	footer,.btnDownloadPdf{
		display: none !important;
	}
	.right_col{
		padding: 0px !important;
		margin: 0x !important;
	}
	body.offline,.container {
		background: #fff !important;
	}

	.no-break {
        page-break-inside: avoid;
    }

	.col-mdMiddle {
	    position: relative;
	    width: 48%;
	    padding-right: 15px;
	    padding-left: 15px;
	}
	#sidebar-menu,.btn-outline-primary,a.detail_product, button, span.right,#idflujo,.duplicate,.menu_fixed,.top_nav,.classViewMargen,.reenviarCot,.datosProforma,.classnoprint{
		display: none !important;
	}
	#aRhnzFiE{
		display: none;
	}

	.normal1{
		max-width: 110% !important;
		width: 110% !important;
	}

	<?php if (AuthComponent::user('id')): ?>
		.container {
		    width: 95% !important;
		    padding: 0;
		    max-width: 100%;
		    box-shadow: none !important;
		}
		.blockwhite {
		    box-shadow: none !important;
		}
	<?php endif ?>

</style>
<?php if ($datos["ProspectiveUser"]["from_bot"] == 1): ?>
<?php endif ?>
	<?php $this->start('AppScript');  ?>

<?php if (!AuthComponent::user("id")): ?>
	
									<script
    src='https://sleekflow.io/whatsapp-button.js?1'
    async
    onLoad="whatsappButton({
    buttonName:'',
    buttonIconSize: '30',
    brandImageUrl:'https://crm.kebco.co/logoWpp.jpg',
    buttonMargin:'false',
    brandName:'KEBCO SAS',
    brandSubtitleText:'Equipos Industriales',
    buttonSize:'large',
    buttonPosition:'right',
    callToAction:'Contactar',
    phoneNumber:'573014485566',
    welcomeMessage:'Hola 👋, te saludamos de kebco equipos industriales',
    prefillMessage:'Hola quiero hablar con *<?php echo mb_strtoupper($datosUsuario['User']['name']) ?>* sobre la cotización *<?php echo $datosQuation['Quotation']['codigo'] ?>*',
    })"
    >
</script>

<?php endif ?>
		<style>
		#wa-cta-button-powered,.wa-powered-by-label{
			display: none !important;
		}
		svg{
			display: block !important;
		}
		</style>	
	<?php $this->end();?>

	
<?php 
	$this->start('AppScript'); ?>

	<script>

		$(".imglink").click(function(event) {
			event.preventDefault();
			$("#ppalImg").attr("src",$(this).attr("href"));
			$('img#ppalImg')
		    .wrap('<span style="display:inline-block"></span>')
		    .css('display', 'block')
		    .parent()
		    .zoom();
		});

	</script>

<?php
	$this->end();
 ?>
