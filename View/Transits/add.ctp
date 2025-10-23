<div class="transits form">
<?php echo $this->Form->create('Transit'); ?>
	<fieldset>
		<legend><?php echo __('Add Transit'); ?></legend>
	<?php
		echo $this->Form->input('product_id');
		echo $this->Form->input('import_id');
		echo $this->Form->input('prospective_user_id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('note');
		echo $this->Form->input('state');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/transits/index.js?".time(),				array('block' => 'AppScript'));
?>
