<div class="assists view">
<h2><?php echo __('Assist'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($assist['Assist']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($assist['User']['name'], array('controller' => 'users', 'action' => 'view', $assist['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Image File'); ?></dt>
		<dd>
			<?php echo h($assist['Assist']['image_file']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ubication'); ?></dt>
		<dd>
			<?php echo h($assist['Assist']['ubication']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($assist['Assist']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($assist['Assist']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($assist['Assist']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Assist'), array('action' => 'edit', $assist['Assist']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Assist'), array('action' => 'delete', $assist['Assist']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $assist['Assist']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Assists'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Assist'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
