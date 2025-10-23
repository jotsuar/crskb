<div class="campaigns form">
<?php echo $this->Form->create('Campaign'); ?>
	<fieldset>
		<legend><?php echo __('Edit Campaign'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('subject');
		echo $this->Form->input('content');
		echo $this->Form->input('mails_senders');
		echo $this->Form->input('cell_senders');
		echo $this->Form->input('products');
		echo $this->Form->input('deadline');
		echo $this->Form->input('state');
		echo $this->Form->input('type');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Campaign.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Campaign.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Campaigns'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Email Trackings'), array('controller' => 'email_trackings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Email Tracking'), array('controller' => 'email_trackings', 'action' => 'add')); ?> </li>
	</ul>
</div>
