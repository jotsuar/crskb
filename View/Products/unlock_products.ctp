
<?php 	
	$rolesPermitidos = array(
    	"Gerente General", "Logística","Asesor Comercial"
    );
	$rolesPriceImport = array(
		Configure::read('variables.roles_usuarios.Logística'),
		Configure::read('variables.roles_usuarios.Gerente General')
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
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h2 class="titleviewer">Salidas por aprobar</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20" style="display: none">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre o asunto">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre o asunto">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>	
	<div class="clientsLegals index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='myTable table-striped table-bordered'>
				<thead>
					<tr>
						<th>
							<?php if (!empty($products)): ?>
								<input type="checkbox" class="checkallProducts">
							<?php endif ?>
						</th>
						<th>Imagen</th>
						<th><?php echo 'Referencia'; ?></th>
						<th><?php echo 'Producto'; ?></th>
						<th><?php echo 'Categoría'; ?></th>
						<th><?php echo __("Inventario actual") ?></th>
						<th class="text-center">
							<?php echo 'Costo reposición'; ?><br>
							<?php echo 'Costo WO'; ?>
						</th>
						<th ><?php echo 'Marca'; ?></th>
						<th class="sizeprecio">Precio</th>
						<th>Último cambio</th>
						<th class="">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($products)): ?>
						<?php foreach ($products as $key => $product): ?>
							<?php $dataProducto = $this->Utilities->getQuantityBlock($product["Product"]); ?>
							<tr>
								<td>
									<?php if ($unlokProducts): ?>
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
										$categorias = $categoriesData[$product["Product"]["category_id"]]; 
									 	$grupos = explode("-->",$categorias);
									 	$num = 1;
									 ?>
									 
			    					<div class="dropdown d-inline styledrop ">
										  <a class="btn btn-outline-secondary dropdown-toggle p-1 rounded" href="#" role="button" id="gruposproducts<?php echo md5($product['Product']['id']) ?>" data-toggle="dropdown" aria-expanded="false">	Ver agrupación 								  
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

								<td class="size11">
									<?php echo $this->element("products_block",["producto" => $product["Product"]]) ?>
								</td>


								<td class="text-center resetpd">

									<div class="price-purchase_price_usd">
										$<?php echo h($product['Product']['purchase_price_usd']); ?> USD
									</div>
									 
									<div class="price-purchase_price_wo">
										$<?php echo h($product['Product']['purchase_price_wo']); ?> COP
									</div>

								</td>								
								<td><?php echo h($product['Brand']['name']); ?>&nbsp;</td>
								<td>$<?php echo number_format((int)h($product['Product']['list_price_usd']),0,",","."); ?>&nbsp;</td>
								<td>
									<?php if (!is_null($product["Product"]["user_id"])): ?>
										<?php echo $this->Utilities->find_name_adviser($product["Product"]["user_id"]) ?> / <?php echo $product["Product"]["modified"] ?>
									<?php endif ?>
								</td>


								<td class="text-center">
			    					<div class="dropdown d-inline styledrop ">
										  <a class="btn btn-outline-secondary dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($product['Product']['id']) ?>" data-toggle="dropdown" aria-expanded="false">
											Ver 								  
										  </a>

										  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($product['Product']['id']) ?>">
										    <a class="dropdown-item" href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($product['Product']['id']))) ?>"><i class="fa fa-eye vtc"></i> Ver producto</a>

										    <?php if ($this->Utilities->validateRoleInventory()): ?>
										    	<a class="dropdown-item" href="<?php echo $this->Html->url(array("controller" => "inventories",'action' => 'index', $this->Utilities->encryptString($product['Product']['id']))) ?>"><i class="fa fa-archive vtc"></i> Detalle de inventario</a>
										    <?php endif ?>

										    <a class="dropdown-item" href="<?php echo $this->Html->url(array('action' => 'edit', $product['Product']['id'],"1" )) ?>"> <i class="fa fa-pencil vtc"></i> Editar producto</a>

										    

								            <?php if ($validRole && $dataProducto["totalDisponible"] <= 0 && $dataProducto["totalBloqueo"] <= 0): ?>
								            	<a class="dropdown-item delete_product" href="#" data-url="<?php echo $this->Html->url(array('action' => 'delete_product', $this->Utilities->encryptString($product['Product']['id']))) ?>" data-state="<?php echo $product["Product"]["state"] ?>">
								            		<i class="fa vtc fa-remove"></i> Eliminar producto
								            	</a>
								            <?php endif ?>

								            <?php if ($unlokProducts && $dataProducto["totalDisponible"] <= 0 && $dataProducto["totalBloqueo"] <= 0): ?>
								            	<a class="dropdown-item blockProduct " href="#" data-url="<?php echo $this->Html->url(array('action' => 'block', $this->Utilities->encryptString($product['Product']['id']))) ?>" data-state="<?php echo $product["Product"]["state"] ?>">
								            		<i class="fa vtc <?php echo $product["Product"]["state"] == 1 ? "fa-lock" : "fa-unlock" ?>"></i> <?php echo $product["Product"]["state"] == 1 ? "Bloquear" : "Desbloquear" ?>
								            	</a>
								            <?php endif ?>

								            <?php if (in_array(AuthComponent::user("role"), $rolesPermitidos)): ?>
									            <a class="dropdown-item notesProduct" href="#" data-id="<?php echo $product["Product"]["id"] ?>" >
									                <i class="fa fa-comments vtc"></i> Gestionar notas del producto
									            </a>
									        <?php endif ?>
										  </div>
									</div>
								</td>
							</tr>
						<?php endforeach ?>
						<?php if (!empty($products)): ?>
							<tr>
								<td colspan="2">
									<button style="display: none" class="btn btn-danger" type="button" id="dropdownMenuAccionesProductos">
									    Desbloquear
									</button>
								</td>
								<td colspan="8"></td>
							</tr>
						<?php endif ?>
					<?php else: ?>
						<tr>
							<td colspan="12" class="text-center">
								No hay productos bloequedos en el momento
							</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>


<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/product/unlock.js?".rand(),				array('block' => 'AppScript'));
?>

<?php echo $this->element("comentario"); ?>