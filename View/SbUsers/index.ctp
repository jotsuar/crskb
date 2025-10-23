<div class="sbUsers index">
	<h2><?php echo __('Sb Users'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('first_name'); ?></th>
			<th><?php echo $this->Paginator->sort('last_name'); ?></th>
			<th><?php echo $this->Paginator->sort('password'); ?></th>
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('profile_image'); ?></th>
			<th><?php echo $this->Paginator->sort('user_type'); ?></th>
			<th><?php echo $this->Paginator->sort('creation_time'); ?></th>
			<th><?php echo $this->Paginator->sort('token'); ?></th>
			<th><?php echo $this->Paginator->sort('last_activity'); ?></th>
			<th><?php echo $this->Paginator->sort('typing'); ?></th>
			<th><?php echo $this->Paginator->sort('online'); ?></th>
			<th><?php echo $this->Paginator->sort('department'); ?></th>
			<th><?php echo $this->Paginator->sort('response_crm'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($sbUsers as $sbUser): ?>
	<tr>
		<td><?php echo h($sbUser['SbUser']['id']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['first_name']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['last_name']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['password']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['email']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['profile_image']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['user_type']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['creation_time']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['token']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['last_activity']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['typing']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['online']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['department']); ?>&nbsp;</td>
		<td><?php echo h($sbUser['SbUser']['response_crm']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $sbUser['SbUser']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $sbUser['SbUser']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $sbUser['SbUser']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $sbUser['SbUser']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Sb User'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Sb Tiempos'), array('controller' => 'sb_tiempos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sb Tiempo'), array('controller' => 'sb_tiempos', 'action' => 'add')); ?> </li>
	</ul>
</div>
