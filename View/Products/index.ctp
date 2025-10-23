<?php 	
	$rolesPermitidos = array(
    	"Gerente General", "Logística","Asesor Comercial"
    );
	$rolesPriceImport = array(
		Configure::read('variables.roles_usuarios.Logística'),
		Configure::read('variables.roles_usuarios.Gerente General'),
		"Asesor Comercial"
	);
	$whitelist = array(
            '127.0.0.1',
            '::1'
        ); 
	$validRole = in_array(AuthComponent::user("role"), $rolesPriceImport) ? true : false;
?>

<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-5">
				<h2 class="titleviewer">Productos creados en el CRM</h2> 
				<?php if ($unlokProducts): ?>
					<br>
					<a href="<?php echo $this->Html->url(array("controller" => "products", "action" => "unlock_products")) ?>" class="btn btn-info">Productos bloqueados (<?php echo $productosBloqueados ?>) </a>
				<?php endif ?>
				<?php if (in_array(AuthComponent::user("role"), $rolesPriceImport)): ?>
					<br>
					<a href="<?php echo $this->Html->url(array("controller" => "products", "action" => "update_prices")) ?>" class="btn btn-primary mt-4">
						Actualizar costos
					</a>
					<br>
					<a href="<?php echo $this->Html->url(array("controller" => "products", "action" => "export_by_category")) ?>" class="btn btn-primary mt-4">
						Exportar productos KEBCO LLC
					</a>
					<a href="<?php echo $this->Html->url(array("controller" => "products", "action" => "set_llc")) ?>" class="btn btn-primary mt-4">
						Sincronizar productos del CRM con KEBCO LLC <i class="fa fa-recycle fa-reload fa-spin vtc"></i>
					</a>
				<?php endif ?>
			</div>
			<div class="col-md-7 text-right">
				<div class="input-group">
					
					<?php if (isset($this->request->query['q'])){ ?>
						<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por referencia, fecha de creación, nombre, descripción, marca o precio">
						<span class="input-group-addon btn_buscar">
			                <i class="fa fa-search"></i>
			            </span>
					<?php } else { ?>
						<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por referencia, fecha de creación, nombre, descripción, marca o precio">
						<span class="input-group-addon btn_buscar">
			                <i class="fa fa-search"></i>
			            </span>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<div class="dropdown dinline" style="display: nones">
				  <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				   Acciones masivas
				  </a>

				  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
				    <a class="dropdown-item masiveProducts" data-type="update" href="#">Actualización masiva</a>
				    <a class="dropdown-item masiveProducts" data-type="create" href="#">Creación masiva</a>
				  </div>
				</div>

				<?php if (in_array(AuthComponent::user("role"), $rolesPriceImport)): ?>
					<a href="<?php echo $this->Html->url(array('controller'=>'products','action'=>'export1', time())) ?>" class="btn btn-outline-primary"> Exportar inventario</a>
					<a href="<?php echo $this->Html->url(array('controller'=>'inventories','action'=>'export2', time())) ?>" class="btn btn-outline-primary"> Exportar detalle inventario</a>				
				<?php endif ?>
				<a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'add')) ?>" class="btn btn-warning"><i class="fa fa-plus-square vtc"></i> <span>Crear producto</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'brands','action'=>'index')) ?>" class="btn btn-warning"><i class="fa fa-eye vtc"></i> <span>Ver marcas</span></a>			
	
				<?php if (AuthComponent::user("role") == "Gerente General"): ?>
					
					<div class="d-inline-block float-right costosgerente">
						<div>COSTO TOTAL USD:  <span><?php echo number_format($costoTotalUsd,"2",",",".") ?> USD</span></div>
						<div>TRM ACTUAL: <span> <?php echo $trmActual ?></span></div>
						<div>COSTO TOTAL COP: <span> <?php echo number_format($costoTotalUsd*$trmActual,"2",",",".") ?> COP</span></div>
					</div>

				<?php endif ?>

			</div>
		</div>
	</div>	

	<div class="row">
		<div class="col-md-3 col-lg-2">
			<div class="blockwhite">
				<h3 class="text-center text-info">
					Filtros
				</h3>
				<hr>
				<div class="form-group">
					<label for="marcasData">Marca</label>
					<select name="marcasData" id="marcasData">
						<option value=""> <?php echo isset($brandSelect) ? "No seleccionar marca" :  "Seleccionar marca" ?> </option>
						<?php foreach ($brands as $key => $value): ?>
							<option value="<?php echo $value["Brand"]["id"] ?>" <?php echo isset($brandSelect) && $value["Brand"]["id"] == $brandSelect ? "selected" : "" ?>><?php echo $value["Brand"]["name"] ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group mt-3 categoriasData_<?php echo $key ?> catDivs">
					<label for="categoryData">Categoría por grupos</label>
					<hr>
				</div>
				<?php $label = "categoría"; ?>
				<div class="form-group mt-3 categoriasData_1_<?php echo $key ?> catDivs">
					<label for="categoryData">Grupo 1</label>
					<select name="category_1" id="category_1">
						<option value="">Seleccionar</option>
						<?php foreach ($categoriesInfoFinal[0] as $key => $value): ?>
							<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
						<?php endforeach ?>
					</select>
				</div>

				<div class="form-group mt-3 categoriasData_2_<?php echo $key ?> catDivs">
					<label for="categoryData">Grupo 2</label>
					<select name="category_2" id="category_2">
						<option value="">Seleccionar</option>
					</select>
				</div>

				<div class="form-group mt-3 categoriasData_3_<?php echo $key ?> catDivs">
					<label for="categoryData">Grupo 3</label>
					<select name="category_3" id="category_3">
						<option value="">Seleccionar</option>
					</select>
				</div>

				<div class="form-group mb-2 mt-3 categoriasData_4_<?php echo $key ?> catDivs">
					<label for="categoryData">Grupo 4</label>
					<select name="category_4" id="category_4">
						<option value="">Seleccionar</option>
					</select>
				</div>
				<hr>
				
				<div class="form-group mt-3">
					<label for="inventoryData">Producto con inventario</label>
					<select name="inventoryData" id="inventoryData">
						<option value=""><?php echo isset($inventarioSelect) ? "No seleccionar inventario" :  "Seleccionar" ?></option>
						<?php foreach (Configure::read("IMPUESTOS") as $key => $value): ?>
							<option value="<?php echo $key ?>" <?php echo isset($inventarioSelect) && $key == $inventarioSelect ? "selected" : "" ?>><?php echo $value ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group mt-3">
					<label for="precioCop">Precio COP</label>
					<div class="col-md-12 pr-5 mt-3">
						<input type="hidden" id="precioCop">
					</div>
					
				</div>
				<div class="form-group mt-4">
					<label for="costoUSD">Costo USD</label>
					<div class="col-md-12 pr-5 mt-3">
						<input type="hidden" id="costoUSD">
					</div>
				</div>
				<div class="form-group mt-4">
					<?php if (isset($brandSelect) || isset($categorySelect) || isset($costoMinSelect) || isset($precioMinSelect)): ?>
						
					<button class="btn btn-primary btn-block" id="borrarTodo" type="button"><i class="fa fa-trash vtc"></i> Borrar filtros</button>
					<?php endif ?>
					<button class="btn btn-success btn-block btn_buscar" id="btnBuscarData" type="button">Buscar <i class="fa fa-search vtc"></i></button>
				</div>
			</div>
		</div>
		<div class="col-md-9 col-lg-10">
			<div class="products index blockwhite">
				<div class="contenttableresponsive table-responsive">
					<table cellpadding="0" cellspacing="0" class="tableproducts table-striped table-bordered">
						<thead>
							<tr>
								<th>
									<input type="checkbox" class="checkallProducts">
								</th>
								<th>Imagen</th>
								<th><?php echo $this->Paginator->sort('Product.part_number', 'Referencia'); ?></th>
								<th><?php echo $this->Paginator->sort('Product.name', 'Producto'); ?></th>
								<th><?php echo $this->Paginator->sort('Product.category_id', 'Categoría'); ?></th>
								<th><?php echo $this->Paginator->sort('Product.type', 'IMP'); ?></th>
								<th><?php echo $this->Paginator->sort('Product.normal', 'Tipo'); ?></th>
								<th><?php echo $this->Paginator->sort('Product.currency', 'Moneda local'); ?></th>
								<th><?php echo $this->Paginator->sort('Product.stock', 'Inventario actual'); ?></th>
								<th ><?php echo $this->Paginator->sort('Product.brand', 'Marca'); ?></th>
								<th class="sizeprecio">Precio</th>
								<?php if ( in_array(AuthComponent::user("role") ,["Logística","Gerente General"] ) || AuthComponent::user("email") == "ventas2@almacendelpintor.com" ): ?>
									<th class="text-center">
										<?php echo 'Costo reposición'; ?><hr>
										<?php echo 'Costo WO'; ?>
										<hr>
										<?php echo 'Costo Real WO'; ?>
										<hr>
										<?php echo 'Costo reposición COL'; ?>
									</th>
								<?php endif ?>
								<th class="">Acciones</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($products as $product): ?>
							<?php $dataProducto = $this->Utilities->getQuantityBlock($product["Product"]); ?>
							<tr style="<?php echo $product["Product"]["state"] == 0 ? "background: #ff000008 !important;" : "" ?>">
								<td>
									<?php if ($dataProducto["totalDisponible"] <= 0 && $dataProducto["totalBloqueo"] <= 0): ?>
										<input type="checkbox" class="checkOneProducts" value="<?php echo $product["Product"]["id"] ?>">										
									<?php endif ?>
								</td>
								<td>
									<?php $ruta = $this->Utilities->validate_image_products($product['Product']['img']); ?>
									<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($product['Product']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="50px" height="45px" class="imgmin-product">
								</td>
								<td><?php echo h($product['Product']['part_number']); ?>&nbsp;</td>
								<td class="sizeprod nameuppercase <?php echo !is_null($product["Product"]["notes"]) ? "nota" : "" ?>">
									<?php if (!is_null($product["Product"]["notes"])): ?>
							            <div class="notaproduct">
							            <span class="triangle"></span>
							            <span class="flechagiro">|</span>
							            <div class="text_nota">
							                <small class="creadornota"><b></b></small>
							                <?php echo $product["Product"]["notes"] ?>
							                <small class="datenota"></small>
							            </div>
							            </div>
							            <?php echo $this->Text->truncate(h($product['Product']['name']), 45,array('ellipsis' => '...','exact' => false)); ?>&nbsp;
							        <?php else: ?>
							            <?php echo $this->Text->truncate(h($product['Product']['name']), 45,array('ellipsis' => '...','exact' => false)); ?>&nbsp;
							        <?php endif ?>
								</td>
								<td class="sizeprod ">
									<?php 
										$categorias = isset($categoriesData[$product["Product"]["category_id"]]) ? $categoriesData[$product["Product"]["category_id"]] : $categoriesData[1] ; 
									 	$grupos = explode("-->",$categorias);
									 	$num = 1;
									 ?>
									 
			    					<div class="dropdown d-inline styledrop ">
										  <a class="btn btn-outline-secondary dropdown-toggle p-1 rounded" href="#" role="button" id="gruposproducts<?php echo md5($product['Product']['id']) ?>" style="padding: 0 !important;" data-toggle="dropdown" aria-expanded="false">	Ver agrupación 								  
										  </a>

										  <div class="dropdown-menu" aria-labelledby="gruposproducts<?php echo md5($product['Product']['id']) ?>">
												<ul class="list-unstyled groupsp dropdown-item">
													<?php foreach ($grupos as $key => $value): ?>
														<li> <b>Grupo <?php echo $num; $num++; ?>:</b> <?php echo $value; ?> </li>
													<?php endforeach ?>
												</ul>
										  </div>
									</div>



								</td>
								<td class="text-center">
									<?php echo $product["Product"]["type"] == 1 ? "Si" : "No"; ?>
								</td>
								<td class="text-center">
									<?php echo $product["Product"]["normal"] == 1 ? "Normal" : "Compuesto"; ?>
								</td>

								<td class="text-center">
									<?php echo $product["Product"]["currency"] == 1 ? "COP" : "USD"; ?>
								</td>

								<td class="size11">
									<?php echo $this->element("products_block",["producto" => $product["Product"],"inventario_wo" => $partsData[$product["Product"]["part_number"]] ,"reserva" => isset($partsData["Reserva"][$product["Product"]["part_number"]]) ? $partsData["Reserva"][$product["Product"]["part_number"]] : null ]) ?>
								</td>

								<td><?php echo h($product['Brand']['name']); ?>&nbsp;</td>
								<td>$<?php echo isset($precios[$product["Product"]["part_number"]]) ? number_format($precios[$product["Product"]["part_number"]],2,",",".") : number_format((int)h($product['Product']['list_price_usd']),0,",","."); ?>&nbsp;</td>
								<?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] ) || AuthComponent::user("email") == "ventas2@almacendelpintor.com"): ?>
									<td class="text-center resetpd">

										<div class="pointer price-purchase_price_usd <?php echo in_array(AuthComponent::user("role"), array("Logística","Gerente General","Asesor Comercial")) || AuthComponent::user("email") == "ventas2@almacendelpintor.com" ? "cambioCostoDataUsd" : "" ?>" data-id="<?php echo $product["Product"]["id"] ?>" data-type="purchase_price_usd" data-price="<?php echo $product['Product']['purchase_price_usd'] ?>" data-currency="USD">
											<b>$<?php echo h($product['Product']['purchase_price_usd']); ?> USD</b>
										</div>
										 
										<div class="pointer price-purchase_price_wo <?php echo in_array(AuthComponent::user("role"), array("Logística","Gerente General","Asesor Comercial")) && !isset($partsData[$product["Product"]["part_number"]][0]["costo"]) ? "cambioCostoDataCop" : "" ?>" data-id="<?php echo $product["Product"]["id"] ?>" data-type="purchase_price_wo" data-price="<?php echo $product['Product']['purchase_price_wo'] ?>" data-currency="COP">
											<b> 
												$
												<?php 

													$costo = $product["Product"]["purchase_price_wo"];
													$costoRep = round($product["Category"]["factor"] * ($product["Product"]["purchase_price_usd"] * $trmActual),2);
													if (isset($costos[$product["Product"]["part_number"]]) 
														// && ($costos[$product["Product"]["part_number"]] * 1) >=  $costoRep 
													) {
														echo $costos[$product["Product"]["part_number"]]*1;
													}else{
														echo $costoRep;
													}

												?> 
												COP
											</b>
										</div>
										<?php if ($product["Category"]["show_cost"] == 1): ?>
											
										
										<!-- <div class="pointer bg-azul">
											<b> 
												$
												<?php 
													
													if (isset($costos[$product["Product"]["part_number"]])) {
														echo $costos[$product["Product"]["part_number"]]*1;
													}else{
														echo 0;
													}

												?> 
												COP
											</b>
										</div> -->

										<?php endif ?>

										<div class="pointer price-purchase_price_usd <?php echo in_array(AuthComponent::user("role"), array("Logística","Gerente General","Asesor Comercial")) || AuthComponent::user("email") == "ventas2@almacendelpintor.com" ? "cambioCostoDataCop2" : "" ?>" data-id="<?php echo $product["Product"]["id"] ?>" data-type="purchase_price_cop" data-price="<?php echo $product['Product']['purchase_price_cop'] ?>" data-currency="COP">
											<b>$<?php echo h($product['Product']['purchase_price_cop']); ?> COP</b>
										</div>

									</td>
								<?php endif ?>
								<td class="text-center">
			    					<div class="dropdown d-inline styledrop ">
										  <a class="btn btn-outline-secondary dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($product['Product']['id']) ?>" data-toggle="dropdown" aria-expanded="false">
											Ver 								  
										  </a>

										  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($product['Product']['id']) ?>">
										    <a class="dropdown-item" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($product['Product']['id']))) ?>"><i class="fa fa-eye vtc"></i> Ver producto</a>

										    <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>

											    <?php if ($this->Utilities->validateRoleInventory()): ?>
											    	<!-- <a class="dropdown-item" href="<?php //echo $this->Html->url(array("controller" => "inventories",'action' => 'index', $this->Utilities->encryptString($product['Product']['id']))) ?>"><i class="fa fa-archive vtc"></i> Detalle de inventario</a> -->
											    	<?php if (!empty($product["Cost"])): ?>
											    		<a class="dropdown-item getCosts" href="<?php echo $this->Html->url(array("controller" => "products",'action' => 'costs', $this->Utilities->encryptString($product['Product']['id']))) ?>"><i class="fa fa-money vtc"></i> Ver detalle de cambio de costo</a>
											    	<?php endif ?>
											    	<?php if ($product["Product"]["transito"] > 0): ?>
											    		<a class="dropdown-item changeState" href="<?php echo $this->Html->url(array("controller" => "products",'action' => 'no_transit', $this->Utilities->encryptString($product['Product']['id']),"?"=> $this->request->query )) ?>"><i class="fa fa-times vtc"></i> Transito cero </a>
											    	<?php endif ?>
											    <?php endif ?>

											    <?php if ($editProducts): ?>
											    	

											    <a class="dropdown-item" href="<?php echo $this->Html->url(array('action' => 'edit', $product['Product']['id'])) ?>"> <i class="fa fa-pencil vtc"></i> Editar producto</a>

											    <?php else: ?>
												
												 <a class="dropdown-item requestEditProduct" href="#" data-id="<?php echo $product["Product"]["id"] ?>"> <i class="fa fa-pencil vtc"></i> Solicitar edición </a>
											    
											    <?php endif ?>

									            <?php if ($validRole && $dataProducto["totalDisponible"] <= 0 && $dataProducto["totalBloqueo"] <= 0): ?>
									            	<a class="dropdown-item delete_product" href="#" data-url="<?php echo $this->Html->url(array('action' => 'delete_product', $this->Utilities->encryptString($product['Product']['id']))) ?>" data-state="<?php echo $product["Product"]["state"] ?>">
									            		<i class="fa vtc fa-remove"></i> Deshabilitar permanentemente
									            	</a>
									            <?php endif ?>

									            <?php if ($validRole): ?>
									            	<a class="dropdown-item importerProduct " href="#" data-url="<?php echo $this->Html->url(array('action' => 'importer', $this->Utilities->encryptString($product['Product']['id']))) ?>">
									            		<i class="fa vtc <?php echo $product["Product"]["type"] == 0 ? "fa-plane" : "fa-bank" ?>"></i> <?php echo $product["Product"]["type"] == 1 ? "Marcar como producto no importado" : "Marcar como producto importado" ?>
									            	</a>
									            <?php endif ?>

									            <?php if ($unlokProducts && $dataProducto["totalDisponible"] <= 0): ?>
									            	<a class="dropdown-item blockProduct " href="#" data-url="<?php echo $this->Html->url(array('action' => 'block', $this->Utilities->encryptString($product['Product']['id']))) ?>" data-state="<?php echo $product["Product"]["state"] ?>">
									            		<i class="fa vtc <?php echo $product["Product"]["state"] == 1 ? "fa-lock" : "fa-unlock" ?>"></i> <?php echo $product["Product"]["state"] == 1 ? "Bloquear" : "Desbloquear" ?>
									            	</a>
									            <?php endif ?>

									            <?php if (in_array(AuthComponent::user("role"), $rolesPermitidos)): ?>
										            <a class="dropdown-item notesProduct" href="#" data-id="<?php echo $product["Product"]["id"] ?>" >
										                <i class="fa fa-comments vtc"></i> Gestionar notas del producto
										            </a>
										        <?php endif ?>
										    <?php endif ?>
										  </div>
									</div>
								</td>

							</tr>
						<?php endforeach; ?>
						<?php if (!empty($products)): ?>
							<tr>
								<td colspan="2">
									<button style="display: none" class="btn btn-danger" type="button" id="dropdownMenuAccionesProductos">
									    Deshabilitar permanentemente
									</button>
								</td>
								<td colspan="8"></td>
							</tr>
						<?php endif ?>
						</tbody>
					</table>
					<div class="row numberpages">
						<?php
							echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
							echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
							echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
							echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
							echo $this->Paginator->last(' >>', array('class' => 'next'), null);
						?>
						<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
					</div>
				</div>
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


<!-- Modal -->
<!-- Modal -->
<div class="modal fade " id="modalMasiva" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Gestión másiva de productos</h5>
      </div>
      <div class="modal-body" id="cuerpoMasivo">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="modalCosto" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Historial de cambios de costo</h5>
      </div>
      <div class="modal-body" id="cuerpoCosto">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script>
	var actual_uri  = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";

    var costoMin = parseFloat(<?php echo $costoMin ?>);
    var costoMax = parseFloat(<?php echo $costoMax ?>);
    var precioMax = parseFloat(<?php echo $precioMax ?>);
    var precioMin = parseFloat(<?php echo $precioMin ?>);
    var costoMinSelect = <?php echo isset($costoMinSelect) ? "parseFloat(".$costoMinSelect.");" : "null" ?>;
    var costoMaxSelect = <?php echo isset($costoMaxSelect) ? "parseFloat(".$costoMaxSelect.");" : "null" ?>;

    var precioMinSelect = <?php echo isset($precioMinSelect) ? "parseFloat(".$precioMinSelect.");" : "null" ?>;
    var precioMaxSelect = <?php echo isset($precioMaxSelect) ? "parseFloat(".$precioMaxSelect.");" : "null" ?>;
    var categorySelect = <?php echo isset($categorySelect) ? "parseInt(".$categorySelect.");" : "null" ?>;

    var categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal) ?>;

    var category1Select = <?php echo isset($category1Select) ? $category1Select : "null" ?>;
    var category2Select = <?php echo isset($category2Select) ? $category2Select : "null" ?>;
    var category3Select = <?php echo isset($category3Select) ? $category3Select : "null" ?>;
    var category4Select = <?php echo isset($category4Select) ? $category4Select : "null" ?>;

</script>
<?php echo $this->element("categories_select", array("categorias" => $categoriesInfoFinal)); ?>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script(array('lib/jquery.jeditable.min.js?'.rand()),						array('block' => 'AppScript'));
	echo $this->Html->script("rangeSlider.js?".rand(),						array('block' => 'AppScript'));
	echo $this->Html->script("controller/product/index.js?".rand(),						array('block' => 'AppScript'));

	// getCosts
?>

<?php 
  echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); 
  echo $this->Html->script("controller/product/edit_products.js?".rand(),    array('block' => 'AppScript')); 
 ?>


<?php echo $this->element("comentario"); ?>

<?php echo $this->Html->css(array('rangeSlider.css'), array('block' => 'AppCss'));?>

<style>
	.bg-block{
		background: red !important;
	}
	td, th {
	    padding: 2px 8px;
	}
	.price-purchase_price_usd {
    	padding: 5px 2px;
	}
</style>