<div class="container mb-4">
	<?php echo $this->Form->create('TechnicalService',array('data-parsley-validate'=>true,'id'=>'form_service','enctype'=>'multipart/form-data')); ?>
		<div class="linedata">
	<div class=" widget-panel widget-style-2 bg-rojo big">
         <i class="fa fa-1x flaticon-settings-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Servicio Técnico</h2>
	</div>
			<div class="technicalServices form blockwhite">
				<div class="row">
					<div class="col-md-12 ">
						<h2 class="text-center">REGISTRAR SERVICIO TÉCNICO</h2>
					</div>
				</div>

				<h2>Datos del cliente</h2>
				<hr>
				<label class="d-block">Selecciona el tipo de cliente:</label>
				<div class="form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="Juridico"> Juridico
					</label>
				</div>
				<div class="form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="Natural">Natural
					</label>
				</div>

				<div class="formJuridico">
					<div class="row">
						<div class="col-md-11 col-10">
							<?php echo $this->Form->input('clients_legal_id',array('label' => 'Cliente juridico','options' => $clientsLegals)); ?>
						</div>
						<div class="col-md-1 col-1 text-center">
							<i class="fa fa-lg fa-plus" id="icon_add_legal_cliente"></i>
						</div>
					</div>
					<div class="selectContact"></div>
				</div>

				<div class="formNatural">
					<div class="row">
						<div class="col-md-11 col-10">
							<?php echo $this->Form->input('clients_natural_id',array('label' => 'Cliente natural',"required" => true)); ?>
						</div>
						<div class="col-md-1 col-1 text-center">
							<i class="fa fa-lg fa-plus" id="icon_add_natural_cliente"></i>
						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-12">
						<hr>
						<h2>
							Datos de quien entrega la máquina 
							<a href="" class="btn btn-info btn-sm" id="btnContac">
								Los datos del cliente son la mismos<i class="fa fa-check vtc"></i>
							</a>  
						</h2>
						<hr>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?php echo $this->Form->input('1.contact_name',array('label' => 'Nombre de quien entrega','placeholder' => 'Nombre de quien entrega','class' => 'form-control','required' => true)); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?php echo $this->Form->input('1.contact_phone',array('label' => 'Teléfono de quien entrega','placeholder' => 'Teléfono de quien entrega','class' => 'form-control','required' => true)); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?php echo $this->Form->input('1.contact_identification',array('label' => 'Identificación de quien entrega','placeholder' => 'Identificación de quien entrega','class' => 'form-control','required' => true)); ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<?php echo $this->Form->input('1.contact_address',array('label' => 'Está es la dirección a la cual retornaremos el equipo en caso de no retirarse de nuestras instalaciones en el tiempo pactado.','placeholder' => 'Direción de retorno','class' => 'form-control','required' => true)); ?>
						</div>
					</div>
					<div class="col-md-6">
						<?php echo $this->Form->input('1.user_id',array('label' => 'Asesor asignado','options' => $usuarios_asesores, "default"=> AuthComponent::user("id"),'class' => 'form-control','required' => true)); ?>
					</div>
					<div class="col-md-6">
						<?php echo $this->Form->input('1.wpp',array('label' => 'El cliente autoriza el envió de mensajes por whatsap en caso de retrazos por demoras','options' => ["1"=>"SI","0"=>"NO"] , "default"=> "0",'class' => 'form-control','required' => true)); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="linedata">
			<!-- <div class="row">
				<div class="col-md-9">
					<h2>Equipos ingresados</h2>
				</div>
				<div class="col-md-3">
					<div class="text-right">
						<a href="#gobottom" id="agregar_new_service">Añadir otro equipo<i class="fa fa-1x fa-plus-square"></i></a>
					</div>
				</div>
			</div> -->

			<div class="dataequipo">
				<div class="rowproduct divButton_1">
					<div class="row">
						<div class="col-md-11 col-10">
							<!-- <a class="" data-toggle="collapse" data-target="#equipo_1">Equipo</a> -->
							Equipo
						</div>
						<div class="col-md-1 col-1 text-right">
							<!-- <a class="btn_delete_equipo" data-uid="1">
								<i class="fa fa-times fa-x"></i>
							</a> -->
						</div>
					</div>
				</div>
				<?php echo $this->Form->hidden('numero',array('value' => '1')); ?>
				<div id="equipo_1" class="blockwhite">
					<div class="form-row">
					    <div class="form-group col-md-12">
					    	<?php echo $this->Form->input('1.equipment',array('label' => 'Equipo','placeholder' => 'Equipo','class' => 'form-control','required' => true)); ?>
					    </div>
					    <div class="form-group col-md-12">
					    	<?php echo $this->Form->input('1.reason',array('label' => 'Motivo de ingreso','placeholder' => 'Motivo de ingreso','class' => 'form-control','required' => true)); ?>
					    </div>
					    <div class="form-group col-md-3">
							<?php echo $this->Form->input('1.brand',array('class' => 'form-control','label' => 'Marca','options'=>$marca)); ?>
						</div>
	
						<div class="form-group col-md-3">
							<?php echo $this->Form->input('1.part_number',array('label' => 'Número de parte','placeholder' => 'Número de parte','class' => 'form-control','required' => true)); ?>
						</div>
						<div class="form-group col-md-3">
							<?php echo $this->Form->input('1.serial_number',array('label' => 'Número de serie','placeholder' => 'Número de serie','class' => 'form-control','required' => true)); ?>
						</div>
						<div class="form-group col-md-3">
							<?php echo $this->Form->input('1.serial_garantia',array('label' => 'Serial','placeholder' => 'Serial (Garantía)','class' => 'form-control')); ?>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-md-12">
							<?php echo $this->Form->input('1.observations',array('type' => 'textarea','maxlength'=>'600','label' => "Observaciones",'placeholder' => 'Por favor escribe aquí como recibes el equipo','required' => true));?>
						</div>
					</div>

					<div class="form-row">	
						<div class="form-group col-md-12">
							<?php echo $this->Form->input('1.possible_failures',array('type' => 'textarea','maxlength'=>'600','label' => "Posibles fallas del equipo",'placeholder' => 'Diagnostico inicial según comentarios del cliente','required' => true));?>
						</div>
					</div>	

					
					<label for="TechnicalServiceMaintenanceAccessories">Accesorios entregados con el equipo 
						<a href="<?php echo $this->Html->url(["controller"=>"aditionals","action"=>"add",time()]) ?>" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-plus vtc"></i> Crear accesorio</a>
					</label>
					<div class="accesorioslist row">
						<?php echo $this->Form->select('1.accessories',$accesorios_mantenimiento,array('multiple' => 'checkbox', 'class' =>'col-md-3' ));?>
					</div>

					<div id="input_otros_div_1">
						<?php echo $this->Form->input('1.otros_input',array('type' => 'text','class'=>'form-control','label' => 'Ingresa los accesorios'));?>
					</div>

					<div class="row">
						<div class="col-md-6">
							<?php echo $this->Form->input('1.maintenance_status',array('label' => 'Estado general del equipo','options'=>$estados_mantenimiento));?>							
						</div>
						<div class="col-md-6">
							<div class="form-group">								
								<?php echo $this->Form->label('1.deadline','Fecha límite');?>	
								<?php echo $this->Form->text('1.deadline',array('label' => 'Estado general del equipo','class'=>'form-control','type'=>'date','min'=>date("Y-m-d"),'max'=>date("Y-m-d",strtotime("+2 month")),"required"));?>	
							</div>
							
						</div>
					</div>
					

					<div class="dataequipoimg">
						<div class="form-row aqthis">
							<div class="form-group col-md-11">
								<?php echo $this->Form->input('1.img1',array('type' => 'file',"data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify imagenesProducto",'label' => 'Imagen del producto (***Requerida para continuar***)','required')); ?>
							</div>
							<div class="form-group col-md-1 text-center">
								<i class="fa fa-lg fa-plus" id="icon_add_imagenes"></i>
							</div>
						</div>

						<div class="form-row">
							<div class="divImagenes col-md-12">
								<div class="form-row aqthis">
									<div class="col-md-12">										
										<?php echo $this->Form->input('1.img2',array('type' => 'file',"data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify imagenesProducto",'label' => 'Segunda imagen del producto',)); ?>
									</div>
								</div>
								<div class="form-row aqthis">
									<div class="col-md-12">
										<?php echo $this->Form->input('1.img3',array('type' => 'file',"data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify imagenesProducto",'label' => 'Tercera imagen del producto')); ?>
									</div>
								</div>
								<div class="form-row aqthis">
									<div class="col-md-12">
										<?php echo $this->Form->input('1.img4',array('type' => 'file',"data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify imagenesProducto",'label' => 'Cuarta imagen del producto')); ?>
									</div>
								</div>
								<div class="form-row aqthis">
									<div class="col-md-12">
										<?php echo $this->Form->input('1.img5',array('type' => 'file',"data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M", "class" => "dropify imagenesProducto",'label' => 'Quinta imagen del producto')); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-right">
			<a id="btn_borrador" class="btn">Guardar y crear un nuevo ST para el mismo cliente</a>
			<input type="submit" class="btn btn-primary" value="Guardar y listar">
			<?php echo $this->Form->end(); ?>
			</div>
		</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/technicalServices/index.js?".rand(),		array('block' => 'AppScript'));
?>

<style>
	#btnContac{
		display: none;
	}
</style>