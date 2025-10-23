<div class="conveyors view">
<h2><?php echo __('Conveyor'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($conveyor['Conveyor']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($conveyor['Conveyor']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($conveyor['Conveyor']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($conveyor['Conveyor']['url']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($conveyor['Conveyor']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($conveyor['Conveyor']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Conveyor'), array('action' => 'edit', $conveyor['Conveyor']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Conveyor'), array('action' => 'delete', $conveyor['Conveyor']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $conveyor['Conveyor']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Conveyors'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conveyor'), array('action' => 'add')); ?> </li>
	</ul>
</div>
