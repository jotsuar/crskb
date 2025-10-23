<?php 
	echo $this->Html->css(array('lib/jquery.typeahead.css'),									array('block' => 'AppCss'));
?>
<?php 
	$marcasArr = array();
	foreach ($flows as $key => $value) {
		$marca = end($value);
		$marcasArr[] = $marca["brand_id"];
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

    $whitelist = array(
            '127.0.0.1',
            '::1'
    );
    $local = true;
    if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
        $local = false;
    }

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
						<div class="col-md-3 item_menu_import activeitem">
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
<!-- 
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
	</div> -->


	<div class="blockwhitearriba ">
		<div class="row">
		<div class="col-md-12">
			<div class="blockwhite spacebtn20">
				<div class="row">
					<div class="col-md-12 blockwhite" style="box-shadow: 0 0.5rem 0.88rem rgba(0, 0, 0, .09);">
						<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100',"type" => "get")); ?>
							<h1 class="nameview spacebtnm text-center">BUSCADOR POR INVENTARIO Y TRANSITO</h1>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="flujoSearch">¿Incluir inventario en tránsito?</label>
										<select name="flujoSearch" id="flujoSearch" class="form-control">
											<option value="">Seleccionar</option>
											<option value="1" <?php echo $flujoSearch == 1 ? "selected" : "" ?>>SI</option>
											<option value="0" <?php echo $flujoSearch == 0 ? "selected" : "" ?>>NO</option>
										</select>
										
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="inventory">¿Incluir referencias con inventario?</label>							
										<select name="inventory" id="inventory" class="form-control">
											<option value="">CON Y SIN INVENTARIO</option>
											<?php for ($i=0; $i < 20; $i++) {  ?>
												<option value="<?php echo $i ?>" <?php echo $inventory == $i ? "selected" : "" ?>> <?php echo $i ?> Unidad(es) </option>
											<?php } ?>
											<option value="20" <?php echo $inventory == 20 ? "selected" : "" ?>>20 o más unidades</option>
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<button type="submit" class="bg-azulclaro btn btn-success mt-4">Búsqueda</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-md-12">
			<?php if (!empty($flows)): ?>
				<h1 class="nameview spacebtnm text-center">Productos para reposición de inventario</h1>
			<?php endif;?>
		<ul class="nav nav-tabs providerslist" id="myTab" role="tablist">
			<?php $num = 0; ?>
			<?php foreach ($flows as $key => $value): ?>
				<li class="nav-item">
					<?php $marca = end($value); $datosMarca = $this->Utilities->getInfoByBrand($marca["brand_id"]);  ?>
					<?php 
						$clase = "";
						if($num == 0 && !isset($this->request->query["brand"])){
							$clase = "active show";
						}elseif (isset($this->request->query["brand"]) && !in_array($this->request->query["brand"], $marcasArr) && $num == 0) {
							$clase = "active show";
						}else{
							if(isset($this->request->query["brand"])){
								if($this->request->query["brand"] == $marca["brand_id"]){
									$clase = "active show";
								}
							}
						}
					 ?>
			    	<a class="nav-link linkTab <?php echo $clase ?>" id="tab_<?php echo md5($key) ?>" data-brand="<?php echo $marca["brand_id"] ?>" data-toggle="tab" href="#KEBCO<?php echo md5($key) ?>" role="tab" aria-controls="<?php echo md5($key) ?>" aria-selected="true">
			    	 <?php echo $datosMarca["brand"]["Brand"]["name"]; ?>
			    	 <!-- <span class="text-danger">(<?php echo count($value) ?>)</span> -->
			    	</a>
			  </li> 
			<?php $num++; endforeach;  ?>
		</ul>
		</div>
		</div>
	</div>

	<?php if (!empty($flows)): ?>
		<div class="tab-content" id="myTabContent">
		  <?php $num = 0; ?>
		  <?php foreach ($flows as $key => $variable): ?>
		  	<?php $marca = end($variable); $datosMarca = $this->Utilities->getInfoByBrand($marca["brand_id"]);  ?>
			<?php 
				$clase = "";
				if($num == 0 && !isset($this->request->query["brand"])){
					$clase = "active show";
				}elseif (isset($this->request->query["brand"]) && !in_array($this->request->query["brand"], $marcasArr) && $num == 0) {
					$clase = "active show";
				}else{
					if(isset($this->request->query["brand"])){
						if($this->request->query["brand"] == $marca["brand_id"]){
							$clase = "active show";
						}
					}
				}
			 ?>
			<div class="tab-pane fade tabContentData <?php echo $clase ?>" id="KEBCO<?php echo md5($key) ?>" role="tabpanel" aria-labelledby="home-tab">

				<div class="blockwhiteabajo2 spacebtn20 limitheightscroll">
				<div class="import p-3 bg-white">
					
				<table class="myTable table-striped table-bordered datosPendientesDespacho">
					<thead>
						<tr class="bwhite">
							<th class="text-center">
								<label class="containercheck mb-3 ml-0 mr-2">
									<input type="checkbox" class="checkAll check_all_<?php echo md5($key) ?>" value="<?php echo $key ?>" data-id="<?php echo md5($key) ?>" data-class="check_prod_<?php echo md5($key) ?>">
									<span class="checkmark"></span>
								</label>
								<span class="ml-4">
									Añadir a pedido
								</span>
									
							</th>
							<?php if (!$local): ?>
								<th>Imagen</th>								
							<?php endif ?>
							<th>Nombre</th>
							<th>Referencia</th>
							<th class="text-center">Vendidas</th>							
							<th class="size4">Inventario actual</th>							
							<th class="size4">Cantidad a importar</th>
							<th class="size1 text-center">Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($variable as $idProduct => $value): ?>
							<?php if (in_array($idProduct, $actualProds)): ?>
								<?php continue; ?>
							<?php endif ?>
							<tr>
								<td class="text-center">
									<label class="containercheck position-absolute" style="margin-top: -10px;">
										<input type="checkbox" class="checkB check_prod_<?php echo md5($key) ?>" data-part="<?php echo $value["part"] ?>" value="<?php echo $idProduct ?>" data-class="check_prod_<?php echo md5($key) ?>" data-id="<?php echo md5($key) ?>" data-product="<?php echo $idProduct ?>" data-brand = "<?php echo $value["brand_id"] ?>" data-delete="delete_products_<?php echo md5($key) ?>" data-check="check_all_<?php echo md5($key) ?>">
										<span class="checkmark"></span>
									</label>

								</td>
								<?php if (!$local): ?>									
									<td>
										<?php $ruta = $this->Utilities->validate_image_products($value['img']); ?>
										<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="65px" height="65px" class="imgmin-product">
									</td>
								<?php endif ?>
								<td class="nameuppercase <?php echo !is_null($value["comment"]) ? "nota" : "" ?>">
									<?php if (!is_null($value["comment"])): ?>
										<div class="notaproduct">
										<span class="triangle"></span>
										<span class="flechagiro">|</span>
										<div class="text_nota">
											<small class="creadornota"><b></b></small>
											<?php echo $value["comment"] ?>
											<small class="datenota"></small>
										</div>
										</div>
										<?php echo $value["name"] ?>
									<?php else: ?>
										<?php echo $value["name"] ?>
									<?php endif ?>
								</td>
								
								<td><?php echo $value["part"]?></td>
								<td class="text-center">
									<a href="#" data-id="<?php echo $idProduct ?>" class="showBills btn btn-outline-primary">
										<b><?php echo $value["QuantityFinal"] ?></b> <i class="fa fa-eye vtc"></i>
									</a>
								</td>
								<td>
									<?php echo $this->element("products_block",["producto" => $this->Utilities->data_product($idProduct)["Product"] ,"inventario_wo" => $partsData[$value["part"]], "bloqueo" => false, "reserva" => isset($partsData["Reserva"][$value["part"]]) ? $partsData["Reserva"][$value["part"]] : null ]) ?>
								</td>
								
								<td class="text-center">
									<input class="form-control quantityNumber d-inline" id="qt_prod_<?php echo $idProduct ?>" name="quantity" type="number" data-class="check_prod_<?php echo md5($key) ?>" data-brand="<?php echo md5($key) ?>" data-id="<?php echo $idProduct ?>" value="<?php echo $value["QuantityFinal"] ?>" min="0">
								</td>
								<td class="text-center">
									<a href="#" data-id="<?php echo $idProduct ?>" class="btn btn-danger d-inline p-2 deleteProduct text-white" data-toggle="tooltip" title="" data-original-title="Eliminar producto">
										<i class="fa fa-trash text-white vtc"></i>
									</a>
									<?php if (in_array(AuthComponent::user("role"), $rolesPermitidos)): ?>
										<a href="#" data-id="<?php echo $idProduct ?>" class="btn btn-incorrecto d-inline p-2 notesProduct text-white ml-2" data-toggle="tooltip" title="" data-original-title="Gestionar notas del producto">
											<i class="fa fa-comments text-white vtc"></i>
										</a>
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>
<!-- 						<tr>
							<td class="align-center pb-3 pt-3">
								
								&nbsp;
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
 -->
					</tbody>
				</table>
				</div>
					<div class="text-center mb-3 mt-3">
						<span>PRODUCTOS AÑADIDOS: </span> <b><span id="totalProducts_<?php echo md5($key) ?>">0</b></span>  | 
						<span class="text-right"> CANTIDAD TOTAL DE ITEMS: </span> <b><span id="quantityTotal_<?php echo md5($key) ?>">0</b></span>
					</div>
				</div>

				<div class="blockwhite">
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="internacional">El pedido se hara nacional o internacional</label>
								<select name="internacional" class="form-control" id="internacional_<?php echo md5($key) ?>">
									<option value="0">Nacional</option>
									<option value="1">Internacional</option>
								</select>
							</div>
						</div>
						<div class="col-md-2 pt-4">
							<a href="#" class="btn btn-danger deleteAll float-right delete_products_<?php echo md5($key) ?>" data-class="check_prod_<?php echo md5($key) ?>">
								Eliminar (<span class="delete_products_<?php echo md5($key) ?>"></span>) registro(s)
							</a>
						</div>
						<div class="col-md-2 pt-4">
							<button type="submit" value="Enviar cotización" class="btn btn-success float-left envioCotClass mb-4" data-class="check_prod_<?php echo md5($key) ?>" data-brand="<?php echo md5($key) ?>">
								Enviar solicitud
							</button>
						</div>
					</div>
					
					<div class="input textarea d-none" style="display: none !important">
						<label for="">Razón de la importación</label>
						<input type="text" name="razon" placeholder="Por favor ingresa la razón por cual solicitas la importación." id="razon_<?php echo md5($key) ?>" cols="30" rows="10" class="form-control mb-3" value="Reposición de inventario para la marca: <?php echo $datosMarca["brand"]["Brand"]["name"] ?>">
					</div>
				</div>

			</div>
		  <?php $num++; endforeach;  ?>
		</div>
	<?php else: ?>
		<div class="blockwhitearriba mt-3 p-3">
			<h2 class="text-center text-warning">
				No se encontraron productos por favor realiza un filtro o inténtalo de nuevo.
			</h2>
		</div>
	<?php endif ?>
</div>
<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>
</div>

<script>
	 var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
    var bloqueos = false;
</script>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/product/inport_data.js?".rand(),				array('block' => 'AppScript'));
	echo $this->Html->script("controller/product/index.js?".rand(),						array('block' => 'AppScript'));
?>

<!-- Modal para crear o editar un producto desde la vista de crear una cotización -->
<div class="modal fade" id="modalViewVentas" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title">
        	Detalle de ventas de producto
        </h2>
      </div>
      <div class="modal-body" id="cuerpoModalViewVentas">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal btn btn-primary" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="modalFactura" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title">
        	Detalle de factura
        </h2>
      </div>
      <div class="modal-body" id="modalBodyFactura">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal btn btn-primary" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>


<style>
	.deleteAll{
		display: none;
	}
	a.linkTab,a.linkTab:hover{
		background-color: #006cd6 !important;
		color: #fff !important;
	}
</style>

<?php echo $this->element("flujoModal"); ?>
<?php echo $this->element("comentario"); ?>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<?php 


echo $this->Html->script(array('https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js?'.rand()),				array('block' => 'jqueryApp'));

echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
 ?>

 <?php echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); ?>

