<div class="customerRequests form">
<?php echo $this->Form->create('CustomerRequest'); ?>
	<fieldset>
		<legend><?php echo __('Edit Customer Request'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('type');
		echo $this->Form->input('identification');
		echo $this->Form->input('email');
		echo $this->Form->input('address');
		echo $this->Form->input('phone');
		echo $this->Form->input('city');
		echo $this->Form->input('rut');
		echo $this->Form->input('state');
		echo $this->Form->input('user_id');
		echo $this->Form->input('user_create');
		echo $this->Form->input('date_created');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('CustomerRequest.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('CustomerRequest.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Customer Requests'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
