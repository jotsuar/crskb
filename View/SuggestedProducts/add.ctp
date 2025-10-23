<div class="container p-0">
	<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>
		<div class="blockwhite spacebtn20">
			<h2 class="titleviewer">Módulo para asociar productos sugeridos a un equipo al cotizar</h2>
		</div>	
	</div>
</div>
<div class="suggestedProducts form blockwhite">
	<?php echo $this->Form->create('SuggestedProduct'); ?>

		<?php
			echo $this->Form->input('product_ppal', ["label"=> "Producto o equipo principal que tendrá partes asociadas", "options" => $products ,"empty" => "Seleccionar", "value" => is_null($principal) ? "" : $principal ]);
			echo $this->Form->input('product_aditional', ["label"=> "Producto o equipo adicional que tendrá partes asociadas (cuando estén juntos) ", "options" => $products ,"empty" => "Seleccionar", "value" => is_null($segundario) ? "" : $segundario ]);
			// echo $this->Form->input('product_id');
			// echo $this->Form->input('quantity');
			// echo $this->Form->input('price_usd');
			// echo $this->Form->input('price_cop');
		?>
		<hr>
		<div class="row" id="tableProducts">
			<div class="col-md-12">
				<h2 class="text-center text-info">
					Seleccione y configure los productos asociados
				</h2>
			</div>
			<div class="col-md-12">
				<div>
					<div class="typeahead__container">
						<div class="typeahead__field">
							<span class="typeahead__query">
								<input class="js-typeahead" type="search" autofocus autocomplete="off" placeholder="Busca tu producto por nombre o referencia">
							</span>
						</div>
					</div>
				</div>
				<div>
				<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
					<table class="table table-bordered">
						<thead class="backblue">
							<tr >
								<th class="text-center bg-blue">Imagen</th>
								<th class="bg-blue">Referencia</th>
								<th class="bg-blue">Producto</th>
								<th class="bg-blue">Marca</th>
								
								<th class="bg-blue">
									Tiempo de entrega
								</th>
								<th class="bg-blue">
									Cantidad
								</th>
								<th class="bg-blue">
									Margen min.
								</th>
								<th class="bg-blue">
									Costo actual
								</th>
								<th class="bg-blue">
									Precio de venta
								</th>
								<th class="bg-blue">Acciones</th>
							</tr>
						</thead>
						<tbody id="TBodyProducto">
							
						</tbody>
					</table>
					
				</div>
			</div>
			<div class="col-md-12">
				<input type="submit" value="Guardar configuración" class="btn btn-success" id="guardaBtn">
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>


<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/product/sugessts.js?".rand(),								array('block' => 'AppScript'));
?>

<?php 
	$this->start('AppScript'); ?>

	<script>

		$("#SuggestedProductProductPpal").select2({
	        placeholder: "Seleccionar producto",
	        allowClear: true,
	        scrollAfterSelect: true,
	    });

	    $("#SuggestedProductProductAditional").select2({
	        placeholder: "Seleccionar producto",
	        allowClear: true,
	        scrollAfterSelect: true,
	    });

	</script>

<?php
	$this->end();
 ?>
