<div class="transits view">
<h2><?php echo __('Transit'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($transit['Transit']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transit['Product']['name'], array('controller' => 'products', 'action' => 'view', $transit['Product']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Import'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transit['Import']['id'], array('controller' => 'imports', 'action' => 'view', $transit['Import']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Prospective User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transit['ProspectiveUser']['id'], array('controller' => 'prospective_users', 'action' => 'view', $transit['ProspectiveUser']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($transit['User']['name'], array('controller' => 'users', 'action' => 'view', $transit['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Note'); ?></dt>
		<dd>
			<?php echo h($transit['Transit']['note']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($transit['Transit']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($transit['Transit']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($transit['Transit']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Transit'), array('action' => 'edit', $transit['Transit']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Transit'), array('action' => 'delete', $transit['Transit']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $transit['Transit']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Transits'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Transit'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Imports'), array('controller' => 'imports', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Import'), array('controller' => 'imports', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prospective Users'), array('controller' => 'prospective_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prospective User'), array('controller' => 'prospective_users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
