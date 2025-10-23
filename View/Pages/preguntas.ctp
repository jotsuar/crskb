<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
	     <i class="fa fa-1x flaticon-growth"></i>
	    <h2 class="m-0 text-white bannerbig" >Módulo de ayuda CRM </h2>
	</div>
</div>
<div class="blockwhite spacebtn20">
	<div class="row">
		<div class="col-md-12">
			<h2 class="titleviewer">Preguntas frecuentes CRM</h2>
		</div>
	</div>
</div>
<div class="spacebtn20 blockwhite">
	<div class="container w-75">
		
	
		<div class="row">
			<div class="col-md-12">
				
				<div class="accordion" id="accordionExample">

					<div class="card">
					    <div class="card-header" id="headingOne">
						    <h2 class="mb-0">
						        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						        	Que es un flujo y como crearlo
						        </button>
						    </h2>
					    </div>
					    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
					      	<div class="card-body">
					        	<div>
					        		<?php echo $this->element("Asks/uno_flujo"); ?>
					        	</div>
					      	</div>
					    </div>
				  	</div>
				  	<div class="card">
					    <div class="card-header" id="headingDos">
						    <h2 class="mb-0">
						        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseDos" aria-expanded="true" aria-controls="collapseDos">
						        	Etapas de un flujo
						        </button>
						    </h2>
					    </div>
					    <div id="collapseDos" class="collapse" aria-labelledby="headingDos" data-parent="#accordionExample">
					      	<div class="card-body">
					        	<div>
					        		<?php echo $this->element("Asks/dos"); ?>
					        	</div>
					      	</div>
					    </div>
				  	</div>
				  	<div class="card">
					    <div class="card-header" id="headingTres">
						    <h2 class="mb-0">
						        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseTres" aria-expanded="true" aria-controls="collapseTres">
						        	Paso de un flujo a contactado 
						        </button>
						    </h2>
					    </div>
					    <div id="collapseTres" class="collapse" aria-labelledby="headingTres" data-parent="#accordionExample">
					      	<div class="card-body">
					        	<div>
					        		<?php echo $this->element("Asks/tres"); ?>
					        	</div>
					      	</div>
					    </div>
				  	</div>
				  	<div class="card">
					    <div class="card-header" id="headingCuatro">
						    <h2 class="mb-0">
						        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseCuatro" aria-expanded="true" aria-controls="collapseCuatro">
						        	Paso de un flujo a cotizado y como crear cotización 
						        </button>
						    </h2>
					    </div>
					    <div id="collapseCuatro" class="collapse" aria-labelledby="headingCuatro" data-parent="#accordionExample">
					      	<div class="card-body">
					        	<div>
					        		<?php echo $this->element("Asks/cuatro"); ?>
					        	</div>
					      	</div>
					    </div>
				  	</div>
				  	<div class="card">
					    <div class="card-header" id="headingCinco">
						    <h2 class="mb-0">
						        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseCinco" aria-expanded="true" aria-controls="collapseCinco">
						        	Paso de un flujo a negociado
						        </button>
						    </h2>
					    </div>
					    <div id="collapseCinco" class="collapse" aria-labelledby="headingCinco" data-parent="#accordionExample">
					      	<div class="card-body">
					        	<div>
					        		<?php echo $this->element("Asks/cinco"); ?>
					        	</div>
					      	</div>
					    </div>
				  	</div>
				  	<div class="card">
					    <div class="card-header" id="headingSeis">
						    <h2 class="mb-0">
						        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSeis" aria-expanded="true" aria-controls="collapseSeis">
						        	Paso de un flujo a pagado
						        </button>
						    </h2>
					    </div>
					    <div id="collapseSeis" class="collapse" aria-labelledby="headingSeis" data-parent="#accordionExample">
					      	<div class="card-body">
					        	<div>
					        		<?php echo $this->element("Asks/seis"); ?>
					        	</div>
					      	</div>
					    </div>
				  	</div>
				  	<div class="card">
					    <div class="card-header" id="headingSiete">
						    <h2 class="mb-0">
						        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSiete" aria-expanded="true" aria-controls="collapseSiete">
						        	Gestión de productos para importación
						        </button>
						    </h2>
					    </div>
					    <div id="collapseSiete" class="collapse" aria-labelledby="headingSiete" data-parent="#accordionExample">
					      	<div class="card-body">
					        	<div>
					        		<?php echo $this->element("Asks/siete"); ?>
					        	</div>
					      	</div>
					    </div>
				  	</div>
				</div>

			</div>
		</div>
	</div>
</div>



<?php
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>

<style>
	.card-body li {
    	list-style: auto !important;
	}
	.card-body *{
    	font-family: 'Raleway', sans-serif !important;
    	text-align: justify;
	}
</style>