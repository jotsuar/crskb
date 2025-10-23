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
<?php echo $this->Form->create('FlowStage',array('id' => 'form_despachado_full','enctype'=>'multipart/form-data')); ?>
<div class="row">
	<div class="col-md-3 col-sm-3">
		<div class="card">
		  <div class="card-header">
		    Direcciones de envío para el cliente: <br> <b><?php echo $cliente["name"]; ?></b>
		    <a href="javascript:void(0)" class="btnoz btn-group-vertical btnAddressClient" data-toggle="tooltip" data-placement="right" title="Agregar dirección" data-id="0" data-client='<?php echo $this->request->data["cliente"] ?>' data-type="<?php echo $this->request->data["type"] ?>" id='btnAddressClient'>
				<span>Añadir nueva dirección <i class="fa fa-plus-circle"></i> </span>
			</a>
		  </div>
		  <div class="card-body">
		  	<?php if (!empty($direcciones)): ?>
		  		
			    <h3 class="card-title">Seleccionar dirección de envío </h3>
			    <div class="card-text">
			    	<div class="card">
					  <ul class="list-group list-group-flush">
					  	<?php foreach ($direcciones as $key => $value): ?>
					  		<li class="list-group-item radiodiv">
					  			<input type="radio" name="direccion" class="direccionesCliente" required="" value="<?php echo $value["Adress"]["id"] ?>"> 
					  			<b>Contacto: </b><?php echo $value['Adress']['name']; ?><br>
								<b>Ciudad: </b><?php echo $value['Adress']['city']; ?><br>|
								<b>Teléfono: </b><?php echo $value['Adress']['phone']; echo $value['Adress']['phone_two'] != null ? " - ".$value['Adress']['phone_two'] : "" ?> <br>
								<b>Dirección de entrega: </b><?php echo $value['Adress']['address']; ?> (<?php echo $value['Adress']['address_detail']; ?>)<br>
					  		</li>
					  	<?php endforeach ?>
					  </ul>
					</div>
			    </div>
			<?php else: ?>
				<p class="card-text text-danger">
					No existen direcciones de envío creadas, por favor agregue una nueva
				</p>
		  	<?php endif ?>
		  	<div class="form-group mt-3">
		  		<?php echo $this->Form->input('flete',array('label' => 'Flete del envio:', 'options' => $flete)); ?>
		  	</div>
		  	<div class="form-group mt-3">
		  		<?php echo $this->Form->input('copias_email',array("type" => "text", "value" => $emails, "label" => "Notificar del envío a estos correos: ")); ?>
		  	</div>
		  </div>
		</div>
	</div>
	<div class="col-md-9 col-sm-9">
		<div class="card">
		  <div class="card-header">
		    Productos a despachar
		  </div>
		  <div class="card-body">
		    <h5 class="card-title"><?php echo $validateTypeCotizacion ?></h5>
		    <div class="card-text">
		    	<?php if (!empty($produtosCotizacion)): ?>
		    		
			    	<?php $num = 0; $i = 1; foreach ($produtosCotizacion as $value): ?>
						<div class="row">
							<div class="col-md-2">
								<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']); ?>
								<img class="img-fluid" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>">
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

									<?php $estado 				= array('1','6'); ?>
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
											<?php echo $this->Form->input('importacion_'.$i,array('type' => 'checkbox','label' => 'Enviar','value' => $value['QuotationsProduct']['id'], "required" ,"class" => "productsEnvioCheck"));?>
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
						<hr>
					<?php $i++; endforeach; ?>
		    	<?php endif ?>
				<div class="form-group">
					<?php 
						echo $this->Form->hidden('prospective_user_id',array('value' => $datosFlujo['ProspectiveUser']['id']));
					?>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<?php 
								echo $this->Form->input('number',array('label' => 'Número de guía','placeholder' => 'Por favor ingresa el número de guía del envío', "class" => "form-control", "required"));
							 ?>
						</div>		
					</div>	
					<div class="col-6">							
						<div class="form-group">
							<?php 
								echo $this->Form->input('conveyor',array('label' => 'Selecciona la transportadora:','options' => $conveyors, "required", "empty" => "Seleccionar"));
							 ?>
						</div>
					</div>			
				</div>	
		
						<div class="form-group">
							<?php echo $this->Form->input('img',array('type' => 'file','label' => 'Por favor adjunta el comprobante del envío que generó la transportadora', "div" => false, "class" => "form-control","accept"=>"image/*")); ?>
						</div>
						<div class="form-group">
							<?php echo $this->Form->input('image_products',array('type' => 'file','label' => 'Por favor adjunta la foto de los productos enviados', "div" => false, "class" => "form-control","accept"=>"image/*")); ?>
						</div>

						

				<label class="cotiza mt-2 d-none">¿Enviar un correo electrónico con la información del despacho al cliente?</label>
				<div class="form-check-inline d-none">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="envioCorreo" required="" checked="" id="inlineRadio1" value="1"> SI
					</label>
				</div>
				<div class="form-check-inline d-none">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="envioCorreo" required="" id="inlineRadio2" value="0"> NO
					</label>
				</div>
		    </div>
		    <?php if ($num > 0 ): ?>		    	
		    	<input type="submit" value="Guardar" class="btn btn-success pull-right mt-3">
		    <?php endif ?>
		  </div>
		</div>
	</div>
</div>
</form>