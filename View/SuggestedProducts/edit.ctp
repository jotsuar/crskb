<div class="suggestedProducts form">
<?php echo $this->Form->create('SuggestedProduct'); ?>
	<fieldset>
		<legend><?php echo __('Edit Suggested Product'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('product_ppal');
		echo $this->Form->input('product_id');
		echo $this->Form->input('quantity');
		echo $this->Form->input('price_usd');
		echo $this->Form->input('price_cop');
		echo $this->Form->input('state');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('SuggestedProduct.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('SuggestedProduct.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Suggested Products'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
