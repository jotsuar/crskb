<div class="shippingsProducts index">
	<h2><?php echo __('Shippings Products'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('shipping_id'); ?></th>
			<th><?php echo $this->Paginator->sort('product_id'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity'); ?></th>
			<th><?php echo $this->Paginator->sort('note'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($shippingsProducts as $shippingsProduct): ?>
	<tr>
		<td><?php echo h($shippingsProduct['ShippingsProduct']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($shippingsProduct['Shipping']['id'], array('controller' => 'shippings', 'action' => 'view', $shippingsProduct['Shipping']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($shippingsProduct['Product']['name'], array('controller' => 'products', 'action' => 'view', $shippingsProduct['Product']['id'])); ?>
		</td>
		<td><?php echo h($shippingsProduct['ShippingsProduct']['quantity']); ?>&nbsp;</td>
		<td><?php echo h($shippingsProduct['ShippingsProduct']['note']); ?>&nbsp;</td>
		<td><?php echo h($shippingsProduct['ShippingsProduct']['state']); ?>&nbsp;</td>
		<td><?php echo h($shippingsProduct['ShippingsProduct']['created']); ?>&nbsp;</td>
		<td><?php echo h($shippingsProduct['ShippingsProduct']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $shippingsProduct['ShippingsProduct']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $shippingsProduct['ShippingsProduct']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $shippingsProduct['ShippingsProduct']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $shippingsProduct['ShippingsProduct']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Shippings Product'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Shippings'), array('controller' => 'shippings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shipping'), array('controller' => 'shippings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
