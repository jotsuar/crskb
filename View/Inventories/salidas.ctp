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
				<h2 class="titleviewer">Inventario que necesita aprobación para salida de inventario</h2>
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
			<h2 class="text-center mb-3">
				Salidas marcadas individualmente
			</h2>
			<table cellpadding="0" cellspacing="0" class='myTable table-striped table-bordered'>
				<thead>
					<tr>
						<th>Imagen</th>
						<th><?php echo 'Referencia'; ?></th>
						<th><?php echo 'Producto'; ?></th>
						
						<th><?php echo __("Inventario actual") ?></th>
						<th class="text-center">
							<?php echo 'Costo reposición'; ?><br>
							<?php echo 'Costo WO'; ?>
						</th>
						<th ><?php echo 'Marca'; ?></th>
						<th><?php echo 'Bodega'; ?></th>
						<th><?php echo 'Motivo salida'; ?></th>
						<th><?php echo 'Cantidad'; ?></th>
						<th>Usuario / Fecha de movimiento</th>
						<th>
							<?php if (!empty($products)): ?>
								<input type="checkbox" class="checkallProducts">
							<?php endif ?>
						</th>
						<th class="">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($products)): ?>
							<tr>
								<td colspan="10"></td>
								<td colspan="1">
									<button style="display: none" class="btn btn-success dropdownMenuAccionesProductos" type="button" id="aproveMassive">
									    Aprobar
									</button>
								</td>
								<td colspan="1">
									<button style="display: none" class="btn btn-danger dropdownMenuAccionesProductos" type="button" id="rejectMassive">
									    Rechazar
									</button>
								</td>
								
							</tr>

						<?php foreach ($products as $key => $product): ?>
							<?php $dataProducto = $this->Utilities->getQuantityBlock($product["Product"]); ?>
							<tr id="trMove_<?php echo $product["Inventory"]["id"] ?>">
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
								<td><?php echo h($product['Product']['brand']); ?>&nbsp;</td>
								<td class="sizeprod ">
									<?php echo $product["Inventory"]["warehouse"] ?>
								</td>
								<td class="sizeprod ">
									<?php echo $product["Inventory"]["reason"] ?>
								</td>
								<td class="sizeprod ">
									<?php echo $product["Inventory"]["quantity"] ?>
								</td>
								<td>
									<?php if (!is_null($product["Inventory"]["user_id"])): ?>
										<?php echo $this->Utilities->find_name_adviser($product["Inventory"]["user_id"]) ?> / <?php echo h($product['Inventory']['created']); ?>
									<?php endif ?>
								</td>
								<td>
									<?php if ($validRole): ?>
										<input type="checkbox" class="checkOneProducts" value="<?php echo $product["Inventory"]["id"] ?>">										
									<?php endif ?>
								</td>


								<td class="text-center">
									<?php if ($validRole): ?>
			    						<a href="#" class="btn btn-success btn_aprobarlistado" data-uid="<?php echo $product["Inventory"]["id"] ?>" data-toggle="tooltip" title="Aprobar salida">
			    							<i class="fa fa-check vtc"></i> 
			    						</a>
			    						<a href="#" class="btn btn-danger NoAprovar rechazoListado" data-id="<?php echo $product["Inventory"]["id"] ?>" data-toggle="tooltip" title="Rechazar salida">
			    							<i class="fa fa-close vtc"></i> 
			    						</a>
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>

					<?php else: ?>
						<tr>
							<td colspan="12" class="text-center">
								No hay salidas de  productos en el momento
							</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="clientsLegals index blockwhite">
		<div class="contenttableresponsive">
			<h2 class="text-center mb-3">
				Salidas marcadas en grupo
			</h2>
			<table cellpadding="0" cellspacing="0" class='myTable table-striped table-bordered'>
				<thead>
					<tr>
						<th><?php echo 'Referencia grupo'; ?></th>
						<th><?php echo 'Motivo salida'; ?></th>
						<th><?php echo 'Cantidad de salidas'; ?></th>
						<th>Usuario / Fecha de movimiento</th>
						<th class="">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($productsGroup)): ?>
						<?php foreach ($productsGroup as $key => $product): ?>
							<tr id="trMoveGroup_<?php echo $product["Inventory"]["packnumber"] ?>">
								<td><?php echo h($product['Inventory']['packnumber']); ?>&nbsp;</td>
								<td class="sizeprod ">
									<?php echo $product["Inventory"]["reason"] ?>
								</td>
								<td class="sizeprod ">
									<?php echo $this->Utilities->getTotalGroupInventory($product["Inventory"]["packnumber"]); ?>
								</td>
								<td>
									<?php if (!is_null($product["Inventory"]["user_id"])): ?>
										<?php echo $this->Utilities->find_name_adviser($product["Inventory"]["user_id"]) ?> / <?php echo h($product['Inventory']['created']); ?>
									<?php endif ?>
								</td>
								<td class="text-center">
									<?php if ($validRole): ?>
			    						<a href="#" class="btn btn-info btn_view_group" data-uid="<?php echo $product["Inventory"]["packnumber"] ?>" data-toggle="tooltip" title="Ver detalle">
			    							<i class="fa fa-eye vtc"></i> 
			    						</a>
			    						<a href="#" class="btn btn-success btn_aprobarlistado_grupo" data-uid="<?php echo $product["Inventory"]["packnumber"] ?>" data-inventories="<?php echo $this->Utilities->getListGroupInventory($product["Inventory"]["packnumber"]) ?>" data-toggle="tooltip" title="Aprobar salidas">
			    							<i class="fa fa-check vtc"></i> 
			    						</a>
			    						<a href="#" class="btn btn-danger NoAprovar rechazoListado_grupo" data-id="<?php echo $product["Inventory"]["packnumber"] ?>" data-inventories="<?php echo $this->Utilities->getListGroupInventory($product["Inventory"]["packnumber"]) ?>" data-toggle="tooltip" title="Rechazar salidas">
			    							<i class="fa fa-close vtc"></i> 
			    						</a>
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>

					<?php else: ?>
						<tr>
							<td colspan="12" class="text-center">
								No hay salidas de  productos en el momento
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
	echo $this->Html->script("controller/inventories/index.js?".rand(),				array('block' => 'AppScript'));
?>

<?php echo $this->element("comentario"); ?>


 <!-- Modal -->
<div class="modal fade " id="modalMovimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Movimiento de inventario asociados en grupo</h5>
      </div>
      <div class="modal-body" id="cuerpoMovimiento">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>