<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-cafe big">
         <i class="fa fa-1x flaticon-report-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h1 class="nameview">INFORME DE INVENTARIO</h1>
				<span class="subname">Informe general de movimientos de inventario, productos y ventas.</span>
				
			</div>
			<div class="col-md-6 pull-right text-right">
					<div class="rangofechas">
						<span>Seleccionar rango de fechas:</span>
						<div class="form-group">
							<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="">
							<a class="btn-primary btn" id="btn_find_adviser">Filtrar Fechas</a>
						</div>
					</div>

					<div style="display: none">
						<div class="form-group">
							<span>Desde</span>
						</div>
						<div class="form-group">
							<input type="date" value="<?php echo $fechaInicioReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_inicio" style="display: none">
						</div>
					</div>
					<div style="display: none">
						<div class="form-group">
							<span>Hasta</span>
						</div>
						<div class="form-group">
							<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin" style="display: none">
						</div>
					</div>
					
			</div>

		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-6 spacebtn20 p-2">
			<div class="blockwhite">
				<h2 class="text-center text-info text-upper">
					Movimientos generales
				</h2>
				<div id="general"></div>
			</div>
		</div>
		<div class="col-md-6 spacebtn20 p-2">
			<div class="blockwhite">
				<h2 class="text-center text-info text-upper">
					Total por tipo de entrada
				</h2>
				<div id="tipos_entrada"></div>
			</div>
		</div>
		<div class="col-md-6 spacebtn20 p-2">
			<div class="blockwhite">
				<h2 class="text-center text-info text-upper">
					Total por bodega de entrada
				</h2>
				<div id="bodegas_entrada"></div>
			</div>
		</div>
		<div class="col-md-6 spacebtn20 p-2">
			<div class="blockwhite">
				<h2 class="text-center text-info text-upper">
					Total por tipo de salida
				</h2>
				<div id="tipos_salida"></div>
			</div>
		</div>
		<div class="col-md-6 spacebtn20 p-2">
			<div class="blockwhite">
				<h2 class="text-center text-info text-upper">
					Total por bodega de salida
				</h2>
				<div id="bodegas_salida"></div>
			</div>
		</div>
		<div class="col-md-12 spacebtn20 p-2">
			<div class="blockwhite">
				<h2 class="text-center text-info text-upper">
					Productos más vendidos
				</h2>
				<table class="table" id="example">
					<thead>
						<tr>
							<th>Número de parte</th>
							<th>Nombre</th>
							<th>Total Vendido</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>





<?php echo $this->element("picker"); ?>




<?php 

echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));
echo $this->Html->script(array('https://code.highcharts.com/highcharts.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/data.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/drilldown.js'),	array('block' => 'AppScript'));
// echo $this->Html->script(array('lib/exporting.js'),	array('block' => 'AppScript'));
// echo $this->Html->script(array('lib/offline-exporting.js'),	array('block' => 'AppScript'));
// echo $this->Html->script(array('lib/export-data.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('controller/inventories/reports.js'),	array('block' => 'AppScript'));

 ?>


<style>
svg{
	display: block !important;
}
.highcharts-data-table{
	display: none !important;
}
</style>