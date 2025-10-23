<?php 
	$whitelist = array(
            '127.0.0.1',
            '::1'
        );
 ?>
<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-morado big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Panel principal de compras </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-4">
				<h2 class="titleviewer">Movimientos de inventario</h2>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<?php echo $this->Form->create('Inventory',array('data-parsley-validate'=>true,"type" =>"get")); ?>
			<div class="row">
				<div class="col-md-6">
							<div class="form-group">
								<label for="tipoMovimiento">Buscar por tipo de tipo de movimiento</label>
								<select name="tipoMovimiento" id="" class="form-control">
									<option value="">Seleccionar tipo de movimiento</option>
									<option value="EN" <?php echo isset($queryData) && $queryData == "EN" ? "selected" : "" ?>>Entrada</option>
									<option value="RM" <?php echo isset($queryData) && $queryData == "RM" ? "selected" : "" ?>>Salida</option>
									<option value="TR" <?php echo isset($queryData) && $queryData == "TR" ? "selected" : "" ?>>Traslado</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<input type="submit" value="Buscar" class="btn btn-success mt-4">
						</div>
					</div>
			</div>
		</form>
	</div>
	<div class="clientsLegals index blockwhite">
		<div class="contenttableresponsive">
			<table cellpadding="0" cellspacing="0" class='myTable table-striped table-bordered'>
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('created',"Fecha y hora movimiento"); ?></th>
						<th><?php echo $this->Paginator->sort('product_id',"Producto"); ?></th>
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
							<td><?php echo h($inventory['Product']['name'])." - ".h($inventory['Product']['part_number']); ?>&nbsp;</td>
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

<script>
	 var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
</script>
<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp')); 
	echo $this->Html->script("controller/inventories/index.js?".rand(),				array('block' => 'AppScript'));
?>