<?php $roles = ["Asesor Técnico Comercial","Asesor Comercial","Gerente línea Productos Pelican","Servicio al Cliente","Asesor Técnico Comercial","Asesor Logístico Comercial","Asesor Externo"] ?>
<div class="col-md-12 spacebtn20">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite headerinformelineal mb-3">
		<div class="row">
			<div class="col-md-12">
				<h1 class="nameview">
					INFORME DE GESTIÓN DE FLUJOS GENERADO POR <?php echo $management["User"]["name"] ?> EL DÍA <?php echo $management["Management"]["created"] ?>
					<a href="<?php echo $this->Html->url(["controller"=>"managements","action"=>'index',]) ?>" class="btn btn-info">
						Ver informes generados
					</a>
				</h1>
			</div>

		</div>
	</div>
	<div class="blockwhite col-md-12 blockinfome pb-5">
			<div class="col-md-12" id="ventas">
				<?php echo $management["Management"]["content"] ?>
			</div>

		</div>
	</div>
</div>

<?php 
	
echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));

?>

<?php 
	$this->start('AppScript'); ?>

	<script>
		$("td").each(function(index, el) {
			$(this).removeAttr('contenteditable')
		});
	</script>

<?php
	$this->end();
 ?>

