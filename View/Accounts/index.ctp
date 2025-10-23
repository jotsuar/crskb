<div class="accounts index">
	<h2><?php echo __('Accounts'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('date_send'); ?></th>
			<th><?php echo $this->Paginator->sort('initial_value'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('date_gest'); ?></th>
			<th><?php echo $this->Paginator->sort('date_deadline'); ?></th>
			<th><?php echo $this->Paginator->sort('date_payment'); ?></th>
			<th><?php echo $this->Paginator->sort('value_payment'); ?></th>
			<th><?php echo $this->Paginator->sort('document'); ?></th>
			<th><?php echo $this->Paginator->sort('notes'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($accounts as $account): ?>
	<tr>
		<td><?php echo h($account['Account']['id']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['date_send']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['initial_value']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($account['User']['name'], array('controller' => 'users', 'action' => 'view', $account['User']['id'])); ?>
		</td>
		<td><?php echo h($account['Account']['date_gest']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['date_deadline']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['date_payment']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['value_payment']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['document']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['notes']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['state']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['created']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $account['Account']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $account['Account']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $account['Account']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $account['Account']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Account'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Receipts'), array('controller' => 'receipts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Receipt'), array('controller' => 'receipts', 'action' => 'add')); ?> </li>
	</ul>
</div>
