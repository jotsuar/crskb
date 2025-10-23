<?php echo $this->html->css("multi-select.css"); ?>
<div class="col-md-12 p-0">
		<div class=" widget-panel widget-style-2 bg-cafe big">
	         <i class="fa fa-1x flaticon-report-1"></i>
	        <h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
		</div>
		<div class="blockwhite spacebtn20 p-2">
			<div class="row">
				<div class="col-md-12">
					<h1 class="nameview">INFORME DE PROSPECTOS</h1>
					<span class="subname">Relación de prospectos asignados por dia a cada vendedor</span>
					
				</div>
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-12 p-3">
							<a href="" class="btn btn-warning float-right mt-1" id="showHideFilter"> <span id="ver">Ver filtros <i class="fa fa-eye vtc"></i> </span> <span id="noVer">Esconder filtros <i class="fa fa-eye-slash vtc"></i></span> </a>
						</div>
						<div class="col-md-6 filtersFlujo d-none">
							<div class="form-group mt-2">
								<?php $actuales=[]; ?>
								<?php echo $this->Form->input('users',array('label' => false,"multiple","options" => $usuarios, "value" => $usersSelecteds, "style" => 'height: 200px;',"multiple" => true ));?>
							</div>
						</div>
						<div class="col-md-6 filtersFlujo d-none">
							<div class="form-group mt-2">
								<?php echo $this->Form->input('origin',array('label' => false,"multiple","options" => $origenes, "value" => $origin, "style" => 'height: 200px;',"multiple" => true ));?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
						<div class="rangofechas">
							<span>Seleccionar rango de fechas:</span>
							<div class="form-group">
								<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="" style="width: 280px;">
								<a id="btn_buscar" class="btn-primary btn">Buscar</a>
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
		<div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12">
				<div class="blockwhite spacebtn20">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="text-success text-center">
								Total ventas: $<?php echo number_format($totalVentas,"2",",",".") ?>
							</h1>
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'ventas_report',"?"=>array("ini" =>$fechaInicioReporte, "end" => $fechaFinReporte  ))) ?>" class="btn btn-outline-success text-center">
								Ver detalle de ventas
							</a>
						</div>
						<div class="col-md-6">
							<div id="contentFunnel"></div>
						</div>
						<div class="col-md-6">
							<div id="contentMedios"></div>
						</div>
						<div class="col-md-12">
							<hr>
						</div>
						<div class="col-md-6">
							<div id="contentClientes"></div>
						</div>
						<div class="col-md-6">
							<div id="contentAsignacion"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12">
				<div class="blockwhite">
					<div class="row">
						<div class="resultadoConsulta col-md-12"></div>
					</div>
				</div>
			</div>
		</div>
	</div>


<?php
echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));
echo $this->Html->script(array('https://code.highcharts.com/highcharts.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/data.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/offline-exporting.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/export-data.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/exporting.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/funnel.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/drilldown.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/accessibility.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('jquery.multi-select.js?'.rand()),					array('block' => 'AppScript'));


echo $this->Html->script("controller/quotations/view.js?".rand(),									array('block' => 'AppScript'));
?>

<?php 
	$this->start('AppScript'); ?>

	<script>

		var funnelData = <?php echo json_encode($dataPrint["dataByState"]); ?>;
		var dataOgigin = <?php echo json_encode($dataPrint["dataByOrigen"]); ?>;
		var dataClientes = <?php echo json_encode($datosClientes); ?>;
		var flujosAsesor = <?php echo json_encode($flujosAsesor); ?>;
		var totalProspectos = <?php echo count($datos) ?>;

	</script>

<?php
	$this->end();
	echo $this->Html->script("controller/prospectiveUsers/report_new_prospect.js?".rand(), array('block' => 'AppScript'));
 ?>

 <style>
 	#noVer{
 		display: none;
 	}
 	svg{
	display: block !important;
}
text.highcharts-credits {
    display: none;
}
 </style>


<?php echo $this->element("picker"); ?>

<?php echo $this->element("flujoModal"); ?>