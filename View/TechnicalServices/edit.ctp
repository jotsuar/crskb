<div class="container">
	<?php echo $this->Form->create('TechnicalService',array('data-parsley-validate'=>true,'id'=>'form_service','enctype'=>'multipart/form-data')); ?>
	<div class="linedata">
		<div class=" widget-panel widget-style-2 bg-rojo big">
         <i class="fa fa-1x flaticon-settings-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Servicio Técnico</h2>
	</div>
		<div class="technicalServices form blockwhite">
			<div class="row">
				<div class="col-md-12">
					<h2 class="text-center">Editar servicio técnico</h2>
				</div>
			</div>

			<h2>Datos del cliente</h2>
			<hr>
			<?php echo $this->Form->input('id',array('value' => $datosT['TechnicalService']['id'])); ?>
			<label>Selecciona el tipo de cliente:</label>
			<div class="form-check-inline">
				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="inlineRadioOptions" <?php echo $this->Utilities->checked_type_client_legal($datosT['TechnicalService']['contacs_users_id']); ?> id="inlineRadio1" value="Juridico"> Juridico
				</label>
			</div>
			<div class="form-check-inline">
				<label class="form-check-label">
					<input class="form-check-input" type="radio" name="inlineRadioOptions" <?php echo $this->Utilities->checked_type_client_natural($datosT['TechnicalService']['clients_natural_id']); ?> id="inlineRadio2" value="Natural">Natural
				</label>
			</div>

			<div class="formJuridico">
				<div class="row">
					<div class="col-md-11">
						<?php echo $this->Form->input('clients_legal_id',array('label' => 'Cliente juridico','options' => $clientsLegals,'value' => $datosT['TechnicalService']['clients_legal_id'])); ?>
					</div>
					<div class="col-md-1 text-center">
						<i class="fa fa-lg fa-plus" id="icon_add_legal_cliente"></i>
					</div>
				</div>
				<div class="selectContact"></div>
			</div>

			<div class="formNatural">
				<div class="row">
					<div class="col-md-11">
						<?php echo $this->Form->input('clients_natural_id',array('label' => 'Cliente natural','value' => $datosT['TechnicalService']['clients_natural_id'])); ?>
					</div>
					<div class="col-md-1 text-center">
						<i class="fa fa-lg fa-plus" id="icon_add_natural_cliente"></i>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?php echo $this->Form->input('wpp',array('label' => 'El cliente autoriza el envió de mensajes por whatsap en caso de retrazos por demoras','options' => ["1"=>"SI","0"=>"NO"] , "default"=> "0",'class' => 'form-control','required' => true, "value" => $datosT['TechnicalService']['wpp'])); ?>
				</div>
				<div class="col-md-12">
						<div class="form-group">
							<?php echo $this->Form->input('contact_address',array('label' => 'Está es la dirección a la cual retornaremos el equipo en caso de no retirarse de nuestras instalaciones en el tiempo pactado.','placeholder' => 'Direción de retorno','class' => 'form-control','required' => true)); ?>
						</div>
					</div>
			</div>
		</div>
	</div>
	<?php echo $this->Form->hidden('numero',array('value' => $equipos_ingresados_num)); ?>
	<?php $i = 1; foreach ($equipos_servicio as $value): ?>
	<div class="dataclientview">
		<h2>Equipo ingresado</h2>
		<div class="row">
				<div class="col-md-12">
					<a href="<?php echo $this->Html->url(["controller"=>"binnacles","action"=>"add",$datosT['TechnicalService']['id'] ]) ?>" class="btn btn-warning btn-sm btnAddBinnacle" ><i class="fa fa-plus vtc"></i> Añadir bitácora</a>

					<a href="<?php echo $this->Html->url(["controller"=>"binnacles","action"=>"index",$datosT['TechnicalService']['id'] ]) ?>" class="bg-blue btn btn-blue btn-sm float-right listBinnacle" ><i class="fa fa-list vtc"></i> Listar bitácora</a>

				</div>
			</div>
	</div>
	<div class="blockwhite spacetop">

		<div id="<?php echo 'equipo_'.$value['ProductTechnical']['id'] ?>">
			<div class="row">
				<div class="col-md-12">
					<?php echo $this->Form->input($i.'.equipment',array('value' => $value['ProductTechnical']['equipment'],'label' => 'Equipo','placeholder' => 'Equipo','required' => true)); ?>
				</div>
				<div class="col-md-12">
					<?php echo $this->Form->input($i.'.reason',array('value' => $value['ProductTechnical']['reason'],'label' => 'Motivo de ingreso','placeholder' => 'Motivo de ingreso','required' => true)); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input($i.'.brand',array('label' => 'Marca','options'=>$marca,'value' => $value['ProductTechnical']['brand'])); ?>
				</div>

				<div class="col-md-3">
					<?php echo $this->Form->input($i.'.part_number',array('value' => $value['ProductTechnical']['part_number'],'label' => 'Número de parte','placeholder' => 'Número de parte','required' => true)); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input($i.'.serial_number',array('value' => $value['ProductTechnical']['serial_number'],'label' => 'Número de serie','placeholder' => 'Número de serie','required' => true)); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input($i.'.serial_garantia',array('label' => 'Serial','placeholder' => 'Serial (Garantía)','class' => 'form-control', 'value' => $value['ProductTechnical']['serial_garantia'])); ?>
				</div>
			</div>
			
			<?php
			echo $this->Form->input($i.'.observations',array('type' => 'textarea','maxlength'=>'600','label' => "Observaciones",'placeholder' => 'Por favor escribe aquí como recibes el equipo','value' => $value['ProductTechnical']['observation'],'required' => true));
			echo $this->Form->input($i.'.possible_failures',array('type' => 'textarea','maxlength'=>'600','label' => "Posibles fallas del equipo",'placeholder' => 'Diagnostico inicial según comentarios del cliente','value' => $value['ProductTechnical']['possible_failures'],'required' => true));
			?>
			<label for="TechnicalServiceMaintenanceAccessories">Accesorios entregados con el equipo</label>
			<div class="accesorioslist row">
				<?php
				echo $this->Form->select($i.'.accessories',$accesorios_mantenimiento,array('multiple' => 'checkbox','class'=>'col-md-3','default' => $this->Utilities->find_check_equipo($value['ProductTechnical']['id'])));
				?>
			<div id="<?php echo 'input_otros_div_'.$i ?>">
				<?php echo $this->Form->input($i.'.otros_input',array('type' => 'text','class'=>'form-control','label' => 'Otros accesorios','value' => $value['ProductTechnical']['otros_input']));?>
			</div>
			</div>
			<?php
			echo $this->Form->input($i.'.maintenance_status',array('label' => 'Estado general del equipo','options'=>$estados_mantenimiento,'value' => $value['ProductTechnical']['maintenance_status']));
			?>
			<div class="row">
			<div class="col-md-12">
					<div class="row spaceimgst">
						<div class="col-md-4">
							<div class="imgservicecontent">
								<?php if ($value['ProductTechnical']['image1'] != ''){ ?>
									<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image1']) ?>)"> </div>
									<a href="javascript:void(0)" class="delete_image" data-uid="<?php echo $datosT['TechnicalService']['id'] ?>" data-image="1" data-texto="<?php echo $value['ProductTechnical']['image1'] ?>" data-product="<?php echo $value['ProductTechnical']['id'] ?>"></a>
								<?php } else { ?>
									<div class="contecntpd">
										<?php echo $this->Form->input($i.'.img1',array('type' => 'file','label' => "Primera imagen del servicio")); ?>
									</div>
								<?php } ?>
							</div>
						</div>

						<div class="col-md-4">
							<div class="imgservicecontent">
								<?php if ($value['ProductTechnical']['image2'] != ''){ ?>
									<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image2']) ?>)"> </div>
									<a href="javascript:void(0)" class="delete_image" data-uid="<?php echo $datosT['TechnicalService']['id'] ?>" data-image="2" data-texto="<?php echo $value['ProductTechnical']['image2'] ?>" data-product="<?php echo $value['ProductTechnical']['id'] ?>"></a>
								<?php } else { ?>
									<div class="contecntpd">
										<?php echo $this->Form->input($i.'.img2',array('type' => 'file','label' => "Segunda imagen del servicio")); ?>
									</div>
								<?php } ?>
							</div>
						</div>

						<div class="col-md-4">
							<div class="imgservicecontent">
								<?php if ($value['ProductTechnical']['image3'] != ''){ ?>
									<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image3']) ?>)"> </div>
									<a href="javascript:void(0)" class="delete_image" data-uid="<?php echo $datosT['TechnicalService']['id'] ?>" data-image="3" data-texto="<?php echo $value['ProductTechnical']['image3'] ?>" data-product="<?php echo $value['ProductTechnical']['id'] ?>" ></a>
								<?php } else { ?>
									<div class="contecntpd">
										<?php echo $this->Form->input($i.'.img3',array('type' => 'file','label' => "Tercera imagen del servicio")); ?>
									</div>
								<?php } ?>
							</div>
						</div>

						<div class="col-md-6">
							<div class="imgservicecontent">
								<?php if ($value['ProductTechnical']['image4'] != ''){ ?>
									<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image4']) ?>)"> </div>
									<a href="javascript:void(0)" class="delete_image" data-uid="<?php echo $datosT['TechnicalService']['id'] ?>" data-image="4" data-texto="<?php echo $value['ProductTechnical']['image4'] ?>" data-product="<?php echo $value['ProductTechnical']['id'] ?>" ></a>
								<?php } else { ?>
									<div class="contecntpd">
										<?php echo $this->Form->input($i.'.img4',array('type' => 'file','label' => "Cuarta imagen del servicio")); ?>
									</div>
								<?php } ?>
							</div>
						</div>

						<div class="col-md-6">
							<div class="imgservicecontent">
								<?php if ($value['ProductTechnical']['image5'] != ''){ ?>
									<div class="imgservice" style="background-image: url(<?php echo $this->Html->url('/img/servicioTecnico/'.$value['ProductTechnical']['image5']) ?>)"> </div>
									<a href="javascript:void(0)" class="delete_image" data-uid="<?php echo $datosT['TechnicalService']['id'] ?>" data-image="5" data-texto="<?php echo $value['ProductTechnical']['image5'] ?>" data-product="<?php echo $value['ProductTechnical']['id'] ?>" ></a>
								<?php } else { ?>
									<div class="contecntpd">
										<?php echo $this->Form->input($i.'.img5',array('type' => 'file','label' => "Quinta imagen del servicio")); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
			</div>
			</div>
		</div>
	</div>
	<?php $i = $i + 1; endforeach ?>

	<div class="submitcontent">
		<?php echo $this->Form->end('Actualizar'); ?>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalVitacora" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Crear bitácora para servicio técnico</h5>
      </div>
      <div class="modal-body" id="cuerpoBit">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/technicalServices/index.js?".rand(),		array('block' => 'AppScript'));
?>