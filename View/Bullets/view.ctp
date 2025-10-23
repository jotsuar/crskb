<div class="bullets view">
<h2><?php echo __('Bullet'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($bullet['Bullet']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product'); ?></dt>
		<dd>
			<?php echo $this->Html->link($bullet['Product']['name'], array('controller' => 'products', 'action' => 'view', $bullet['Product']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($bullet['Bullet']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($bullet['Bullet']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($bullet['Bullet']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Bullet'), array('action' => 'edit', $bullet['Bullet']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Bullet'), array('action' => 'delete', $bullet['Bullet']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $bullet['Bullet']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Bullets'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bullet'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
