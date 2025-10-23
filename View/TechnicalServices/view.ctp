<?php if (AuthComponent::user("id")): ?>
	<div class="">
	    <button id="imprimeData" class="btn btn-primary ">Imprimir</button>
	    <a href="<?php echo $this->Html->url(["controller"=>"binnacles","action"=>"index",$technicalServices['TechnicalService']['id'] ]) ?>" class="bg-blue btn btn-blue float-right listBinnacle" ><i class="fa fa-list vtc"></i> Listar bitácora</a>
	</div>
<div class=" widget-panel widget-style-2 bg-rojo big">
         <i class="fa fa-1x flaticon-settings-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Servicio Técnico</h2>
	</div>
<?php endif ?>
<div class="container osspecialrsp" style="max-width: 1140px">

	<div class="col-md-12">

		<div class="technicalServices view ">
			
			<div class="blockwhite">
				<div class="data-textData row" style="display: none;">
					<div class="col-md-12">
						<h2 class="titulost">KEBCO SAS, COMPROBANTE DE INGRESO DE EQUIPO A SERVICIO TÉCNICO, ORDEN <?php echo $technicalServices['TechnicalService']['code'] ?> <br> <?php echo $this->Utilities->date_castellano($technicalServices['TechnicalService']['created']); ?></h2>						
					</div>
				</div>
				<div class="row dataFull">
					<div class="col-md-3 centerimg img-dataText">
						<img src="<?php echo $this->Html->url('/img/assets/brand.png'); ?>" class="logost img-fluid">
					</div>
					<div class="col-md-6">
						<h2 class="titulost">COMPROBANTE DE INGRESO DE EQUIPO A SERVICIO TÉCNICO <div class="centerimg img-texts" style="display:none">
								<img src="<?php echo $this->Html->url('/img/assets/brand.png'); ?>" class="logost img-fluid">
							</div>
						</h2>
					</div>
					<div class="col-md-3 codest">
						<h3 class="">ORDEN <?php echo $technicalServices['TechnicalService']['code'] ?></h3>
						<p class=""><?php echo $this->Utilities->date_castellano($technicalServices['TechnicalService']['created']); ?></p>
					</div>						
				</div>

				<div class="dataclientview">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-8 col-6 nopdiv">
								<h2>Datos del Cliente</h2>
							</div>
							<div class="col-md-4 col-6 nopdiv ">
								<h2>Estado: <?php echo $this->Utilities->state_service_tchnical($technicalServices['TechnicalService']['state']); ?></h2>
							</div>				
						</div>
					</div>	
					<div class="col-md-12 p-0">
						<div class="table-responsive">
							<table class="table table-hovered">
								<tbody>
									<tr>
										<td class="p-0">
											<p><b>Nombre:</b><?php echo $this->Utilities->name_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
										</td>
										<td class="p-0">
											<p><b>Ciudad:</b><?php echo $this->Utilities->city_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
										</td>
									</tr>
									<tr>
										<td class="p-0">
											<p><b>Teléfono:</b><?php echo $this->Utilities->telephone_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
										</td>
										<td class="p-0">
											<p><b>Celular:</b><?php echo $this->Utilities->cellphone_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
										</td>
									</tr>
									<tr>
										<td colspan="2" class="p-0">
											<p><b>Email:</b><?php echo $this->Utilities->email_client_contact_services($technicalServices['TechnicalService']['id']); ?></p>
										</td>
									</tr>
								</tbody>
							</table>
						</div>						
					</div>
					<?php if (!empty($technicalServices["TechnicalService"]["contact_name"])): ?>
						<div class="col-md-12 p-0" style="margin-top: -30px;">
							<h2>Datos de quien entrega el equipo</h2>
							<div class="table-responsive">
								<table class="table table-hovered">
									<tr>
										<td class="p-0">
											<p><b>Nombre:</b><?php echo $technicalServices["TechnicalService"]["contact_name"]; ?></p>
										</td>
										<td class="p-0">
											<p><b>Teléfono:</b><?php echo $technicalServices["TechnicalService"]["contact_phone"]; ?></p>
										</td>
									</tr>
									<tr>
										<td class="p-0">
											<p><b>Identificación:</b><?php echo $technicalServices["TechnicalService"]["contact_identification"]; ?></p>
										</td>
										<td class="p-0">
											<p><b>Direción de retorno:</b><?php echo $technicalServices["TechnicalService"]["contact_address"]; ?></p>
										</td>
									</tr>
								</table>
							</div>
						</div>				
					<?php endif ?>					
				</div>
			</div>

			<div class="dataclientview" style="margin-top: -10px;">
				<?php foreach ($equipos_servicio as $value): ?>
					
					<div class="blockwhite pt-0">
						<h2>Equipo ingresado</h2>
						<div id="<?php echo 'equipo_'.$value['ProductTechnical']['id'] ?>" >
							<div class="col-md-12 p-0">
								<div class="table-responsive">
									<table class="table table-hovered">
										<tbody>
											<tr>
												<td class="p-0">
													<p><b>Equipo:</b><?php echo $value['ProductTechnical']['equipment'] ?></p>
												</td>
												<td class="p-0">
													<p><b>Número de serie:</b><?php echo $value['ProductTechnical']['serial_number'] ?></p>
												</td>
											</tr>
											<tr>
												<td class="p-0">
													<p><b>Número de parte:</b><?php echo $value['ProductTechnical']['part_number'] ?></p>
												</td>
												<td class="p-0">
													<p><b>Marca:</b><?php echo $value['ProductTechnical']['brand'] ?></p>
												</td>
											</tr>
											<tr>
												<td class="p-0">
													<p><b>Motivo de Ingreso:</b><?php echo $value['ProductTechnical']['reason'] ?></p>
												</td>
												<td class="p-0">
													<p><b>Serial:</b><?php echo $this->Utilities->data_null($value['ProductTechnical']['serial_garantia']) ?></p>
												</td>
											</tr>
											<?php if (!is_null($technicalServices["TechnicalService"]["deadline"])): ?>
												<tr>
													<td class="p-0" colspan="2">
														<p><b>Fecha límite de entrega:</b> <?php echo $technicalServices['TechnicalService']['deadline'] ?></p>
													</td>											
												</tr>
											<?php endif ?>
											<tr>
												<td class="p-0" colspan="2">
														<p><b>Observaciones con que se recibió el equipo:</b></p>
												</td>
											</tr>
											<tr>
												<td class="p-0" colspan="2">
													<div class="bordespan p-0 px-1">	<span><?php echo $value['ProductTechnical']['observation'] ?></span></div>
												</td>
											</tr>
											<tr>
												<td class="p-0" colspan="2">
													<p><b>Posibles fallas indicadas por el cliente</b></p>
												</td>
											</tr>
											<tr>
												<td class="p-0" colspan="2">
													<div class="bordespan p-0 px-1">	<span><?php echo $value['ProductTechnical']['possible_failures'] ?></span></div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>												
							</div>
							<div class="statusequipo">
								<div class="row">
									<div class="col-md-5">
										<h4>Estado General del Equipo</h4>
									</div>
									<div class="col-md-7 optionstatus">
										<span class="<?php echo $value['ProductTechnical']['maintenance_status']?> s">Satisfactorio<div class="equis"><i class="fa fa-times"></i> </div></span>
										<span class="<?php echo $value['ProductTechnical']['maintenance_status']?> a">Aceptable<div class="equis"><i class="fa fa-times"></i> </div></span>
										<span class="<?php echo $value['ProductTechnical']['maintenance_status']?> d">Deficiente<div class="equis"><i class="fa fa-times"></i> </div></span>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="dataclientview col-md-12">
									<h2 class="specialpdf">Registro fotográfico</h2>
									<div class="col-md-12">
										<?php if ($value['ProductTechnical']['image1'] == '' && $value['ProductTechnical']['image2'] == '' && $value['ProductTechnical']['image3'] == '' && $value['ProductTechnical']['image4'] == '' && $value['ProductTechnical']['image5'] == ''): ?>
											No se encuentran imágenes asociadas a esta Orden de Servicio
										<?php endif ?> 
									</div>
								</div>

								<?php if ($value['ProductTechnical']['image1'] != ''){ ?>
									<div class="col-md-4">
										<div class="imgservicecontent">
											<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image1']) ?>)"> </div>
										</div>
									</div>
								<?php } ?>

								<?php if ($value['ProductTechnical']['image2'] != ''){ ?>
									<div class="col-md-4">
										<div class="imgservicecontent">
											<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image2']) ?>)"> </div>
										</div>
									</div>
								<?php } ?>

								<?php if ($value['ProductTechnical']['image3'] != ''){ ?>
									<div class="col-md-4">
										<div class="imgservicecontent">
											<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image3']) ?>)"> </div>
										</div>
									</div>
								<?php } ?>

								<?php if ($value['ProductTechnical']['image4'] != ''){ ?>
									<div class="col-md-6">
										<div class="imgservicecontent">
											<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image4']) ?>)"> </div>
										</div>
									</div>
								<?php } ?>

								<?php if ($value['ProductTechnical']['image5'] != ''){ ?>
									<div class="col-md-6">
										<div class="imgservicecontent">
											<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image5']) ?>)"> </div>
										</div>
									</div>
								<?php } ?>
							</div>
							<div class="dataclientview">
								<h2>Accesorios entregados con el equipo</h2>
								<div class="col-md-12 accessoriesclient">
									<?php echo $this->Form->select('accessories',$accesorios_mantenimiento,array('multiple' => 'checkbox','default' => $this->Utilities->find_check_equipo($value['ProductTechnical']['id']),'disabled' => true)); ?>

								</div>
								<?php if ($value['ProductTechnical']['otros_input'] != ''){ ?>
									<div class="input_otros_div">
										<?php echo $this->Form->input('otros_input',array('type' => 'text','class'=>'form-control','label' => 'Otros accesorios','value' => $value['ProductTechnical']['otros_input'],'disabled' => true));?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php endforeach ?>
			</div>

			<div class="blockwhite spacetop">
				<div class="dataclientview">
					<h2 class="nospace">Condiciones del Servicio</h2>
					<div class="col-md-12 text-justify condiciones">
						<p>- Autorizo a KEBCO S.A.S. para realizar las pruebas y trabajos necesarios para el diagnóstico del equipo.</p>
						<p>- La revisión y diagnóstico del equipo tiene un costo máximo de una (1) hora de trabajo técnico.</p>
						<p>- Autorizo a KEBCO S.A.S. para realizar la devolución del equipo 15 días después de diagnosticado y no aprobada su reparación.</p>
						<p>- La devolución del equipo se realiza por correo pagado contra-entrega en la dirección consignada en este documento. KEBCO
						S.A.S. no se hace responsable de la recepción del equipo en dicha dirección.</p>
						<p>- El cambio de repuestos se realiza con la previa autorización y cancelación de los mismos. La cotización de la reparación se
						realiza después del desarme del equipo, determinando todos los imprevistos de reparación.</p>
						<p>- Los equipos que requieran repuestos electrónicos (tarjetas) y no convencionales, pueden tomar mayor tiempo en reparación
						por motivos de importación de repuestos.</p>
						<p>- Si el equipo después de 30 días de reparado y noticado su reparación no es recogido, KEBCO S.A.S. no se hace responsable del
						mismo.</p>
						<p>- Si el equipo después de 90 días de ingresado y no fue reparado será deschado y KEBCO S.A.S. no se hace responsable del	mismo.</p>
						<p>- Piezas por donde pase fluido o generen desgaste de funcionamiento no tienen garantía. Los desperfectos de fábrica en
						repuestos tienen un tiempo de garantía de un (1) año.</p>
						<p>- Si una vez entregado el equipo presenta desperfecto de fábrica en los repuestos reemplazados debe ser reportado en las
						próximas 48 horas, para poder tener efecto la garantía de mano de obra en el servicio técnico.</p>
						<p>- El horario de atención de Servicio Técnico es de lunes a viernes 7:30am a 5:30pm y sábados de 8:00am a 12:00m.</p>
					</div>
				</div>

				

				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12 mt-3">
							<h2 class="text-center">Aceptación de condiciones</h2>
						</div>
						<div class="col-md-5 text-center firmas <?php echo !empty($technicalServices["User"]["signature"]) ? "mt-5" : "" ?>">
							<?php if (!empty($technicalServices["User"]["signature"])): ?>
								<img src="<?php echo $this->Html->url('/img/users/'.$technicalServices["User"]["signature"]) ?>" class="img-fluid w-100" style="height: 130px;"> 
							<?php endif ?>
							<h2><?php echo $this->Utilities->find_name_lastname_adviser($technicalServices['TechnicalService']['user_id']); ?>, CC: <?php echo $technicalServices["User"]["identification"]?> </h2> 
							Elaborado por
						</div>
						<div class="col-md-2 text-center"></div>
						<div class="col-md-5 text-center firmas mt-5">
							<?php if (AuthComponent::user("id") && empty($technicalServices["TechnicalService"]["firma_cliente"]) && $technicalServices["TechnicalService"]["state"] == 0 && ($technicalServices["TechnicalService"]["prospective_users_id"]) == 0 ): ?>
								<a href="" class="btn btn-info" id="firmarOrden">
									Firmar orden
								</a>
							<?php endif ?>
							<?php if (true): ?>
							<?php if (!empty($technicalServices["TechnicalService"]["firma_cliente"])): ?>
								<img src="<?php echo $this->Html->url('/img/asistencias/imagenes/'.$technicalServices["TechnicalService"]["firma_cliente"]) ?>" class="img-fluid w-100" style="height: 130px;"> 
							<?php endif ?>	
							<h2 class="w-100 <?php echo !empty($technicalServices["TechnicalService"]["firma_cliente"]) ? 'pt-0 mb-2' : 'mt-4 ' ?>">
								Firma, quien entrega
							</h2>
							<?php if (!empty($technicalServices["TechnicalService"]["identification_entrega"])): ?>
								<span class="font-italic font21" style="font-family: 'La Belle Aurore rev=2';font-weight: 400;font-style: normal;font-stretch: normal;line-height: initial;"><?php echo $technicalServices["TechnicalService"]["identification_entrega"] ?> </span>
							<?php endif ?>
							<h2 class="w-100 <?php echo !empty($technicalServices["TechnicalService"]["identification_entrega"]) ? 'pt-0 mb-2' : 'mt-4 ' ?>">
								Número de cédula, quien entrega
							</h2>
							<?php if (!empty($technicalServices["TechnicalService"]["celular_entrega"])): ?>
								<span class="font-italic font21" style="font-family: 'La Belle Aurore rev=2';font-weight: 400;font-style: normal;font-stretch: normal;line-height: initial;"><?php echo $technicalServices["TechnicalService"]["celular_entrega"] ?> </span>
							<?php endif ?>
							<h2 class="w-100 <?php echo !empty($technicalServices["TechnicalService"]["celular_entrega"]) ? 'pt-0 mb-2' : 'mt-4 ' ?>">
								Número de celular, quien entrega
							</h2>
							<?php endif ?>
						</div>						
					</div>
				</div>

				<div>
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
				</div>		
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalListVitacora" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Listar bitácora de trabajo para este servicio</h5>
      </div>
      <div class="modal-body" id="cuerpoListBit">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalFirma" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Firmar Orden de servicio</h5>
      </div>
      <div class="modal-body" id="cuerpoFirma">

      	<div class="row">

      		<div class="col-md-12">
      			<div class="row">
      				<div class="col-md-12">
						 		<canvas id="draw-canvas" class="d-block mx-auto" width="1024" height="175">
						 			No tienes un buen navegador.
						 		</canvas>
						 	</div>
						 	<div class="col-md-12 text-center">
								<input type="button" class="btn btn-secondary" id="draw-submitBtn" value="Crear firma"></input>
								<input type="button" class="btn btn-secondary" id="draw-clearBtn" value="Borrar imagen"></input>
							</div>
      			</div>
      		</div>
      		<div class="col-md-9 d-block mx-auto">
      			<?php echo $this->Form->create('TechnicalService',array('data-parsley-validate'=>true,'id'=>'form_service_firma','enctype'=>'multipart/form-data')); ?>
      				<?php echo $this->Form->input('id',array('value' => $technicalServices['TechnicalService']['id'])); ?>
	      			<div class="row">
	      				
	      				<div class="col-md-12 p-3">
									<img id="draw-image" class="border w-100 text-danger" src="" alt="La firma es requerida!"/>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<?php echo $this->Form->input('firma_img',array('label' => 'Celular','placeholder' => 'Nombre de quien entrega',"type"=>"hidden","id"=>"draw-dataUrl",'class' => 'form-control','required' => true)); ?>
										</div>
										<div class="col-md-6">
											<?php echo $this->Form->input('identification_entrega',array('label' => 'Cédula','placeholder' => 'Nombre de quien entrega','data-parsley-type'=>"number",'class' => 'form-control','required' => true)); ?>
										</div>
										<div class="col-md-6">
											<?php echo $this->Form->input('celular_entrega',array('label' => 'Celular','placeholder' => 'Nombre de quien entrega','data-parsley-type'=>"number",'class' => 'form-control','required' => true)); ?>
										</div>
										<div class="col-md-12">
											<input type="submit" value="Guardar firma" class="btn btn-success btn-block">
										</div>
										
									</div>
								</div>

	      			</div>
      			<?php echo $this->Form->end(); ?>
      		</div>

					
				 	
				</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/technicalServices/index.js?".rand(),		array('block' => 'AppScript'));
?>

<?php echo $this->Html->script("printArea.js?".rand(),           array('block' => 'jqueryApp')); ?>
<?php echo $this->Html->script("firma_orden.js?".rand(),           array('block' => 'AppScript')); ?>
<script>
    $("#imprimeData").click(function(event) {
        window.print();
    });
</script>

<style>
	#draw-canvas {
  border: 2px dotted #CCCCCC;
  border-radius: 5px;
  cursor: crosshair;
}

</style>

<style media="print">
	.condiciones > p {
		font-size: 16px !important;
	}
	.condiciones {
		font-size: 16px !important;
	}
     .mCustomScrollbar,.widget-panel,.nav_menu,#imprimeData {
        display:none;
     }          
     .right_col{
     	background: #fff !important;
     }
     .right_col,.content-all{
     	padding: 1px !important;
     	margin: 0 !important;
     }
     .col-md-12{
     	padding: 10px !important;
     }
     body{
	  float: none !important;
	  width: auto !important;
	  margin:  0 !important;
	  padding: 0 !important;
	  font-weight: bold !important;
	  background-color: transparent !important;
	  padding: 0px !important;
	  background: #fff !important;
	}
	.osspecialrsp{
		width: 100% !important;
	}
	.container{
		margin: unset !important;
	}
	.body{
		background: #fff !important;
	}

	.centerimg{
		width: 100px !important;
		display: inline-block;
	}
	.centerimg > img{
		width: 100px !important;
	}
	.col-md-3{
		width: 100%;
	}
	.p-0 {
    padding: 0!important;
}
	h2.titulost{
		font-size: 20px !important;
		padding: 0px !important; 
	}
	.dataFull,#firmarOrden{
		display: none !important;
	}
	.data-textData{
		display: block !important;
	}
	.dataclientview {
		margin-top: -20px;
	}
	/*body { color: #000 !important|; font: 100%/150% Georgia, "Times New Roman", Times, serif; }*/

</style>    