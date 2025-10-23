<title>
	Productos Disponibles Página <?php echo $page ?>
</title>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h1 class="m-0 text-white bannerbig" > Productos Disponibles en inventario CRM </h1>
	</div>

	<div class="templates index blockwhite p-0">
		<div class="row">
			<div class="col-md-12 mb-2 ">
				<div class="card">
				  	<div class="card-body">
					<?php foreach ($products as $key => $product): ?>
						
					

						  
						  <ul class="list-group list-group-flush border mb-2">
						  	<?php 

						  		$costo 	  = $product["Product"]["purchase_price_wo"];
								$costoRep = round($product["Category"]["factor"] * ($product["Product"]["purchase_price_usd"] * $trmActual),2);

								if (isset($costos[$product["Product"]["part_number"]]) && ($costos[$product["Product"]["part_number"]] * 1) >=  $costoRep ) {
									$precio = $costos[$product["Product"]["part_number"]]*1;
								}else{
									$precio = $costoRep;
								}

								$precioVenta = round($precio / 0.65) * 1 ;

						  	 ?>
						    <li class="list-group-item"><b>Producto: </b><?php echo $product["Product"]["name"] ?> </li>
						    <li class="list-group-item"><b>Descripción:</b> <?php echo $product["Product"]["description"] ?> </li>
						    <li class="list-group-item"><b>Referencia O SKU:</b> <?php echo $product["Product"]["part_number"] ?> </li>
						    <li class="list-group-item"><b>Precio:</b> $ <?php echo number_format($product["Product"]["precio"],0,",",".") ?> COP </li>
						    <li class="list-group-item"><b>Marca:</b> <?php echo $product["Product"]["brand"] ?></li>
						    <li class="list-group-item"><b>Categoría:</b> <?php echo $product["Category"]["name"] ?></li>
						  </ul>
						  
					<?php endforeach ?>
				    <b>NOTA: </b> Los precios no incluyen IVA.
				  	</div>
				</div>
			</div>
		</div>
	</div>
</div>



<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>