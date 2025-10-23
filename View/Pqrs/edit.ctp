<div class="pqrs form">
<?php echo $this->Form->create('Pqr'); ?>
	<fieldset>
		<legend><?php echo __('Edit Pqr'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('email');
		echo $this->Form->input('city');
		echo $this->Form->input('phone');
		echo $this->Form->input('subject');
		echo $this->Form->input('description');
		echo $this->Form->input('code');
		echo $this->Form->input('state');
		echo $this->Form->input('response_type');
		echo $this->Form->input('file1');
		echo $this->Form->input('file2');
		echo $this->Form->input('file3');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Pqr.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Pqr.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Pqrs'), array('action' => 'index')); ?></li>
	</ul>
</div>
