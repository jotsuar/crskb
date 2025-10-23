<?php echo $this->Form->create('Manage',array('data-parsley-validate'=>true)); ?>
	<?php
		echo $this->Form->input('title',array('label' => 'Título:'));
		echo $this->Form->input('message',array('label' => 'Mensaje:', 'type' => 'textarea','rows'=>'3'));
		echo $this->Form->input('type',array('label' => 'Tipo mensaje:', 'options' => ["0" => "Normal", "1" => "Importante de lectura obligatoria" ], "default" => 1 ));
	?>
<?php echo $this->Form->end('Guardar'); ?>