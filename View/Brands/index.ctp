<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de Marcas</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'garantias','action'=>'index')) ?>" class="crearclientej"><i class="fa fa-1x fa-list vtc"></i> <span>Gestionar garantías de proveedor</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'brands','action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva marca</span></a>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre o razón social">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre o razón social">
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
						<th><?php echo $this->Paginator->sort('name',"Nombre Marca"); ?></th>
						<th><?php echo $this->Paginator->sort('provider',"Nombre Proveedor"); ?></th>
						<th><?php echo $this->Paginator->sort('name',"Razón social"); ?></th>
						<th><?php echo $this->Paginator->sort('dni',"Identificación"); ?></th>
						<th><?php echo $this->Paginator->sort('brand_id',"Marca principal"); ?></th>
						<th><?php echo $this->Paginator->sort('id_llc',"Conectado con LLC"); ?></th>
						<th><?php echo $this->Paginator->sort('min_price_importer',"Costo mínimo de importación"); ?></th>
						<th><?php echo $this->Paginator->sort('state',"Estado"); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($brands as $brand): ?>
					<?php if ($brand["Brand"]["id"] == 1): ?>
						<?php continue;; ?>
					<?php endif ?>
					<tr>
						<td><?php echo h($brand['Brand']['name']); ?>&nbsp;</td>
						<td><?php echo h($brand['Brand']['provider']); ?>&nbsp;</td>
						<td><?php echo h($brand['Brand']['social_reason']); ?>&nbsp;</td>
						<td><?php echo h($brand['Brand']['dni']); ?>&nbsp;</td>
						<td><?php echo $brand['Brand']['brand_id'] == 0 ? "Ninguna" : $brand["Father"]["name"] ; ?>&nbsp;</td>
						<td><?php echo $brand['Brand']['id_llc'] == null ? "No" : "SI" ; ?>&nbsp;</td>
						<td><?php echo number_format($brand['Brand']['min_price_importer'],0,",","."); ?>&nbsp;</td>
						<td><?php echo h($brand['Brand']['state']) == 1 ? "Activo" : "Inactivo"; ?>&nbsp;</td>
						<td class="actions">
							<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($brand['Brand']['id']))) ?>" data-toggle="tooltip" title="Ver marca"><i class="fa fa-fw fa-eye"></i>
					            </a>
					        <a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($brand['Brand']['id']) )) ?>" data-toggle="tooltip" title="Editar marca"><i class="fa fa-fw fa-pencil"></i>
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
	echo $this->Html->script(array('lib/jquery-3.0.0.js'),						array('block' => 'jqueryApp'));
	 echo $this->Html->script("controller/inventories/detail.js",    array('block' => 'AppScript'));
	 	echo $this->Html->script("controller/clientsLegal/index.js",				array('block' => 'AppScript'));
?>