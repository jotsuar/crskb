<div class="container">
	<div class="col-md-12">
		<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>		
		<div class="blockwhite spacebtn20">
			<h2 class="titleviewer">Movimiento de inventario para el producto: <br> <?php echo $product["Product"]["name"] ?> - <?php echo $product["Product"]["part_number"] ?></h2>
		</div>	
		<div class="products form blockwhite">
			<?php echo $this->Form->create('Inventory'); ?>
			<?php
				echo $this->Form->input('product_id', array("type" => "hidden"));
				echo $this->Form->input('quantity',array("label" => "Cantidad","min" => 0, "value" => empty($this->request->data["Inventory"]["quantity"]) ? '0' : $this->request->data["Inventory"]["quantity"] ));
				echo $this->Form->input('reason',array("type" => "text", "required" => true, "label" => "Razón del movimiento"));
				echo $this->Form->input('type', array("label" => "Tipo de movimiento <small>(recuerda que las salidas deberán ser aprobadas por la gerencia)</small>","options" => Configure::read("TYPES_MOVEMENT_TEXT")));
				echo $this->Form->input('type_movement',array("type" => "hidden", "required" => false));
				echo $this->Form->input('user_id', array("type" => "hidden") );
				echo $this->Form->input('prospective_user_id',array("type" => "hidden", "value" => null));
				echo $this->Form->input('warehouse',array("label" => "Bodega","options" => Configure::read("BODEGAS")));
				echo $this->Form->input('import_id',array("type" => "hidden", "value" => null));
			?>
			<?php echo $this->Form->end(__('Registrar')); ?>
		</div>
	</div>
</div>


<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/inventories/index.js?".rand(),						array('block' => 'AppScript'));
 ?>