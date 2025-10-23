<div class="quotationsProducts form">
<?php echo $this->Form->create('QuotationsProduct'); ?>
	<fieldset>
		<legend><?php echo __('Add Quotations Product'); ?></legend>
	<?php
		echo $this->Form->input('quotation_id');
		echo $this->Form->input('id_llc');
		echo $this->Form->input('state_llc');
		echo $this->Form->input('product_id');
		echo $this->Form->input('note');
		echo $this->Form->input('price');
		echo $this->Form->input('quantity');
		echo $this->Form->input('currency');
		echo $this->Form->input('change');
		echo $this->Form->input('trm_change');
		echo $this->Form->input('quantity_back');
		echo $this->Form->input('margen');
		echo $this->Form->input('delivery');
		echo $this->Form->input('state');
		echo $this->Form->input('biiled');
		echo $this->Form->input('warehouse');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Quotations Products'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Quotations'), array('controller' => 'quotations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Quotation'), array('controller' => 'quotations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
