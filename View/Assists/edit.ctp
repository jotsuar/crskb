<div class="assists form">
<?php echo $this->Form->create('Assist'); ?>
	<fieldset>
		<legend><?php echo __('Edit Assist'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('image_file');
		echo $this->Form->input('ubication');
		echo $this->Form->input('state');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Assist.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Assist.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Assists'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
