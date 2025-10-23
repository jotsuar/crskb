<div class="percentages view">
<h2><?php echo __('Percentage'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($percentage['Percentage']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($percentage['User']['name'], array('controller' => 'users', 'action' => 'view', $percentage['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Min'); ?></dt>
		<dd>
			<?php echo h($percentage['Percentage']['min']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Max'); ?></dt>
		<dd>
			<?php echo h($percentage['Percentage']['max']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Value'); ?></dt>
		<dd>
			<?php echo h($percentage['Percentage']['value']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($percentage['Percentage']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($percentage['Percentage']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Percentage'), array('action' => 'edit', $percentage['Percentage']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Percentage'), array('action' => 'delete', $percentage['Percentage']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $percentage['Percentage']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Percentages'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Percentage'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
