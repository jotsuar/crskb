<div class="contentmargin">
	<div class="datosTechnicalService">
		<img src="<?php echo WWW_ROOT.'/img/assets/brand.png' ?>">
		<p class="text-right"><b><?php echo $this->Utilities->date_castellano($technicalServices['TechnicalService']['created']); ?></b></p>
		<h2 class="text-center colorazul">DIAGNÓSTICO DE SERVICIO TÉCNICO - KEBCO S.A.S.</h2>
		<p class="text-right"><b>ÓRDEN <?php echo $technicalServices['TechnicalService']['code'] ?></b></p>
		<hr>
		<h2 class="text-center colorazul">Datos del Cliente</h2>
		<b>Nombre: </b><?php echo $this->Utilities->name_client_contact_services($technicalServices['TechnicalService']['id']); ?><br>
		<b>Ciudad: </b><?php echo $this->Utilities->city_client_contact_services($technicalServices['TechnicalService']['id']); ?><br>
		<b>Teléfono: </b><?php echo $this->Utilities->telephone_client_contact_services($technicalServices['TechnicalService']['id']); ?><br>
		<b>Celular: </b><?php echo $this->Utilities->cellphone_client_contact_services($technicalServices['TechnicalService']['id']); ?><br>
		<b>Email: </b><?php echo $this->Utilities->email_client_contact_services($technicalServices['TechnicalService']['id']); ?><br>
		<hr>
		<h2 class="text-center colorazul">Información del Equipo</h2>
		<?php foreach ($productClient as $valueP): ?>
			<b>Equipo: </b><?php echo $valueP['ProductTechnical']['equipment'] ?><br>
			<b>N. parte: </b><?php echo $valueP['ProductTechnical']['part_number'] ?><br>
			<b>Serial: </b><?php echo $valueP['ProductTechnical']['serial_number'] ?><br>
			<b>Marca: </b><?php echo $valueP['ProductTechnical']['brand'] ?><br>
			<b>Motivo de Ingreso: </b><?php echo $valueP['ProductTechnical']['reason'] ?><br>
			<hr>
		<?php endforeach ?>
		<h2 class="text-center colorazul">Informe del equipo ingresado</h2>
		<b>Reporte del equipo: </b><?php echo strip_tags($report) ?><br>
		<b>Observación del equipo: </b><?php echo strip_tags($observation) ?>
		<hr>
	</div>
	
	<h2 class="text-center colorazul">Registro fotográfico</h2>
	<?php if ($technicalServices['TechnicalService']['image1'] == '' && $technicalServices['TechnicalService']['image2'] == '' && $technicalServices['TechnicalService']['image3'] == '' && $technicalServices['TechnicalService']['image4'] == '' && $technicalServices['TechnicalService']['image5'] == ''): ?>
		No se encuentran imágenes asociadas a esta órden de servicio
	<?php endif ?> 

	<?php if ($technicalServices['TechnicalService']['image1'] != ''){ ?>
		<img src="<?php echo WWW_ROOT.'/img/servicioTecnico/'.$technicalServices['TechnicalService']['image1'] ?>" class="images-product">
	<?php } ?>

	<?php if ($technicalServices['TechnicalService']['image2'] != ''){ ?>
		<img src="<?php echo WWW_ROOT.'/img/servicioTecnico/'.$technicalServices['TechnicalService']['image2'] ?>" class="images-product">
	<?php } ?>

	<?php if ($technicalServices['TechnicalService']['image3'] != ''){ ?>
		<img src="<?php echo WWW_ROOT.'/img/servicioTecnico/'.$technicalServices['TechnicalService']['image3'] ?>" class="images-product">
	<?php } ?>

	<?php if ($technicalServices['TechnicalService']['image4'] != ''){ ?>
		<img src="<?php echo WWW_ROOT.'/img/servicioTecnico/'.$technicalServices['TechnicalService']['image4'] ?>" class="images-product">
	<?php } ?>

	<?php if ($technicalServices['TechnicalService']['image5'] != ''){ ?>
		<img src="<?php echo WWW_ROOT.'/img/servicioTecnico/'.$technicalServices['TechnicalService']['image5'] ?>" class="images-product">
	<?php } ?>
	<!-- 
		<h2 class="text-center colorazul">
			<?php echo $this->Utilities->cotizacion_servicio_tecnico($technicalServices['TechnicalService']['cotizacion']) ?>
		</h2>
	-->
	
	<br>
    <br>
	<b>Cordial saludo,</b>
	<div class="datasesorview">
		<b class="firmaUsuario"><?php echo mb_strtoupper($datosUsuario['User']['name']) ?></b><br>
		<b class="firmaUsuario"><?php echo $datosUsuario['User']['role'] ?></b><br>
		<?php echo 'CEL: '.$datosUsuario['User']['cell_phone'] ?><br>
		<?php echo 'TEL: '.$datosUsuario['User']['telephone'] ?><br>
		<?php echo $datosUsuario['User']['email'] ?><br>
	</div> 
	<br>
	<img src="<?php echo WWW_ROOT.'/img/footerpdf.png' ?>" class="images-footer">
</div>