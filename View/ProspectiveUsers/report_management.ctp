	<div class="col-md-12">
		<div class=" widget-panel widget-style-2 bg-cafe big">
			<i class="fa fa-1x flaticon-report-1"></i>
			<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
		</div>
		<div class="blockwhite spacebtn20">
			<div class="row">
				<div class="col-md-5">
					<h1 class="nameview">INFORME DE EFECTIVIDAD POR ASESOR</h1>
					<span class="subname">Efectividad medida por la generación de ventas a partir de prospectos asignados</span>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-4">
							<span>Seleccionar Asesor</span>
							<?php echo $this->Form->input('user',array('label' => false, 'id' => 'usuario', 'options' => $usuarios, 'class' => '','multiple'));
							?>
						</div>
						<div class="col-md-8">
							<div class="row">
								<div class="col-md-12">
									<span>Seleccionar rango de fechas para calcular efectividad:</span>
									<div class="rangofechas">
										<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="w-100">

										<div style="display: none">
											<span>Desde</span>
											<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio" placeholder="Desde" style="display: none">
										</div>

										<div style="display: none">
											<span>Hasta</span>
											<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control " id="input_date_fin" placeholder="Desde" style="display: none">
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<span>Seleccionar rango de fechas para calcular comisiones según efectividad:</span>
									<div class="rangofechas">
										<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin2" class="w-100">

										<div style="display: none">
											<span>Desde</span>
											<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio2" placeholder="Desde" style="display: none">
										</div>

										<div style="display: none">
											<span>Hasta</span>
											<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin2" placeholder="Desde" style="display: none">
										</div>
										
										
									</div>
								</div>

								<div class="col-md-12">
									<a class="btn-primary btn mt-3 float-right" id="btn_find_adviser_443">Buscar</a>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


<!-- <div class="blockwhite spacebtn20">
	<ul class="subinforme">
		<li class="activesub">
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management'),true ) ?>" class="informeWeb">Informe de Gestión Comercial</a>
		</li>					
	</ul>
</div> -->

			<div class="row">
				<div class="col-md-12 d-none">
					<div class="blockwhite spacebtn20">
						<div id="container2"></div>
						<div class="cuadroGrafica"></div>
						<canvas id="allquote"></canvas>
					</div>						
				</div>
				<br>
				<div class="col-md-12">
					<div class="row resultInformation">	</div>
				</div>
			</div>
	</div>


<?php echo $this->Html->css("lib/choices.min.css") ?>
<?php
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),													array('block' => 'jqueryApp'));
echo $this->Html->script(array('http://momentjs.com/downloads/moment.min.js'),									array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/highcharts.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/data.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/exporting.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('https://code.highcharts.com/modules/accessibility.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('lib/choices.min.js'),	array('block' => 'AppScript'));
echo $this->Html->script(array('lib/export-data.js'),	array('block' => 'AppScript'));
echo $this->Html->script("controller/prospectiveUsers/report_management.js?".rand(),							array('block' => 'AppScript'));
?>

<style>
svg{
	display: block !important;
}
.highcharts-figure,
.highcharts-data-table table {
    min-width: 310px;
    max-width: 800px;
    margin: 1em auto;
}
.highcharts-credits{
	display: none !important;
}
</style>


<?php echo $this->element("picker"); ?>
