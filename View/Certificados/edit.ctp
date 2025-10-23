<div class="certificados form">
<?php echo $this->Form->create('Certificado'); ?>
	<fieldset>
		<legend><?php echo __('Edit Certificado'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('clients_natural_id');
		echo $this->Form->input('clients_legal_id');
		echo $this->Form->input('name');
		echo $this->Form->input('identification');
		echo $this->Form->input('course');
		echo $this->Form->input('city_date');
		echo $this->Form->input('imagename');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Certificado.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Certificado.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Certificados'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Clients Naturals'), array('controller' => 'clients_naturals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Natural'), array('controller' => 'clients_naturals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clients Legals'), array('controller' => 'clients_legals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clients Legal'), array('controller' => 'clients_legals', 'action' => 'add')); ?> </li>
	</ul>
</div>
