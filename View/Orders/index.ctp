<div class="orders index">
	<h2><?php echo __('Orders'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('prospective_user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('clients_legal_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nacional'); ?></th>
			<th><?php echo $this->Paginator->sort('clients_natural_id'); ?></th>
			<th><?php echo $this->Paginator->sort('contacs_user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('quotation_id'); ?></th>
			<th><?php echo $this->Paginator->sort('payment_type'); ?></th>
			<th><?php echo $this->Paginator->sort('payment_text'); ?></th>
			<th><?php echo $this->Paginator->sort('prefijo'); ?></th>
			<th><?php echo $this->Paginator->sort('code'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('total'); ?></th>
			<th><?php echo $this->Paginator->sort('iva'); ?></th>
			<th><?php echo $this->Paginator->sort('deadline'); ?></th>
			<th><?php echo $this->Paginator->sort('note'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orders as $order): ?>
	<tr>
		<td><?php echo h($order['Order']['id']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['prospective_user_id']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['clients_legal_id']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['nacional']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($order['ClientsNatural']['name'], array('controller' => 'clients_naturals', 'action' => 'view', $order['ClientsNatural']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($order['ContacsUser']['name'], array('controller' => 'contacs_users', 'action' => 'view', $order['ContacsUser']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($order['Quotation']['name'], array('controller' => 'quotations', 'action' => 'view', $order['Quotation']['id'])); ?>
		</td>
		<td><?php echo h($order['Order']['payment_type']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['payment_text']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['prefijo']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['code']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['state']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['total']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['iva']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['deadline']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['note']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($order['User']['name'], array('controller' => 'users', 'action' => 'view', $order['User']['id'])); ?>
		</td>
		<td><?php echo h($order['Order']['created']); ?>&nbsp;</td>
		<td><?php echo h($order['Order']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $order['Order']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $order['Order']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $order['Order']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $order['Order']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Order'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clients Legals'), array('controller' => 'clients_legals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Legal'), array('controller' => 'clients_legals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients Naturals'), array('controller' => 'clients_naturals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Natural'), array('controller' => 'clients_naturals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contacs Users'), array('controller' => 'contacs_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contacs User'), array('controller' => 'contacs_users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Quotations'), array('controller' => 'quotations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Quotation'), array('controller' => 'quotations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Shippings'), array('controller' => 'shippings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shipping'), array('controller' => 'shippings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
