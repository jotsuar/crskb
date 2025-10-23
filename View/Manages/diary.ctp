<?php
echo $this->Html->css(array('lib/fullcalendar.min.css'),												array('block' => 'AppCss'));
?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azul big">
		<i class="fa fa-1x flaticon-settings"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Configuraciones </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-6">
				<h2 class="titleviewer">Mi Agenda</h2>
			</div>
			<div class="col-md-6 text-right">				
				<ul class="subpagos">
					<li class="activesub">
						<a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>">Mi Agenda</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ManagementNotices','action'=>'index')) ?>">Avisos Públicos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>">Banners de Cotizaciones</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'index')) ?>">Gestión de Usuarios</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12">
	<div class="blockwhite">
		<div id='calendarView'></div>
	</div>
</div>

<?php
echo $this->Html->script(array('lib/full_calendar/moment.min.js','lib/full_calendar/jquery-3.3.1.min.js?'.rand()),	array('block' => 'jqueryApp'));
echo $this->Html->script(array('lib/full_calendar/fullcalendar.min.js','lib/full_calendar/calendarView.js'),		array('block' => 'fullCalendar'));
?>