<div class="importProducts index">
	<h2><?php echo __('Import Products'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('import_id'); ?></th>
			<th><?php echo $this->Paginator->sort('product_id'); ?></th>
			<th><?php echo $this->Paginator->sort('quotations_products_id'); ?></th>
			<th><?php echo $this->Paginator->sort('prospective_user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity_final'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity_back'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity_back_total'); ?></th>
			<th><?php echo $this->Paginator->sort('currency'); ?></th>
			<th><?php echo $this->Paginator->sort('price'); ?></th>
			<th><?php echo $this->Paginator->sort('numero_orden'); ?></th>
			<th><?php echo $this->Paginator->sort('proveedor'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha_orden'); ?></th>
			<th><?php echo $this->Paginator->sort('link'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha_estimada'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha_miami'); ?></th>
			<th><?php echo $this->Paginator->sort('numero_guia'); ?></th>
			<th><?php echo $this->Paginator->sort('transportadora'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha_nacionalizacion'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha_producto_empresa'); ?></th>
			<th><?php echo $this->Paginator->sort('state_import'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($importProducts as $importProduct): ?>
	<tr>
		<td><?php echo h($importProduct['ImportProduct']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($importProduct['Import']['id'], array('controller' => 'imports', 'action' => 'view', $importProduct['Import']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($importProduct['Product']['name'], array('controller' => 'products', 'action' => 'view', $importProduct['Product']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($importProduct['QuotationsProducts']['id'], array('controller' => 'quotations_products', 'action' => 'view', $importProduct['QuotationsProducts']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($importProduct['ProspectiveUser']['id'], array('controller' => 'prospective_users', 'action' => 'view', $importProduct['ProspectiveUser']['id'])); ?>
		</td>
		<td><?php echo h($importProduct['ImportProduct']['quantity']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['quantity_final']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['quantity_back']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['quantity_back_total']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['currency']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['price']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['numero_orden']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['proveedor']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['fecha_orden']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['link']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['fecha_estimada']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['fecha_miami']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['numero_guia']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['transportadora']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['fecha_nacionalizacion']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['fecha_producto_empresa']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['state_import']); ?>&nbsp;</td>
		<td><?php echo h($importProduct['ImportProduct']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $importProduct['ImportProduct']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $importProduct['ImportProduct']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $importProduct['ImportProduct']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $importProduct['ImportProduct']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Import Product'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Imports'), array('controller' => 'imports', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Import'), array('controller' => 'imports', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Quotations Products'), array('controller' => 'quotations_products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Quotations Products'), array('controller' => 'quotations_products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prospective Users'), array('controller' => 'prospective_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prospective User'), array('controller' => 'prospective_users', 'action' => 'add')); ?> </li>
	</ul>
</div>
