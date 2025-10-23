<?php //if (isset($internacional)): ?>

	<?php if (isset($error_products) || isset($error_brands)): ?>

		<h3 class="text-info text-center mb-2">Por favor valida con el área encargada lo siguiente:</h3>

		<?php if (isset($error_products)): ?>
			<h6 class="text-center">Las siguientes números de parte cotizadas no tienen conexión con la plataforma de KEBCO LLC</h6>
			<ul class="text-center list-unstyled">
				<?php foreach ($error_products as $key => $value): ?>
					<li class="badge-info center-margin col-md-3 mt-1">
						<?php echo $value ?>
					</li>	
				<?php endforeach ?>
			</ul>
		<?php endif ?>
		<?php if (isset($error_brands)): ?>
			<h6 class="text-center">Las siguientes marcas no tienen conexión con la plataforma de KEBCO LLC</h6>
			<ul class="text-center list-unstyled">
				<?php foreach ($error_brands as $key => $value): ?>
					<li class="badge-danger center-margin col-md-3 mt-1">
						<?php echo $value ?>
					</li>	
				<?php endforeach ?>
			</ul>
		<?php endif ?>
	<?php else: ?>
		

<?php// else: ?>

<?php if (false): ?>
	<div class="col-md-12">
		<h4 class="text-center text-warning">
			No se ha detectado factura registrada en CRM ni en WO asociada a este flujo. <br> 
			¿ Deseas asociarla ? 

			<a class="btn btn-info info_bill text-white" data-uid="<?php echo $datos['ProspectiveUser']['id'] ?>">
				<i class="fa fa-pencil vtc"></i>
				Ingresar factura
			</a>
		</h4>
	</div>
<?php endif ?>


<?php echo $this->Form->create('FlowStage',array('id' => 'form_administrar_pedido')); ?>
<?php echo $this->Form->hidden('flujo_id',array('value' => $flujo_id)); ?>
<?php echo $this->Form->hidden('flowstage_id',array('value' => $id_etapa_pagado)); ?>
<?php echo $validateTypeCotizacion ?>
<?php 
	
	$inventories = array();

	foreach ($produtosCotizacion as $key => $value) {
		$productData =  $this->Utilities->getQuantityBlock($value["Product"],$flujo_id); 

		$totalGeneral 	= 0;
		if (!empty($inventioWo[$value['Product']['part_number']])) {
			foreach ($inventioWo[$value['Product']['part_number']] as $key => $valueInv) {
				$totalGeneral+= $valueInv["total"];
			}
		}
		$productData["totalDisponible"] = ($totalGeneral+$productData["quantity_back"]) < 0 ? 0 : $totalGeneral+$productData["quantity_back"];

		$totalDisponible = $productData["totalDisponible"];
		$inventories[$value["Product"]["id"]] = $totalDisponible;
	}

	
 ?>
<?php $i = 1; foreach ($produtosCotizacion as $value): ?>
<?php if ($value["QuotationsProduct"]["quantity"] == 0 || $value["QuotationsProduct"]["state"] != 0): ?>
	<?php continue; ?>
<?php endif ?>
<div class="col-md-12">
	<div class="row spacetopbottom">
		<div class="col-md-2">
			<?php $ruta = $this->Utilities->validate_image_products($value['Product']['img']); ?>
			<img class="img-fluid productoImagenData" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>">
		</div>
		<div class="col-md-10">
			<div class="dataproductview2">
				<strong class="text-success">
					Referencia: <?php echo $value['Product']['part_number'] ?> / Marca: <?php echo $value['Product']['brand'] ?> 
					<?php echo $value["QuotationsProduct"]["currency"] == "usd" || $value["QuotationsProduct"]["change"] == 1 ? "- Vendido en USD" : "" ?> 
				</strong>
				<h3 class=""><?php echo $this->Text->truncate(strip_tags($value['Product']['name']), 70,array('ellipsis' => '...','exact' => false)); ?></h3>
				<p class="descriptionview nc dnone"><?php echo $this->Text->truncate(strip_tags($value['Product']['description']), 400,array('ellipsis' => '...','exact' => false)); ?></p>
				<div class="row">
					<div class="col-md-3 p-0">
						<?php 
								$totalDisponible 	=  $inventories[$value["Product"]["id"]];

								$inventories[$value["Product"]["id"]] -= $value["QuotationsProduct"]["quantity"];
						?>
						<?php 
							$importacionTotal 	=  0;

							if($value["QuotationsProduct"]["currency"] == "usd" || $value["QuotationsProduct"]["change"] == 1){
								if ($datos["ProspectiveUser"]["country"] != "colombia") {
									$importacionTotal = $value["QuotationsProduct"]["quantity"];
								}else{
									$importacionTotal = $value['QuotationsProduct']['quantity'] - $totalDisponible;
								}
							}elseif($value['QuotationsProduct']['quantity'] > $totalDisponible){
								$importacionTotal = $value['QuotationsProduct']['quantity'] - $totalDisponible;
							}
						?>
						<?php 
							$style = "";
							if(Configure::read("entregaProductValidation.".$value['QuotationsProduct']['delivery']) <  Configure::read("entregaProductValidation.10-12 días hábiles") && $value['QuotationsProduct']['quantity'] > $totalDisponible){
								$style = "";
							}elseif ($value['QuotationsProduct']['quantity'] > $totalDisponible){
								$style = "";
							}

						 ?>
						<p class="cantidadquote2" style="<?php echo $style ?>" >
							Vendidos: <b><?php echo $value['QuotationsProduct']['quantity'] ?></b><br>
						</p>
					</div>
					<div class="col-md-3 cantidadinv">
						<div>
							<?php echo $this->element("products_block",["producto" => $value["Product"], "flujoBloqueoData" => $flujo_id, "inventario_wo" => $inventioWo[$value['Product']['part_number']],"reserva" => isset($inventioWo["Reserva"][$value["Product"]["part_number"]]) ? $inventioWo["Reserva"][$value["Product"]["part_number"]] : null ]) ?>
						</div>
					</div>							
					<div class="col-md-4 p-0">
						<div class="inputimportacion priceview2" style="    max-height: 150px !important;">
							<?php if ($value['QuotationsProduct']['state'] == 0 ) { ?>		
									<?php if ($value["QuotationsProduct"]["biiled"] == 2): ?>
										<?php $displayStyle = "none"; ?>
									<?php else: ?>
										<?php $displayStyle = !$this->Utilities->validateCategoryService($value["Product"]["category_id"]) && ($value['QuotationsProduct']['quantity'] > $totalDisponible || ($value["QuotationsProduct"]["currency"] == "usd")) ? 'block' :  'none'; ?>	
									<?php endif ?>

									<p style="display: <?php echo $displayStyle ?>" class="mb-0">										
										<?php echo $this->Form->input('importacion_'.$i,array('type' => 'checkbox','label' => 'Pedir por importación','value' => $value['QuotationsProduct']['id'], "class" => "importDataDeliveryCheck", "data-uid" => $value['QuotationsProduct']['id'], "checked" => $displayStyle == 'block' ? true : false, "readonly", $displayStyle, "div" => false ));?>	
									</p>			
							<?php } else { ?>
								<p>	<?php echo $this->Utilities->name_state_product_import($value['QuotationsProduct']['state']); ?> </p>
							<?php } ?>
					 		<?php  if (
					 					$value["QuotationsProduct"]["biiled"] == 2 || 
					 					$this->Utilities->validateCategoryService($value["Product"]["category_id"]) || 
					 					$value['QuotationsProduct']['quantity'] <= $totalDisponible && 
					 					($value["QuotationsProduct"]["currency"] != "usd") 
					 				): ?> 
					 				<p class="mb-0">
										<b class="deliveryData_<?php echo $value['QuotationsProduct']['id'] ?>">Entrega: <?php echo $value['QuotationsProduct']['delivery'] ?></b>	
					 				</p>
					 				<p class="mb-0">
					 					<span class="text-danger">
					 						Nota: Sin solicitud de importación.
					 					</span>
					 				</p>

							<?php endif ?>
							<?php if ( 
										in_array($value["QuotationsProduct"]["biiled"], [0,1,2,3]) && (
											!$this->Utilities->validateCategoryService($value["Product"]["category_id"]) && 
											$value['QuotationsProduct']['quantity'] > $totalDisponible || 
											($value["QuotationsProduct"]["currency"] == "usd") 
										)										
									): ?>

									<?php echo $this->Form->input('timeDelivery.'.$value['QuotationsProduct']['id'],array('type' => 'select','label' => false,'value' => $value['QuotationsProduct']['delivery'], "options" => Configure::read("entregaImport"),"div" => false, 'default' => '4-5 días hábiles' , "class" => "importDeliveryTime_".$value['QuotationsProduct']['id'] ));?>
							
							<?php endif ?>
						</div>
					</div>	
					<div class="col-md-2 p-0">
						<div class="inputimportacion priceview2" style="    max-height: 150px !important;">
							<?php if ( 
										in_array($value["QuotationsProduct"]["biiled"], [0,1]) && (
											!$this->Utilities->validateCategoryService($value["Product"]["category_id"]) && 
											$value['QuotationsProduct']['quantity'] > $totalDisponible || 
											($value["QuotationsProduct"]["currency"] == "usd") 
										)										
									): ?>
								<?php echo $this->Form->input('importacionFinal.'.$value['QuotationsProduct']['id'],array('type' => 'number','label' => false,'value' => $importacionTotal,"div" => false, "readonly", "class" => "form-control mt-1 mb-1" ));?>
							<?php endif ?>
						</div>
					</div>																
				</div>
			</div>
		</div>
	</div>
</div>
<hr>
<?php $i++; endforeach ?>
</form>

<?php endif ?>