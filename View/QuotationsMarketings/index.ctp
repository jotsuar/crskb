<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azul big">
		<i class="fa fa-1x flaticon-settings"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Configuraciones </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">
					<?php if (is_null($bot)): ?>
						Gestión de cotizaciones para envio masivo
					<?php else: ?>
						Gestión de cotizaciones para cotizar automaticamente en el chat
					<?php endif ?>
				</h2>
			</div>
			<div class="col-md-6 text-right">
				<?php if (!is_null($bot)): ?>
						<a href="<?php echo $this->Html->url(array('action'=>'add',$bot)) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva cotización</span></a>
					<?php else: ?>
						<a href="<?php echo $this->Html->url(array('action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva cotización</span></a>
					<?php endif ?>
				
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-bordered">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('name',"Asunto"); ?></th>
						<th><?php echo $this->Paginator->sort('header_id','Banner'); ?></th>
						<th><?php echo $this->Paginator->sort('total_visible','Mostrar total'); ?></th>
						<!-- <th><?php //echo $this->Paginator->sort('state'); ?></th> -->
						<th><?php echo $this->Paginator->sort('created','Fecha de creación'); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($quotationsMarketings as $quotationsMarketing): ?>
					<tr>
						<td><?php echo h($quotationsMarketing['QuotationsMarketing']['name']); ?>&nbsp;</td>
						<td>
							<?php echo $this->Html->link($quotationsMarketing['Header']['name'], array('controller' => 'headers', 'action' => 'view', $quotationsMarketing['Header']['id'])); ?>
						</td>
						<td><?php echo ($quotationsMarketing['QuotationsMarketing']['total_visible']) == 1 ? "Si" : "No"; ?>&nbsp;</td>
						<!-- <td><?php //echo ($quotationsMarketing['QuotationsMarketing']['state']) == 1 ? "Activa" : "Inactiva"; ?>&nbsp;</td> -->
						<td><?php echo h($quotationsMarketing['QuotationsMarketing']['created']); ?>&nbsp;</td>
						<td class="actions">
							<a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($quotationsMarketing['QuotationsMarketing']['id']),  !is_null($bot) ? $bot : '' )) ?>" data-toggle="tooltip" data-placement="right" title="Editar cotización"><i class="fa fa-fw fa-pencil"></i>
					            </a>
					        <a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($quotationsMarketing['QuotationsMarketing']['id']) ,!is_null($bot) ? $bot : '' )) ?>" data-toggle="tooltip" data-placement="right" title="Ver cotización"><i class="fa fa-fw fa-eye"></i>
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

<?php 
	
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),array('block' => 'jqueryApp'));

 ?>