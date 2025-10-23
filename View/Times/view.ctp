<div class="times view">
<h2><?php echo __('Time'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($time['Time']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($time['User']['name'], array('controller' => 'users', 'action' => 'view', $time['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Minutes'); ?></dt>
		<dd>
			<?php echo h($time['Time']['minutes']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Minutes Sat'); ?></dt>
		<dd>
			<?php echo h($time['Time']['minutes_sat']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Block User'); ?></dt>
		<dd>
			<?php echo h($time['Time']['block_user']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($time['Time']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($time['Time']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Time'), array('action' => 'edit', $time['Time']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Time'), array('action' => 'delete', $time['Time']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $time['Time']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Times'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Time'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
