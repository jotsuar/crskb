<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h1 class="nameview">INFORME DE CLIENTES NUEVOS Y EXISTENTES WO</h1>
				<span class="subname">Informe de ventas realizadas por clientes nuevos mes a mes, para medir la efectividad de las redes</span>
			</div>			
		</div>
	</div>
	<div class="row mt-4">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="blockwhite p-2 mb-3">
				<div class='bg-gris border-0 card d-inline-block my-2 p-3 w-100 text-center'>
					<?php $varOp = 0; ?>
					<?php foreach ($anios as $key => $value): ?>
						<button id='datos_anio_<?php echo $value ?>' class="buttonsDataAnio btn btn-outline-primary <?php echo $varOp == 0 ? 'btn-primary' : '';  ?>">
						    <?php echo str_replace("_", " ", $value) ?>
						    <?php $varOp++; ?>
						</button>
					<?php endforeach ?>
				</div>
				<div class="card bg-gris border-0 p-3">
					<div id="datosAnio"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-6 col-lg-6 col-md-6">
			<div class="blockwhite p-2 mb-3">
				<div class="card bg-gris border-0 p-3">
					<div id="countClientes"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-6 col-lg-6 col-md-6">
			<div class="blockwhite p-2 mb-3">
				<div class="card bg-gris border-0 p-3">
					<div id="ventasClientes"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="blockwhite p-2 mb-3">
				<div class='bg-gris border-0 card d-inline-block my-2 p-3 w-100 text-center'>
					<?php $varOp = 0; ?>
					<?php foreach ($anios as $key => $value): ?>
						<button id='compara_ventas_<?php echo $value ?>' class="buttonsData btn btn-outline-primary <?php echo $varOp == 0 ? 'btn-primary' : '';  ?>">
						    <?php echo str_replace("_", " ", $value) ?>
						    <?php $varOp++; ?>
						</button>
					<?php endforeach ?>
				</div>
				<div class="card bg-gris border-0 p-3">
					<div id="containerMetas"></div>
				</div>
				<div class="card bg-gris border-0 p-3">
					<div id="containerMetas3"></div>
				</div>
				<div class="card bg-gris border-0 p-3">
					<div id="containerMetas2"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="blockwhite p-2 mb-3">
				<div class='bg-gris border-0 card d-inline-block my-2 p-3 w-100 text-center'>
					<div class="row">
						<?php foreach ($dataOnlyYear as $anio => $clientes): ?>
							<div class="col-md-12 p-2" style="border-top: 5px solid #fff; border-bottom: 5px solid #fff;">
								<h2 class="text-center text-info">
									Información sobre clientes para el año: <?php echo $anio ?>
								</h2>
								<table class="table table-bordered table-hovered">
									<thead class="thead-dark">
										<tr class="text-center">
											<th class="bg-blue" style="width: 30%">
												Total Clientes que solo compraron en el año <?php echo $anio ?> y no compraron de nuevo
											</th>
											<th class="bg-blue" style="width: 30%">
												Total clientes que compraron el año <?php echo $anio ?>
											</th>
											<th class="bg-blue" style="width: 30%">
												% Clientes con unica compra del año <?php echo $anio ?>
											</th>
											<th class="bg-blue" style="width: 10%">
												Ver detalle
											</th>
										</tr>
									</thead>
									<tbody>
										<tr class="text-center">
											<td>
												<?php echo count($clientes) ?>
											</td>
											<td>
												<?php echo count($clientesByAnio[$anio]) ?>
											</td>
											<td>
												<?php echo round(count($clientes) / count($clientesByAnio[$anio]) * 100,2) ?> %
											</td>
											<td>
												<a href="<?php echo $this->Html->url(["controller" => "pages", "action" => "detail_clientes_anio", $anio ]) ?>" target="_blank" class="btn btn-info">
													<i class="fa fa-eye vtc"></i> Ver detalle
												</a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php endforeach ?>
						<?php foreach ($dataByAnio as $anio => $clientes): ?>
							<div class="col-md-12 p-2" style="border-top: 5px solid #fff; border-bottom: 5px solid #fff;">
								<h2 class="text-center text-info">
									Información sobre clientes para los años: <?php echo $anio ?>
								</h2>
								<table class="table table-bordered table-hovered">
									<thead class="thead-dark">
										<tr class="text-center">
											<th class="bg-blue" style="width: 30%">
												Total Clientes que solo compraron en los años <?php echo $anio ?>
											</th>
											<th class="bg-blue" style="width: 30%">
												Total clientes que compraron en los año <?php echo $anio ?>
											</th>
											<th class="bg-blue" style="width: 30%">
												% Clientes con compra los años <?php echo $anio ?>
											</th>
											<th class="bg-blue" style="width: 10%">
												Ver detalle
											</th>
										</tr>
									</thead>
									<tbody>
										<tr class="text-center">
											<td>
												<?php echo count($clientes) ?>
											</td>
											<td>
												<?php 

													$aniosData = explode("-", $anio);
													$total = 0;
													foreach ($aniosData as $key => $value) {
														$total+=count($clientesByAnio[$value]);
													}

												 ?>
												<?php echo $total ?>
											</td>
											<td>
												<?php echo round(count($clientes) / $total * 100,2) ?> %
											</td>
											<td>
												<a href="<?php echo $this->Html->url(["controller" => "pages", "action" => "detail_clientes_anio", $anio ]) ?>" target="_blank" class="btn btn-info">
													<i class="fa fa-eye vtc"></i> Ver detalle
												</a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php endforeach ?>
						<div class="col-md-12 mb-3"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
    $this->start('AppScript'); ?>

    <script>
    	const anioActual 		= '<?php echo date("Y") ?>';
    	const anios 			= <?php echo json_encode($anios) ?>;
    	const labelsMes			= <?php echo json_encode($labelsMes) ?>;

    	var clientesNuevosArr 			= <?php echo json_encode($clientesNuevosArr) ?>;
    	var clientesNuevosArrActual 	= <?php echo json_encode($clientesNuevosArr[date("Y")]) ?>;
    	var ventasNuevosArr 			= <?php echo json_encode($ventasNuevosArr) ?>;
    	var ventasNuevosArrActual 		= <?php echo json_encode($ventasNuevosArr[date("Y")]) ?>;

    	const dataPrev 		 	= <?php echo json_encode($totalByCompany) ?>;
    	const dataCompare	 	= <?php echo json_encode($totalPaymentByMonth) ?>;
    	const dataCompareActual = <?php echo json_encode($totalPaymentByMonth[date("Y")]) ?>;

    	const dataCompareViejos 	  = <?php echo json_encode($dataClientAntiguo) ?>;
    	const dataCompareActualViejos = <?php echo json_encode($dataClientAntiguo[date("Y")]) ?>;

    	const dataPrevNumber			= <?php echo json_encode($totalComprasMes) ?>;
    	const dataCompareNumber	 		= <?php echo json_encode($totalNumberByMonth) ?>;
    	const dataCompareActualNumber 	= <?php echo json_encode($totalNumberByMonth[date("Y")]) ?>;

    	const promediosByAnioNuevos 	= <?php echo json_encode($promediosByAnioNuevos) ?>;
    	const valoresClientesNuevos 	= <?php echo json_encode($valoresClientesNuevos) ?>;
    	const promediosByAnioViejos 	= <?php echo json_encode($promediosByAnioViejos) ?>;
    	const valoresClientesViejos 	= <?php echo json_encode($valoresClientesViejos) ?>;
    </script>	

<?php
    $this->end();
 ?>
<?php
	echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));
	echo $this->Html->script(array('https://code.highcharts.com/highcharts.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('https://code.highcharts.com/modules/data.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('https://code.highcharts.com/modules/drilldown.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/exporting.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/offline-exporting.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/export-data.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('controller/config/clientes.js'),	array('block' => 'AppScript'));

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
</style>