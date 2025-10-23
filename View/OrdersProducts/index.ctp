<div class="ordersProducts index">
	<h2><?php echo __('Orders Products'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('order_id'); ?></th>
			<th><?php echo $this->Paginator->sort('product_id'); ?></th>
			<th><?php echo $this->Paginator->sort('price'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity'); ?></th>
			<th><?php echo $this->Paginator->sort('iva'); ?></th>
			<th><?php echo $this->Paginator->sort('currency'); ?></th>
			<th><?php echo $this->Paginator->sort('cost'); ?></th>
			<th><?php echo $this->Paginator->sort('margin'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($ordersProducts as $ordersProduct): ?>
	<tr>
		<td><?php echo h($ordersProduct['OrdersProduct']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($ordersProduct['Order']['id'], array('controller' => 'orders', 'action' => 'view', $ordersProduct['Order']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($ordersProduct['Product']['name'], array('controller' => 'products', 'action' => 'view', $ordersProduct['Product']['id'])); ?>
		</td>
		<td><?php echo h($ordersProduct['OrdersProduct']['price']); ?>&nbsp;</td>
		<td><?php echo h($ordersProduct['OrdersProduct']['quantity']); ?>&nbsp;</td>
		<td><?php echo h($ordersProduct['OrdersProduct']['iva']); ?>&nbsp;</td>
		<td><?php echo h($ordersProduct['OrdersProduct']['currency']); ?>&nbsp;</td>
		<td><?php echo h($ordersProduct['OrdersProduct']['cost']); ?>&nbsp;</td>
		<td><?php echo h($ordersProduct['OrdersProduct']['margin']); ?>&nbsp;</td>
		<td><?php echo h($ordersProduct['OrdersProduct']['state']); ?>&nbsp;</td>
		<td><?php echo h($ordersProduct['OrdersProduct']['created']); ?>&nbsp;</td>
		<td><?php echo h($ordersProduct['OrdersProduct']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $ordersProduct['OrdersProduct']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $ordersProduct['OrdersProduct']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $ordersProduct['OrdersProduct']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $ordersProduct['OrdersProduct']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Orders Product'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
