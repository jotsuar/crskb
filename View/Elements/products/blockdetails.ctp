<?php if (isset($value["Product"]["compositions"])): ?>
	<div class="col-md-12 p-4">
		<h3 class="text-center">
			Producto compuesto por las siguientes referencias:
		</h3>
		<div class="row mt-2">
			<?php foreach ($value["Product"]["compositions"] as $keyComp => $valueComp): ?>
				<div class="p-2 col-md-4 border">
					<p class="mb-0">
						<b>Referencia: </b> <?php echo $valueComp["Product"]["part_number"] ?>
					</p>
					<p class="mb-0">
						<b>Producto: </b> <?php echo $valueComp["Product"]["name"] ?>
					</p>
				</div>
			<?php endforeach ?>
		</div>
	</div>
<?php endif ?>
<div class="editDataForm valores <?php echo 'divFormCantidades_'.$value["Product"]["id"] ?>">
	<?php echo $this->Form->create('Product',array('data-parsley-validate'=>true,'id' => 'formCantidades_'.$value["Product"]["id"],"url" => ["controller" => "products","action" =>"change_quantities", $datosImport["Import"]["id"],$value["Product"]["id"]])); ?>
	<div class="row">
		<div class="col-md-3 mb-3 controlp <?php echo !is_null($datos_producto["Product"]["notes"]) ? "nota" : "" ?>">
			<?php $ruta = $this->Utilities->validate_image_products($datos_producto['Product']['img']); ?>
			<img class="img-fluid imgmin-product w-100" dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($datos_producto['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" style="    max-width: 200px;">
			<?php if (!is_null($datos_producto["Product"]["notes"])): ?>
				<div class="notaproduct">
					<span class="triangle"></span>
					<span class="flechagiro">|</span>
					<div class="text_nota">
						<small class="creadornota"><b></b></small>
						<?php echo $datos_producto["Product"]["notes"] ?>
						<small class="datenota"></small>
					</div>
				</div>
			<?php endif ?>
		</div>
		<div class="col-md-9">
			<div class="data_product_imp mb-0">
				<h5 class="<?php echo !is_null($datos_producto["Product"]["notes"]) ? "nota" : "" ?>">
							
					<p><?php echo $datos_producto['Product']['name']; ?> - REF. <?php echo $datos_producto['Product']['part_number']; ?> </p>						
					<p>Fabricante <?php echo $datos_producto['Product']['brand']; ?></p>						

				</h5> 
			</div>
		</div>
	</div>
	<?php foreach ($value["Product"]["details"] as $keyDetail => $valueDetail): ?>
		<div class="row">
			<div class="col-md-12">
				<p><b>Tipo: </b> <?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$valueDetail["ImportRequestsDetail"]["type_request"]) ?></p>
				<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>
					<p><b>Flujo: </b> <?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?></p>
				<?php endif;?>
				<div class="form-group">
					<label for="Cantidad">Cantidad</label>
					<?php if ($validRole): ?>
											
					<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>

						<?php echo $this->Form->input('quantity.'.$valueDetail['ImportProductsDetail']['id'],array('label' => false,"type"=>"number","div"=>false,'class' => 'form-control bg-secondary','value' => $valueDetail['ImportProductsDetail']['quantity'],'readonly' => true,'id' => 'cantidad_'. $valueDetail['ImportProductsDetail']['id'] ));?>
						<?php else: ?>	
							<?php echo $this->Form->input('quantity.'.$valueDetail['ImportProductsDetail']['id'],array('label' => false,"type"=>"number","div"=>false,'class' => 'form-control editarCantidad_'.$value["Product"]["id"],'value' => $valueDetail['ImportProductsDetail']['quantity'],'readonly' => true,'id' => 'cantidad_'. $valueDetail['ImportRequestsDetail']['id'],"min" => 0 ));?>
						<?php endif; ?>
				<?php else: ?>	
					<?php echo $valueDetail['ImportProductsDetail']['quantity'] ?>
				<?php endif ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	<div class="row">
		<div class="col-md-12">
			<?php if ($validRole): ?>
				<input type="submit" value="Cambiar valores" class='btn btn-success mx-auto d-block mt-2 valores editarCantidad_<?php echo $value["Product"]["id"] ?>' style="display: block !important;">
			<?php endif; ?>
		</div>
	</div>
	</form>
</div>
<div class="table-responsives">
	<div class="mt-1 mb-1">
		<?php foreach ($value["Product"]["details"] as $keyDetail => $valueDetail): ?>
			<?php $cantidadProducto = $valueDetail["ImportProductsDetail"]["quantity"]; ?>
			<div class="row border">
				<div class="col">
					<p><b>Fecha: </b>
						<?php $fecha = $valueDetail["ImportRequestsDetail"]["created"]; ?>
						<?php echo $this->Utilities->date_castellano3($fecha); ?>
					</p>
				</div>
				<div class="col">
					<p><b>Tipo: </b> <?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$valueDetail["ImportRequestsDetail"]["type_request"]) ?></p>
					
				</div>
				
				<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>
					<div class="col">
							<p class="d-inline"><b>Flujo: </b> </p>
							<div class="dropdown d-inline styledrop">
								<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?>
								</a>

								<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ?>">
									<a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($valueDetail["ImportRequestsDetail"]["prospective_user_id"]); ?>">Ver flujo</a>
									<a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ?>" href="#">Ver cotización</a>
									<a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?>">Ver órden de compra</a>
									<a class="dropdown-item getPagos" href="#" data-flujo="<?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?>">Ver comprobante(s) de pago</a>
								</div>
							</div>
							
							
					</div>
					<div class="col">
						<p><b>Cotizador: </b> <?php echo $this->Utilities->get_user_flujo($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ?></p>				
					</div>
					<!-- <div class="col">
						<p><b>Cliente: </b> <?php echo $this->Text->truncate($this->Utilities->name_prospective($valueDetail['ImportRequestsDetail']['prospective_user_id']), 30,array('ellipsis' => '...','exact' => false)); ?> </p>				
					</div> -->
		
					<div class="col">
						<p><b>Margen: </b> 

							<?php $datosProductoVenta =  $this->Utilities->getCostProductForImport($valueDetail["ImportRequestsDetail"]["prospective_user_id"], $value["Product"]["id"]); 

							if(isset($datosProductoVenta["header"]) && $datosProductoVenta["header"] == 3){
								$costoProducto = $value["Product"]["purchase_price_usd"];
							}else{
								$costoProducto = $value['ImportProduct']["currency"] == "cop" ? $value["Product"]["purchase_price_cop"] : $value["Product"]["purchase_price_usd"];
							} ?>
							<?php 
								$product_id = $value["Product"]["id"];
								$currency 	=  $value['ImportProduct']["currency"];

								$margenFinal = $this->Utilities->calculateMargen($trmActual,$this->Utilities->getProductFactor($product_id),$costoProducto,$datosProductoVenta,$cantidadProducto, $value["ImportProduct"]["currency"]); 

								$productosMargen[] = array(
									"reference" => $value["Product"]["part_number"],
									"name" 		=> $value["Product"]["name"],
									"margen" 	=> $margenFinal
								);
							?>
							
							<?php if ($margenFinal < 30): ?>
								<a class="pointer bg-danger p-1 text-white viewMargenDetail aa" data-id="calculate_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($value["Product"]["id"]) ?>">
									<?php echo $margenFinal ?>% <i class="fa fa-eye vtc"></i>
								</a>
							<?php else: ?>
								<a class="pointer bg-success p-1 text-white viewMargenDetail" data-id="calculate_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($value["Product"]["id"]) ?>">
									<?php echo $margenFinal ?>% <i class="fa fa-eye vtc"></i>
								</a>
							<?php endif ?>


						 </p>	
						 <div class="d-none" id="calculate_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($value["Product"]["id"]) ?>">
						 	<?php echo $this->element("trm_import", compact("costoProducto","datosProductoVenta","currency","product_id","margenFinal")); ?>
						 </div>			
					</div>

				<?php else: ?>
					<div class="col">
						<p><b>Solicita: </b> <?php echo $this->Utilities->find_name_adviser($valueDetail["ImportRequestsDetail"]["user_id"]) ?></p>				
					</div>
					<div class="col">
						<p><b>Razón: </b> <?php echo $valueDetail["ImportRequestsDetail"]["description"] ?></p>				
					</div>
					<!-- <div class="col">
						<p>
							<b>Cantidad: </b> 

								<?php echo $valueDetail['ImportProductsDetail']['quantity'] ?>

						 </p>				
					</div> -->
					<div class="col">
						<p><b>Margen: </b>

							<?php 

								//$datosProductoVenta =  $this->Utilities->getCostProductForImport($this->Utilities->getProspectiveIdVenta($value["Product"]["id"]), $value["Product"]["id"]); 
								$datosProductoVenta =  $this->Utilities->getCostProductForImport(0, $value["Product"]["id"]); 

								if(isset($datosProductoVenta["header"]) && $datosProductoVenta["header"] == 3){
									$costoProducto = $value["Product"]["purchase_price_usd"];
								}else{
									$costoProducto = $value['ImportProduct']["currency"] == "cop" ? $value["Product"]["purchase_price_cop"] : $value["Product"]["purchase_price_usd"];
								} ?>

								<?php 
								$product_id 	= $value["Product"]["id"];
								$currency 		=  $value['ImportProduct']["currency"];
								$margenFinal 	= $this->Utilities->calculateMargen($trmActual,$this->Utilities->getProductFactor($product_id),$costoProducto,$datosProductoVenta,$cantidadProducto, $value["ImportProduct"]["currency"]);

								$productosMargen[] = array(
									"reference" => $value["Product"]["part_number"],
									"name" 		=> $value["Product"]["name"],
									"margen" 	=> $margenFinal
								); 
							?>
							
							<?php if ($margenFinal < 30): ?>
								<a class="pointer bg-danger p-1 text-white viewMargenDetail bb" data-id="calculate_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($value["Product"]["id"]) ?>">
									<?php echo $margenFinal ?>% <i class="fa fa-eye vtc"></i>
								</a>
							<?php else: ?>
								<a class="pointer bg-success p-1 text-white viewMargenDetail" data-id="calculate_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($value["Product"]["id"]) ?>">
									<?php echo $margenFinal ?>% <i class="fa fa-eye vtc"></i>
								</a>
							<?php endif ?>

						 </p>	
						 <div class="d-none" id="calculate_<?php echo md5($valueDetail["ImportRequestsDetail"]["id"]) ?>_<?php echo md5($value["Product"]["id"]) ?>">
						 	<?php $noMsg = true; ?>
						 	<?php echo $this->element("trm_import", compact("costoProducto","datosProductoVenta","cantidadProducto","currency","product_id","productosMargen","margenFinal","noMsg")); ?>
						 </div>			
					</div>
								

				<?php endif; ?>
			</div>
			<div class="row text-center">
					<?php if (!empty($valueDetail["ImportProductsDetail"]["delivery"])): ?>
						<?php $fecha = !is_null($valueDetail["ImportRequestsDetail"]["deadline"]) ? $valueDetail["ImportRequestsDetail"]["deadline"] : $this->Utilities->calculateFechaFinalEntrega($fecha,Configure::read("variables.entregaProductValues.".$valueDetail["ImportProductsDetail"]["delivery"]));
									$dataDay = $this->Utilities->getClassDate($fecha); ?>
						<?php if ($dataDay == 0): ?>
							<h2 class="bgs-danger text-danger daysdelivery">
								Se entrega hoy
							</h2>
						<?php elseif($dataDay > 0): ?>
							<h2 class="bgs-danger text-danger daysdelivery">
								Retraso de <?php echo $dataDay ?> día(s) se debió entregar el <?php echo date("Y-m-d",strtotime("-".$dataDay." day")) ?>
							</h2>
						<?php elseif($dataDay <= -5): ?>
							<h2 class="bgs-success text-success daysdelivery">Se entrega en  <?php echo abs($dataDay) ?> día(s)</span>
						<?php else: ?>
							<h2 class="bgs-success text-success daysdelivery">Se entrega en <?php echo abs($dataDay) ?> día(s)</span>
						<?php endif ?>
					<?php endif ?>
			</div>	 
		<?php endforeach; ?>
	</div>
</div>
