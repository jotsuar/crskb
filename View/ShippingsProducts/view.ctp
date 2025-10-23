<div class="shippingsProducts view">
<h2><?php echo __('Shippings Product'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($shippingsProduct['ShippingsProduct']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Shipping'); ?></dt>
		<dd>
			<?php echo $this->Html->link($shippingsProduct['Shipping']['id'], array('controller' => 'shippings', 'action' => 'view', $shippingsProduct['Shipping']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product'); ?></dt>
		<dd>
			<?php echo $this->Html->link($shippingsProduct['Product']['name'], array('controller' => 'products', 'action' => 'view', $shippingsProduct['Product']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Quantity'); ?></dt>
		<dd>
			<?php echo h($shippingsProduct['ShippingsProduct']['quantity']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Note'); ?></dt>
		<dd>
			<?php echo h($shippingsProduct['ShippingsProduct']['note']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($shippingsProduct['ShippingsProduct']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($shippingsProduct['ShippingsProduct']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($shippingsProduct['ShippingsProduct']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Shippings Product'), array('action' => 'edit', $shippingsProduct['ShippingsProduct']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Shippings Product'), array('action' => 'delete', $shippingsProduct['ShippingsProduct']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $shippingsProduct['ShippingsProduct']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Shippings Products'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shippings Product'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Shippings'), array('controller' => 'shippings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shipping'), array('controller' => 'shippings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
