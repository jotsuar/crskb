<?php echo $this->Form->create('Composition', array('role' => 'form','data-parsley-validate=""','class'=>'form-material m-t-40')); ?>
<div class='form-group'>
	<?php echo $this->Form->label('Composition.product_id',__('Producto para asociar'), array('class'=>'control-label'));?>
	<?php echo $this->Form->input('Composition.product_id', array('class' => 'form-control border-input','label'=>false,'div'=>false, 'type' => 'select','empty' => __("Seleccione una opción"),'options' => $ingredients,"required" => true )); ?>
</div>

<div class='form-group'>
	<?php echo $this->Form->input('Composition.principal', array('class' => 'form-control border-input','label'=>false,'div'=>false, 'type' => 'hidden',"value" => $id )); ?>
</div>

<div class="col-md-12 m-b-4">
			<button type='submit' class='btn btn-success pull-right'><?php echo __("Guardar");?></button>
		</div>
<?php echo $this->Form->end(); ?>