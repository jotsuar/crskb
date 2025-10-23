<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Panel de aprobación ampliación de tiempo de bloqueo por gestión</h2>
				<a href="<?php echo $this->Html->url(["controller"=>"config_flows","action"=>"edit"]) ?>" class="btn btn-primary">
					Configuración bloqueos <i class="fa fa-wrench vtc"></i>
				</a>
			</div>
			<div class="col-md-6 text-right">
				<a href="<?php echo $this->Html->url(array('controller'=>'prospective_users','action'=>'autorization')) ?>" class="crearclientej"><i class="fa fa-1x fa-plus-square vtc"></i> <span>Nueva autorización de tiempo</span></a>
			</div>	
		</div>	
	</div>
</div>




<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	// echo $this->Html->script("controller/quotations/actions_qts.js?".rand(),			array('block' => 'AppScript')); 
?>
