<?php
	echo $this->Html->css(array('lib/fullcalendar.min.css'),											array('block' => 'AppCss'));
?>

<div id='calendarDashboard'></div>

<?php
	$this->Html->script(array('lib/full_calendar/moment.min.js','lib/full_calendar/jquery-3.3.1.min.js?'.rand()),		array('block' => 'jqueryApp'));
	$this->Html->script(array('lib/full_calendar/fullcalendar.min.js','lib/full_calendar/calendarDashboard.js'),		array('block' => 'fullCalendar')); 
?>