<div class="quotationsProducts form">


	<?php 

			$estados = [

				0 => "Por administrar",
				1 => "Pedido administrado (Sin importar)",
				2 => "Pedido administrado	(importado)",
				3 => "Enviado",
				4 => "Entregado",
				5 => "Importacion en proceso (pedido administrado)",
				6 => "Importacion finalizada (pedido administrado)",

			];


	 ?>
<?php echo $this->Form->create('QuotationsProduct'); ?>
	<fieldset>
		<legend><?php echo __('Editar producto de una cotización'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('quotation_id');
		echo $this->Form->input('id_llc');
		echo $this->Form->input('state_llc');
		echo $this->Form->input('product_id');
		echo $this->Form->input('note');
		echo $this->Form->input('price');
		echo $this->Form->input('quantity');
		echo $this->Form->input('currency');
		echo $this->Form->input('change');
		echo $this->Form->input('trm_change');
		echo $this->Form->input('quantity_back');
		echo $this->Form->input('margen');
		echo $this->Form->input('delivery',["options" => Configure::read("variables.entregaProduct")]);
		echo $this->Form->input('state',["options" => $estados]);
		echo $this->Form->input('biiled');
		echo $this->Form->input('warehouse');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>



<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
?>

<?php 
	$this->start('AppScript'); ?>

	<script>

		$("#QuotationsProductProductId").select2({
	        placeholder: "Seleccionar producto",
	        allowClear: true
	    });

	</script>

<?php
	$this->end();
 ?>
