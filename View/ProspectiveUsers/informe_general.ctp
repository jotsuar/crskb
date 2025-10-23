<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Informes generales diaríos del CRM </h2>
	</div>
	<div class="">
		<div class="row">
			<div class="col-md-12">
				<h1 class="text-center"> Resumen del informe generado el día: <?php echo date("d/m/Y",strtotime($fecha)) ?> </h1>
			</div>	
			<?php foreach ($informes as $key => $value): ?>
				<div class="col-md-2 blocksize">
					<div class="titleviewer "><h2>(<?php echo $value["Informe"]["total"] ?>)</h2> <span><?php echo Configure::read("Informes.".$value["Informe"]["type"]) ?></span>  </div>
				</div>	
			<?php endforeach ?>
		</div>
	</div>
	<?php foreach ($informes as $key => $value): ?>
		<div class="blockwhite spacebtn20">
			<div class="row">
				<div class="col-md-12">
					<h2 class="titleviewer"> <?php echo Configure::read("Informes.".$value["Informe"]["type"]) ?> (<?php echo $value["Informe"]["total"] ?>) </h2>
				</div>	
				<div class="col-md-12 titlesaccess">
					<?php $data = $this->Utilities->getArrayFromJson(json_decode($value["Informe"]["datos"])); ?>
					<?php if ($value["Informe"]["type"] == "request_import_brands_email"): ?>
						<div class="row">
							<?php echo $this->element("Informes/request_import_brands_email",["requests" => $data]) ?>
						</div>
					<?php endif ?>
					<?php if ($value["Informe"]["type"] == "informe_diario_clientes"): ?>
						<div class="row">
							<?php echo $this->element("Informes/informe_diario_clientes",["data" => $data]) ?>
						</div>
					<?php endif ?>
					<?php if ($value["Informe"]["type"] == "flujos_sin_gestionar"): ?>
						<div class="row">
							<?php echo $this->element("Informes/flujos_sin_gestionar",["flujos" => $data]) ?>
						</div>
					<?php endif ?>
					<?php if ( in_array($value["Informe"]["type"], ["reporte_sin_asignar","servicios_sin_terminar","servicios_sin_pago"]) ): ?>
						<div class="row">
							<?php echo $this->element("Informes/servicios_sin_terminar",["services" => $data]) ?>
						</div>
					<?php endif ?>
					<?php if ( in_array($value["Informe"]["type"], ["informe_sinterminar"]) ): ?>
						<div class="row">
							<div class="col-md-12">
								<?php echo $this->element("Informes/informe_sinterminar",["datos" => $data]) ?>
							</div>
						</div>
					<?php endif ?>
				</div>
			</div>	
		</div>
	<?php endforeach ?>
</div>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>