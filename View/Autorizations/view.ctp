<div class="autorizations view">
<h2><?php echo __('Autorization'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($autorization['Autorization']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Prospective User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($autorization['ProspectiveUser']['id'], array('controller' => 'prospective_users', 'action' => 'view', $autorization['ProspectiveUser']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor'); ?></dt>
		<dd>
			<?php echo h($autorization['Autorization']['valor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($autorization['Autorization']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($autorization['Autorization']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Autorization'), array('action' => 'edit', $autorization['Autorization']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Autorization'), array('action' => 'delete', $autorization['Autorization']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $autorization['Autorization']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Autorizations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Autorization'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prospective Users'), array('controller' => 'prospective_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prospective User'), array('controller' => 'prospective_users', 'action' => 'add')); ?> </li>
	</ul>
</div>
