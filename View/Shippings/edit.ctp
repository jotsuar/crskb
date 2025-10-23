<div class="shippings form">
<?php echo $this->Form->create('Shipping'); ?>
	<fieldset>
		<legend><?php echo __('Edit Shipping'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('adress_id');
		echo $this->Form->input('order_id');
		echo $this->Form->input('document');
		echo $this->Form->input('type');
		echo $this->Form->input('guide');
		echo $this->Form->input('conveyor_id');
		echo $this->Form->input('note');
		echo $this->Form->input('state');
		echo $this->Form->input('date_initial');
		echo $this->Form->input('date_preparation');
		echo $this->Form->input('date_send');
		echo $this->Form->input('date_end');
		echo $this->Form->input('Product');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Shipping.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Shipping.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Shippings'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Adresses'), array('controller' => 'adresses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Adress'), array('controller' => 'adresses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Conveyors'), array('controller' => 'conveyors', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conveyor'), array('controller' => 'conveyors', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
