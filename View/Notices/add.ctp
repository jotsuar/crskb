<div class="notices form">
<?php echo $this->Form->create('Notice'); ?>
	<?php
		echo $this->Form->input('user_id',["value" =>AuthComponent::user("id"), "type" => "hidden" ]);
		echo $this->Form->input('name',["label" => "Nombre" ]);
		echo $this->Form->input('description',["label" => "Nota"]);
	?>
<?php echo $this->Form->end(__('Guardar')); ?>
</div>
