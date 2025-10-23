<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Remarketing autómatico de productos en base a ventas de productos</h2>
			</div>
			<div class="col-md-6">
				<a href="<?php echo $this->Html->url(array("controller" => "campaigns", "action" => "index")) ?>" class="btn btn-info pull-right">
					Campañas activas
				</a>
			</div>
		</div>
	</div>
	<?php echo $this->Form->create("Product",array('id' => 'form_campaign')); ?>
	<div class="row">
		<div class="col-md-4">
			<div class="blockwhite">
				<div class="form-group mt-3">
					<label for="marcasData">Selección Marca</label>
					<select name="marcasData" id="marcasData">
						<option value=""> <?php echo isset($brandSelect) ? "No seleccionar marca" :  "Seleccionar marca" ?> </option>
						<?php foreach ($brands as $key => $value): ?>
							<option value="<?php echo $value["Brand"]["id"] ?>" <?php echo isset($brandSelect) && $value["Brand"]["id"] == $brandSelect ? "selected" : "" ?>><?php echo $value["Brand"]["name"] ?></option>
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
			</div>
		</div>
		<div class="col-md-8">
			<div class=" blockwhite spacebtn20" id="paso1">
				<h2 class="text-center text-info">
					<strong class="text-danger">
						Paso 1,	
					</strong> filtrar productos y seleccionar tiempo de envío
				</h2>
				<hr>
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
					<h3 class="text-center text-info mt-3">
						Productos agregados
					</h3>
					<hr><br>
					<div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
						<table class="table table-hovered table-bordered">
							<thead>
								<tr>
									<th>
										Imagen
									</th>
									<th>
										Referencia
									</th>
									<th>
										Producto
									</th>
									<th>
										Acciones
									</th>
								</tr>
							</thead>
							<tbody id="TBodyProducto">
								
							</tbody>
						</table>
						
					</div>
					<div class="ml-0 mr-0 row mt-4">
						<div class="col-md-12" id="botones2" style="display: nonea">
							<div class="form-group mt-2">
								<?php echo $this->Form->input('name_campaign',array('label' => 'Nombre de la campaña','placeholder' => 'Ingresa el nombre de la campaña de marketing', "id" => "nombrecampana", "required","class" => "form-control"));?>	
								<?php echo $this->Form->input('product_ids',array("type" => "hidden", "id" => "productosData","class" => "form-control"));?>
								<?php echo $this->Form->input('type',array("type" => "hidden", "id" => "campanaType","class" => "form-control", "value" => 2));?>	
							</div>
							<div class="form-group mt-2">
								<?php echo $this->Form->input('name',array('label' => 'Asunto del correo electrónico','placeholder' => 'Ingresa el asunto del correo electrónico', "id" => "asuntocorreo","class" => "form-control"));?>			
							</div>
							<div class="form-group mt-2">
								<?php $days = range(15, 360,15); ?>
								<?php echo $this->Form->input('deadline',array('label' => 'Tiempo en días que en que debe ser enviada la campaña despues de la compra','placeholder' => 'Tiempo en días que en que debe ser enviada la campaña despues de la compra', "id" => "deadlineCampana","class" => "form-control", "options" =>array_combine($days,$days), "empty" => "Seleccionar", "required" => true ));?>			
							</div>		
							<div class="form-group mt-3">
								<select name="data[Product][controlProductos]" id="controlProductos" class="form-control mt-3 mb-2"> <br>
									<option value="all">Usar todos los productos para la búsqueda</option>
									<option value="one">Usar por lo menos uno de los productos para la búsqueda</option>
								</select>
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class=" blockwhite spacebtn20">
				<h2 class="text-center text-info">
					<strong class="text-danger">
						Paso 2,	
					</strong> Construir el correo que se envíará 
				</h2>
				<hr>
				<div class="mt-3 mb-5" id="resultado" style="display: nonea;">					
					<div class="form-group">
						<?php echo $this->Form->input('cuerpo',array('label' => 'Cuerpo del correo electrónico','placeholder' => 'Ingresa el cuerpo del correo electrónico', "type" => "textarea", "id" => "cuerpocorreo", "required" => false,"class" => "form-control"));?>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-success pull-right" value="Enviar información" >
					</div>
				</div>
			</div>
		</div>	
	</div>
	</form>
		

</div>
<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<?php echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));?>
<?php 
	
		$whitelist = array(
            '127.0.0.1',
            '::1'
        ); 

 ?>

 <?php echo $this->element("flujoModal"); ?>
<script>
	var actual_uri  = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
</script>
<script>
	var categorySelect = <?php echo isset($categorySelect) ? "parseInt(".$categorySelect.");" : "null" ?>;
	var categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal); ?>;
	var category1Select  = null;
	var category2Select  = null;
	var category3Select  = null;
	var category4Select  = null;
</script>

<?php
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/product/remarketing_auto.js?".rand(),						array('block' => 'AppScript'));
	echo $this->Html->script("controller/categories/categories_down.js?".rand(),						array('block' => 'AppScript'));
?>

