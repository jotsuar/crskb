<?php 	
	$rolesPriceImport = array(
		Configure::read('variables.roles_usuarios.Logística'),
		Configure::read('variables.roles_usuarios.Gerente General')
	);
	$validRole = in_array(AuthComponent::user("role"), $rolesPriceImport) ? true : false;

	$roleAdmin = AuthComponent::user("role") == "Gerente General" ? true : false;
?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12">
				<h2 class="titleviewer">Panel de bloqueos y movimientos de inventario para productos con bloqueo</h2>
				<a href="<?php echo $this->Html->url(array("controller" => "inventories", "action" => "movements")) ?>" class="btn btn-primary float-right">
					<i class="fa fa-arrow-left vtc"></i> Ir a movimientos de inventarío normales 
				</a>
			</div>
			<div class="col-md-7 text-right">
				<?php echo $this->Form->create('',array('class' => 'form w-100',"type" => "get")); ?>
					<div class="input-group">					
						<?php if (isset($this->request->query['q'])){ ?>
							<input type="text" id="q" name="q" value="<?php echo isset($this->request->query['q']) ? $this->request->query['q'] : ""; ?>" class="form-control" placeholder="Buscador por referencia, o flujo">
							<button type="submit" class="input-group-addon btn_buscar">
				                <i class="fa fa-search"></i>
				            </button>
						<?php } else { ?>
							<input type="text" id="q" name="q" value="<?php echo isset($this->request->query['q']) ? $this->request->query['q'] : ""; ?>" class="form-control" placeholder="Buscador por referencia, o flujo">
							<button type="submit" class="input-group-addon btn_buscar">
				                <i class="fa fa-search"></i>
				            </button>
						<?php } ?>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="myTable table-striped table-bordered">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('created',"Fecha y hora bloqueo"); ?></th>
						<th><?php echo "Imagen"; ?></th>
						<th><?php echo $this->Paginator->sort('Product.part_number',"Referencia "); ?></th>
						<th><?php echo $this->Paginator->sort('Product.name',"Producto"); ?></th>
						<th><?php echo $this->Paginator->sort('Product.due_date',"Fecha límite de bloqueo"); ?></th>
						<th><?php echo $this->Paginator->sort('prospective_user_id',"Flujo "); ?></th>
						<th><?php echo $this->Paginator->sort('state',"Estado del bloqueo"); ?></th>
						<th class="text-center"><?php echo "Cantidad bloqueada"; ?></th>
						<th class="actions text-center"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($blocks as $bloqueo): ?>
						<tr>
							<td><?php echo h($bloqueo['ProductsLock']['created']); ?>&nbsp;</td>
							<td>
								<?php $ruta = $this->Utilities->validate_image_products($bloqueo['Product']['img']); ?>
									<img class="img-fluid productoImagenData" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" style="width: 60px;">
													</td>
							<td><?php echo h($bloqueo['Product']['part_number']); ?>&nbsp;</td>

							<td><?php echo h($bloqueo["Product"]["name"]) ?></td>

							<td>
								<?php echo $bloqueo["ProductsLock"]["due_date"] ?>
							</td>
							<td>
								<?php if ($bloqueo['ProspectiveUser']['id']): ?>									
									<?php echo $this->Html->link($bloqueo['ProspectiveUser']['id'], array('controller' => 'ProspectiveUsers', 'action' => 'index', "?" => array( "q" => $bloqueo['ProspectiveUser']['id'] ) ), array("target" => "_blank","class" => "btnmin m-1")); ?>
								<?php endif ?>
							</td>
							<td>
								<?php echo $bloqueo["ProductsLock"]["state"] == 1 ? "Bloqueo activo" : "Desbloqueado" ?>
							</td>
							<td class="text-center resetpd detailinv">
								<p class="c1">MEDELLÍN: <b><?php echo $bloqueo["ProductsLock"]["quantity"] ?></b></p>
								<p class="c2">BOGOTÁ: <b><?php echo $bloqueo["ProductsLock"]["quantity_bog"] ?></b></p>
								<p class="c1">TRÁNSITO: <b><?php echo $bloqueo["ProductsLock"]["quantity_back"] ?></b></p>
							</td>							
							<td class="actions size11 text-center">
								<?php if ($bloqueo['ProductsLock']['state'] == 1 && AuthComponent::user("role") == Configure::read("variables.roles_usuarios.Gerente General")): ?>
									<a href="#" data-uid="<?php echo $bloqueo['ProductsLock']['id'] ?>" class="btn_desbloqueo btn btn-warning" data-toggle="tooltip" title="Desbloquear inventario">
										<i class="fa fa-unlock vtc"></i>
									</a>
									<div id="infoProductInventoryUnlook" style="display: none">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12">
												</div>
												<div class="col-md-12">
													<div class="dataproductview2">
														<small class="text-success">Referencia: <?php echo $bloqueo["Product"]['part_number'] ?> / Marca: <?php echo $bloqueo["Product"]['brand'] ?></small> <br>
														<small class=""><?php echo $this->Text->truncate(strip_tags($bloqueo["Product"]['name']), 70,array('ellipsis' => '...','exact' => false)); ?></small> <br>
														<h3 class="text-info text-center">Detalle de inventario a desbloquear</h3>
														<small class="mb-0"><b>Inventario Medellín:</b> <?php echo $bloqueo["ProductsLock"]["quantity"] ?></small><br>
														<small class="mb-0"><b>Inventario Bogotá:</b> <?php echo $bloqueo["ProductsLock"]["quantity_bog"] ?></small><br>
														<small class="mb-0"><b>Inventario Transito:</b> <?php echo $bloqueo["ProductsLock"]["quantity_back"] ?></small>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif ?>
								<?php if ($validRole): ?>
									<a href="#" data-product="<?php echo $bloqueo['ProductsLock']['product_id'] ?>" data-toggle="tooltip" data-id="<?php echo $bloqueo['ProductsLock']['id'] ?>" class="trasladoInventario btn btn-secondary" title="Realizar traslado de inventario para facturar">
										<i class="fa fa-reload fa-arrows-h vtc"></i>
									</a>
								<?php endif ?>

							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
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





<?php echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp')); ?>

<?php 
  echo $this->Html->script("controller/inventories/blocks.js?".rand(),    array('block' => 'AppScript')); ?>



<!-- Modal -->
<div class="modal fade " id="modalMovimientoTraslado" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Traslado de inventario para facturación</h5>
      </div>
      <div class="modal-body" id="cuerpoMovimientoTraslado">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<style>
	.entrada,.salida,.traslado,.infoInventory{
		display: none;
	}
</style>
