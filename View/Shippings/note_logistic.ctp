<?php echo $this->Form->create('Shipping'); ?>
<?php 
	
	echo $this->Form->input('id');
	echo $this->Form->input('note_logistic',["label"=>"Nota Lógistica"]);


 ?>
<?php echo $this->Form->end(__('Guardar nota')); ?>