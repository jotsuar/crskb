<?php 
	$readonly = "";
	if(AuthComponent::user("role") != 'Gerente General'){
		// $readonly = "readonly";
	}
 ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Editar garantía para marca</h2>
	</div>	
	<div class="blockwhite spacebtn20">
				
		<div class="brands form">
		<?php echo $this->Form->create('Garantia',array('data-parsley-validate'=>true,)); ?>

			<div class="row">
				<div class="col-md-4">
					
					<div class="form-group mt-3">
						<label for="marcasData">Selección Marca</label>
						<select name="brand_id" id="marcasData">
							<option value=""> <?php echo isset($brandSelect) ? "No seleccionar marca" :  "Seleccionar marca" ?> </option>
							<?php foreach ($brands as $key => $value): ?>
								<option value="<?php echo $key ?>" <?php echo isset($garantia["Garantia"]["brand_id"]) && $key == $garantia["Garantia"]["brand_id"] ? "selected" : "" ?>><?php echo $value ?></option>
							<?php endforeach ?>
						</select>
						
					</div>
					<hr>

					<div class="form-group mt-4">
						<label for="">Selección por categorías y grupos</label>
						<hr>
					</div>
					<div class="form-group mt-2 categoriasData_1_ catDivs">
						<label for="categoryData">Grupo 1</label>
						<select name="category_1" id="category_1">
							<option value="">Seleccionar</option>
							<?php foreach ($categoriesInfoFinal[0] as $key => $value): ?>
								<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						</select>
					</div>

					<div class="form-group mt-3 categoriasData_2_ catDivs">
						<label for="categoryData">Grupo 2</label>
						<select name="category_2" id="category_2">
							<option value="">Seleccionar</option>
						</select>
					</div>

					<div class="form-group mt-3 categoriasData_3_ catDivs">
						<label for="categoryData">Grupo 3</label>
						<select name="category_3" id="category_3">
							<option value="">Seleccionar</option>
						</select>
					</div>

					<div class="form-group mb-2 mt-3 categoriasData_4_ catDivs">
						<label for="categoryData">Grupo 4</label>
						<select name="category_4" id="category_4">
							<option value="">Seleccionar</option>
						</select>
					</div>
					<div class="form-group mt-3">
						<a href="javascript:void(0)" class="btn btn-info btn-block" id="buscaProductos">Buscar productos</a>
					</div>
				</div>
				<div class="col-md-8">
					<?php
						echo $this->Form->input('id');
						echo $this->Form->input('name',array("label" => "Nombre personalizado Garantia","required"));
						echo $this->Form->input('description',array("label" => "Descripción o texto de la garantía (corta) ","required","style" => "height: 50px !important" ));
						echo $this->Form->input('description_long',array("label" => "Descripción o texto de la garantía (larga)","required"));
					?>
					<div>
						<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th class="text-center">Imagen</th>
										<th>Referencia</th>
										<th>Producto</th>
										<th>Marca</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody id="TBodyProducto">
									
								</tbody>
							</table>							
						</div>
					</div>
					<div class="form-group mt-4">
						<input type="submit" value="Guardar" class="btn btn-primary">
					</div>
				</div>


			</div>

			
		<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));	
	echo $this->Html->script("controller/brands/save.js?".rand(),						array('block' => 'AppScript'));
?>
<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<?php echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));?>
<?php 
	
		$whitelist = array(
            '127.0.0.1',
            '::1'
        ); 

 ?>

<script>
	var actual_uri  = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
</script>

<script>
	var categorySelect = <?php echo isset($categorySelect) ? "parseInt(".$categorySelect.");" : "null" ?>;
	var categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal); ?>;
	var category1Select  = <?php echo is_null($garantia["Garantia"]["category_1"]) ? "null" : $garantia["Garantia"]["category_1"]  ?>;
	var category2Select  = <?php echo is_null($garantia["Garantia"]["category_2"]) ? "null" : $garantia["Garantia"]["category_2"]  ?>;
	var category3Select  = <?php echo is_null($garantia["Garantia"]["category_3"]) ? "null" : $garantia["Garantia"]["category_3"]  ?>;
	var category4Select  = <?php echo is_null($garantia["Garantia"]["category_4"]) ? "null" : $garantia["Garantia"]["category_4"]  ?>;
	var productsIdsSelect = <?php echo json_encode($produtsIds) ?>;
</script>

<?php
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/product/garantia.js?".rand(),						array('block' => 'AppScript'));
	echo $this->Html->script("controller/categories/categories_down.js?".rand(),						array('block' => 'AppScript'));
?>
