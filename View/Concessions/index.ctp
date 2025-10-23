<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de empresas pertenecientes a consesiones</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'clients_legals','action'=>'index')) ?>" class="crearclientej"><i class="fa fa-1x fa-list vtc"></i> <span>Volver a clientes jurídicos</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'concessions','action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva consesion</span></a>
			</div>
		</div>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="input-group">
			<?php if (isset($this->request->query['q'])){ ?>
				<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador por nombre">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } else { ?>
				<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por nombre">
				<span class="input-group-addon btn_buscar">
					<i class="fa fa-search"></i>
				</span>
			<?php } ?>
		</div>			
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-bordered">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('name','Nombre'); ?></th>
						<th><?php echo $this->Paginator->sort('description','Descripción'); ?></th>
						<th><?php echo $this->Paginator->sort('clients_legal_id','Empresa o consesión'); ?></th>
						<th class="actions"><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>


					<?php foreach ($concessions as $concession): ?>
						<tr>
							<td><?php echo h($concession['Concession']['name']); ?>&nbsp;</td>
							<td><?php echo h($concession['Concession']['description']); ?>&nbsp;</td>
							<td>
								<?php echo $this->Html->link($concession['ClientsLegal']['name'], array('controller' => 'clients_legals', 'action' => 'view', $concession['ClientsLegal']['id'])); ?>
							</td>
							<td class="actions">
								<?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $concession['Concession']['id'])); ?>
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


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js'),						array('block' => 'jqueryApp'));
	 echo $this->Html->script("controller/inventories/detail.js",    array('block' => 'AppScript'));
	 	echo $this->Html->script("controller/clientsLegal/index.js",				array('block' => 'AppScript'));
?>