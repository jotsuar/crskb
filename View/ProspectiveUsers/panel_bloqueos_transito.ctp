 <?php 

    $whitelist = array(
            '127.0.0.1',
            '::1'
        );

    $rolesPermitidos = array(
    	"Gerente General", "Logística","Asesor Comercial"
    );

 ?>
<div class="col-md-12 p-0">
	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1 class="nameview">PANEL PRINCIPAL DE COMPRAS - PANEL DE BLOQUEOS ACTIVOS EN TRÁNSITO</h1>
			</div>
		</div>
	</div>
	<div class="blockwhitearriba ">
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs providerslist" id="myTab" role="tablist">
					<?php $num = 0; ?>
					<?php foreach ($marcas as $key => $value): ?>
						<li class="nav-item">
							<?php 
								$clase = "";
								if($num == 0 && !isset($this->request->query["brand"])){
									$clase = "active show";
								}elseif (isset($this->request->query["brand"]) && !in_array($this->request->query["brand"], $marcas) && $num == 0) {
									$clase = "active show";
								}else{
									if(isset($this->request->query["brand"])){
										if($this->request->query["brand"] == $key){
											$clase = "active show";
										}
									}
								}
							 ?>
					    	<a class="nav-link linkTab <?php echo $clase ?>" id="tab_<?php echo md5($key) ?>" data-brand="<?php echo $key ?>" data-toggle="tab" href="#KEBCO<?php echo md5($key) ?>" role="tab" aria-controls="<?php echo md5($key) ?>" aria-selected="true">
					    	 <?php echo $value; ?>
					    	 <span class="text-danger">(<?php echo count($bloqueos[$key]) ?>)</span>
					    	</a>
					  </li> 
					<?php $num++; endforeach;  ?>
				</ul>
			</div>
		</div>
	</div>
	<?php if (!empty($bloqueos)): ?>
		<div class="tab-content" id="myTabContent">
		  <?php $num = 0; ?>
		  <?php foreach ($marcas as $key => $variable): ?>
			<?php 
				$clase = "";
				if($num == 0 && !isset($this->request->query["brand"])){
					$clase = "active show";
				}elseif (isset($this->request->query["brand"]) && !in_array($this->request->query["brand"], $marcas) && $num == 0) {
					$clase = "active show";
				}else{
					if(isset($this->request->query["brand"])){
						if($this->request->query["brand"] == $key){
							$clase = "active show";
						}
					}
				}
			 ?>
			<div class="tab-pane fade tabContentData <?php echo $clase ?>" id="KEBCO<?php echo md5($key) ?>" role="tabpanel" aria-labelledby="home-tab">

				<div class="blockwhiteabajo2 spacebtn20 limitheightscroll">
				<div class="import">
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
							<th>Imagen</th>
							<th>Nombre</th>
							<th>Referencia</th>
							<th class="text-center">En tránsito</th>							
							<th class="text-center">Bloqueadas</th>								
							<th class="size4">Inventario actual</th>							
							<th class="size4">Cantidad a importar</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($bloqueos[$key] as $idProduct => $value): ?>
							<?php $idProduct = $value["inventories"]["product_id"] ?>
							<tr>
								<td class="text-center">
									<label class="containercheck">
										<input type="checkbox" class="checkB check_prod_<?php echo md5($key) ?>" data-part="<?php echo $value["products"]["part_number"] ?>" value="<?php echo $idProduct ?>" data-class="check_prod_<?php echo md5($key) ?>" data-id="<?php echo md5($key) ?>" data-product="<?php echo $idProduct ?>" data-brand = "<?php echo $value["products"]["brand_id"] ?>" data-delete="delete_products_<?php echo md5($key) ?>" data-check="check_all_<?php echo md5($key) ?>">
										<span class="checkmark"></span>
									</label>

								</td>
								<td>
									<?php $ruta = $this->Utilities->validate_image_products($value["products"]['img']); ?>
									<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value['products']['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="65px" height="65px" class="imgmin-product">
								</td>
								<td class="nameuppercase <?php echo !is_null($value["products"]["notes"]) ? "nota" : "" ?>">
									<?php if (!is_null($value["products"]["notes"])): ?>
										<div class="notaproduct">
										<span class="triangle"></span>
										<span class="flechagiro">|</span>
										<div class="text_nota">
											<small class="creadornota"><b></b></small>
											<?php echo $value["notes"] ?>
											<small class="datenota"></small>
										</div>
										</div>
										<?php echo $value["products"]["name"] ?>
									<?php else: ?>
										<?php echo $value["products"]["name"] ?>
									<?php endif ?>
								</td>
								
								<td><?php echo $value["products"]["part_number"]?></td>
								<td class="text-center">
									<a href="#" data-id="<?php echo $idProduct ?>" class="showBills btn btn-outline-primary">
										<b><?php echo $value["0"]["total"] ?></b>
									</a>
								</td>
								<td class="text-center">
									<a href="#" data-id="<?php echo $idProduct ?>" class="showBills btn btn-outline-primary">
										<b><?php echo $value["products_locks"]["quantity_back"] ?></b>
									</a>
								</td>
								<td>
									<?php echo $this->element("products_block",["producto" => $this->Utilities->data_product($value["inventories"]["product_id"])["Product"],"inventario_wo" => $partsData[$value["products"]["part_number"]], "reserva" => isset($partsData["Reserva"][$value["products"]["part_number"]]) ? $partsData["Reserva"][$value["products"]["part_number"]] : null ]) ?>
								</td>
								
								<td class="text-center">
									<input class="form-control quantityNumber d-inline" id="qt_prod_<?php echo $idProduct ?>" name="quantity" type="number" data-class="check_prod_<?php echo md5($key) ?>" data-brand="<?php echo md5($key) ?>" data-id="<?php echo $idProduct ?>" value="<?php echo $value["products_locks"]["quantity_back"] ?>" min="0">
								</td>
							</tr>
						<?php endforeach ?>
						<tr>
							<td class="align-center pb-3 pt-3">
								<a href="#" class="btn btn-danger deleteAll delete_products_<?php echo md5($key) ?>" data-class="check_prod_<?php echo md5($key) ?>">
									Eliminar (<span class="delete_products_<?php echo md5($key) ?>"></span>) registro(s)
								</a>
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

					</tbody>
				</table>
				</div>
					<div class="text-center mb-3 mt-3">
						<span>PRODUCTOS AÑADIDOS: </span> <b><span id="totalProducts_<?php echo md5($key) ?>">0</b></span>  | 
						<span class="text-right"> CANTIDAD TOTAL DE ITEMS: </span> <b><span id="quantityTotal_<?php echo md5($key) ?>">0</b></span>
					</div>
				</div>

				<div class="blockwhite">
					<div class="form-group">
						<label for="internacional">El pedido se hara nacional o internacional</label>
						<select name="internacional" class="form-control" id="internacional_<?php echo md5($key) ?>">
							<option value="0">Nacional</option>
							<option value="1">Internacional</option>
						</select>
					</div>
					<div class="input textarea d-none" style="display: none !important">
						<label for="">Razón de la importación</label>
						<input type="text" name="razon" placeholder="Por favor ingresa la razón por cual solicitas la importación." id="razon_<?php echo md5($key) ?>" cols="30" rows="10" class="form-control mb-3" value="Reposición de inventario para la marca: <?php echo $value["products"]["brand"] ?>, teniendo en cuenta los bloqueos existentes.">
					</div>
					<div class="w-100 pb-4">
						<button type="submit" value="Enviar cotización" class="btn btn-success float-right envioCotClass mb-4" data-class="check_prod_<?php echo md5($key) ?>" data-brand="<?php echo md5($key) ?>">
						Enviar solicitud
					</button>
					</div>
				</div>

			</div>
		  <?php $num++; endforeach;  ?>
		</div>
	<?php else: ?>
		<h2 class="text-center text-warning">
			No se encontraron productos bloqueados con transito
		</h2>
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
    var bloqueos = true;
</script>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/product/inport_data.js?".rand(),				array('block' => 'AppScript'));
	echo $this->Html->script("controller/product/index.js?".rand(),						array('block' => 'AppScript'));
?>

<!-- Modal para crear o editar un producto desde la vista de crear una cotización -->
<div class="modal fade" id="modalViewVentas" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
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

<style>
	.deleteAll{
		display: none;
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

