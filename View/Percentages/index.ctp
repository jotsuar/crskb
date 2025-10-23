<div class="percentages index">
	<h2><?php echo __('Percentages'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('min'); ?></th>
			<th><?php echo $this->Paginator->sort('max'); ?></th>
			<th><?php echo $this->Paginator->sort('value'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($percentages as $percentage): ?>
	<tr>
		<td><?php echo h($percentage['Percentage']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($percentage['User']['name'], array('controller' => 'users', 'action' => 'view', $percentage['User']['id'])); ?>
		</td>
		<td><?php echo h($percentage['Percentage']['min']); ?>&nbsp;</td>
		<td><?php echo h($percentage['Percentage']['max']); ?>&nbsp;</td>
		<td><?php echo h($percentage['Percentage']['value']); ?>&nbsp;</td>
		<td><?php echo h($percentage['Percentage']['created']); ?>&nbsp;</td>
		<td><?php echo h($percentage['Percentage']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $percentage['Percentage']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $percentage['Percentage']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $percentage['Percentage']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $percentage['Percentage']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Percentage'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
