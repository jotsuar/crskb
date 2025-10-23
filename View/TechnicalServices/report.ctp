<div class="container osspecialrsp">
	<div class="col-md-12">
		<div class="technicalServices view pdpagest">
			<div class="blockwhite">
				<div class="row">
					<div class="col-md-3 centerimg">
						<img src="<?php echo $this->Html->url('/img/assets/brand.png'); ?>" class="logost img-fluid">
					</div>
					<div class="col-md-6">
						<h2 class="titulost">DIAGNÓSTICO DE SERVICIO TÉCNICO - KEBCO S.A.S.</h2>
					</div>
					<div class="col-md-3 codest">
						<h3 class="">ÓRDEN <?php echo $technicalServices['TechnicalService']['code'] ?></h3>
						<p class=""><?php echo $this->Utilities->date_castellano($technicalServices['TechnicalService']['created']); ?></p>
					</div>						
				</div>

				<div class="dataclientview">
					<div class="dataclientview">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-8 col-6 nopdiv">
									<h2>Datos del Cliente</h2>
								</div>
								<div class="col-md-4 col-6 nopdiv">
									<h2>Estado: <?php echo $this->Utilities->state_service_tchnical($technicalServices['TechnicalService']['state']); ?></h2>
								</div>				
							</div>
						</div>	
					</div>		
					<div class="col-md-12">
						<div class="row lineb">
							<div class="col-md-6">
								<p><b>Nombre:</b><?php echo $this->Utilities->name_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
							</div>
							<div class="col-md-6">	
								<p><b>Ciudad:</b><?php echo $this->Utilities->city_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
							</div>	
						</div>
						<div class="row lineb">
							<div class="col-md-6">
								<p><b>Teléfono:</b><?php echo $this->Utilities->telephone_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
							</div>
							<div class="col-md-6">	
								<p><b>Celular:</b><?php echo $this->Utilities->cellphone_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
							</div>
						</div>
						<div class="row linebs">
							<div class="col-md-12">	
								<p><b>Email:</b><?php echo $this->Utilities->email_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
							</div>	
						</div>						
					</div>						
				</div>
			</div>

			<div class="dataclientview equiposdc">
				<h2>Información del Equipo</h2>
				<div class="blockwhite">
					<?php foreach ($productClient as $valueP): ?>
						<div class="col-md-12">
							<div class="row lineb">
								<div class="col-md-6">
									<p><b>Equipo:</b><?php echo $valueP['ProductTechnical']['equipment'] ?></p>
								</div>
								<div class="col-md-2">
									<p><b>N. parte:</b><?php echo $valueP['ProductTechnical']['part_number'] ?></p>
								</div>
								<div class="col-md-2">	
									<p><b>Serial:</b><?php echo $valueP['ProductTechnical']['serial_number'] ?><</p>
								</div>								
								<div class="col-md-2">	
									<p><b>Marca:</b><?php echo $valueP['ProductTechnical']['brand'] ?></p>
								</div>	
							</div>
							<div class="row lineb">
								<div class="col-md-4">
									<p><b>Motivo de Ingreso:</b><?php echo $valueP['ProductTechnical']['reason'] ?></p>
								</div>
							</div>
						</div>
					<?php endforeach ?>
				</div>
			</div>
			<br>

			<div class="blockwhite">
				<div class="dataclientview ">
					<!-- <h2>Informe de los equipos ingresados</h2> -->
					<h2>Informe del equipo ingresado</h2>
					<div class="col-md-12 resetfont">
					<!-- 	<div class="row lineb resetfont">
							<div class="col-md-12 resetfont">
								<p><b>Número de equipos:</b><a href="<?php echo $this->Html->url(array('action' => 'view', $technicalServices['TechnicalService']['id'])) ?>" data-toggle="tooltip" title="Ver detalle del servicio"><?php echo $equipos_ingresados_num ?></a></p>
							</div>
						</div> -->
						<div class="row lineb resetfont">
							<div class="col-md-12 resetfont">
								<!-- <p><b>Reporte de los equipos:</b><?php echo $technicalServices['TechnicalService']['report'] ?></p> -->
								<p><b>Reporte del equipo:</b><?php echo $technicalServices['TechnicalService']['report'] ?></p>
							</div>
						</div>
						<div class="row lineb resetfont">
							<div class="col-md-12 resetfont">
								<!-- <p><b>Observación de los equipos:</b><?php echo $technicalServices['TechnicalService']['observation'] ?></p> -->
								<p><b>Observación del equipo:</b><?php echo $technicalServices['TechnicalService']['observation'] ?></p>
							</div>
						</div>

						<div class="row">
							<div class="dataclientview col-md-12">
								<h2>Registro fotográfico</h2>
								<div class="row">
									<div class="col-md-12">
										<?php if ($technicalServices['TechnicalService']['image1'] == '' && $technicalServices['TechnicalService']['image2'] == '' && $technicalServices['TechnicalService']['image3'] == '' && $technicalServices['TechnicalService']['image4'] == '' && $technicalServices['TechnicalService']['image5'] == ''): ?>
											No se encuentran imágenes asociadas a esta Órden de Servicio
										<?php endif ?> 
									</div>
								</div>
							</div>

							<?php if ($technicalServices['TechnicalService']['image1'] != ''){ ?>
								<div class="col-md-4">
									<div class="imgservicecontent">
										<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$technicalServices['TechnicalService']['image1']) ?>)"> </div>
									</div>
								</div>
							<?php } ?>

							<?php if ($technicalServices['TechnicalService']['image2'] != ''){ ?>
								<div class="col-md-4">
									<div class="imgservicecontent">
										<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$technicalServices['TechnicalService']['image2']) ?>)"> </div>
									</div>
								</div>
							<?php } ?>

							<?php if ($technicalServices['TechnicalService']['image3'] != ''){ ?>
								<div class="col-md-4">
									<div class="imgservicecontent">
										<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$technicalServices['TechnicalService']['image3']) ?>)"> </div>
									</div>
								</div>
							<?php } ?>

							<?php if ($technicalServices['TechnicalService']['image4'] != ''){ ?>
								<div class="col-md-6">
									<div class="imgservicecontent">
										<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$technicalServices['TechnicalService']['image4']) ?>)"> </div>
									</div>
								</div>
							<?php } ?>

							<?php if ($technicalServices['TechnicalService']['image5'] != ''){ ?>
								<div class="col-md-6">
									<div class="imgservicecontent">
										<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$technicalServices['TechnicalService']['image5']) ?>)"> </div>
									</div>
								</div>
							<?php } ?>
						</div>

					</div>
				</div>
			</div>
			<div class="statuscotizacion">
				<h2><?php echo $this->Utilities->cotizacion_servicio_tecnico($technicalServices['TechnicalService']['cotizacion']) ?></h2>
			</div>
			<br>
			<div class="blockwhite">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12 text-center firmas">
							<h2><?php echo $this->Utilities->find_name_lastname_adviser($technicalServices['TechnicalService']['user_id']); ?></h2>
							Elaborado por
						</div>

					</div>
				</div>

				<footer>
					<div class="siguenos-redessociales">
						<div class="basegray"></div>
						<div class="contentcenter">
							<span>SÍGUENOS EN REDES SOCIALES</span>
							<ul class="list-inline dpinline">
								<li class="list-inline-item">
									<i class="fa fa-facebook"></i>
									<span>almacendelpintor</span>
								</li>
								<li class="list-inline-item">
									<i class="fa fa-instagram"></i>
									<span>almacendelpintor</span>
								</li>
								<li class="list-inline-item">
									<i class="fa fa-youtube-play"></i>
									<span>almacendelpintor</span>
								</li>
								<li class="list-inline-item">
									<i class="fa fa-whatsapp"></i>
									<span>301 448 5566</span>
								</li>															
							</ul>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row datasedes">
							<div class="col-md-4 text-center">
								<p><b>KEBCO S.A.S. NIT: 900412283-0</b></p>
								<p>ventas@almacendelpintor.com</p>
							</div>
							<div class="col-md-4 text-center">
								<p><b>OFICINA PRINCIPAL MEDELLÍN</b></p>
								<p>Calle 10 # 52A - 18 Int. 104 Centro Integral la 10</p>
								<p>Teléfono (4) 448 5566</p>
							</div>
							<div class="col-md-4 text-center">
								<p><b>OFICINA BOGOTÁ</b></p>
								<p>Av. Calle 26 # 85D - 55 LE 25 C.E. Dorado Plaza</p>
								<p>Teléfono (4) 448 5566</p>
							</div>										
						</div>				
					</div>				
				</footer>
			</div>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>