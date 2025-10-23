<div class="quotationsProducts index">
	<h2><?php echo __('Quotations Products'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('quotation_id'); ?></th>
			<th><?php echo $this->Paginator->sort('id_llc'); ?></th>
			<th><?php echo $this->Paginator->sort('state_llc'); ?></th>
			<th><?php echo $this->Paginator->sort('product_id'); ?></th>
			<th><?php echo $this->Paginator->sort('note'); ?></th>
			<th><?php echo $this->Paginator->sort('price'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity'); ?></th>
			<th><?php echo $this->Paginator->sort('currency'); ?></th>
			<th><?php echo $this->Paginator->sort('change'); ?></th>
			<th><?php echo $this->Paginator->sort('trm_change'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity_back'); ?></th>
			<th><?php echo $this->Paginator->sort('margen'); ?></th>
			<th><?php echo $this->Paginator->sort('delivery'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('biiled'); ?></th>
			<th><?php echo $this->Paginator->sort('warehouse'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($quotationsProducts as $quotationsProduct): ?>
	<tr>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($quotationsProduct['Quotation']['name'], array('controller' => 'quotations', 'action' => 'view', $quotationsProduct['Quotation']['id'])); ?>
		</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['id_llc']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['state_llc']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($quotationsProduct['Product']['name'], array('controller' => 'products', 'action' => 'view', $quotationsProduct['Product']['id'])); ?>
		</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['note']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['price']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['quantity']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['currency']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['change']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['trm_change']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['quantity_back']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['margen']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['delivery']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['state']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['biiled']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['warehouse']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['created']); ?>&nbsp;</td>
		<td><?php echo h($quotationsProduct['QuotationsProduct']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $quotationsProduct['QuotationsProduct']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $quotationsProduct['QuotationsProduct']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $quotationsProduct['QuotationsProduct']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $quotationsProduct['QuotationsProduct']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Quotations Product'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Quotations'), array('controller' => 'quotations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Quotation'), array('controller' => 'quotations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
