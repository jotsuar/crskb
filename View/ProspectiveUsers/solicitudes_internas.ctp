<?php 	
	$rolesPriceImport = array(
		Configure::read('variables.roles_usuarios.Logística'),
		Configure::read('variables.roles_usuarios.Gerente General')
	);
	$validRole = in_array(AuthComponent::user("role"), $rolesPriceImport) ? true : false;
	$totalValueFinal = 0;
?>
<div class="col-md-12 p-0">
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
						<div class="col-md-3 item_menu_import ">
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
						<div class="col-md-3 item_menu_import activeitem">
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

	<div class="blockwhite-import spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2>Solicitudes de Importación internas realizadas por los empleados</h2>
				<?php if (!empty($solicitudesActivas) && AuthComponent::user("role") == "Gerente General"): ?>					
					<a href="#" class="classAproveImportAll float-right btn btn-success" data-uid="<?php echo 1 ?>">
						Aprobar todas <i class="fa fa-check vtc"></i>
					</a>
					<a href="#" class="classDeleteImportAll float-right btn btn-danger" data-uid="2">
						Rechazar todas <i class="fa fa-ban vtc"></i>
					</a>
				<?php endif ?>
			</div>
		</div>
	</div>

	<div class="blockwhite spacebtn20">
		<?php if (empty($solicitudesActivas)): ?>
			<div class="blockwhite-import spacebtn20">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2>No hay solicitudes importación</h2>
					</div>
				</div>
			</div>
		<?php endif ?>
		<div class="contenttableresponsive">
			<?php foreach ($solicitudesActivas as $keyDetails => $details): ?>
				<?php //var_dump($details); die; ?>
							
				<?php $totalCop = 0;	$totalUsd = 0; $totalProducts = 0; $totalQuantity = 0; ?>
				<div class="card mb-4"> 
				    <div class="card-header reset <?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$details["ImportRequestsDetail"]["type_request"]) ?> " id="headingOne">
					    <h2 class="displayinline mb-0 w-100">
					       <p class="m-0 pl-4 pt-2 w-100"> Solicita 
					          	<span>
					          	<?php $usrname = $details["User"]["name"]; $arr = explode(" ", $usrname); echo $arr[0]; ?>
					          	 el <?php echo $details["ImportRequestsDetail"]['created'] ?> 
					          		
					          	</span>
					          	<?php if (AuthComponent::user("role") == "Gerente General"): ?>					          		
					          		|
									<a href="#" class="classAproveImport float-right btn btn-outline-success" data-uid="<?php echo $details["ImportRequestsDetail"]["id"] ?>">
										Aprobar solicitud <i class="fa fa-check vtc"></i>
									</a>
									<a href="#" class="classDeleteImport float-right btn btn-outline-danger" data-uid="<?php echo $details["ImportRequestsDetail"]["id"] ?>">
										Rechazar solicitud <i class="fa fa-ban vtc"></i>
									</a>
									<a href="#" class="classAddProducts float-right btn btn-outline-info" data-uid="<?php echo $details["ImportRequestsDetail"]["id"] ?>">
										Agregar productos <i class="fa fa-plus vtc"></i>
									</a>

					          	<?php endif ?>
					          </p>
					    </h2>
				    </div>
				    
				    <div>
					    <div class="card-body">
					    	<div class="card-text">
					    		<ul class="p-0">
					    			<li class="mb-3 pl-0 mgb">
					    				<b>Razón de la importación</b>: <?php echo $details["ImportRequestsDetail"]["description"] ?>
					    			</li>
					    		</ul>
					    		
					    	</div>
							<div class="contenttableresponsive">
							<table class="table-striped table-bordered myTable">
								<thead>
									<tr>
										<th>Imagen</th>
										<th>Producto</th>
										<th class="size2"># Parte</th>
										<th class="size2">Marca</th>
										<th class="size">Inventario actual</th>							
										<th class="size">Cant. solicitada</th>
										<th class="size">
											Costo USD
										</th>
										<th>
											Costo total
										</th>
										<th>
											Acciones
										</th>
									</tr>
								</thead>
								<?php $totalQuantity = 0; ?>
								<?php $costTotal = 0; ?>
								<tbody class="os3">
									<?php foreach ($details["Product"] as $idProduct => $value): ?>
										<tr class="os1">
											<td>
												<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
												<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="55px" height="55px" class="imgmin-product">
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
													<p class="cantd"> <?php echo $value["name"] ?>
												<?php else: ?>
													<p class="cantd"> <?php echo $value["name"] ?>
												<?php endif ?>
											</td>
											<td>
												<?php echo $value["part_number"]?>
											</td>
											<td>
												<?php echo $value["brand"]?>
											</td>
											<td class="controlquantity">
												<?php echo $this->element("products_block",["producto" => $value, "inventario_wo" => $inventioWo[$value['part_number']],"bloqueo" => false, "no_show_total" => true, "reserva" => isset($inventioWo["Reserva"][$value['part_number']]) ? $inventioWo["Reserva"][$value['part_number']] : null ]) ?>
											</td>
											
											<td>
												<?php echo $value["ImportRequestsDetailsProduct"]["quantity"]; $totalQuantity+= $value["ImportRequestsDetailsProduct"]["quantity"] ?>
											</td>
											<td>
												$ <?php echo $value["purchase_price_usd"]; $costTotal += ($value["ImportRequestsDetailsProduct"]["quantity"] * $value["purchase_price_usd"]) ?>
											</td>
											<td>
												$ <?php echo ($value["ImportRequestsDetailsProduct"]["quantity"] * $value["purchase_price_usd"]) ?>
											</td>
											<td>
												<a href="" class="btn btn-warning btnEditarProductoRef" data-id="<?php echo $value["ImportRequestsDetailsProduct"]["id"] ?>">
													Editar cantidad
												</a>
												<?php if (count($details["Product"]) == 1): ?>
													<a href="#" class="classDeleteImport btn btn-danger" data-uid="<?php echo $details["ImportRequestsDetail"]["id"] ?>">
														Rechazar solicitud <i class="fa fa-ban vtc"></i>
													</a>
												<?php else: ?>
													<a href="" class="btn btn-danger btnEliminarProductoRef" data-id="<?php echo $value["ImportRequestsDetailsProduct"]["id"] ?>">
														Eliminar producto
													</a>
												<?php endif ?>
												
											</td>
										</tr>
										<?php if (isset($value["compositions"])): ?>
											<tr>
												<td colspan="10">
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
									<?php endforeach ?>
									<tr class="bg-blue" style="background-color: #004990 !important">
										<td style="border: none !important; font-size: 1.5rem !important;" colspan="5">
											<span class="float-right">
												Cantidad total:												
											</span>
										</td>
										<td style="border: none !important; font-size: 1.5rem !important;">
											<span><?php echo $totalQuantity ?></span>
										</td>
										<td style="border: none !important; font-size: 1.5rem !important;">
											<span>Costo total:</span>
										</td>
										<td style="border: none !important; font-size: 1.5rem !important;" colspan="2">
											<span>$ <?php echo number_format($costTotal, 2,".",",") ?></h1>
										</td>
									</tr>
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

<div class="modal fade" id="modalAddProductRef" tabindex="-1" role="dialog" aria-labelledby="modalAddProductRef" aria-hidden="true">
	<div class="modal-dialog modal-lg2" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					Agregar más productos a la solicitud
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" >

				<div class="form-group mb-3">
					<label for="nombreParte">Escriba el nombre o número de parte a buscar (mínimo  caracteres)</label>
					<div class="row ml-0 mr-0">
						<input type="search" id="numbreParte" class="form-control col-md-9">
						<input type="hidden" id="actualID" class="form-control col-md-9">
						<a href="" class="btn btn-info col-md-2 ml-md-3  ml-lg-3 mt-xs-2" id="buscarProducto">
							Buscar producto
						</a>
					</div> 
					<hr>
				</div>

				<div id="modalOtrosDatosRef">
					
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalCantidades" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Cambiar Cantidad </h2>
      </div>
      <div class="modal-body" id="bodyCantidad">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
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

<?php
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),							array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/imports_revisions_normal.js?".rand(),		array('block' => 'AppScript'));
	echo $this->Html->script("controller/import/search.js?".rand(),		array('block' => 'AppScript'));
?>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<?php 


echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
 ?>