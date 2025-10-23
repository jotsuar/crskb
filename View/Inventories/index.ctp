<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<a href="<?php echo $this->Html->url(array('controller'=>'products','action'=>'index')) ?>" class="btn btn-info rounded-circle" data-toggle="tooltip" title="Regresar al listado de productos" > 
			<i class="fa fa-fw fa-arrow-left"></i> 
		</a>
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">
					Gestión de inventario para el producto: 
					<?php $productInfoDataset =  $this->Utilities->getQuantityBlock($product["Product"]); ?>
					<br> 
					<?php echo $product["Product"]["name"] ?> - <?php echo $product["Product"]["part_number"] ?><br>
					<b>Costo del producto USD: </b> <?php echo number_format($product["Product"]["purchase_price_usd"],2,",",".") ?> <br>
					<b>Costo del producto COP: </b> <?php echo number_format($product["Product"]["purchase_price_cop"],2,",",".") ?> <br>
				</h2>
				<div class="row">
					<div class="col-md-12">
						<h3>
							<b>Inventario actual: </b>
							<p class="mb-0"><b>Inventario Medellín:</b> <?php echo $productInfoDataset["productData"]["quantity"] ?></p>
							<p class="mb-0"><b>Inventario Bogotá:</b> <?php echo $productInfoDataset["productData"]["quantity_bog"] ?></p>
							<p class="mb-0"><b>Inventario Transito:</b> <?php echo $productInfoDataset["productData"]["quantity_back"] ?></p>
							<div>
						</h3>
						<div>
							<?php echo $this->element("products_block",["producto" => $product["Product"]]) ?>
						</div>	
					</div>
				</div>
				
			</div>
			<div class="col-md-6 text-right">
				<div class="input-group stylish-input-group">
					<div class="row w-100">
						<div class="col-md-12">
							<!-- <a href="#" data-id="<?php echo $productId ?>" id="createMovementData" class="crearclientej">
								<i class="fa fa-1x fa-plus-square"></i> 
								<span>Nuevo movimiento</span>
							</a> -->
						</div>
						
<!-- 						<div class="col-md-8">
							<div class="rangofechas w-100">
								<input type="date" value="<?php // echo ""; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
								<input type="text" value="<?php // echo ""; ?>" id="fechasInicioFin" class="w-50">
								<input type="date" value="<?php // echo "" ?>" max="<?php // echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
								<a class="btn-primary" id="btn_find_adviser">Filtrar Fechas</a>
							</div>
						</div> -->
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="myTable table-striped table-bordered">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('created',"Fecha y hora movimiento"); ?></th>
						<th><?php echo $this->Paginator->sort('type',"Tipo de movimiento"); ?></th>
						<th><?php echo $this->Paginator->sort('type_movement',"Detalle tipo movimiento"); ?></th>
						<th class="text-center"><?php echo $this->Paginator->sort('quantity',"Cantidad"); ?></th>
						<th><?php echo $this->Paginator->sort('warehouse',"Bodega"); ?></th>
						<th><?php echo $this->Paginator->sort('prospective_user_id',"Flujo"); ?></th>
						<th><?php echo $this->Paginator->sort('import_id',"Importación"); ?></th>
						<th><?php echo $this->Paginator->sort('reason',"Razón del movimiento"); ?></th>
						<th><?php echo $this->Paginator->sort('user_id',"Usuario que realizó el movimiento"); ?></th>
						<th><?php echo $this->Paginator->sort('state',"Estado del movimiento"); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($inventories as $inventory): ?>
						<tr>
							<td><?php echo h($inventory['Inventory']['created']); ?>&nbsp;</td>
							<td><?php echo Configure::read("TYPES_MOVEMENT_TEXT.".h($inventory['Inventory']['type'])) ; ?>&nbsp;</td>
							<td><?php echo Configure::read("INVENTORY_TYPE_REASON.".h($inventory['Inventory']['type_movement'])); ?>&nbsp; <b><?php echo empty($inventory["Inventory"]["inventory_id"]) ? $inventory["Inventory"]["id"] : $inventory["Inventory"]["inventory_id"] ?></b></td>
							<td class="text-center"> <?php echo $inventory["Inventory"]["type"] == 1 ? "<i class='fa fa-plus text-success vtc'></i>" : "<i class='fa fa-minus text-danger vtc'></i>"  ?> <?php echo h($inventory['Inventory']['quantity']); ?>&nbsp;</td>
							<td>
								<?php echo $inventory['Inventory']['warehouse'] ?>
							</td>
							<td>
								<?php if ($inventory['ProspectiveUser']['id']): ?>									
									<?php echo $this->Html->link($inventory['ProspectiveUser']['id'], array('controller' => 'ProspectiveUsers', 'action' => 'index', "?" => array( "q" => $inventory['ProspectiveUser']['id'] ) ), array("target" => "_blank","class" => "idflujotable m-1")); ?>
								<?php endif ?>
							</td>
							<td>
								<?php if ($inventory['Import']['id']): ?>									
									<?php echo $this->Html->link($inventory['Import']['id'], array('controller' => 'products', 'action' => 'products_import', $this->Utilities->encryptString($inventory['Import']['id']) ), array("target" => "_blank","class" => "idflujotable m-1") ); ?>
								<?php endif ?>
							</td>
							<td><?php echo h($inventory['Inventory']['reason']); ?>&nbsp;</td>
							<td>
								<?php echo $inventory['User']['name'] ?>
							</td>
							<td><?php 


								switch ($inventory['Inventory']['state']) {
									case '0':
										echo "Rechazado";
										break;
									case '1':
										echo "Activo";
										break;
									case '2':
										echo "Por aprobar";
										break;
									case '4':
										echo "Rechazado: ".$inventory["Inventory"]["reason_reject"];
										break;
								}

							 ?>&nbsp;</td>
							<td class="actions">
								<?php if ($inventory['Inventory']['state'] == 2 && AuthComponent::user("role") == Configure::read("variables.roles_usuarios.Gerente General")): ?>
									<a href="#" data-uid="<?php echo $inventory['Inventory']['id'] ?>" class="btn_aprobar" title="Aprobar">
										<i class="fa fa-check"></i>
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



<?php // echo $this->element("picker"); ?>
<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/inventories/index.js?".rand(),						array('block' => 'AppScript'));
 ?>

 <style>
	.entrada,.salida,.traslado,.infoInventory{
		display: none;
	}
</style>


<?php 
  echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); 
  echo $this->Html->script("controller/inventories/newMovementIndex.js?".rand(),    array('block' => 'AppScript')); ?>


 <!-- Modal -->
<div class="modal fade " id="modalMovimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Movimiento de inventario</h5>
      </div>
      <div class="modal-body" id="cuerpoMovimiento">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>