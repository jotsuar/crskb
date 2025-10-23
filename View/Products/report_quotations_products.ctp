<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12">
				<h2 class="titleviewer">Informe de venta y/o cotizaciones de producto</h2>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group mt-3">
							<label for="marcasData">Selección Marca</label>
							<select name="marcasData" id="marcasData">
								<option value=""> <?php echo isset($brandSelect) ? "No seleccionar marca" :  "Seleccionar marca" ?> </option>
								<?php foreach ($brands as $key => $value): ?>
									<option value="<?php echo $value["Brand"]["id"] ?>" <?php echo isset($brandSelect) && $value["Brand"]["id"] == $brandSelect ? "selected" : "" ?>><?php echo $value["Brand"]["name"] ?></option>
								<?php endforeach ?>
							</select>
							
						</div>
					</div>
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group mt-2 categoriasData_1_ catDivs">
									<label for="categoryData">Grupo 1</label>
									<select name="category_1" id="category_1">
										<option value="">Seleccionar</option>
										<?php foreach ($categoriesInfoFinal[0] as $key => $value): ?>
											<option value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mt-3 categoriasData_2_ catDivs">
									<label for="categoryData">Grupo 2</label>
									<select name="category_2" id="category_2">
										<option value="">Seleccionar</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mt-3 categoriasData_3_ catDivs">
									<label for="categoryData">Grupo 3</label>
									<select name="category_3" id="category_3">
										<option value="">Seleccionar</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-2 mt-3 categoriasData_4_ catDivs">
							<label for="categoryData">Grupo 4</label>
							<select name="category_4" id="category_4">
								<option value="">Seleccionar</option>
							</select>
						</div>
							</div>
						</div>
					</div>
				</div>

						
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h2 class="titlesectionquote text-primary text-center">
				<div class="row">
					<div class="col-md-4">
						Buscador de productos
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<span>Seleccionar rango de fechas:</span>
						</div>
					</div>
					<div class="col-md-3">
						
						<div class="form-group">
							<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
						</div>
	
					<div style="display: none">
						<span>Desde</span>
						<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
						<span>Hasta</span>
						<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
					</div>
					</div>
				</div>
				<strong class="text-success"></strong> 


									
			</h2>
			<div class=" blockwhiteabajo spacebtn20" id="paso1">
				<div class="row">
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
							<div class="col-md-12 text-center" id="botones" style="display: none">
								<input type="hidden" id="controlProductos" value="one">
								<!-- <select name="controlProductos" id="controlProductos" class="form-control mt-3 mb-2"> <br>
									<option value="all">Usar todos los productos para la búsqueda</option>
									<option value="one">Usar por lo menos uno de los productos para la búsqueda</option>
								</select> -->
								<a href="#" class="btn btn-secondary btnSearchData" data-type="cotizado">
									<b>VER COTIZADOS</b>
								</a>
								<a href="#" class="btn btn-success btnSearchData" data-type="venta">
									<b>VER VENDIDOS</b>	
								</a>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<div class="col-md-12">
			<h2 class="titlesectionquote text-primary text-center"><strong class="text-success">Visualiza y descarga el informe </h2>
			<div class=" blockwhiteabajo spacebtn20">
				<div class=" mb-5" id="resultado">
					
				</div>
			</div>
		</div>

	</div>
	
		

</div>


<?php echo $this->Html->css(array('lib/jquery.typeahead.css'), array('block' => 'AppCss'));?>
<?php echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));?>
<?php 
	
		$whitelist = array(
            '127.0.0.1',
            '::1'
        ); 

 ?>

 <?php echo $this->element("picker"); ?>
 <?php echo $this->element("flujoModal"); ?>
<script>
	var actual_uri  = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
</script>

<script>
	const categorySelect = <?php echo isset($categorySelect) ? "parseInt(".$categorySelect.");" : "null" ?>;
	var categoriesInfoFinal = <?php echo json_encode($categoriesInfoFinal); ?>;
	var category1Select  = null;
	var category2Select  = null;
	var category3Select  = null;
	var category4Select  = null;
	<?php if(!empty($lists)): ?>
		var lists = <?php echo json_encode($lists); ?>;
	<?php endif; ?>
	<?php if(!empty($listEmails)): ?>
		var listEmails 	= <?php echo json_encode($listEmails); ?>;
	<?php endif; ?>
</script>

<?php
	echo $this->Html->script("lib/jquery.typeahead.js",								array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/product/report_quotations.js?".rand(),						array('block' => 'AppScript'));
	echo $this->Html->script("controller/categories/categories_down.js?".rand(),						array('block' => 'AppScript'));
?>

<style>
	div#accordion.import .table-bordered thead td, .table-bordered thead th {
    background: #004990 !important;
	}
</style>	