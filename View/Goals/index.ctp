<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Tesorería </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Gestión de Metas de facturación</h2>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'goals','action'=>'add')) ?>" ><i class="fa fa-1x fa-plus-square"></i> <span>Crear nueva meta</span></a>
			</div>
		</div>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="myTable table-striped table-bordered">
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('year', 'Año'); ?></th>
						<th><?php echo $this->Paginator->sort('user_id', 'Usuario'); ?></th>
						<th><?php echo ('Total'); ?></th>
						<th class="actions"><?php echo __('Acciones'); ?></th>
					</tr>	
				</thead>
				<tbody>
					<?php foreach ($goals as $key => $goal): ?>
						<tr>
							<td><?php echo h($goal['Goal']['year']); ?>&nbsp;</td>
							<td>
								<?php echo empty($goal['User']['name']) ? "TODOS" : $goal['User']['name']?>
							</td>
							<td>
								$
								<?php $total = $goal['Goal']['01'] + $goal['Goal']['02'] +$goal['Goal']['03'] +$goal['Goal']['04'] +$goal['Goal']['05'] +$goal['Goal']['06'] +$goal['Goal']['07'] +$goal['Goal']['08'] +$goal['Goal']['09'] +$goal['Goal']['10'] +$goal['Goal']['11'] +$goal['Goal']['12'];
								 	  echo number_format($total);
								 ?>
							</td>
							<td class="actions">
								<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($goal['Goal']['id']))) ?>" data-toggle="tooltip" title="Ver meta"><i class="fa fa-fw fa-eye"></i>
						            </a>
						        <a href="<?php echo $this->Html->url(array('action' => 'edit', $this->Utilities->encryptString($goal['Goal']['id']) )) ?>" data-toggle="tooltip" title="Editar meta"><i class="fa fa-fw fa-pencil"></i>
						            </a>
							</td>
						</tr>	
					<?php endforeach ?>
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