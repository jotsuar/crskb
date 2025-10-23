<div class="binnacles form">
<?php echo $this->Form->create('Binnacle'); ?>
	<fieldset>
		<legend><?php echo __('Edit Binnacle'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('note');
		echo $this->Form->input('technical_service_id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('date_ini');
		echo $this->Form->input('date_end');
		echo $this->Form->input('state');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Binnacle.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Binnacle.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Binnacles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Technical Services'), array('controller' => 'technical_services', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Technical Service'), array('controller' => 'technical_services', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
