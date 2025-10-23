
<?php 
	$importacion = false;
	$imports = array();

   	if(!empty($datosFlujo["Import"]["id"]) && empty($datosFlujo["Imports"]) ){
   		$imports[] 		= $datosFlujo["Import"];
   		$importacion 	= true;
   	}else if(!empty($datosFlujo["Imports"])){
   		$importacion 	= true;
   		$imports 		= $datosFlujo["Imports"];
   	}
 ?>

 <div class="container p-0 containerCRM">

<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
	     <i class="fa fa-1x flaticon-growth"></i>
	    <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
</div>

<div class=" blockwhite spacebtn20">
	<div class="row ">
		<div class="col-md-12">
			<h2 class="titleviewer">Agregar despacho para la orden #: <?php echo $orderData["Order"]["prefijo"] ?>-<?php echo $orderData["Order"]["code"] ?> asociada al flujo 
				<div class="dropdown d-inline">
				  	<a class="bg-blue btn btn-sm btn-success dropdown-toggle p-1 rounded text-white" href="#" role="button" id="dropdownMenuLink_<?php echo md5($orderData["Order"]["prospective_user_id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				   	<?php echo $orderData["Order"]["prospective_user_id"] ?>
				  	</a>

					<div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo md5($orderData["Order"]["prospective_user_id"]) ?>">
					    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $orderData["Order"]["prospective_user_id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($orderData["Order"]["prospective_user_id"]); ?>">Ver flujo</a>
					    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($orderData["Order"]["prospective_user_id"]) ?>" href="#">Ver cotización</a>
					    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $orderData["Order"]["prospective_user_id"] ?>">Ver órden de compra</a>
					</div>
				</div> 
			</h2>

			
		</div>
	</div>
</div>
<div class="blockwhite spacebtn20">
<?php if (($debeVolver)): ?>
	<div class="container">
		<h3 class="notemodal">Se detectó que en la cotización productos cotizados en USD</h3>
		<div class="col-md-12 text-center mb-3">
			<a href="<?php echo $this->Html->url(array("controller" => "quotations", "action" => "view", $this->Utilities->encryptString($cotizacion))) ?>" class="btn btn-success" target="_blank">
				Ver cotización
			</a>
		</div>
		<p class="text-center">Se debe hacer una conversión de dolares a pesos para poder procesar la solcitud de facturación</p>
		<hr>
		<div class="row text-center">
			<div class="col-md-12">
				<h3 class="notemodal">Procesar cambio con el trm del día de facturación: </h3>
			</div>
			<div class="col-md-4 ">
				<select name="trmDia" id="trmDia">
					<?php for ($day=0; $day < 10; $day++) : ?>
						<option value="<?php echo $this->Utilities->getDayTrm( date("Y-m-d",strtotime("- $day day")), $ajuste, $trmActual )[1]; ?>">
							<?php echo $this->Utilities->getDayTrm( date("Y-m-d",strtotime("- $day day")), $ajuste, $trmActual )[0]; ?>
						</option>				
					<?php endfor; ?>
				</select>
			</div>
			<div class="col-md-4">
				<input type="number" name="trmDiaCustom" id="trmDiaCustom" min="0" class="form-control" placeholder="Otro valor">
			</div>
			<div class="col-md-4">
				<a href="#" class="btn btn-warning btn-block" id="btnProcesarCambioDolar" data-flujo="<?php echo $orderData["Order"]["prospective_user_id"] ?>" data-quotation="<?php echo $cotizacion ?>">
					Procesar cambio
				</a>
			</div>

		</div>
	</div>
	<style>
		#btn_guardar_pagado{
			display: none;
		}
	</style>
<?php else: ?>


<?php echo $this->Form->create('Shipping',["type" => "file",'data-parsley-validate'=>true]); ?>
	<div class="row">
		<?php if ($max_hour_shipping >= date("H:i:s") ): ?>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div class="card-header">
						    Direcciones de envío para el cliente: <br> <b><?php echo $cliente["name"]; ?> <?php echo isset($cliente["concesion"]) ? " | Concesión: ". $cliente["concesion"] : '' ?> </b>
						    <a href="javascript:void(0)" class="btnoz btn-group-vertical btnAddressClient" data-toggle="tooltip" data-placement="right" title="Agregar dirección" data-id="0" data-client='<?php echo is_null($orderData["ProspectiveUser"]["clients_natural_id"]) || $orderData["ProspectiveUser"]["clients_natural_id"]  == 0 ? $cliente["clients_legals_id"] : $cliente["id"] ?>' data-type="<?php echo is_null($orderData["ProspectiveUser"]["clients_natural_id"]) || $orderData["ProspectiveUser"]["clients_natural_id"]  == 0 ? "legal" : "natural" ?>" id='btnAddressClient'>
								<span>Añadir nueva dirección <i class="fa fa-plus-circle"></i> </span>
							</a>

						  </div>
					</div>
				</div>
				<div class="card-body">
			  	<?php if (!empty($direcciones)): ?>
			  		
				    <h3 class="card-title">Seleccionar dirección de envío </h3>
				    <div class="card-text">
				    	
				    		<div class="row w-100">
				    			<table class="table table-hovered table-bordered table-striped datosPendientesDespacho w-100" style="width: 100% !important">
				    				<thead>
				    					<tr>
				    						<th class="p-0">Selec.</th>
				    						<th class="p-0">Contacto</th>
				    						<th class="p-0">Ciudad</th>
				    						<th class="p-0">Teléfono</th>
				    						<th class="p-0">Dirección</th>
				    					</tr>
				    				</thead>
				    				<tbody>
				    					
						    			<?php foreach ($direcciones as $key => $value): ?>
						    				<tr>
						    					<td class="p-0 ">
						    						<input type="radio" name="direccion" class="direccionesCliente m-2" required="" value="<?php echo $value["Adress"]["id"] ?>"> 
						    					</td>
						    					<td class="p-0">
						    						<?php echo $value['Adress']['name']; ?>
						    					</td>
						    					<td class="p-0">
						    						<?php echo $value['Adress']['city']; ?>
						    					</td>
						    					<td class="p-0">
						    						<?php echo $value['Adress']['phone']; echo $value['Adress']['phone_two'] != null ? " - ".$value['Adress']['phone_two'] : "" ?>
						    					</td>
						    					<td class="p-0">
						    						<?php echo $value['Adress']['address']; ?> (<?php echo $value['Adress']['address_detail']; ?>
						    					</td>
						    				</tr>
									  	<?php endforeach ?>
				    				</tbody>
				    			</table>
				    		</div>
				    </div>
				<?php else: ?>
					<p class="card-text text-danger">
						No existen direcciones de envío creadas, por favor agregue una nueva
					</p>
			  	<?php endif ?>
			  </div>
			</div>
		<?php endif ?>
		<div class="col-md-4">
			<div class="form-group">

				<?php 

					$optionsType = [];

					if ($max_hour_envoice > date("H:i:s")) {
						$optionsType["1"] = "Facturación";
					}

					if ($max_hour_remission > date("H:i:s")) {
						$optionsType["3"] = "Remisión";
					}

					if ($max_hour_shipping > date("H:i:s")) {
						$optionsType["0"] = "Despacho solo o despacho con remisión (Especificar en nota) ";
					}

					if ($max_hour_envoice > date("H:i:s") && $max_hour_shipping > date("H:i:s")) {
						$optionsType["2"] = "Despacho y facturación";
					}

				 ?>

				<?php echo $this->Form->input('request_type',["label" => "Tipo de solicitud", "options" => $optionsType, "required" => true, "empty" => "Seleccionar" ]); ?>	
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $this->Form->input('order_id',["type" => "hidden", "value" => $order_id]); ?>
				<?php $optionsEntrega = $max_hour_shipping > date("H:i:s") ? ["0"=>"Ninguno","1" => "Envio a domicilio", "2" => "Entrega o recoge en tienda","3"=>"Contraentrega", "4" => "Crédito"] : ["0"=>"Ninguno"];


				 ?>
				<?php echo $this->Form->input('type',["label" => "Tipo de envio", "options" => $optionsEntrega, "required" => true ]); ?>	
				<?php echo $this->Form->input('date_initial',["type" => "hidden", "value" => date("Y-m-d") ]); ?>	
				<?php echo $this->Form->input('state',["type" => "hidden","value" => 0]); ?>		
			</div> 
		</div>
		
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $this->Form->input('envio',["label" => "Enviar correo al cliente","options" => [ "1" => "Si", "0" => "No"], "default" => 0 ]); ?>
			</div>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->input('copias_email',array("type" => "text", "value" => $emails, "label" => "Notificar del envío a estos correos: ")); ?>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->input('email_envoice',array("type" => "email", "label" => "Correo para enviar la factura electrónica", "value" => !empty($lastShipping) ? $lastShipping["Shipping"]["email_envoice"] : '' )); ?>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->input('bodega',array("options"=>["Medellín"=>"Medellín","Bogotá"=>"Bogotá"], "label" => "Bodega donde se saldrán los productos")); ?>
		</div>
		<div class="col-md-6" style=" <?php echo $max_hour_shipping >= date("H:i:s") ? "" : "display:nones2 !important;" ?> ">
			<div class="form-group">
				<?php echo $this->Form->input('note',["label" => "Nota","required"=>true]); ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<?php echo $this->Form->input('rut',["label" => "RUT Cliente (Requerido para facturar)", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "10M", "class" => "dropify"]); ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<?php echo $this->Form->input('orden',["label" => "Orden de compra", "type" => "file", "data-allowed-file-extensions" => "pdf jpg png jpeg gif", "data-max-file-size" => "10M", "class" => "dropify"]); ?>
			</div>
		</div>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">
						Productos a despachar y/o facturar
					</h3>
				</div>
				<div class="col-md-12">
					<?php if (!empty($produtosCotizacion)): ?>
		    		<div class="row">
		    			<?php if ($internacionalLLC): ?>
		    				<?php $num = 0; $i = 1; foreach ($produtosCotizacion as $value): ?>
					    	    <div class="col-md-6">
									<div class="row">
										<div class="col-md-2">
											<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']); ?>
											<img class="img-fluid" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" style="width: 100px">
										</div>
										<div class="col-md-10 modaldatadespacho">
											<div class="dataproductview2">
												<strong class="text-success">Referencia: <?php echo $value['Product']['part_number'] ?> / Marca: <?php echo $value['Product']['brand'] ?></strong>
												<h3 class=""><?php echo $this->Text->truncate(strip_tags($value['Product']['name']), 70,array('ellipsis' => '...','exact' => false)); ?></h3>
												<p class="descriptionview nc dnone">
													<?php echo $this->Text->truncate(strip_tags($value['Product']['description']), 400,array('ellipsis' => '...','exact' => false)); ?>
												</p>

												<div class="row nomr">
													<div class="col-md-3 pdnone">
														<p class="cantidadquote2">Cantidad: <b><?php echo $value['QuotationsProduct']['quantity'] ?></b></p>
													</div>	
													<div class="col-md-9 pdnone">
														<p class="priceview2">Entrega: <b><?php echo $value['QuotationsProduct']['delivery'] ?></b> -
														<span class="statemodald">
															<?php if (!is_null($value["QuotationsProduct"]["id_llc"])): ?>
																<?php switch ($value["QuotationsProduct"]["state_llc"]) {
																	case '0':
																		echo 'Factura creada LLC';
																		break;
																	
																	case '1':
																		echo 'Factura gestionada sin terminar LLC';
																		break;
																	case '2':
																		echo 'Factura gestionada terminada LLC';
																		break;
																	default:
																		echo "";
																} ?>
															<?php else: ?>
																<?php echo $this->Utilities->find_state_dispatches($value['QuotationsProduct']['state']); ?>
															<?php endif ?>
															
														</span></p>
													</div>												
												</div>
											</div>

										<div class="contentsend">
											<div class="contentchecksend text-center">

												<?php $llc = false; ?>
												<?php if (!is_null($value["QuotationsProduct"]["id_llc"]) && $value["QuotationsProduct"]["state_llc"] == 2): ?>
													<?php $llc = true; ?>
												<?php endif ?>

												<?php $estado 				= array('1','6','3','4','2'); ?>
												<?php $estadosImportaciones = array(2); ?>

												<?php 
													$estadoImportacion = 0;

													if($importacion){
														$estadoImportacion = $this->Utilities->validateImportToSend($imports, $value["Product"]["id"], $datosFlujo["ProspectiveUser"]["id"], $value['QuotationsProduct']['quantity']);
													}else{
														if($value['QuotationsProduct']['state'] == 2){
															$estadoImportacion = 5;
														}
													}
												?>
												
												<?php if ( $value['QuotationsProduct']['biiled'] == 1 || in_array($value['QuotationsProduct']['state'],$estado) || in_array($estadoImportacion, $estadosImportaciones) || $llc  ) { ?>
														<?php echo $this->Form->input('productos.'.$i,array('type' => 'checkbox','label' => 'Enviar','value' => $value['QuotationsProduct']['id'], "required" ,"class" => "productsEnvioCheck"));?>
														<?php $num++; ?>
												<?php } else {
													$i = $i - 1;
												} ?>
											</div>
											
											<?php if (!is_null($value["QuotationsProduct"]["id_llc"])): ?>
												
											<?php else: ?>
												<span class="statemodald text-body mt-2"><?php echo $this->Utilities->getStateImport($estadoImportacion); ?></span>
											<?php endif ?>
										</div>


										</div>	

									</div>
								</div>
							<?php $i++; endforeach; ?>
		    			<?php else: ?>
		    				<?php $num = 0; $i = 1; foreach ($produtosCotizacion as $value): ?>
					    	    <div class="col-md-12 border border-light col-md-12">
									<div class="row">
										<div class="col-md-2">
											<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
											<img class="img-fluid" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" style="width: 100px">
										</div>
										<div class="col-md-10 modaldatadespacho">
											<div class="dataproductview2">
												<strong class="text-success">Referencia: <?php echo $value['part_number'] ?> / Marca: <?php echo $value['brand'] ?></strong>
												<h3 class=""><?php echo $this->Text->truncate(strip_tags($value['name']), 70,array('ellipsis' => '...','exact' => false)); ?></h3>
												<p class="descriptionview nc dnone">
													<?php echo $this->Text->truncate(strip_tags($value['description']), 400,array('ellipsis' => '...','exact' => false)); ?>
												</p>

												<div>
													<?php echo $this->element("products_block",["producto" => $value,"inventario_wo" => $partsData[$value["part_number"]] ,"reserva" =>  null ]) ?>
												</div>

												<div class="row nomr">
													<div class="col-md-12 pdnone">
														<p class="cantidadquote2">Cantidad: <b><?php echo $value['OrdersProduct']['quantity'] ?></b></p>
														<div class="cantidadquote2 pl-0" style="padding-left: 0 !important;">
															<?php if ( $value['OrdersProduct']['state'] == 0  ) { ?>

																<?php $estado 				= array('1','6','3','4'); ?>
																<?php $estadosImportaciones = array(2); ?>

																<?php 
																	$estadoImportacion = 0;
																	if (isset($dataProducts[$value["id"]])) {
																		if($importacion){
																			$estadoImportacion = $this->Utilities->validateImportToSend($imports, $value["id"], $datosFlujo["ProspectiveUser"]["id"], $dataProducts[ $value["id"] ]['quantity']);
																		}else{
																			if($dataProducts[ $value["id"] ]['state'] == 2){
																				$estadoImportacion = 5;
																			}
																		}
																	}
																?>

																<?php $posD = strpos(trim(strtolower($value["part_number"])), "ser-") ; ?>
																<?php if ($dataProducts[$value["id"]]['biiled'] == 1 || (isset($cantidades[$value['part_number']]) && $cantidades[$value['part_number']]) || $posD !== false ): ?>
																	<div class="row">
																		
																	
																		<?php if ($max_hour_shipping >= date("H:i:s") || $max_hour_remission >= date("H:i:s")): ?>
																			<div class="col-md-12">
																				<?php echo $this->Form->input('productos.',array('type' => 'checkbox','label' => 'Solicitar envio/remisión','value' => $value['OrdersProduct']['id'], "required" => false, "data-parsley-mincheck" => 1 ,"class" => "productsEnvioCheck mr-1" ,'div' => 'form-check pl-2'));?>
																			</div>																	
																			
																		<?php endif ?>

																		<?php if ($max_hour_envoice >= date("H:i:s")): ?>

																			<div class="pl-3">
																				<?php echo $this->Form->input('productos_factura.',array('type' => 'checkbox','label' => 'Solicitar facturación','value' => $value['OrdersProduct']['id'], "required" => false, "data-parsley-mincheck" => 1 ,"class" => "productsFacturaCheck form-label mr-1", 'div' => 'form-check','style' => 'margin-left: -7px;'));?>
																			</div>
																			<div class="col">
																				<?php echo $this->Form->input('productos_serial.'.$value['OrdersProduct']['id'],array('type' => 'text','label' => 'Número de serie para facturar', "required" => false, "class" => "productsFacturaSerie w-50"));?>
																			</div>	
																		<?php else: ?>																	
																		<?php endif ?>
																	</div>
																<?php else: ?>
																<?php endif ?>
																<span class="statemodald text-body mt-2"><?php echo ($this->Utilities->getStateImport($estadoImportacion)); ?></span>
																	<span class="statemodald ml-3">
																		<?php echo $this->Utilities->find_state_dispatches($dataProducts[ $value["id"] ]['state']); ?>
																	</span>

																<?php //if ( ( $value["OrdersProduct"]["state"] == 0 || $value["OrdersProduct"]["state"] == 1 ) && ( $dataProducts[ $value["id"] ]['biiled'] == 1 || in_array($dataProducts[ $value["id"] ]['state'],$estado) || in_array($estadoImportacion, $estadosImportaciones) )   ): ?>

																	
																	
																<?php //endif ?>
																<?php $num++; ?>
														<?php } else {
															$i = $i - 1;
														} ?>
														</div>
													</div>											
												</div>
											</div>

											


										</div>

									</div>
								</div>
							<?php $i++; endforeach; ?>
		    			<?php endif ?>
			    	
					</div>
		    	<?php endif ?>
		    		<input type="submit" class="btn btn-success float-right mt-4" value="Solicitar despacho" >
				</div>
			</div>
		</div>	
	</div>


<?php echo $this->Form->end(); ?>
<?php endif; ?>
</div>
</div>
<style>
	.dataTables_wrapper {
		width: 100% !important;
	}
</style>
<?php echo $this->element("address"); ?>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/shippings/admin.js?".rand(),				array('block' => 'AppScript'));
?>

<?php echo $this->element("flujoModal"); ?>