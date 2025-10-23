<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de autorizaciones de fechas de inasistencia</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('action'=>'add')) ?>" class="btn btn-info"><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva autorización</span></a>
			</div>
		</div>
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-hovered table-bordered">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('user_id','Usuario'); ?></th>
						<th><?php echo $this->Paginator->sort('date_excluded','Fecha excluida'); ?></th>
						<th><?php echo $this->Paginator->sort('created','Fecha de creación'); ?></th>
						<th class="actions"><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($excludes as $exclude): ?>
				<tr>
					<td>
						<?php echo $exclude['User']['name']; ?>
					</td>
					<td><?php echo h($exclude['Exclude']['date_excluded']); ?>&nbsp;</td>
					<td><?php echo h($exclude['Exclude']['created']); ?>&nbsp;</td>
					<td class="actions">
						<?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $exclude['Exclude']['id']), ["class" => "btn btn-warning"] ); ?>
							<?php echo $this->Form->postLink(__('Borrar'), array('action' => 'delete', $exclude['Exclude']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $exclude['Exclude']['id']), "class" => "btn btn-danger" )); ?>
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

	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));

 ?>
