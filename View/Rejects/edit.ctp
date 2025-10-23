<div class="rejects form">
<?php echo $this->Form->create('Reject'); ?>
	<fieldset>
		<legend><?php echo __('Edit Reject'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('product_id');
		echo $this->Form->input('import_id');
		echo $this->Form->input('quantity');
		echo $this->Form->input('reason');
		echo $this->Form->input('user_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Reject.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Reject.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Rejects'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Imports'), array('controller' => 'imports', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Import'), array('controller' => 'imports', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
