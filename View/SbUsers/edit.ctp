<div class="sbUsers form">
<?php echo $this->Form->create('SbUser'); ?>
	<fieldset>
		<legend><?php echo __('Edit Sb User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('password');
		echo $this->Form->input('email');
		echo $this->Form->input('profile_image');
		echo $this->Form->input('user_type');
		echo $this->Form->input('creation_time');
		echo $this->Form->input('token');
		echo $this->Form->input('last_activity');
		echo $this->Form->input('typing');
		echo $this->Form->input('online');
		echo $this->Form->input('department');
		echo $this->Form->input('response_crm');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('SbUser.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('SbUser.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Sb Users'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Sb Tiempos'), array('controller' => 'sb_tiempos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sb Tiempo'), array('controller' => 'sb_tiempos', 'action' => 'add')); ?> </li>
	</ul>
</div>
