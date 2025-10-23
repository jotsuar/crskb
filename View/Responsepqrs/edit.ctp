<div class="responsepqrs form">
<?php echo $this->Form->create('Responsepqr'); ?>
	<fieldset>
		<legend><?php echo __('Edit Responsepqr'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('id_pqr');
		echo $this->Form->input('user_id');
		echo $this->Form->input('description');
		echo $this->Form->input('file');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Responsepqr.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Responsepqr.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Responsepqrs'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
