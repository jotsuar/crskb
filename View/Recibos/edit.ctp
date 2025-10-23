<div class="recibos form">
<?php echo $this->Form->create('Recibo'); ?>
	<fieldset>
		<legend><?php echo __('Edit Recibo'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('numero');
		echo $this->Form->input('fecha_recibo');
		echo $this->Form->input('credito');
		echo $this->Form->input('debito');
		echo $this->Form->input('details');
		echo $this->Form->input('receipt_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Recibo.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Recibo.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Recibos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Receipts'), array('controller' => 'receipts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Receipt'), array('controller' => 'receipts', 'action' => 'add')); ?> </li>
	</ul>
</div>
