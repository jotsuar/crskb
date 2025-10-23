<div class="liquidations form">
<?php echo $this->Form->create('Liquidation'); ?>
	<fieldset>
		<legend><?php echo __('Edit Liquidation'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('valor_recaudo');
		echo $this->Form->input('valor_tiempo');
		echo $this->Form->input('valor_efectividad');
		echo $this->Form->input('valor_bono');
		echo $this->Form->input('valor_a_pagar');
		echo $this->Form->input('total_recaudado');
		echo $this->Form->input('total_ventas');
		echo $this->Form->input('state');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Liquidation.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Liquidation.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Liquidations'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Receipts'), array('controller' => 'receipts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Receipt'), array('controller' => 'receipts', 'action' => 'add')); ?> </li>
	</ul>
</div>
