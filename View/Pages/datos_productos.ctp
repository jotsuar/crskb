
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h1 class="nameview">ANALISIS COMPARATIVO DE VENTA POR LINEA DE PRODUCTOS </h1>
				<span class="subname">Informe de venta por linea de productos de los años 2023 y 2024</span>
			</div>			
		</div>
	</div>
	<div class="row mt-4">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="blockwhite p-2 mb-3">
				<div class='bg-gris border-0 card d-inline-block my-2 p-3 w-100 text-center'>
					<div class="row">
						<div class="col-md-6">
							
							<div class="form-group mt-3 catDivs">
								<label for="categoryData">Empleado</label>
								<select class="form-control select2" data-parentid="empleado" name="empleado" id="empleado" data-idcat="category_1">
									<option value="0">Todos</option>
									<?php foreach ($datosEmpleados as $key => $value): ?>
										<option  value="<?php echo $value["id"] ?>"><?php echo $value["name"] ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						
						<div class="col-md-3">
							
							<div class="form-group mt-3 categoriasData_1_ catDivs">
								<label for="categoryData">Grupo 1</label>
								<select data-level="1" data-nextlevel="2"  class="categories_select" data-parentid="category_1" name="category_1" id="category_1">
									<option value="">Seleccionar</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							

							<div class="form-group mt-3 categoriasData_2_ catDivs">
								<label for="categoryData">Grupo 2</label>
								<select data-level="2" data-nextlevel="3" data-parentid="category_1" class="categories_select" name="category_2" id="category_2">
									<option value="">Seleccionar</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							

							<div class="form-group mt-3 categoriasData_3_ catDivs">
								<label for="categoryData">Grupo 3</label>
								<select data-level="3" data-nextlevel="4" data-parentid="category_2" class="categories_select" name="category_3" id="category_3">
									<option value="">Seleccionar</option>
								</select>
							</div>
						</div>
						<!-- <div class="col-md-3">
							

							<div class="form-group mb-2 mt-3 categoriasData_4_ catDivs">
								<label for="categoryData">Grupo 4</label>
								<select data-level="4" data-nextlevel="4" data-parentid="category_3" class="categories_select" name="category_4" id="category_4">
									<option value="">Seleccionar</option>
								</select>
							</div>
						</div> -->
					</div>	
				</div> 
				<div class="card bg-gris border-0 p-3">
					<div id="datosProductos2024" style="height2: 600px;"></div>

				</div>

				<div class="card bg-gris border-0 p-3">

					<div class="row">
						<div class="col-md-12">
							<h2>Detalle de productos vendidos en 2023 y 2024</h2>
							<table class="table w-100" id="detalleProductos2023">
								<thead>
									<tr>
										<th>Producto</th>
										<th>Número de parte</th>
										<th>Dinero total 2023</th>
										<th>Total unidades 2023</th>
										<th>Dinero total 2024</th>
										<th>Total unidades 2024</th>
										<th>Detalle de cotizaciones</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
					

				</div>

			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="detalleProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalle de cotizaciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="bodyDetalleCotizaciones">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<?php
	echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));
	echo $this->Html->script(array('https://code.highcharts.com/highcharts.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('https://code.highcharts.com/modules/data.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('https://code.highcharts.com/modules/drilldown.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/exporting.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/offline-exporting.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/export-data.js'),	array('block' => 'AppScript'));
	echo $this->Html->script("https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js",									array('block' => 'AppScript'));
	
?>


<?php 
    $this->start('AppScript'); ?>
	<script>


		const datos2023 		= <?php echo json_encode($datos2023); ?>;
		const datos2024 	= <?php echo json_encode($datos2024); ?>;
		const datosEmpleados 	= <?php echo json_encode($datosEmpleados); ?>;
		const datosEmpleados2024 	= <?php echo json_encode($datosEmpleados2024); ?>;
		const datosEmpleados2023 	= <?php echo json_encode($datosEmpleados2023); ?>;

		const url_details   = '<?php echo $this->Html->url(["action"=>"show_details",'controller'=>"quotations"],true) ?>';
		
	</script>
	<?php echo $this->Html->script("controller/pages/datos_productos.js?".time()); ?>
<?php
    $this->end();
 ?>



<style>
	svg{
		display: block !important;
	}
	.highcharts-data-table{
		display: none !important;
	}
	footer{
		display: none;
	}
	.table-responsive #flujosData_wrapper>.row, #debtsData_wrapper>.row{
		margin-right: 0px;
    	margin-left: 0px;
	}
	table, th, td, tbody, thead{
		font-weight: 550 !important;
	}
</style>