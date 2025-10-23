<?php echo $this->Form->create('Producto', array('role' => 'form','data-parsley-validate=""','class'=>'form-material m-t-40')); ?>
<div class='form-group'>
	<?php echo $this->Form->label('Producto.product_id',__('Producto para asociar'), array('class'=>'control-label'));?>
	<?php echo $this->Form->input('Producto.product_id', array('class' => 'form-control border-input','label'=>false,'div'=>false, 'type' => 'select','empty' => __("Seleccione una opción"),'options' => $productos,"required" => true )); ?>
</div>

<div class='form-group'>
	<?php echo $this->Form->input('Producto.delivery', array('class' => 'form-control border-input','label'=>false,'div'=>false, "options" => $entrega, "label" => "Tiempo de entrega")); ?>
	<?php echo $this->Form->input('Producto.key', array('class' => 'form-control border-input','label'=>false,'type'=>"hidden", "value" => $number)); ?>
</div>

<div class='form-group'>
	<?php echo $this->Form->input('Producto.quantity', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type"=>"number","min"=>1,"default"=>1, "label" => "Cantidad")); ?>
</div>

<div class='form-group'>
	<?php echo $this->Form->input('Producto.price', array('class' => 'form-control border-input','label'=>false,'div'=>false,"type"=>"number","min"=>1,"default"=>1, "step" => "0.01", "label" => "Precio del producto")); ?>
</div>

<div class='form-group'>
	<?php echo $this->Form->input('Producto.currency', array('class' => 'form-control border-input','label'=>false,'div'=>false, "options" => ["cop" => "COP", "usd" => "USD"], "label" => "Moneda")); ?>
</div>
<div class="col-md-12 m-b-4">
			<button type='submit' class='btn btn-success pull-right mt-4'><?php echo __("Guardar");?></button>
		</div>
<?php echo $this->Form->end(); ?>