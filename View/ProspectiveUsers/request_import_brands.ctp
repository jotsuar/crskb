<?php 
	echo $this->Html->css(array('lib/jquery.typeahead.css'),									array('block' => 'AppCss'));
	$products = array();
	function addProductToArray($product,$products){
		if(array_key_exists($product["id"], $products)){
			$products[$product["id"]]["quantity"] += $product["ImportRequestsDetailsProduct"]["quantity"];
		}else{
			$products[$product["id"]] = array(
				"quantity"  	=> $product["ImportRequestsDetailsProduct"]["quantity"],
				"id"				=> $product["id"],
				"cost"			=> $product["purchase_price_usd"],
				"brand"			=> md5($product["brand_id"])
			);
		}
		return $products;
	}
?>
<?php 
	$marcasArr = array();
	foreach ($requests as $key => $value) {
		$marcasArr[] = $value["Brand"]["id"];
	}
 ?>
 <?php 
    $whitelist = array(
            '127.0.0.1',
            '::1'
        );
     $rolesPermitidos = array(
    	"Gerente General", "Logística","Asesor Comercial"
    );
 ?>
<script>
	var productsTable 	= {};
	var trmActual 		= <?php $trmActual = is_null($trmActual) || $trmActual == false ? 4200 : $trmActual ; echo $trmActual; ?>;
	var factorImport 	= <?php echo $factorImport ?>;
</script>


<div class="col-md-12 col-lg-12 p-0">
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 text-center">
				<span class="pull-right">
					
				</span>				
			</div>
			<div class="col-md-12 text-center">
				<h1 class="nameview">PANEL PRINCIPAL DE COMPRAS</h1>
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'flujos_import')) ?>" class="pull-right btn btn-info">					
					<span class="d-block"><i class="fa flaticon-growth ml-0"></i> Flujos con importaciones en proceso</span>
				</a>
			</div>			
		</div>
	</div>

	<?php if ($movileAccess): ?>
		<?php echo $this->element("order_responsive"); ?>
	<?php endif ?>

<div class="mb-4 subpmenu">
	<div class="col-md-12">
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<h2 class="titlemenuline">GESTIÓN LOGÍSTICA</h2>
			</div>			
			<div class="row pr-2">
					<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
						<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
						<!-- <div class="activesub impblock-color1"> -->
						<div class="col-md-3 item_menu_import <?php echo is_null($internacional) ? "activeitem" : "" ?>">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands')) ?>">
								<i class="fa fa-list-alt d-xs-none vtc"></i>
								<span class="d-block"> Pedidos a Proveedores</span>
							</a>
						</div>
						<div class="col-md-3 item_menu_import <?php echo !is_null($internacional) ? "activeitem" : "" ?>">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands','internacional')) ?>">
								<i class="fa fa-list-alt d-xs-none vtc"></i>
								<span class="d-block"> Pedidos Prov Internacionales</span>
							</a>
						</div>
					<?php endif ?>
					<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
						<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
						<div class="col-md-3 item_menu_import">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'import_ventas')) ?>">
								<i class="fa d-xs-none fa-dropbox vtc"></i>
								<span class="d-block"> Reposición de Inventario</span>
							</a>
						</div>
					<?php endif ?>
					<div class="col-md-3 item_menu_import">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'add_import')) ?>">
							<i class="fa d-xs-none fa-cart-plus vtc"></i>
							<span class="d-block"> Crear solicitud Interna</span>
						</a>
					</div>					
			</div>	
		</div>
		<div class="col-md-6">
			<div class="row">
				<h2 class="titlemenuline">GESTIÓN GERENCIAL</h2>
			</div>
			<div class="row pl-2">
					<div class="col-md-3 item_menu_import">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
							<i class="fa fa-list-alt d-xs-none vtc"></i> <i class="fa fa-plane d-xs-none vtc"></i>
							<span class="d-block"> Gestión y Aprobación</span> </a>
					</div>					
					
					<div class="col-md-3 item_menu_import">
						<a href="<?php echo $this->Html->url(["controller" => "products", "action" => "products_rotation" ]) ?>"><i class="fa d-xs-none fa-cogs vtc"></i>
							<span class="d-block"> Productos configurados</span>
						</a>
					</div>		
					<div class="col-md-3 item_menu_import">
						<a href="<?php echo $this->Html->url(["controller" => "products", "action" => "new_panel" ]) ?>">
							<i class="fa d-xs-none fa-cloud-upload vtc"></i>
							<span class="d-block"> Solicitudes automáticas</span>
						</a>
					</div>
					<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"): ?>						
						<div class="col-md-3 item_menu_import">
							<a href="<?php echo $this->Html->url(["controller" => "ProspectiveUsers", "action" => "solicitudes_internas" ]) ?>">
								<i class="fa d-xs-none fa-users vtc"></i>
								<span class="d-block"> Solicitudes internas</span>
							</a>
						</div>		
					<?php endif ?>	

			</div>
		</div>			
	</div>
</div>


</div>


	<div class="">
		<div class="row">
		<div class="col-md-12 ">

			<a href="<?php echo $this->Html->url(["action" => "index", "controller" => "rejects"]) ?>" class="text-center btn btn-warning float-right btnrjt">
				Inventario rechazado <span class="text-danger">(<?php echo $totalRechazo ?>)</span>
			</a>
			<button type="button" class="btn crearclientej float-right" id="dataImport" data-toggle="modal" data-target="#exampleModalScrollable">
				Importar más productos
			</button>
		<ul class="nav nav-tabs resetostab" id="myTab" role="tablist">
			<?php $numberTab = 0; ?>
			<?php foreach ($requests as $key => $request): ?>
				<li class="nav-item">
					<?php 

						$totalActive = 0;
						foreach ($request["ImportRequestsDetail"]["details"] as $key => $labelValue) {
							if($labelValue["state"] == 1){
								$totalActive++;
							}
						}

					?>
					<?php 
						$clase = "";
						if($numberTab == 0 && !isset($this->request->query["brand_id"])){
							$clase = "active show";
						}elseif (isset($this->request->query["brand_id"]) && !in_array($this->request->query["brand_id"], $marcasArr) && $numberTab == 0) {
							$clase = "active show";
						}else{
							if(isset($this->request->query["brand_id"])){
								if($this->request->query["brand_id"] == $request["Brand"]["id"]){
									$clase = "active show";
								}
							}
						}
					 ?>
					<?php if (!is_null($internacional)): ?>
						<a class="nav-link <?php echo $clase ?> brandsLink" id="<?php echo md5($request["Brand"]["id"]) ?>-tab" data-toggle="tab" href="#<?php echo md5($request["Brand"]["id"]) ?>" role="tab" aria-controls="<?php echo md5($request["Brand"]["id"]) ?>" aria-selected="true" data-brand="<?php echo md5($request["Brand"]["id"]) ?>" data-url="<?php echo $this->Html->url(array("controller"=>"ProspectiveUsers","action" => "request_import_brands","internacional","?" => array("brand_id" => $request["Brand"]["id"]))) ?>">
				    		<?php echo $request["Brand"]["name"] ?> <span class="text-danger">(<?php echo $totalActive ?>)</span>
				    	</a>
					<?php else: ?>
						
				    	<a class="nav-link <?php echo $clase ?> brandsLink" id="<?php echo md5($request["Brand"]["id"]) ?>-tab" data-toggle="tab" href="#<?php echo md5($request["Brand"]["id"]) ?>" role="tab" aria-controls="<?php echo md5($request["Brand"]["id"]) ?>" aria-selected="true" data-brand="<?php echo md5($request["Brand"]["id"]) ?>" data-url="<?php echo $this->Html->url(array("controller"=>"ProspectiveUsers","action" => "request_import_brands","?" => array("brand_id" => $request["Brand"]["id"]))) ?>">
				    		<?php echo $request["Brand"]["name"] ?> <span class="text-danger">(<?php echo $totalActive ?>)</span>
				    	</a>
					<?php endif ?>
			  	</li>
			<?php $numberTab++; endforeach;  ?>
		</ul>
		</div>
		</div>
	</div>

	<div class="row">
	<div class="col-md-12 here">
		<div class="tab-content" id="myTabContent">
			<?php $numberContent = 0; ?>
			<?php foreach ($requests as $key => $request): ?>
				<?php 
					$clase = "";
					if($numberContent == 0 && !isset($this->request->query["brand_id"])){
						$clase = "active show";
					}elseif (isset($this->request->query["brand_id"]) && !in_array($this->request->query["brand_id"], $marcasArr) && $numberContent == 0) {
						$clase = "active show";
					}else{
						if(isset($this->request->query["brand_id"])){
							if($this->request->query["brand_id"] == $request["Brand"]["id"]){
								$clase = "active show";
							}
						}
					}
				 ?>
				<?php $products = array(); ?>
				<div class="tab-pane fade <?php echo $clase ?>" id="<?php echo md5($request["Brand"]["id"]) ?>" role="tabpanel" aria-labelledby="<?php echo md5($request["Brand"]["id"]) ?>-tab" >
					<div class=" spacebtn20">
					<div class="accordion" id="accordionExample">

						<div id="accordion" class="import">
							<div class="col-md-12 py-2">
								<div class="row">
									<div class="col-md-12">
										<?php if (AuthComponent::user("role") == "Gerente General"): ?>
											<label class="containercheck mb-4 mt-1 ml-0 mr-2">
											<input type="checkbox" class="checkAllProducts check_all_<?php echo md5($request["Brand"]["id"]) ?>" value="<?php echo $request["Brand"]["id"] ?>" data-id="<?php echo md5($request["Brand"]["id"]) ?>" data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>">
											<span class="checkmark"></span>
											</label>
											<span class="ml-4 aligncheck">
												Seleccionar todos los productos
											</span>
										<?php endif ?>
										<a href="javascript:void(0)" class="btn btn-info btn-sm pull-right viewInventory" data-id="<?php echo ($request["Brand"]["id"]) ?>">
											<i class="fa fa-eye vtc"></i> Ver inventario de la marca
										</a>
									</div>
									<div class="col-md-6 col-lg-3 minpedido">
										Precio min. de importación <b>
										$ <?php echo number_format($request["Brand"]["min_price_importer"],0,",",".") ?></b> 
									</div>
									<div class="col-md-6 col-lg-3 totalmoneda">
										ÓRDEN EN
									    <input 
									    	type="radio" 
									    	name="options_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	id="cop_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	autocomplete="off" 
									    	checked 
									    	class="changeCurrency currency_brand_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	data-brand="<?php echo md5($request["Brand"]["id"]) ?>" 
									    	data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>"
									    	value="USD"
									    > USD

									    <input 
									    	type="radio" 
									    	name="options_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	id="usd_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	autocomplete="off" 
									    	class="changeCurrency currency_brand_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	data-brand="<?php echo md5($request["Brand"]["id"]) ?>" 
									    	data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>"
									    	value="COP"
									    > COP

									    <div class="ivaMoneda iva_<?php echo md5($request["Brand"]["id"]) ?>">
									    	¿ÓRDEN CON IVA?
									    <input 
									    	type="radio" 
									    	name="impuestos_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	id="si_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	autocomplete="off" 
									    	checked 
									    	class="changeIva iva_brand_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" 
											data-brand="<?php echo md5($request["Brand"]["id"]) ?>" 
									    	value="1"
									    > SI

									    <input 
									    	type="radio" 
									    	name="impuestos_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	id="no_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	autocomplete="off" 
									    	class="changeIva iva_brand_<?php echo md5($request["Brand"]["id"]) ?>" 
									    	data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" 																				
											data-brand="<?php echo md5($request["Brand"]["id"]) ?>" 
									    	value="0"
									    > NO
									    </div>

									</div>
									<div class="col-md-6 col-lg-3 trm">
											TRM ACTUAL:  <b> <?php echo number_format($trmActual, 2, ",",".") ?> $</b> 
									</div>
									<div class="col-md-6 col-lg-3 factor">
											FACTOR DE IMPORTACIÓN:  <b> <?php echo number_format($factorImport, 2, ",",".") ?></b>
									</div>
								</div>
							</div>
							<?php foreach ($request["ImportRequestsDetail"]["otherDetails"] as $keyType => $detailData): ?>
								<div class="card">
								    <div class="card-header reset <?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$keyType) ?>" id="hheading<?php echo md5($keyType) ?>_<?php echo md5($request["Brand"]["id"]) ?> ">
								      <h5 class="mb-0">

								        <button class="btn btn-link bold " data-toggle="collapse" data-target="#collapse<?php echo md5($keyType) ?>_<?php echo md5($request["Brand"]["id"]) ?>" aria-expanded="true" class="text-white" aria-controls="collapse<?php echo md5($keyType) ?>_<?php echo md5($request["Brand"]["id"]) ?>">
								        		<i class="fa fa-chevron-down" id="changeicons"></i>
								          <?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$keyType) ?> <!-- <span class="btn btn-danger" data-toggle="tooltip" data-original-title="Ver y configurar productos"><i class="fa fa-eye vtc"></i></span> -->
								        </button>
								      </h5>
								    </div>

								    <div id="collapse<?php echo md5($keyType) ?>_<?php echo md5($request["Brand"]["id"]) ?>" class="collapse " aria-labelledby="hheading<?php echo md5($keyType) ?>_<?php echo md5($request["Brand"]["id"]) ?>" data-parent="#accordion">
									    <div class="card-body">
									    	<?php foreach ($detailData as $keyDetails => $details): ?>

									    		<?php if (!isset($details["InfoProducts"])): ?>
									    			<?php continue; ?>
									    		<?php endif ?>
							
											<?php $totalCop = 0;	$totalUsd = 0; $totalProducts = 0; $totalQuantity = 0; ?>
											<div class="card mb-4"> 
											    <div class="card-header reset <?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$details["type_request"]) ?> " id="headingOne">
												    <h2 class="mb-0 displayinline">
												        <button class="btn btn-link m-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
												          <p class="m-0"> Solicita 
												          	<?php $usrname = $details["InfoProducts"]["User"]["name"]; $arr = explode(" ", $usrname); echo $arr[0]; ?>
												          	 el <?php echo $this->Utilities->date_castellano3(h($details['created'])) ?> 
											          		<?php //echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$details["type_request"]) ?> 
												          	<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
												          		<!-- (<?php echo $details["prospective_user_id"] ?>) -->
												          		para <?php if(!empty($details["prospective_user_id"])): ?>											
																	<?php echo $this->Utilities->name_prospective($details["prospective_user_id"],true) ?>
																<?php endif; ?>
												          	<?php endif ?>
												          </p>
												        </button>
												    </h2>
											    </div>
											    
											    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
												    <div class="card-body">
												    	<div class="card-text">
												    		<ul class="p-0">
												    			<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
													    			<li class="ml-4">
													    				<b>FLUJO</b>: 
												    					<div class="dropdown d-inline styledrop ">
																			<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($details["id"]) ?>_<?php echo md5($request["Brand"]["id"]) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			    <?php echo $details["prospective_user_id"] ?>
																			</a>
																			<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
																		        <?php if(!empty($details["prospective_user_id"])): ?>									
																		        	<span class="">
																						<a href="#" class="classInformeCliente btn btn-outline-danger" data-uid="<?php echo $details["id"] ?>">
																							Informar demora <i class="fa fa-warning vtc spin"></i>
																						</a>
																					</span>
																				<?php endif; ?>
																          	<?php endif ?>	
																			<span class="mt-1">
																				<a href="#" class="classDeleteImport btn btn-outline-danger" data-uid="<?php echo $details["id"] ?>">
																					Rechazar solicitud <i class="fa fa-ban vtc"></i>
																				</a>
																			</span>

																			<span class="pull-right savebefore mr-1 ">
																				<?php if ($details["type_request"] == 3): ?>
																					<a href="#" class="btn btnReload" data-id="<?php echo $details["id"] ?>">
																						Devolver a reposición
																						<i class="fa fa-retweet vtc"></i>
																					</a>
																				<?php else: ?>
																					<input type="checkbox" id="savebeforebtn" class="mt-1 wishList wish_check_prod_<?php echo md5($request["Brand"]["id"]) ?>" data-wish="<?php echo md5($details["id"]) ?>" data-check="detail_<?php echo md5($details["id"]) ?>" data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" value="<?php echo $details["id"] ?>" data-brand="<?php echo md5($request["Brand"]["id"]) ?>" >
																						 <label for="savebeforebtn">Guardar para después</label><br>
																					
																				<?php endif ?>
																			</span>	

																			<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($details["id"]) ?>_<?php echo md5($request["Brand"]["id"]) ?>">
																			    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $details["prospective_user_id"] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($details["prospective_user_id"]); ?>">Ver flujo</a>
																			    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($details["prospective_user_id"]) ?>" href="#">Ver cotización</a>
																			    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $details["prospective_user_id"] ?>">Ver órden de compra</a>
																			    <a class="dropdown-item getPagos" href="#" data-flujo="<?php echo $details["prospective_user_id"] ?>">Ver comprobante(s) de pago</a>
																			</div>
														 				</div>
																		<?php $relacionados = $this->Utilities->validateOtherDetails($details["prospective_user_id"], $details["import_request_id"]) ?>
																		<?php if ($relacionados): ?>
																			<a href="#" class="viewRelations btn btn-outline-primary" data-id="<?php echo $details["prospective_user_id"] ?>" data-request="<?php echo $details["import_request_id"] ?>" >
																			Ver solicitudes relacionadas <i class="fa fa-eye vtc"></i>
																		</a>
													    			</li>
															<?php endif ?>
												    			<?php else: ?>
													    			<li class="mgb">
													    				<b>Razón de la importación</b>: <?php echo $details["description"] ?>
													    				<span class="mt-1">
																			<a href="#" class="classDeleteImport btn btn-outline-danger" data-uid="<?php echo $details["id"] ?>">
																				Rechazar solicitud <i class="fa fa-ban vtc"></i>
																			</a>
																		</span>
													    				<span class="pull-right savebefore mr-1 " style="margin-top: -30px;">
													    					<input type="checkbox" id="savebeforebtn" class="mt-1 wishList wish_check_prod_<?php echo md5($request["Brand"]["id"]) ?>" data-wish="<?php echo md5($details["id"]) ?>" data-check="detail_<?php echo md5($details["id"]) ?>" data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" value="<?php echo $details["id"] ?>" data-brand="<?php echo md5($request["Brand"]["id"]) ?>" >
																			<label for="savebeforebtn">Guardar para después</label><br>
													    				</span>
													    			</li>
													    			
												    			<?php endif;?>
												    		</ul>
												    		
												    	</div>
														<div class="contenttableresponsive">
														<table class="table-striped table-bordered myTable">
															<thead>
																<tr>
																	<th class="text-center">Añadir</th>
																	<th>Imagen</th>
																	<th>Producto</th>
																	<th>Marca</th>
																	<!-- <th class="size2"># Parte</th> -->
																	<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
																		<th style="min-width: 310px !important">Fecha de entrega</th>
																	<?php endif;?>
																	<!-- <th class="size1">Solicita</th>							 -->
																	<th class="size">Inventario actual</th>							
																	<th class="size">Cant. a importar</th>
																	<th class="size">
																		Costo 
																		<span class="priceUsd usd_brand_<?php echo md5($request["Brand"]["id"]) ?>">USD</span> 
																		<span class="priceCop cop_brand_<?php echo md5($request["Brand"]["id"]) ?>">COP</span>
																	</th>
																	<th class="size">
																		Total 
																		<span class="priceUsd usd_brand_<?php echo md5($request["Brand"]["id"]) ?>">USD</span> 
																		<span class="priceCop cop_brand_<?php echo md5($request["Brand"]["id"]) ?>">COP + IVA 19%</span>
																	</th>
																	<th>Acciones</th>
																</tr>
															</thead>
															<tbody class="os3">
																<?php foreach ($details["InfoProducts"]["Product"] as $idProduct => $value): ?>
																	<?php if ($value["ImportRequestsDetailsProduct"]["state"] == 1): ?>
																		
																	
																		<tr class="os1">
																			<td class="text-center">
																				<label class="containercheck">
																				<input 
																					type="checkbox" 
																					class="checkB check_prod_<?php echo md5($request["Brand"]["id"]) ?> detail_<?php echo md5($details["id"]) ?>" 
																					value="<?php echo $idProduct ?>" 
																					data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-id="<?php echo $value["id"] ?>" 
																					data-calculate="#calculate_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?>"
																					data-margen="#margen_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?>"
																					data-detail="<?php echo md5($details["id"]) ?>" 
																					data-product="<?php echo md5($value["id"]) ?>"
																					data-request="<?php echo $request["ImportRequest"]["id"] ?>" 
																					data-delivery="<?php echo $value["ImportRequestsDetailsProduct"]["delivery"] ?>" 
																					data-detalle="<?php echo $details["id"] ?>" 
																					data-brand = "<?php echo md5($request["Brand"]["id"]) ?>"
																					data-flujo = "<?php echo $details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY") ? $details["prospective_user_id"] : 0 ?>" 
																					data-alternative = "<?php echo $details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY") ? $details["prospective_user_id"] : $this->Utilities->getProspectiveIdVenta($value["id"]) ?>"
																					data-all="check_all_<?php echo md5($request["Brand"]["id"]) ?>"
																					data-type="<?php echo $details["type_request"] ?>"
																					data-envio="submitEnvio_<?php echo md5($request["Brand"]["id"]) ?>"
																					data-marca = "<?php echo $request["Brand"]["id"] ?>" 
																					data-type="<?php echo $details["type_request"] ?>"
																					data-uniq="Kb_<?php echo uniqid() ?>"
																					<?php echo $details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY") ? "checked disabled" : "" ?>
																				>
																				<span class="checkmark aaaa"></span>
																				</label>
																				<?php 

																					if($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")){
																						$totalProducts++;
																						$totalQuantity += $value["ImportRequestsDetailsProduct"]["quantity"];
																					}

																				?>
																			</td>
																			<td>
																				<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
																				<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="55px" height="55px" class="imgmin-product">
																				<?php if (in_array(AuthComponent::user("role"), $rolesPermitidos)): ?>
																				
																			<?php endif ?>
																			</td>
																			<td class="<?php echo !is_null($value["notes"]) ? "nota" : "" ?>">
																				<?php if (!is_null($value["notes"])): ?>
																					<div class="notaproduct">
																						<span class="triangle"></span>
																						<span class="flechagiro">|</span>
																						<div class="text_nota">
																							<small class="creadornota"><b></b></small>
																							<?php echo $value["notes"] ?>
																							<small class="datenota"></small>
																						</div>
																					</div>
																					<p class="cantd">(<?php echo $value["ImportRequestsDetailsProduct"]["quantity"] ?>)</p>  <?php echo $value["name"] ?> <b><?php echo $value["part_number"]?></b>
																				<?php else: ?>
																					<p class="cantd">(<?php echo $value["ImportRequestsDetailsProduct"]["quantity"] ?>)</p>  <?php echo $value["name"] ?> <b><?php echo $value["part_number"]?></b>
																				<?php endif ?>
																			</td>
																			<td>
																				<?php echo $value["brand"]?>
																			</td>
																			<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
																				<td>
																					<?php 
																						$fecha = !is_null($details["deadline"]) ? $details["deadline"] : $this->Utilities->calculateFechaFinalEntrega($details["created"],Configure::read("variables.entregaProductValues.".$value["ImportRequestsDetailsProduct"]["delivery"]));
																						$dataDay = $this->Utilities->getClassDate($fecha);
																					?>
																					<span class="">
																						<?php echo $this->Utilities->date_castellano($fecha); ?>
																					</span>
																					<br>
																					<?php if ($dataDay == 0): ?>
																						<span class="bg-danger text-white">¡Para entrega hoy!</span>
																					<?php elseif($dataDay > 0): ?>
																						<span class="bg-danger text-white">¡Retraso de <?php echo $dataDay ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
																					<?php elseif($dataDay <= -5): ?>
																						<span class="bg-success text-white">¡Para entrega en  <?php echo abs($dataDay) ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
																					<?php else: ?>
																						<span class="bg-warning">¡Para entrega en  <?php echo abs($dataDay) ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
																					<?php endif ?>
																					
																				</td>
																				<?php $products = addProductToArray($value,$products); ?>
																			<?php endif;?>
																			<!-- <td>
																				<?php echo $value["ImportRequestsDetailsProduct"]["quantity"] ?>
																			</td> -->
																			<td class="controlquantity">
																				<?php echo $this->element("products_block",["producto" => $value, "inventario_wo" => $inventioWo[$value['part_number']],"bloqueo" => false, "no_show_total" => true, "reserva" => isset($inventioWo["Reserva"][$value['part_number']]) ? $inventioWo["Reserva"][$value['part_number']] : null ]) ?>
																			</td>
																			
																			<td>
																				<!-- Cantidad -->
																				<input 
																					class="form-control quantityNumber quantity_brand_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?> " 
																					id="qt_prod_<?php echo $value["id"] ?>" 
																					name="quantity" 
																					type="number" 
																					data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-brand="<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-id="<?php echo $idProduct ?>" 
																					value="<?php echo $value["ImportRequestsDetailsProduct"]["quantity"] ?>" 
																					min="0" 
																					<?php echo $details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY") ? "readonly" : "" ?>
																				>
																			</td>
																			<td>
																				<!-- Costo en dolares -->
																				<input 
																					class="form-control priceUsd usd_brand_<?php echo md5($request["Brand"]["id"]) ?> price_usd_brand_<?php echo md5($request["Brand"]["id"]) ?>_<?php echo md5($value["id"]) ?> cost_usd_product_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?>" 
																					id="qt_prod_<?php echo $idProduct ?>" 
																					name="value_cost_usd" 
																					type="text" 
																					data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-brand="<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-currency="usd" 
																					data-id="<?php echo md5($value["id"]) ?>" 
																					value="<?php echo $value["purchase_price_usd"] ?>" 
																					min="0"
																				>
																				<!-- Costo en COP -->
																				<input 
																					class="form-control priceCop cop_brand_<?php echo md5($request["Brand"]["id"]) ?> price_cop_brand_<?php echo md5($request["Brand"]["id"]) ?>_<?php echo md5($value["id"]) ?> cost_cop_product_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?>" 
																					id="qt_prod_<?php echo $idProduct ?>" 
																					name="value_cost_cop" 
																					type="text" 
																					data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-brand="<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-currency="cop" 
																					data-id="<?php echo md5($value["id"]) ?>" 
																					value="<?php echo $value["purchase_price_cop"] ?>" 
																					min="0"
																				>
																			</td>
																			<td>
																				<!-- Total dolares -->
																				<input 
																					class="form-control priceUsd usd_brand_<?php echo md5($request["Brand"]["id"]) ?> total_cost_usd_product_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?>" 
																					id="qt_prod_<?php echo $idProduct ?>" 
																					name="total_cost_usd" 
																					type="text" 
																					data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-brand="<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-currency="usd" 
																					data-id="<?php echo md5($value["id"]) ?>" 
																					value="<?php echo $value["purchase_price_usd"] * $value["ImportRequestsDetailsProduct"]["quantity"] ?>" 
																					min="0" 
																					readonly
																				>
																				<!-- Total COP -->

																				<input 
																					class="form-control priceCop cop_brand_<?php echo md5($request["Brand"]["id"]) ?> total_cost_cop_product_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?>" 
																					id="qt_prod_<?php echo $idProduct ?>" 
																					name="total_cost_cop" 
																					type="text" 
																					data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-brand="<?php echo md5($request["Brand"]["id"]) ?>" 
																					data-currency="cop" 
																					data-id="<?php echo md5($value["id"]) ?>" 
																					value="<?php echo ($value["purchase_price_cop"] * $value["ImportRequestsDetailsProduct"]["quantity"]) * 1.19 ?>"
																					min="0"
																					readonly
																				>
																				<?php $totalCop += (($value["purchase_price_cop"] * $value["ImportRequestsDetailsProduct"]["quantity"])*1.19) ?>
																				<?php $totalUsd += ($value["purchase_price_usd"] * $value["ImportRequestsDetailsProduct"]["quantity"]) ?>
																			</td>
																			<td> 
																				<?php if($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
																					<?php 
																						$datosProductoVenta =  $this->Utilities->getCostProductForImport($details["prospective_user_id"], $value["id"]); ?>
																				<?php else: ?>
																					<?php 
																						$datosProductoVenta =  $this->Utilities->getCostProductForImport($this->Utilities->getProspectiveIdVenta($value["id"]), $value["id"]);  
																					?>
																				<?php endif ?>
																				<?php $margenFinal = $this->Utilities->calculateMargen($trmActual,$this->Utilities->getProductFactor($value["id"]),$value["purchase_price_usd"],$datosProductoVenta,$value["ImportRequestsDetailsProduct"]["quantity"], "usd"); 
																					?> 
																				<span class="viewdetailc btn-outline-primary" style="padding: 0px !important;">
																						<span style="line-height: 0px;border-radius: 50%;" id="margen_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?>">
																						<?php if ($margenFinal < 30): ?>
																							<a class="pointer bg-danger p-1 text-white" data-id="calculate_<?php echo md5($value["id"]) ?>_<?php echo md5($value["id"]) ?>">
																								<?php echo $margenFinal ?>% <i class="fa fa-eye vtc"></i>
																							</a>
																						<?php else: ?>
																							<a class="pointer bg-success p-1 text-white" data-id="calculate_<?php echo md5($value["id"]) ?>_<?php echo md5($value["id"]) ?>">
																								<?php echo $margenFinal ?>% <i class="fa fa-eye vtc"></i>
																							</a>
																						<?php endif ?>

																					</span>
																				</span>
																			</td>
																		</tr>
																		<?php if (isset($value["compositions"])): ?>
																			<tr>
																				<td colspan="12">
																					<div class="col-md-12 p-4">
																						<h3 class="text-center">
																							Producto compuesto por las siguientes referencias:
																						</h3>
																						<div class="row mt-2">
																							<?php foreach ($value["compositions"] as $keyComp => $valueComp): ?>
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
																				</td>
																			</tr>
																		<?php endif ?>
																		<?php if ($details["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>													
																			<tr class="os2">
																				<td colspan="12" id="calculate_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?>" class="removemg usd_brands_<?php echo md5($request["Brand"]["id"]) ?>">
																					<?php $product_id = $value["id"]; $currency = "usd"; $costoProducto = $value["purchase_price_usd"]; ?>
																
																					<?php echo $this->element("trm_import", compact("costoProducto","datosProductoVenta","currency","product_id","margenFinal")); ?>		

																				</td>
																			</tr>
																		<?php else: ?>
																			<tr class="os2">
																				<td colspan="12" id="calculate_<?php echo md5($details["id"]) ?>_<?php echo md5($value["id"]) ?>" class="removemg usd_brands_<?php echo md5($request["Brand"]["id"]) ?>">
																					<?php $product_id = $value["id"]; $currency = "usd"; $costoProducto = $value["purchase_price_usd"]; ?>
																					
																					<?php echo $this->element("trm_import", compact("costoProducto","datosProductoVenta","currency","product_id","margenFinal")); ?>
																				</td>
																			</tr>
																			
																		<?php endif ?>
																	<?php endif ?>
																<?php endforeach ?>
															</tbody>
														</table>
														</div>
												    </div>
											    </div>
											</div>
										<?php endforeach ?>
									    </div>
								    </div>
								</div>
							<?php endforeach ?>
						</div>
						
						
					</div>
					<script>
						productsTable["<?php echo md5($request["Brand"]["id"]) ?>"] = <?php echo json_encode($products); ?>;
					</script>

					<div class="blockwhite spacebtn20 validaempty" id="tableOrder_<?php echo md5($request["Brand"]["id"]) ?>"></div>
					
					<div class=" blockwhite spacebtn20">
						<h2 class="text-center text-primary mb-4">DILIGENCIA LOS DATOS DE LA ORDEN</h2>

						<div class="row mt-2">
							<div class="col-md-6">
								<div class="input textarea">
									<label for="">Razón de la importación</label>
									<textarea name="razon" placeholder="Por favor ingresa la razón por cual solicitas la importación." id="razon_<?php echo md5($request["Brand"]["id"]) ?>"  class="form-control"></textarea>
								</div>
								<div class="input textarea border pl-1 pr-1 pt-3 pb-1 mt-2 border-info">
									<label for="" class="text-center my-1 w-100">
										Texto al proveedor 								
									</label>
									<?php if (AuthComponent::user("role") == "Gerente General"): ?>
											<br>
											<label for="">
												Seleccionar plantilla de texto al proveedor
											</label>
											<select name="a" id="PlantillaTexto" class="form-control mb-2" data-area="text_brand_<?php echo md5($request["Brand"]["id"]) ?>">
												<option value="">Seleccionar</option>
												<?php foreach ($notas as $key => $value): ?>
													<option value="<?php echo $key ?>"> <?php echo $value ?></option>
												<?php endforeach ?>
											</select>
										<?php endif ?>
									<textarea name="razon" placeholder="" id="text_brand_<?php echo md5($request["Brand"]["id"]) ?>" cols="20" rows="15" class="textProvee form-control mb-3"></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input textarea">
									<label for="">Nota interna para gerencia</label>
									<textarea name="nota" placeholder="Ingresa una nota importante para la gerencia, esto servirá al momento de tomar una desición." id="nota_<?php echo md5($request["Brand"]["id"]) ?>"  class="form-control"></textarea>
								</div>

								<div class="input textarea mt-2">
									<label for="">Comentarios de la orden de compra</label>
									<textarea name="razon" placeholder="Por favor ingresa los comentarios que agregarás en la orden de compra final" id="comentario_<?php echo md5($request["Brand"]["id"]) ?>"  class="form-control"></textarea>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group mt-2">
											<label for="formaPago">Selecciona la forma de pago al proveedor</label>
											<select name="formaPagoCol" id="formaPagoCol" class="form-control forma_pago_cop_brand_<?php echo md5($request["Brand"]["id"]) ?> cop_brand_<?php echo md5($request["Brand"]["id"]) ?> ">
												<?php foreach (Configure::read("PAYMENT_COP") as $key => $value): ?>
													<option value="<?php echo $key ?>"><?php echo $value ?></option>
												<?php endforeach ?>
											</select>

											<select name="formaPagoUsd" id="formaPagoUsd" class="form-control forma_pago_usd_brand_<?php echo md5($request["Brand"]["id"]) ?> usd_brand_<?php echo md5($request["Brand"]["id"]) ?> ">
												<?php foreach (Configure::read("PAYMENT_USD") as $key => $value): ?>
													<option value="<?php echo $key ?>"><?php echo $value ?></option>
												<?php endforeach ?>
											</select>
										</div>
										
									</div>
									<div class="col-md-6">
										<div class="form-group mt-2">
											<label for="">Número de cotización</label>
											<input type="number" name="razon" placeholder="Por favor ingresa el número de cotización" id="cotnumb_<?php echo md5($request["Brand"]["id"]) ?>" value="1" min="1" class="form-control">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group mt-2">
											<label for="">Dirección de entrega</label>
											<input type="text" name="razon" placeholder="Por favor ingresa la dirección de entrega" id="direccion_<?php echo md5($request["Brand"]["id"]) ?>"  class="form-control">
										</div>
									</div>
									<!-- <div class="col-md-4">
										<div class="form-group mt-2">
											<?php if (empty($request["Brand"]["children"])): ?>
												<input type="hidden" id="brandData_<?php echo md5($request["Brand"]["id"]) ?>" value="<?php echo $request["Brand"]["id"] ?>">
											<?php else: ?>
												<label for="brandData_<?php echo md5($request["Brand"]["id"]) ?>">Seleccionar marca del proveedor a enviar</label>
												<select name="brandData_<?php echo md5($request["Brand"]["id"]) ?>" id="brandData_<?php echo md5($request["Brand"]["id"]) ?>" class="form-control">
													<option value="<?php echo $request["Brand"]["id"] ?>"><?php echo $request["Brand"]["name"] ?> - Principal</option>
													<?php foreach ($request["Brand"]["children"] as $keyCh => $valueCh): ?>
														<option value="<?php echo $valueCh["Brand"]["id"] ?>"><?php echo $valueCh["Brand"]["name"] ?>  </option>
													<?php endforeach ?>
												</select>
											<?php endif ?>
										</div>
									</div> -->
									<div class="col-md-12">
										<div class="form-group">
											<label for="tipoCrea_<?php echo md5($request["Brand"]["id"]) ?>">¿En caso de existir una solicitud de importación activa para la marca actual sin gestionar por parte de gerencia que hacer?</label>
											<select name="submitEnvio" id="tipoCrea_<?php echo md5($request["Brand"]["id"]) ?>" required="" class="form-control mb-2 tipoCrea">
												<option value="">Seleccionar</option>
												<option value="1">Crear nueva solicitud</option>
												<option value="2">Agregar a la solicitud activa (Se tomará la más reciente)</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										
										<input type="hidden" id="internacional_<?php echo md5($request["Brand"]["id"]) ?>" value="<?php echo is_null($internacional) ? 0 : 1 ?>">
										<?php if (AuthComponent::user("role") == "Gerente General"): ?>
										<div class="form-group mt-2">
											<label for="submitEnvio_<?php echo md5($request["Brand"]["id"]) ?>">Por favor seleccione como desea guardar la orden</label>
											<select name="submitEnvio" id="submitEnvio_<?php echo md5($request["Brand"]["id"]) ?>" required="" class="form-control mb-2 formaEnvio" data-emails="emailsEnvio_<?php echo md5($request["Brand"]["id"]) ?>">
												<option value="">Seleccionar tipo de guardado</option>
												<option value="0">Guardado normal que requiere aprobación</option>
												<option value="1">Guardado con aprobación y envío al proveedor directamente</option>
												<option value="2">Guardado con aprobación sin envío al proveedor directamente</option>
											</select>
											
											<div class="emailCopia mb-4" id="emailsEnvio_<?php echo md5($request["Brand"]["id"]) ?>" style="display: none">
												<label for="emailCopy">Por favor escriba los correos electrónicos a los que desea enviar una copia de la orden</label>
												<input type="text" id="emailCopy" class="form-control" value="<?php echo $request["Brand"]["email"] ?>">
											</div>
											

										</div>
										<button type="submit" class="btn btn-success float-right envioImportClass mb-4 mt-3" data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" data-brand="<?php echo md5($request["Brand"]["id"]) ?>">
												GENERAR ÓRDEN AL PROVEEDOR
											</button>

										<?php else: ?>
											<input type="hidden" id="submitEnvio_<?php echo md5($request["Brand"]["id"]) ?>" value="0">
											<button type="submit" class="btn btn-success float-right envioImportClass mb-4" data-class="check_prod_<?php echo md5($request["Brand"]["id"]) ?>" data-brand="<?php echo md5($request["Brand"]["id"]) ?>">
											GENERAR ÓRDEN AL PROVEEDOR
											</button>
										<?php endif ?>
									</div>

								</div>
							</div>
						</div>
						<div class="col-md-12 text-right spacebtn20">
							Total Productos: 	
							<b><span class="total_product_brand_<?php echo md5($request["Brand"]["id"]) ?>">
								<?php echo $totalProducts ?>
							</span></b>

							Cantidad total:   
							<b><span class="total_quantity_brand_<?php echo md5($request["Brand"]["id"]) ?>">
								<?php echo $totalQuantity ?>
							</span></b>

							Costo total:    
							<b><span class="priceUsd usd_brand_<?php echo md5($request["Brand"]["id"]) ?> total_usd_brand_<?php echo md5($request["Brand"]["id"]) ?>"> <?php echo $totalUsd ?> USD</span> 
							<span class="priceCop cop_brand_<?php echo md5($request["Brand"]["id"]) ?> total_cop_brand_<?php echo md5($request["Brand"]["id"]) ?>"> <?php echo $totalCop ?> COP</span></b> 

						</div>

						</div>
						
					</div>
					
			
				</div>
			<?php $numberContent++; endforeach ?>
		</div>
	</div>
	</div>

</div>



<!-- Modal -->
<div class="modal fade " id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Importar productos</h5>
      </div>
      <div class="modal-body" id="cuerpoOtro">
        
      </div>
    </div>
  </div>
</div>
<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>

<!-- Modal para crear o editar un producto desde la vista de crear una cotización -->
<div class="modal fade" id="modal_form_products" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_form_products_label"></h2>
      </div>
      <div class="modal-body">
          <div id="modal_form_products_body"></div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata" id="btn_action_products">Crear producto</a>
      </div>      
    </div>
  </div>
</div>


<!-- Modal para crear o editar un producto desde la vista de crear una cotización -->
<div class="modal fade" id="modalRelation" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Solicitudes de importación relacionadas a un flujo </h2>
      </div>
      <div class="modal-body" id="modalRelationBody">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="modalInventario" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg4" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Inventario actual para la marca en gestión </h2>
      </div>
      <div class="modal-body" id="bodyInventario">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

  <!-- <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script> -->


<script>
	 var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";

    var typesSend = ["Enviar para aprobación","Aprobar y enviar al proveedor","Aprobar y no enviar al proveedor" ]

</script>

<?php 
	
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp'));
	echo $this->Html->script(array('http://code.jquery.com/jquery-migrate-1.2.1.js'),array('block' => 'jqueryApp'));
	echo $this->Html->script(array('lib/jquery-ui.min.js'),array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/add_import.js?".rand(),array('block' => 'AppScript'));
	echo $this->Html->script("controller/prospectiveUsers/request_import.js?".rand(),array('block' => 'AppScript'));
	//getPagos
?>

<?php echo $this->element("flujoModal"); ?>


<style>

	.motivoEliminacion .form-group{
		margin-top: 20px;
		margin-bottom: 20px !important;
	}
	.ivaMoneda{
		display:none;
	}
	.os2 .stylegeneralbox2{
		width: 100% !important;
		position: initial;
	}

</style>


<?php echo $this->element("comentario"); ?>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<?php 


echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
 ?>

 <?php echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); ?>
