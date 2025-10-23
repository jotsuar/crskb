<div class="sbTiempos form">
<?php echo $this->Form->create('SbTiempo'); ?>
	<fieldset>
		<legend><?php echo __('Add Sb Tiempo'); ?></legend>
	<?php
		echo $this->Form->input('sb_user_id');
		echo $this->Form->input('type');
		echo $this->Form->input('fecha');
		echo $this->Form->input('date_ini');
		echo $this->Form->input('date_end');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Sb Tiempos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Sb Users'), array('controller' => 'sb_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sb User'), array('controller' => 'sb_users', 'action' => 'add')); ?> </li>
	</ul>
</div>
