<div class="envoices form">
<?php echo $this->Form->create('Envoice'); ?>
	<fieldset>
		<legend><?php echo __('Add Envoice'); ?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('user_gest');
		echo $this->Form->input('order_id');
		echo $this->Form->input('prefijo');
		echo $this->Form->input('identificator');
		echo $this->Form->input('note');
		echo $this->Form->input('state');
		echo $this->Form->input('date_initial');
		echo $this->Form->input('date_end');
		echo $this->Form->input('Product');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Envoices'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
