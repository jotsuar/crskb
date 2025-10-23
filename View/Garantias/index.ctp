
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de Garantías por proveedor</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'brands','action'=>'index')) ?>" class="crearclientej"><i class="fa fa-1x fa-list vtc"></i> <span>Gestionar marcas</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'garantias','action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva garantía</span></a>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre o marca">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre o marca">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="myTable table-striped table-bordered">
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('name',__("Nombre")); ?></th>
					<th><?php echo $this->Paginator->sort('brand_id',__("Marca")); ?></th>
					<th><?php echo __("Categoría 1"); ?></th>
					<th><?php echo __("Categoría 2"); ?></th>
					<th><?php echo __("Categoría 3"); ?></th>
					<th><?php echo __("Categoría 4"); ?></th>
					<th><?php echo $this->Paginator->sort('description',__("Descripción")); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php if (empty($garantias)): ?>
					<tr>
						<td class="text-center" colspan="4">
							Sin datos para mostrar
						</td>
					</tr>
				<?php endif ?>
				<?php foreach ($garantias as $garantia): ?>
					<tr>
						<td><?php echo h($garantia['Garantia']['name']); ?>&nbsp;</td>
						<td><?php echo h($garantia['Brand']['name']); ?>&nbsp;</td>
						<td><?php echo !empty($garantia["Garantia"]["category_1"]) ? $categories[$garantia["Garantia"]["category_1"]] : "" ?></td>
						<td><?php echo !empty($garantia["Garantia"]["category_2"]) ? $categories[$garantia["Garantia"]["category_2"]] : "" ?></td>
						<td><?php echo !empty($garantia["Garantia"]["category_3"]) ? $categories[$garantia["Garantia"]["category_3"]] : "" ?></td>
						<td><?php echo !empty($garantia["Garantia"]["category_4"]) ? $categories[$garantia["Garantia"]["category_4"]] : "" ?></td>
						<td><?php echo h($garantia['Garantia']['description']); ?>&nbsp;</td>
						<td class="actions">
					        <a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($garantia['Garantia']['id']) )) ?>" data-toggle="tooltip" title="Editar garantía"><i class="fa fa-fw fa-pencil"></i>
					            </a>
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
	var isRole = 1;
</script>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	 echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript'));
	 	echo $this->Html->script("controller/clientsLegal/index.js?".time(),				array('block' => 'AppScript'));
?>
