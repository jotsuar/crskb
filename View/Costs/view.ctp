<div class="costs view">
<h2><?php echo __('Cost'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($cost['Cost']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cost['Product']['name'], array('controller' => 'products', 'action' => 'view', $cost['Product']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Purchase Price Usd'); ?></dt>
		<dd>
			<?php echo h($cost['Cost']['purchase_price_usd']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cost['User']['name'], array('controller' => 'users', 'action' => 'view', $cost['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($cost['Cost']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($cost['Cost']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cost'), array('action' => 'edit', $cost['Cost']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cost'), array('action' => 'delete', $cost['Cost']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $cost['Cost']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Costs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cost'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
