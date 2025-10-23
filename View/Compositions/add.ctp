<div class="compositions form">
<?php echo $this->Form->create('Composition'); ?>
	<fieldset>
		<legend><?php echo __('Add Composition'); ?></legend>
	<?php
		echo $this->Form->input('principal');
		echo $this->Form->input('product_id');
		echo $this->Form->input('state');
		echo $this->Form->input('modifed');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Compositions'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
