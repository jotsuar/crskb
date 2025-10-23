<div class="envoices index">
	<h2><?php echo __('Envoices'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_gest'); ?></th>
			<th><?php echo $this->Paginator->sort('order_id'); ?></th>
			<th><?php echo $this->Paginator->sort('prefijo'); ?></th>
			<th><?php echo $this->Paginator->sort('identificator'); ?></th>
			<th><?php echo $this->Paginator->sort('note'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('date_initial'); ?></th>
			<th><?php echo $this->Paginator->sort('date_end'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($envoices as $envoice): ?>
	<tr>
		<td><?php echo h($envoice['Envoice']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($envoice['User']['name'], array('controller' => 'users', 'action' => 'view', $envoice['User']['id'])); ?>
		</td>
		<td><?php echo h($envoice['Envoice']['user_gest']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($envoice['Order']['id'], array('controller' => 'orders', 'action' => 'view', $envoice['Order']['id'])); ?>
		</td>
		<td><?php echo h($envoice['Envoice']['prefijo']); ?>&nbsp;</td>
		<td><?php echo h($envoice['Envoice']['identificator']); ?>&nbsp;</td>
		<td><?php echo h($envoice['Envoice']['note']); ?>&nbsp;</td>
		<td><?php echo h($envoice['Envoice']['state']); ?>&nbsp;</td>
		<td><?php echo h($envoice['Envoice']['date_initial']); ?>&nbsp;</td>
		<td><?php echo h($envoice['Envoice']['date_end']); ?>&nbsp;</td>
		<td><?php echo h($envoice['Envoice']['created']); ?>&nbsp;</td>
		<td><?php echo h($envoice['Envoice']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $envoice['Envoice']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $envoice['Envoice']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $envoice['Envoice']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $envoice['Envoice']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Envoice'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
