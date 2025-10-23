<div class="excludes view">
<h2><?php echo __('Exclude'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($exclude['Exclude']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($exclude['User']['name'], array('controller' => 'users', 'action' => 'view', $exclude['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Excluded'); ?></dt>
		<dd>
			<?php echo h($exclude['Exclude']['date_excluded']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($exclude['Exclude']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($exclude['Exclude']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Exclude'), array('action' => 'edit', $exclude['Exclude']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Exclude'), array('action' => 'delete', $exclude['Exclude']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $exclude['Exclude']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Excludes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Exclude'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
