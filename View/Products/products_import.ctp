<?php 	
$rolesPriceImport = array(
	Configure::read('variables.roles_usuarios.Logística'),
	Configure::read('variables.roles_usuarios.Gerente General')
);
$validRole = in_array(AuthComponent::user("role"), $rolesPriceImport) ? true : false;
$totalValueFinal = 0;
$rolesPermitidos = array(
	"Gerente General", "Logística","Asesor Comercial"
);

$whitelist = array(
	'127.0.0.1',
	'::1'
);


$productosMargen = array();
$productosMargen2 = array();

$ImportProductReject = true;

$cantidadFinal = 0;
$ventasFinal = 0;

?>

<?php  foreach ($importaciones as $value): ?>
	<?php 
		$cantidadFinal+= $value["ImportProduct"]["quantity"];
		if($value["ImportProduct"]["state_import"] > 2){
			$ImportProductReject = false;
		}
	 ?>

<?php endforeach;?>

<script>
	var productsTable 	= {};
	var trmActual 		= <?php $trmActual = is_null($trmActual) || $trmActual == false ? 4200 : $trmActual ; echo $trmActual; ?>;
	var factorImport 	= <?php echo $factorImport ?>;
</script>

	<div class="mt-3">
		<div class=" widget-panel widget-style-2 bg-morado big">
			<i class="fa fa-1x flaticon-tourism d-xs-none"></i>
			<h2 class="m-0 text-white bannerbig">Módulo de Compras</h2>
		</div>
		
		<div class="dataaproba">
			<span class="showHide">
				<i class="fa fa-arrows-alt vtc"></i>
			</span>

			<div class="col-md-12">
			<div class="row dataRowD">
				<div class="col-md-4 col-xs-12 col-sm-12">
					<?php if ($datosImport["Import"]["state"] == 3): ?>
						<h1 class=""> <b>SOLICITUD PENDIENTE</b></h1>
					<?php endif ?> 
					<?php if ($datosImport["Import"]["state"] == 1): ?>
						<h1 class="">
							<b>SOLICITUD APROBADA Y EN PROCESO</b>
						</h1>
					<?php endif ?> 
					<?php if ($datosImport["Import"]["state"] == 4): ?>
						<h1 class=""><b>SOLICITUD RECHAZADA</b></h1>
					<?php endif ?> 
 				
				</div>			
				<div class="col-md-8 col-xs-12 col-sm-12 pull-right text-right">
					<div class="row p-xs-3 pull-right <?php echo $ImportProductReject && $datosImport['Import']['state'] == Configure::read('variables.importaciones.proceso') && $datosImport['Import']['send_provider'] == 1 && AuthComponent::user("role") == "Gerente General" ? "pull-right" : ""  ?> ">
						<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Logística"]) && ($datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud') 
							|| ( $datosImport['Import']['state'] == Configure::read('variables.importaciones.proceso') && $moreItems )
						 ) ): ?>
							<?php if (AuthComponent::user("role") == "Gerente General" ): ?>
								<a href="javascript:void(0)" class="btn btn-info mt-2 viewInventory" data-id="<?php echo ($datosImport["Brand"]["id"]) ?>">
									<i class="fa fa-eye vtc"></i> Ver inventario de alta rotación de la marca
								</a>
							<?php endif ?>
							<button type="button" class="btn btn-warning mt-2 " id="btnAddProduct" >
								Agregar referencia <i class="fa fa-plus vtc"></i>
							</button>
						<?php endif ?>
						<?php if ($ImportProductReject && $datosImport['Import']['state'] == Configure::read('variables.importaciones.proceso') && $datosImport['Import']['send_provider'] == 1 && AuthComponent::user("role") == "Gerente General" || AuthComponent::user("email") == 'logistica@kebco.co' ): ?>
							<a href="#" data-uid="<?php echo $datosImport['Import']['id'] ?>" data-state="2" class="btn_rechazar mt-2 btn btn-danger" title="Rechazar">Rechazar orden en proceso</a>
						<?php endif ?>
						<?php if ($datosImport["Import"]["state"] == 10): ?>
							<?php if (AuthComponent::user("role") == "Gerente General" && $totalEmpresa >= 1): ?>
								<a class="btn btn-primary" href="<?php echo $this->Html->url(["controller" => "import_requests","action"=>"finally_import",$datosImport['Import']["id"], $this->Utilities->encryptString( !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ) ]); ?>">
									Terminar <i class="fa fa-check vtc"></i>
								</a>
							<?php elseif(AuthComponent::user("role") == "Logística" && $totalEmpresa >= 1 ): ?>
								<a class="btn btn-primary" href="<?php echo $this->Html->url(["controller" => "import_requests","action"=>"finally_import",$datosImport['Import']["id"], $this->Utilities->encryptString( !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ) ]); ?>">
									Terminar <i class="fa fa-check vtc"></i>
								</a>
							<?php endif ?>	
						<?php endif ?>

						<?php if ( (Configure::read('variables.roles_usuarios.Gerente General') == AuthComponent::user("role") || Configure::read('variables.roles_usuarios.Logística') == AuthComponent::user("role") ) && $datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud')  ): ?>
							
							<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("email") == 'logistica@kebco.co'): ?>
							
								<button type="button" class="btn btn-secondary mt-2"  data-toggle="modal" data-target="#modalProviderEmailsCopy" >
									Aprobar y enviar al proveedor
								</button>
								<button type="button" class="btn btn-secondary mt-2 " id="sendProviderInfo" data-uid="<?php echo $this->Utilities->encryptString($datosImport['ImportRequest']['id']) ?>">
									Aprobar sin enviar
								</button>	
							<?php endif ?>
							<?php if (!empty($datosImport["ImportRequest"])): ?>
								<button type="button" class="btn btn-secondary mt-2 " data-uid="<?php echo $this->Utilities->encryptString($datosImport['ImportRequest']['id']) ?>" id="dataImportProvider" data-toggle="modal" data-target="#modalProviderView">
									Ver OC
								</button>			
							<?php endif ?>
							<a href="#" data-uid="<?php echo $datosImport['Import']['id'] ?>" class="btn_rechazar btn btn-danger mt-2" title="Rechazar" data-state="1">Rechazar</a>

						<?php elseif(( $datosImport['Import']['state'] == Configure::read('variables.importaciones.proceso') && $moreItems ) && (Configure::read('variables.roles_usuarios.Gerente General') == AuthComponent::user("role") || Configure::read('variables.roles_usuarios.Logística') == AuthComponent::user("role") ) ): ?>
							<button type="button" class="btn btn-outline-primary mt-2" data-uid="<?php echo $this->Utilities->encryptString($datosImport['ImportRequest']['id']) ?>" id="pdfGenerate" data-toggle="tooltip" title="Visualizar OC en .pdf" data-original-title="Visualizar OC en .pdf">
									   <i class="fa fa-file-text-o vtc"></i> VER O.C en PDF
									</button>
						<?php endif ?>
						<?php if ( (Configure::read('variables.roles_usuarios.Gerente General') == AuthComponent::user("role") || AuthComponent::user("email") == 'logistica@kebco.co') && $datosImport['Import']['state'] == Configure::read('variables.importaciones.proceso')): ?>
							<?php if (!empty($datosImport["ImportRequest"]) && $datosImport['Import']['send_provider'] == 0  ): ?>
								<button type="button" class="btn btn-secondary mt-2 " data-uid="<?php echo $this->Utilities->encryptString($datosImport['ImportRequest']['id']) ?>" id="dataImportProvider" data-toggle="modal" data-target="#modalProviderView" data-uid="<?php echo $this->Utilities->encryptString($datosImport['ImportRequest']['id']) ?>">
									Ver OC
								</button>
							<?php endif ?>
						<?php endif ?>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>

<div class="col-md-12 p-0">
	<div class="row osnaider mt-3">
	<?php $i = 1; foreach ($importaciones as $value): ?>
		
	
	<?php 
		if($value["ImportProduct"]["state_import"] > 2){
			$ImportProductReject = false;
		}
	 ?>
	<?php if ($value['ImportProduct']['quantity'] == 0): ?>
		<?php continue; ?>
	<?php endif ?>
	<?php 
	$datos_producto 			= $this->Utilities->data_product($value['ImportProduct']['product_id']);
	if ($solicitud == 1) {
		$datosQuationsProduct 	= $this->Utilities->get_data_quotations_product($value['ImportProduct']['quotations_products_id']);
		$flujo_id 				= $this->Utilities->find_flujoid_quotationid($datosQuationsProduct['QuotationsProduct']['quotation_id']);
	}
	?>
	<?php if ($i == 1): ?>
		<div class="col-md-12 rowdataimp px-4" >
			<div class="row">
				<div class="sizebox">
		            <a href="/CRM/ProspectiveUsers/request_import_brands" class="text-white">
		                <div class="widget-panel widget-style-2 bg-general">
		                    
		                    <h2 class="m-0"><?php echo $value['Import']['code_import'] ?> </h2>
		                    <div>ID IMPORTACIÓN</div>
		                </div>
		            </a>
		        </div>
		        <div class="sizebox">
		            <a href="/CRM/ProspectiveUsers/request_import_brands" class="text-white">
		                <div class="widget-panel widget-style-2 bg-general">
		                    
		                    <h2 class="m-0"><a id="costoFinal"></a>&nbsp; $<?php echo number_format($datosImport["Import"]["total_price"],2,",",".") ?> </h2>
		                    <div>TOTAL COSTO</div>
		                </div>
		            </a>
		        </div>
		        <?php if ( (Configure::read('variables.roles_usuarios.Gerente General') == AuthComponent::user("role") || Configure::read('variables.roles_usuarios.Logística') == AuthComponent::user("role") ) && $datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud')): ?>

		        <div class="sizebox">
		            <a href="/CRM/ProspectiveUsers/request_import_brands" class="text-white">
		                <div class="widget-panel widget-style-2 bg-general">
		                    
		                    <h2 class="m-0"><a id="ventasFinal"></a>&nbsp; </h2>
		                    <div>TOTAL VENTAS</div>
		                </div>
		            </a>
		        </div>
		        <?php endif ?>
				<div class="sizebox">
		            <a href="/CRM/ProspectiveUsers/request_import_brands" class="text-white">
		                <div class="widget-panel widget-style-2 bg-general">
		                    
		                    <h2 class="m-0"><?php echo $cantidadFinal ?></h2>
		                    <div>CANTIDAD ITEMS</div>
		                </div>
		            </a>
		        </div>
				<div class="sizebox">
		            <a href="/CRM/ProspectiveUsers/request_import_brands" class="text-white">
		                <div class="widget-panel widget-style-2 bg-general">
		                    
		                    <h2 class="m-0">$<?php echo number_format($trmActual, 2, ",",".") ?></h2>
		                    <div>TRM ACTUAL</div>
		                </div>
		            </a>
		        </div>
				<div class="sizebox">
		            <a href="/CRM/ProspectiveUsers/request_import_brands" class="text-white">
		                <div class="widget-panel widget-style-2 bg-general">
		                    
		                    <h2 class="m-0"><?php echo number_format($factorImport, 2, ",",".") ?></h2>
		                    <div>FACTOR IMPORTACIÓN</div>
		                </div>
		            </a>
		        </div>  
		        <?php if ( (Configure::read('variables.roles_usuarios.Gerente General') == AuthComponent::user("role") || Configure::read('variables.roles_usuarios.Logística') == AuthComponent::user("role") ) && $datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud')): ?>
				<div class="sizebox">
		            <a href="#" class="text-white">
		                <div class="widget-panel widget-style-2 bg-general mpf">
		                    
		                    <h2 class="m-0"><strong><span id="finalVisible" class="bg-danger"></span></strong> <a href="" class="btn btn-secondary" id="viewDetialMargen" data-toggle="tooltip" title="" data-original-title="Ver detalle de margen">
							<i class="fa fa-eye vtc"></i>
						</a></h2>
		                    <div>MARGEN PROM. FINAL </div>
		                </div>
		            </a>
		        </div> 
		        <?php endif ?> 		                              
		    </div>                        

		    <div class="row">
				<div class="col-md-12 asesorimp imp">
					<p><b>Marca o proveedor: </b><?php echo $value['Product']['brand'] ?> <?php echo $value['Product']['brand_id'] == $datosImport["Brand"]["id"] ? "" : " - ".$datosImport["Brand"]["name"] ?> </p>
					<p><b>Solicita: </b><?php echo $this->Utilities->find_name_lastname_adviser($value['Import']['user_id']); ?></p>
					<?php if ($datosImport["Import"]["state"] == 1 && !is_null($datosImport["Import"]["deadline"])): ?>
						<p><b>Fecha límite: </b><?php echo $value['Import']['deadline']; ?></p>	
					<?php endif ?>
				</div> 

				<div class="col-md-12 asesorimp imp">
					<b>Texto a enviar al proveedor</b> <?php echo $value["Import"]["text_brand"] ?>
					<?php if ($validRole && $datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud')): ?><?php endif ?>
				</div>
				<?php if (!empty($datosImport["Import"]["nota"])): ?>
					<div class="col-md-12 asesorimp imp text-danger text-center px-5">
						 <h2 class="text-danger border border-red py-4 font-italic" style="font-size: x-large;"><b>Nota logística o asesor:</b> <?php echo $value["Import"]["nota"] ?></h2>
					</div>
				<?php endif ?>
			</div>
						<?php if ($solicitud == 1): ?>
							<div class="col-md-12 clienteimp imp"><p><b>Cliente: </b><?php echo $this->Utilities->name_prospective_contact($flujo_id); ?></p></div>
							<div class="col-md-12 cotiimp imp">
								<p><b>Cotización: </b><?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($flujo_id))) { ?>
									<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($flujo_id)) ?>">
										<?php echo $this->Utilities->find_name_document_quotation_send($flujo_id) ?>
									</a>
								<?php } else { ?>
									<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($this->Utilities->find_id_document_quotation_send($flujo_id)))) ?>">
										<?php echo $this->Utilities->find_name_document_quotation_send($flujo_id) ?>
									</a>
								<?php } ?>
								</p>
							</div>
					</div>
				<?php endif ?>
				</div>
		</div>
	<?php endif ?>
	<div class="row">
	<div class="col-md-12 mt-2 mb-2 sdw" id="divData_<?php echo $value["ImportProduct"]["product_id"] ?>">
		<div class="blockwhite cuadro_importacion_id" data-uid="<?php echo $value['ImportProduct']['id'] ?>">
			<?php if ($value['ImportProduct']['state_import'] == Configure::read('variables.control_importacion.nacionalizacion') && $validRole ): ?>
				<div class="content_copie2 d-inline-block">
					<div class="center">
						<a href="javascript:void(0)" class="btn btn-primary product_empresa" data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-state="<?php echo $value['ImportProduct']['state_import'] ?>">PRODUCTO EN LA EMPRESA</a>
						
					</div>
				</div>
			<?php endif ?>
			<div class="content_copie2 d-inline-block  w-50">
				<div class="center">
					<button class="btn btn-info cuadro_importacion_id" data-uid="<?php echo $value['ImportProduct']['id'] ?>">
						Ver detalle seguimiento <i class="fa fa-eye vtc"></i>
					</button>
					
				</div>
			</div>
			<div class="row">
				<div class="col-md-1 controlp <?php echo !is_null($datos_producto["Product"]["notes"]) ? "nota" : "" ?>">
					<?php $ruta = $this->Utilities->validate_image_products($datos_producto['Product']['img']); ?>
					<img class="img-fluid imgmin-product" dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($datos_producto['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>">
					<?php if (!is_null($datos_producto["Product"]["notes"])): ?>
						<div class="notaproduct">
							<span class="triangle"></span>
							<div class="text_nota">
								<small class="creadornota"><b></b></small>
								<?php echo $datos_producto["Product"]["notes"] ?>
								<small class="datenota"></small>
							</div>
						</div>
					<?php endif ?>
				</div>
				<div class="col-md-3">
					<div class="data_product_imp mb-0 mt-2">
						<h3 class="<?php echo !is_null($datos_producto["Product"]["notes"]) ? "nota" : "" ?>">
							
							<p class="upper"><?php echo $datos_producto['Product']['name']; ?> - REF. <?php echo $datos_producto['Product']['part_number']; ?> </p>						
							<p>Fabricante: <?php echo $datos_producto['Product']['brand']; ?></p>						

							<?php if ( 
								(!empty($datosImport["ImportRequest"]) && $datosImport['Import']['send_provider'] == 1 || empty($datosImport["ImportRequest"]) && $datosImport['Import']['send_provider'] == 1 ) && 
								($value['ImportProduct']['state_import'] != Configure::read('variables.control_importacion.nacionalizacion') && $validRole && $value['ImportProduct']['state_import'] != Configure::read('variables.control_importacion.producto_empresa')) 
								&& $value['ImportProduct']['currency'] == "cop"  
							): ?>
							<a href="javascript:void(0)" class="btn btn-outline-warning product_empresa" data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-state="<?php echo $value['ImportProduct']['state_import'] ?>" data-nacional="1">Terminar flujo <i class="fa fa-check"></i></a>
						<?php endif;?>

						<?php if (in_array(AuthComponent::user("role"), $rolesPermitidos)): ?>
							<a href="#" data-id="<?php echo $datos_producto["Product"]["id"] ?>" class="btn btn-incorrecto notesProduct text-white" data-toggle="tooltip" title="" data-original-title="Gestionar notas del producto">
								<i class="fa fa-comments text-white vtc"></i>
							</a>
						<?php endif ?>
						
						<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>									
								<a href="#" class="classInformeCliente btn btn-outline-danger mt-1"  data-toggle="tooltip" title="" data-original-title="Informar demora" data-uid="<?php echo $valueDetail["ImportRequestsDetail"]["id"] ?>"> <i class="fa fa-calendar vtc spin"></i></a>
						<?php endif; ?>
						<?php if (!empty($datosImport["ImportRequest"]) && $datosImport['Import']['send_provider'] == 1 || empty($datosImport["ImportRequest"]) && $datosImport['Import']['send_provider'] == 1 && $validRole ): ?>
							<a href="javascript:void(0)" class="btn-novedadesorder2 btn_reportar_novedad" data-uid="<?php echo $value['ImportProduct']['id'] ?>">REPORTAR NOVEDAD</a>
						<?php endif?>

						<?php if ( (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística")  && $datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud')): ?>
						<a href="#" data-id="<?php echo $datos_producto["Product"]["id"] ?>" data-import="<?php echo $datosImport["Import"]["id"] ?>" class="btn btn-danger  deleteOnlyProduct text-white" data-toggle="tooltip" title="" data-original-title="Rechazar producto de la solicitud">
							<i class="fa fa-times text-white vtc"></i>
						</a>
						<a href="javascript:void(0)" class="btn-novedadesorder2 btn_novedad" style="font-size: 14px;" data-uid="<?php echo $value['ImportProduct']['id'] ?>">
								<i class="fa fa-warning text-white vtc"></i> <span class="indicator" id="count_notificaciones"><?php echo $this->Utilities->number_novedades($value['ImportProduct']['id']); ?></span>
								</a>
					<?php endif ?>
				</h3> 

					</div>
					
				</div>

				<div class="col-md-8 alingnbox new_block_row">
					<h3 class="mb-0 mt-1">CANTIDAD SOLICITADA: <b><?php echo $value['ImportProduct']['quantity'] ?> </b>
						<?php if ($value['ImportProduct']['state_import'] != Configure::read('variables.control_importacion.producto_empresa') ): ?>
					<?php if ($validRole): ?>
						<a href="javascript:void(0)" data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-product_id="<?php echo $value['ImportProduct']['product_id'] ?>" class="btn_editar_cantidad btn_editar_cantidad_<?php echo $value['ImportProduct']['product_id'] ?> btn" data-class='<?php echo 'editarCantidad_'.$value["Product"]["id"] ?>' title="Editar cantidad"><i class="fa vtc fa-pencil"></i> Cambiar</a>
					<?php endif ?>
					<?php endif ?>
					</h3>
						<?php if ( $validRole && $datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud')): ?>
							<div class="row">
								<div class="col-md-12">
									<?php $producto = $this->Utilities->getQuantityBlock($datos_producto["Product"]); ?>
									<?php echo $this->element("products_block2",["producto" => $datos_producto["Product"], "inventario_wo" => $inventioWo[$datos_producto['Product']['part_number']], "importaciones" => true,"bloqueo" => true, "no_show_total" => true, "reserva" => isset($inventioWo["Reserva"][$datos_producto["Product"]["part_number"]]) ? $inventioWo["Reserva"][$datos_producto["Product"]["part_number"]] : null]) ?>
								</div>
							</div>
						<?php  endif; ?>
						<?php if ($validRole && ( $datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud')  ) ) { ?>
							<hr>
							<?php if ($validRole): ?>																
								<!-- <a href="javascript:void(0)" data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-product_id="<?php echo $value['ImportProduct']['product_id'] ?>" class="btn_guardar_cantidad btn_guardar_cantidad_<?php echo $value['ImportProduct']['product_id'] ?> btn btn-success" title="Editar cantidad">Guardar</a> -->
								<b>Costo: </b>
									<?php if ($value["ImportProduct"]["currency"] == "usd"): ?>
										$ <?php echo number_format($value["Product"]["purchase_price_usd"],2,",",".") ?> USD
									<?php else: ?>
										$ <?php echo number_format($value["Product"]["purchase_price_cop"],2,",",".") ?> COP
									<?php endif ?>
									<script>
										var currencyProduct = '<?php echo $value["ImportProduct"]["currency"]; ?>'
									</script>
									<b>Subtotal:</b>
									<?php if ($value["ImportProduct"]["currency"] == "usd"): ?>
										$ <?php echo number_format($value["Product"]["purchase_price_usd"] * $value['ImportProduct']['quantity'],2,",","."); $totalValueFinal+= ($value["Product"]["purchase_price_usd"] * $value['ImportProduct']['quantity']) ?> USD
									<?php else: ?>
										$ <?php echo number_format($value["Product"]["purchase_price_cop"] * $value['ImportProduct']['quantity'],2,",","."); $totalValueFinal+=($value["Product"]["purchase_price_cop"] * $value['ImportProduct']['quantity']) ?> COP
									<?php endif ?>
									
									<?php if (in_array(AuthComponent::user("role"), ["Gerente General"])): ?>
										<b>	Costo máximo de compra: </b>
										
										<?php if (!is_null($datos_producto["Product"]["max_cost"])): ?>
											<span class="valuecmc">	$ <?php echo number_format($datos_producto["Product"]["max_cost"],2,",",".") ?> </span>
											<?php if ($datos_producto["Product"]["max_cost"] < $value["ImportProduct"]["price"]): ?>
												<p class="d-inline aviso" style="animation: myanim 0.9s infinite;">	
													<b>	¡¡¡ El producto supera el costo establecido !!!</b>
												</p>	
											<?php endif ?>
										<?php else: ?>	
											<!-- SIN CONFIGURAR -->
										<?php endif ?>
										<a href="#" data-id="<?php echo $datos_producto["Product"]["id"] ?>" class="btn maxCost text-white" data-toggle="tooltip" title="" data-original-title="Gestionar costo máximo de compra" data-clase="form_costoMax_<?php echo $datos_producto["Product"]["id"] ?>">
											<i class="fa fa-pencil vtc"></i> Asignar / Modificar
										</a>
										<?php echo $this->Form->create('Product',array('data-parsley-validate'=>true,"class"=>"d-inline d-none formCostos",'id' => 'form_costoMax_'.$value["Product"]["id"],"url" => ["controller" => "products","action" =>"update_cost_max",$datosImport["Import"]["id"], $value["Product"]["id"]])); ?>
											<div class="form-group">	
												<label class="d-inline" for="costoProducto_<?php echo $value["Product"]["id"] ?>">Costo máximo de compra</label>
												<input type="number" id="costoProducto_<?php echo $value["Product"]["id"] ?>" min="1" value="<?php echo !is_null($datos_producto["Product"]["max_cost"]) ? $datos_producto["Product"]["max_cost"] : 1 ?>" class="form-control d-inline col-md-1 col-sm-6" name="max_cost" step="any">
												<input type="submit" value="Actualizar costo" class="btn btn-success mt-3 d-inline vtc">
											</div>	
										</form>
									<?php endif ?>
							<?php endif ?>
						<?php } else { ?>
							<!-- <?php echo $value['ImportProduct']['quantity']; ?> -->
						<?php } ?>
								<?php if ($solicitud == 1): ?>
									<p>Se solicitó el <?php echo $this->Utilities->date_castellano($value['Import']['created']); ?></p>
									<p>Quedan <?php echo $datosQuationsProduct['QuotationsProduct']['delivery']; ?>, aproximadamente el 
										<?php echo $this->Utilities->date_castellano($this->Utilities->calculateFechaFinalEntrega($value['Import']['created'],Configure::read("variables.entregaProductValues.".$datosQuationsProduct['QuotationsProduct']['delivery']))); ?></p>
									<?php endif; ?>

								<?php if ($value['ImportProduct']['state_import'] == Configure::read('variables.control_importacion.producto_empresa')): ?>
										<a href="javascript:void(0)" class="btn-novedadesorder mt-3">IMPORTACIÓN FINALIZADA</a>
										<?php foreach ($value["Product"]["details"] as $keyDetail => $valueDetail): ?>
											<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ): ?>
												<a href="<?php echo $this->Html->url(["controller"=>"products","action"=>"validate_import",$value["Product"]["id"],$valueDetail["ImportRequestsDetail"]["prospective_user_id"],$this->Utilities->getQuotationId($valueDetail["ImportRequestsDetail"]["prospective_user_id"]) ]) ?>" class=" reviewProduct"><i class="fa fa-check vtc"></i> VERIFICAR Y TERMINAR FLUJO <?php echo $valueDetail["ImportRequestsDetail"]["prospective_user_id"] ?></a>
											<?php endif; ?>
										<?php endforeach; ?>

									<?php endif ?>
								


							</div>


			</div>

			<?php if (!empty($value["Product"]["details"]) && $value['ImportProduct']['state_import'] != Configure::read('variables.control_importacion.producto_empresa') ): ?>
				
				<?php foreach ($value["Product"]["details"] as $keyDetail => $valueDetail): ?>
					<?php $cantidadProducto = $valueDetail["ImportProductsDetail"]["quantity"]; ?>
					<?php if(!empty($valueDetail["ImportRequestsDetail"]["prospective_user_id"])): ?>
						<?php $datosProductoVenta =  $this->Utilities->getCostProductForImport($valueDetail["ImportRequestsDetail"]["prospective_user_id"], $value["Product"]["id"]); 

						if ($datosProductoVenta["change"] == 1) {
							$ventasFinal += ($datosProductoVenta["original_price"]*$datosProductoVenta["quantity"]); 
						}else{
							$ventasFinal += ( ($datosProductoVenta["price"]/$trmActual) *$datosProductoVenta["quantity"]); 
						}

						if(isset($datosProductoVenta["header"]) && $datosProductoVenta["header"] == 3){
							$costoProducto = $value["Product"]["purchase_price_usd"];
						}else{
							$costoProducto = $value['ImportProduct']["currency"] == "cop" ? $value["Product"]["purchase_price_cop"] : $value["Product"]["purchase_price_usd"];
						} ?>
						<?php 
							$product_id = $value["Product"]["id"];
							$currency 	=  $value['ImportProduct']["currency"];

							$margenFinal = $this->Utilities->calculateMargen($trmActual,$this->Utilities->getProductFactor($product_id),$costoProducto,$datosProductoVenta,$cantidadProducto, $value["ImportProduct"]["currency"]); 

							$productosMargen2[] = array(
								"reference" => $value["Product"]["part_number"],
								"name" 		=> $value["Product"]["name"],
								"margen" 	=> $margenFinal
							);
						?>
					<?php else: ?>
						<?php $cantidadProducto = $valueDetail["ImportProductsDetail"]["quantity"]; ?>
						<?php 
							// $datosProductoVenta =  $this->Utilities->getCostProductForImport($this->Utilities->getProspectiveIdVenta($value["Product"]["id"]), $value["Product"]["id"]); 
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

								$productosMargen2[] = array(
									"reference" => $value["Product"]["part_number"],
									"name" 		=> $value["Product"]["name"],
									"margen" 	=> $margenFinal
								); 
							?>
					<?php endif; ?>
				<?php endforeach ?>
							
				<?php echo $this->element("products/blockdetails",["value" => $value,"validRole" => $validRole,"datos_producto" => $datos_producto,"datosImport" => $datosImport ]) ?>
			<?php endif ?>
			</div><!-- fin blockwhite cuadro_importacion_id -->
			<?php if (!empty($datosImport["ImportRequest"]) && $datosImport['Import']['send_provider'] == 1 || empty($datosImport["ImportRequest"]) && $datosImport['Import']['send_provider'] == 1 ): ?>			

				<div class="row mx-0 my-2">
					<div class="col-md-12 py-2 blockwhite">
						<div class="row bs-wizard">
							<div class="col-md-2 bs-wizard-step complete">
								<div class="progress"><div class="progress-bar"></div></div>
								<span class="bs-wizard-dot <?php echo $validRole ? "state_solicitud" : "" ?> "></span>
								<div class="bs-wizard-info text-center">Solicitud de importación</div>
							</div>

							<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_orden($value['ImportProduct']['state_import']); ?>">
								<div class="progress"><div class="progress-bar"></div></div>
								<span class="bs-wizard-dot <?php echo $validRole ? "state_orden" : "" ?> " data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-state="<?php echo $value['ImportProduct']['state_import'] ?>"></span>
								<div class="bs-wizard-info text-center">Orden creada a proveedor</div>
							</div>
							<?php $endData = $value["Product"]["details"]; ;?>
							<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_proveedor($value['ImportProduct']['state_import']); ?>">
								<div class="progress"><div class="progress-bar"></div></div>
								<span class="bs-wizard-dot <?php echo $validRole ? "state_despacho_proveedor" : "" ?> " data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-state="<?php echo $value['ImportProduct']['state_import'] ?>" data-deadline="<?php echo !is_null($endData[0]["ImportRequestsDetail"]["deadline"]) ? $endData[0]["ImportRequestsDetail"]["deadline"] : $datosImport['Import']['deadline'] ?>"></span>
								<div class="bs-wizard-info text-center">Despacho de proveedor</div>
							</div>

							<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_miami($value['ImportProduct']['state_import']); ?>">
								<div class="progress"><div class="progress-bar"></div></div>
								<span class="bs-wizard-dot <?php echo $validRole ? "state_llegada_miami" : "" ?> " data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-state="<?php echo $value['ImportProduct']['state_import'] ?>"></span>
								<div class="bs-wizard-info text-center">Producto en bodega Miami</div>
							</div>

							<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_amerimpex($value['ImportProduct']['state_import']); ?>">
								<div class="progress"><div class="progress-bar"></div></div>
								<span class="bs-wizard-dot <?php echo $validRole ? "state_amerimpex" : "" ?>" data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-state="<?php echo $value['ImportProduct']['state_import'] ?>"></span>
								<div class="bs-wizard-info text-center">Despacho a Amerimpex</div>    
							</div>

							<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_nacionalizacion($value['ImportProduct']['state_import']); ?>">
								<div class="progress"><div class="progress-bar"></div></div>
								<span class="bs-wizard-dot <?php echo $validRole ? "state_nacionalizacion" : "" ?>" data-uid="<?php echo $value['ImportProduct']['id'] ?>" data-state="<?php echo $value['ImportProduct']['state_import'] ?>"></span>
								<div class="bs-wizard-info text-center">Nacionalización</div>
							</div>

						</div>
					</div>
				</div> <!-- cierre -->

				<div class="blockwhite cuadro_importacion cuadro_importacion_<?php echo $value['ImportProduct']['id'] ?>"></div>
			<?php endif ?>
		</div>

	</div>

				<?php $i++; endforeach ?>
</div>
</div>
</div>
<div class="boxfalse" style="width: 100%; height: 250px;"></div>

			<div class="modal fade " id="modalProviderView" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h5 class="modal-title" id="exampleModalScrollableTitle">Solicitud que se enviará al proveedor</h5>
						</div>
						<div class="modal-body" id="cuerpoProvider"></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
			<?php if ($validRole && $datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud')): ?>
				<div class="modal fade " id="modalMargenView" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h5 class="modal-title" id="exampleModalScrollableTitle">Detalle margen por producto</h5>
							</div>
							<div class="modal-body" id="cuerpoMargen">
								<?php $margenSuma = 0; $margenFinalTodos = 0; ?>
								<table class="table table-hovered table-striped">
									<thead>
										<th>
											Referencia
										</th>
										<th>
											Producto
										</th>
										<th>
											Margen
										</th>
									</thead>
									<tbody>
										<?php foreach ($productosMargen2 as $key => $value): ?>
											<tr>
												<td>
													<?php echo $value["reference"] ?>
												</td>
												<td>
													<?php echo $value["name"] ?>
												</td>
												<td>
													<span class="<?php echo $value["margen"] >= 30 ? "text-success" : "text-danger" ?>"><?php echo $value["margen"] ?></span>

													<?php $margenSuma+= $value["margen"]; ?>
												</td>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-md-12 text-info">
										Nota: Para información más detallada en cada producto de esta orden se puede visualizar
									</div>
								</div>
								<div class="d-none" id="detalleMargen">
									<?php $margenFinalTodos = round(($margenSuma / ( count($productosMargen2) == 0 ? 1 : count($productosMargen2) ) ),2); ?>
									<p class="d-none" id="margenFinal"><?php echo $margenFinalTodos ?></p>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="modal fade " id="modalProviderEmailsCopy" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h5 class="modal-title" id="exampleModalScrollableTitle">Enviar solicitud al proveedor</h5>
						</div>
						<div class="modal-body" id="cuerpoProviderEmails">
							<div class="form-group">
								<label for="emailCopy">Enviar copias de correo eléctronico de la solicitud a los siguientes correos: </label>
								<?php $correos = $brand["Brand"]["email"]; 
									if (!is_null($brand["Brand"]["copy_emails"])) {
										$correos.=",".$brand["Brand"]["copy_emails"];
									}
								?>
								<input type="text" id="emailCopy" class="form-control" value="<?php echo $correos ?>">

								<?php echo $this->Form->input('enviar', array('label'=>'¿Enviar notificación a los clientes?','class'=>'form-control','div'=>false, 'id' => 'enviarInfo', 'options' => ["1"=>"SI, Enviar notificaciones",'0'=>'No, No envir notificaciones'] )); ?>
								<button class="btn btn-success mt-3 pull-right" id="EnviarEmail" data-uid="<?php echo $this->Utilities->encryptString($datosImport['ImportRequest']['id']) ?>">Enviar</button>
							</div>
						</div>
					</div>
				</div>

			</div>

			<!-- Modal -->
			<div class="modal fade" id="empresaFake" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">
								Procesar producto en la empresa
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body" id="modalEmpresaProducto">

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>

<!-- Modal -->
<div class="modal fade " id="detalleDeMargen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg2" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title" id="exampleModalLabel">
					Detalle de margen
				</h5>
			</div>
			<div class="modal-body" id="bodyMarge">

			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="cantidadesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					Editar cantidades
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="bodyCantidades">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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


<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Logística"]) && ( $datosImport['Import']['state'] == Configure::read('variables.importaciones.solicitud') || ( $datosImport['Import']['state'] == Configure::read('variables.importaciones.proceso') && $moreItems ) ) ): ?>

	<?php $currency = end($importaciones); ?>

	<div class="modal fade" id="modalAddProduct" tabindex="-1" role="dialog" aria-labelledby="modalAddProduct" aria-hidden="true">
		<div class="modal-dialog modal-lg2" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">
						Agregar más productos a la orden, marca: <?php echo $brand["Brand"]["name"] ?>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" >

					<div class="form-group mb-3">
						<label for="nombreParte">Escriba el nombre o número de parte a buscar de la marca <?php echo $brand["Brand"]["name"] ?></label>
						<div class="row ml-0 mr-0">
							<input type="search" id="numbreParte" class="form-control col-md-9">
							<a href="" class="btn btn-info col-md-2 ml-md-3  ml-lg-3 mt-xs-2" id="buscarProducto">
								Buscar producto
							</a>
						</div> 
						<hr>
					</div>

					<div id="modalOtrosDatos">
						
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">
						Agregar referencia
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="bodyAdd">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

<script>
	var productsIds = <?php echo json_encode($productsIds) ?>;
	var brandId 	= <?php echo $brand["Brand"]["id"] ?>;
	var importId 	= <?php echo $datosImport['Import']["id"] ?>;
	var requestId 	= <?php echo $datosImport["ImportRequest"]["id"] ?>;
	var currency 	= "<?php echo $currency["ImportProduct"]["currency"] ?>";
</script>
<?php endif; ?>

<div class="modal fade" id="modalInforme" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Informar demora </h2>
      </div>
      <div class="modal-body" id="bodyDemora">
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

<script>
	
	var valorTotal  = <?php echo $totalValueFinal ?>;
	var ventasFinal = <?php echo round(doubleval($ventasFinal),2) ?>;
	var reject 		= <?php echo $ImportProductReject && $datosImport['Import']['state'] == Configure::read('variables.importaciones.proceso') && $datosImport['Import']['send_provider'] == 1 ? "true" : "false"; ?>
</script>

<script>
	var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
	var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
</script>

<style>
	.valores,.formCostos{
		display: none !important;
	}
	@keyframes myanim {
        70% {
           background: white;
           padding: 3px 6px;
        }
    }
</style>

<?php echo $this->element("flujoModal"); ?>

<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.time()),								array('block' => 'jqueryApp'));
echo $this->Html->script("controller/product/products_import.js?".time(),					array('block' => 'AppScript'));
echo $this->Html->script("controller/prospectiveUsers/imports_revisions.js?".time(),		array('block' => 'AppScript'));
?>

<?php echo $this->element("comentario"); ?>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<?php 


echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
?>

<?php 
echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); ?>
