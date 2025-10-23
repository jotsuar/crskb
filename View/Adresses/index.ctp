<div class="adresses index">
	<h2><?php echo __('Adresses'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('address'); ?></th>
			<th><?php echo $this->Paginator->sort('address_detail'); ?></th>
			<th><?php echo $this->Paginator->sort('city'); ?></th>
			<th><?php echo $this->Paginator->sort('phone'); ?></th>
			<th><?php echo $this->Paginator->sort('phone_two'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('clients_legal_id'); ?></th>
			<th><?php echo $this->Paginator->sort('clients_natural_id'); ?></th>
			<th><?php echo $this->Paginator->sort('default'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('updated'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($adresses as $adress): ?>
	<tr>
		<td><?php echo h($adress['Adress']['id']); ?>&nbsp;</td>
		<td><?php echo h($adress['Adress']['name']); ?>&nbsp;</td>
		<td><?php echo h($adress['Adress']['address']); ?>&nbsp;</td>
		<td><?php echo h($adress['Adress']['address_detail']); ?>&nbsp;</td>
		<td><?php echo h($adress['Adress']['city']); ?>&nbsp;</td>
		<td><?php echo h($adress['Adress']['phone']); ?>&nbsp;</td>
		<td><?php echo h($adress['Adress']['phone_two']); ?>&nbsp;</td>
		<td><?php echo h($adress['Adress']['state']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($adress['ClientsLegal']['name'], array('controller' => 'clients_legals', 'action' => 'view', $adress['ClientsLegal']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($adress['ClientsNatural']['name'], array('controller' => 'clients_naturals', 'action' => 'view', $adress['ClientsNatural']['id'])); ?>
		</td>
		<td><?php echo h($adress['Adress']['default']); ?>&nbsp;</td>
		<td><?php echo h($adress['Adress']['created']); ?>&nbsp;</td>
		<td><?php echo h($adress['Adress']['updated']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $adress['Adress']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $adress['Adress']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $adress['Adress']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $adress['Adress']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Adress'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clients Legals'), array('controller' => 'clients_legals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Legal'), array('controller' => 'clients_legals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients Naturals'), array('controller' => 'clients_naturals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Natural'), array('controller' => 'clients_naturals', 'action' => 'add')); ?> </li>
	</ul>
</div>
