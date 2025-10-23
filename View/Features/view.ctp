<div class="features view">
<h2><?php echo __('Feature'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($feature['Feature']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($feature['Feature']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($feature['Feature']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($feature['Feature']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($feature['Feature']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Feature'), array('action' => 'edit', $feature['Feature']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Feature'), array('action' => 'delete', $feature['Feature']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $feature['Feature']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Features'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Feature'), array('action' => 'add')); ?> </li>
	</ul>
</div>
