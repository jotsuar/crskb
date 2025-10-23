<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de tiempos para bloqueos del chat</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'times','action'=>'add')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva configuración</span></a>
			</div>
		</div>
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-hovered">
				<thead>
				<tr>
						<th><?php echo $this->Paginator->sort('user_id','Usuario'); ?></th>
						<th><?php echo $this->Paginator->sort('minutes','Minutos para bloquear en semana'); ?></th>
						<th><?php echo $this->Paginator->sort('minutes_sat', 'Minutos para bloquear el sábado'); ?></th>
						<th><?php echo $this->Paginator->sort('vacation', 'Vacaciones'); ?></th>
						<th><?php echo $this->Paginator->sort('block_user','¿Bloquear usuario?'); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($times as $time): ?>
				<tr>
					<td>
						<?php echo $time['User']['name']; ?>
					</td>
					<td><?php echo h($time['Time']['minutes']); ?>&nbsp;</td>
					<td><?php echo h($time['Time']['minutes_sat']); ?>&nbsp;</td>
					<td>
						<?php if ($time['Time']['vacation'] == 1): ?>
							<b>SI</b> <br>
							<?php echo $time["Time"]["date_ini"] ?> / <?php echo $time["Time"]["date_end"] ?>
						<?php else: ?>
							NO
						<?php endif ?>
					</td>
					<td><?php echo ($time['Time']['block_user']) == 1 ? 'Si' : 'No'; ?>&nbsp;</td>
					<td class="actions">
						<a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($time['Time']['id']) )) ?>" data-toggle="tooltip" title="Editar configuración"><i class="fa fa-fw fa-pencil"></i>
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
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/templates/index.js?".rand(),				array('block' => 'AppScript'));
?>