<?php $whitelist = array('127.0.0.1','::1' ); ?>
<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<div class="container p-0">
	<div class=" blockwhite spacebtn20">
		<div class="row">
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
						<div class="col-md-3 item_menu_import">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands')) ?>">
								<i class="fa fa-list-alt d-xs-none vtc"></i>
								<span class="d-block"> Pedidos a Proveedores</span>
							</a>
						</div>
						<div class="col-md-3 item_menu_import">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands','internacional')) ?>">
								<i class="fa fa-list-alt d-xs-none vtc"></i>
								<span class="d-block"> Pedidos Prov Internacionales</span>
							</a>
						</div>
					<?php endif ?>
					<div class="col-md-3 item_menu_import">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'add_import')) ?>">
							<i class="fa d-xs-none fa-cart-plus vtc"></i>
							<span class="d-block"> Crear solicitud Interna</span>
						</a>
					</div>	
					<?php $roles = array(Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Logística')) ?>
						<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
						<div class="col-md-3 item_menu_import">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'import_ventas')) ?>">
								<i class="fa d-xs-none fa-dropbox vtc"></i>
								<span class="d-block"> Reposición de Inventario</span>
							</a>
						</div>
					<?php endif ?>	
					
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
					<div class="col-md-3 item_menu_import activeitem">
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
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				
				<h3 class="text-center">
					Configurar más productos
				</h3>
				<div class="typeahead__container">
					<div class="typeahead__field">
						<span class="typeahead__query">
							<input class="js-typeahead" type="search" autofocus autocomplete="off" placeholder="Busca tu producto por nombre o referencia">
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">

				<div class="contenttableresponsive">
					<div class="row">
						<div class="col-md-12">
							<h2 class="text-center mb-3">
								Productos actualmente configurados
							</h2>
							<?php 
								$nameBrand =  null;

								if (!empty($products)) {
									$numBrand = 0;
									foreach ($products as $key => $value) {
										if ($numBrand == 0) {
											$nameBrand = $key;
											break;
										}
										$numBrand++;
									}
								}

								if (isset($this->request->query["brand"]) && !empty($this->request->query["brand"])) {
									$brandActual = $this->Utilities->getInfoByBrand($this->request->query["brand"]);
									$nameBrand   = empty($brandActual["brand"]) ? null : $brandActual["brand"]["Brand"]["name"];
								}

							 ?>
						</div>
						<div class="col-md-12">
							
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<?php $num = 0; ?>
							<?php foreach ($products as $marca => $value): ?>
								<li class="nav-item" role="presentation">
								    <button class="nav-link <?php echo !is_null($nameBrand) && $nameBrand == $marca  ? "active" : ""; $num++; ?>" id="<?php echo 'MARCA_'.md5($marca); ?>-tab" data-toggle="tab" data-target="#<?php echo 'MARCA_'.md5($marca); ?>" type="button" role="tab" aria-controls="<?php echo 'MARCA_'.md5($marca); ?>" aria-selected="true"><?php echo $marca ?></button>
								  </li>	
							<?php endforeach ?>
						</ul>
						</div>
						<div class="col-md-12">
							<div class="tab-content py-4 pl-2" id="myTabContent">
								<?php $num = 0; ?>
								<?php foreach ($products as $marca => $categorias): ?>
									<div class="tab-pane fade <?php echo !is_null($nameBrand) && $nameBrand == $marca  ? "show active" : ""; $num++; ?>" id="<?php echo 'MARCA_'.md5($marca); ?>" role="tabpanel" aria-labelledby="<?php echo 'MARCA_'.md5($marca); ?>-tab">
										
										<div class="row">
  											<div class="col-2">
  												<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
  													<?php $numInt = 0; ?>
  													<?php foreach ($categorias as $categoria => $productos): ?>
  														<button class="nav-link <?php echo $numInt == 0 ? "active" : ""; $numInt++; ?>" id="v-pills-<?php echo 'MARCA_'.md5($marca); ?>_<?php echo md5($categoria) ?>-tab" data-toggle="pill" data-target="#v-pills-<?php echo 'MARCA_'.md5($marca); ?>_<?php echo md5($categoria) ?>" type="button" role="tab" aria-controls="v-pills-<?php echo 'MARCA_'.md5($marca); ?>_<?php echo md5($categoria) ?>" aria-selected="true">
  															<?php echo $categoria; ?>
  														</button>														
													<?php endforeach ?>
  												</div>
  											</div>
  											<div class="col-10">
  												<div class="tab-content" id="v-pills-tabContent">
  													<?php $numInt = 0; ?>
  													<?php foreach ($categorias as $categoria => $productos): ?>
  														<div class="tab-pane fade <?php echo $numInt == 0 ? "show active" : ""; $numInt++; ?>" id="v-pills-<?php echo 'MARCA_'.md5($marca); ?>_<?php echo md5($categoria) ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo 'MARCA_'.md5($marca); ?>_<?php echo md5($categoria) ?>-tab">
  															<div class="row">
  																<?php foreach ($productos as $key => $product): ?>
																	<div class="col-md-6">
																		<?php $dataProducto = $this->Utilities->getQuantityBlock($product["Product"]); ?>
																		<div class="card card-body">
															                <div class="media align-items-center align-items-lg-start text-center text-lg-left flex-column flex-lg-row">
															                    <div class="mr-2 mb-3 mb-lg-0"> 
															                    	<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
																					<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($product['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="100px" height="100px" class="imgmin-product">
															                    </div>
															                    <div class="media-body">
															                        <h6 class="media-title font-weight-semibold"> 
															                        	<b>REF: </b><?php echo h($product['Product']['part_number']); ?>&nbsp; - <?php echo $this->Text->truncate(h($product['Product']['name']), 100,array('ellipsis' => '...','exact' => false)); ?>&nbsp;
															                        </h6>
															                        <ul class="list-inline list-inline-dotted mb-3 mb-lg-2">
															                            <li class="list-inline-item">
															                            	<b>Marca: </b> <?php echo h($product['Product']['brand']); ?>&nbsp;
															                            </li>
															                            <li class="list-inline-item"><a href="#" class="text-muted" data-abc="true">Mobiles</a></li>
															                        </ul>
															                        <p class="mb-1">
															                        	<?php 
																							$categorias = $categoriesData[$product["Product"]["category_id"]]; 
																						?>
																						<b>Categoría: </b> <?php echo str_replace("-->", " <b>|</b> ", $categorias);?>
															                        </p>
															                        <ul class="list-inline list-inline-dotted mb-1">
															                        	<li class="list-inline-item">
															                        		<b>Stock Mínimo: </b> <?php echo $product["Product"]["min_stock"] ?>
															                        	</li>
																					    <li class="list-inline-item">
																					    	<b>Punto de reorder: </b> <?php echo $product["Product"]["reorder"] ?>
																					    </li>
																					    <li class="list-inline-item">
															                        		<b>Stock Máximo: </b> <?php echo $product["Product"]["max_cost"] ?>
															                        	</li>
															                        </ul>
															                        <ul class="list-inline list-inline-dotted mb-0">
															                        	<li class="list-inline-item">
															                        		<a class="btn btn-outline-primary" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($product['Product']['id']))) ?>"><i class="fa fa-eye vtc"></i> Ver producto</a>
															                        	</li>
																					    <li class="list-inline-item">
																					    	<a class="btn btn-outline-warning" href="<?php echo $this->Html->url(array('action' => 'edit', $product['Product']['id'],"1","2" )) ?>"> <i class="fa fa-pencil vtc"></i> Editar producto</a>
																					    </li>
																					    <li class="list-inline-item">
																					    	<?php 

																					    		if (isset($partsData[$product["Product"]["part_number"]])) {
																					    			if (empty($partsData[$product["Product"]["part_number"]])) {
																					    				$total = $product["Product"]["max_cost"];
																					    			}else{
																					    				$actual = 0;
																					    				if (!is_null($product["Product"]["transito"])) {
																					    					$actual+=$product["Product"]["transito"];
																					    				}
																					    				foreach ($partsData[$product["Product"]["part_number"]] as $key => $value) {
																					    					$actual+=$value["total"];
																					    				}
																					    				if($actual >= $product["Product"]["max_cost"]){
																					    					$total = 0;
																					    				}else{
																					    					$total = $product["Product"]["max_cost"] - $actual;
																					    				}
																					    			}
																					    		}else{
																					    			$total = $product["Product"]["max_cost"];
																					    		}


																					    	?>
																					    	<a class="btn btn-outline-success solicita" href="<?php echo $this->Html->url(array('action' => 'solicitar', $product['Product']['id'], )) ?>" data-total="<?php echo $total; ?>"> <i class="fa fa-plus vtc"></i> 
																					    		Solicitar <?php echo $total ?> unidades
																					    	</a>
																					    </li>
																					    <?php if (!is_null($product["Product"]["imagen_categoria"])): ?>
																					    	<li class="list-inline-item">
																						    	<a class="btn btn-outline-info test-popup-link" href="<?php echo $this->Html->url('/img/categories/'.$product["Product"]["imagen_categoria"]) ?>"> <i class="fa fa-image vtc"></i> Ver imagen de referencia</a>
																						    </li>
																					    <?php endif ?>
															                        </ul>
															                    </div>
															                    <div class="mt-3 mt-lg-0 ml-lg-3 text-center">
															                    	<?php echo $this->element("products_block",["producto" => $product["Product"],"inventario_wo" => $partsData[$product["Product"]["part_number"]], "reserva" => isset($partsData["Reserva"][$product["Product"]["part_number"]]) ? $partsData["Reserva"][$product["Product"]["part_number"]] : null]) ?>
															                    </div>
															                </div>
															        	</div>
															         </div>
																<?php endforeach ?>
  															</div>								

  														</div>													
													<?php endforeach ?>
  												</div>
  											</div>
  										</div>

										

									</div>
								<?php endforeach ?>
							</div>
						</div>
					
					</div>
				</div>

			</div>
		</div>
	</div>
</div>





<script type="text/javascript">
	
	var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
	var referencias = <?php echo json_encode(Set::extract($products, "{n}.Product.part_number" )); ?>

</script>


<?php 
	echo $this->Html->css('magnific-popup.css');
	echo $this->Html->script("magnificpopup.js?".rand(),			array('block' => 'AppScript')); 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("lib/jquery.typeahead.js",	array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/product/reorder_list.js?".rand(),				array('block' => 'AppScript'));
?>


