<div class="col-md-12 spacebtn20">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite headerinformelineal">
		<div class="row">
			<div class="col-md-8">
				<h1 class="nameview">INFORME DE GESTIÓN COMERCIAL</h1>
			</div>
			<div class=" col-md-4 pull-right text-right">
				<div class="rangofechas">
					<input type="date" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
					<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="">
					<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
					<a class="btn-primary btn" id="btn_find_adviser">Filtrar Fechas</a>
				</div>
				
			</div>
		</div>


	</div>
</div>

<div class="col-md-12">
	<div class="blockwhite spacebtn20">
		<ul class="subinforme">
			<li>
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  )) ) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos'),true ) ?>" class="informeWeb">Informe de prospectos</a>
			</li>
			<li>
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_adviser',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_adviser'),true ) ?>" class="informeWeb">Informe de atención de flujos</a>
			</li>
			<li>
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management'),true ) ?>" class="informeWeb">Informe de Gestión Comercial</a>
			</li>
			<li class="activesub">
				<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_advisers',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_advisers'),true ) ?>" class="informeWeb">Informe de Asesores</a>
			</li>
			<!-- <li>
				<a href="<?php //echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_customer_new',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" data-url = "<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_customer_new'),true ) ?>" class="informeWeb">Informe de gestión de nuevos clientes</a>
			</li> -->					
		</ul>
	</div>
</div>
<div class="col-md-12 blockinfome">
	<div class="row myChart">
		<div class="col-md-6" id="efectivity"></div>
		<div class="col-md-6" id="ventas"></div>
		<div class="col-md-6 mt-5" id="prospectos"></div>
		<div class="col-md-6 mt-5" id="demoras"></div>
		<div class="col-md-12 mt-5" id="cotizaciones"></div>
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
echo $this->Html->script("controller/prospectiveUsers/report_advisers.js?".rand(),								array('block' => 'AppScript'));
?>

<?php echo $this->element("picker"); ?>
<style>
svg{
	display: block !important;
}
.highcharts-data-table{
	display: none !important;
}
</style>