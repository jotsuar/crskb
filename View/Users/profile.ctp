<div class="col-md-12">
	<div class="blockwhite mb15">
		<div class="d-flex align-items-center">
			<div class="img-responsive avatarprofile" style="background-image: url('<?php echo $this->Html->url('/img/users/'.AuthComponent::user('img')); ?>')"></div>
			<div class="main-user">
				<h2><?php echo AuthComponent::user('name'); ?> - <?php echo AuthComponent::user('role'); ?></h2>
				<ul class="submenus">
					<li class="nav-item">
						<a class="" href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>">Gestiones</a>
					</li>
					<li class="nav-item">
						<a class="" href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'adviser?filter=1')) ?>">Negocios</a>
					</li>
					<li class="nav-item">
						<a class="" href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'quotes_sent')) ?>">Cotizaciones</a>
					</li>  
					<li>
						<a href="javascript:void(0)" id="btn_password" title="Cambiar contraseña">Cambiar contraseña</a>
					</li>                      
				</ul>
			</div>
		</div>

	</div>
</div>

<div class="col-md-12">
	<div class="row">
		<div class="col-xl-4 col-lg-12">
			<div class="pdtb">
				<div class="users view blockwhite">
					<h2>Perfil de Usuario</h2>
					<br>
					<?php echo $this->Form->create('User',array('enctype'=>"multipart/form-data",'data-parsley-validate'=>true)); ?>
					<?php
					echo $this->Form->input('name',array('placeholder' => 'Nombre completo','label' => 'Nombre completo', 'value' => AuthComponent::user('name')));
					echo $this->Form->input('identification',array('placeholder' => 'Identificación','label' => 'Identificación','value' => AuthComponent::user('identification'), "required"));
					echo $this->Form->input('telephone',array('placeholder' => 'Teléfono','label' => 'Teléfono','value' => AuthComponent::user('telephone')));
					echo $this->Form->input('cell_phone',array('placeholder' => 'Celular','label' => 'Celular','value' => AuthComponent::user('cell_phone')));
					echo $this->Form->input('role',array('placeholder' => 'Rol','label' => 'Rol','value' => AuthComponent::user('role'),'disabled' => true));
					echo $this->Form->input('city',array('placeholder' => 'Ciudad','label' => 'Ciudad','autocomplete'=>'on','value' => AuthComponent::user('city')));
					echo $this->Form->input('img',array('type' => 'file','label' => 'Imagen de perfil','class' => 'dropify',"data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M"));
					echo $this->Form->input('signature',array('type' => 'file','label' => 'Firma para documentos','class' => 'dropify',"data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M"));
					echo $this->Form->input('email',array('placeholder' => 'Correo eléctronico','label' => 'Correo electrónico','value' => AuthComponent::user('email'),'disabled' => true));
					echo $this->Form->input('days_notify',array('label' => 'Frecuencia de notificación para el contacto con clientes luego de cotizado','placeholder' => 'Frecuencia de días','value' => AuthComponent::user('days_notify'), "options" => $optionsDays));

					if (AuthComponent::user("role") == "Asesor Externo") {
						echo $this->Form->input('password_email',array('type' => 'hidden'));
						echo $this->Form->input('account_number',array('placeholder' => 'Número de cuenta bancaria','label' => 'Número de cuenta bancaria','value' => AuthComponent::user('account_number')));
						echo $this->Form->input('bank',array('placeholder' => 'Nombre Banco','label' => 'Nombre Banco','value' => AuthComponent::user('bank')));
						echo $this->Form->input('account_type',array("options"=>["Ahorros"=>"Ahorros","Corriente"=>"Corriente"],'label' => 'Tipo de cuenta','value' => AuthComponent::user('account_type')));
					}else{
						echo $this->Form->input('password_email',array('placeholder' => 'Contraseña del email','label' => 'Contraseña del email','value' => str_replace("@@KEBCO@@", "", base64_decode(AuthComponent::user('password_email'))) ,"type" => empty(AuthComponent::user('password_email')) ? "password" : "hidden2","required" => true));

					}
					?>
					<br>
					<?php if (empty(AuthComponent::user("password_email")) && AuthComponent::user("role") != "Asesor Externo" ): ?>
					<?php else: ?>					
					<?php endif ?>
						<a href="#" class="btn btn-success" id="testConfiguration">Probar configuración</a>
						<?php echo $this->Form->end('Actualizar'); ?>
				</div>
			</div>
		</div>

		<div class="col-xl-8 col-lg-12">
			<div class="pdtb">
				<div class="blockwhite sizeheight">
					<h2 class="spacebottom">Actividad del usuario</h2>
					
					<ul class="nav nav-tabs" id="loguser" role="tablist">
					  <li class="nav-item">
					    <a class="nav-link active" id="loghoy" data-toggle="tab" href="#hoy" role="tab" aria-controls="hoy" aria-selected="true">Hoy</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link" id="ayer-tab" data-toggle="tab" href="#ayer" role="tab" aria-controls="ayer" aria-selected="false">Ayer</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link" id="antier-tab" data-toggle="tab" href="#antier" role="tab" aria-controls="antier" aria-selected="false">Antier</a>
					  </li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade show active" id="hoy" role="tabpanel" aria-labelledby="loghoy">
							<div class="col-md-12 statussesion">
								<div class="row">
									<div class="col-md-6 statusahora">
										<h2>Estado actual - <span> <?php echo $this->Utilities->find_state_conection(AuthComponent::user('id')); ?></span></h2>
									</div>
								</div>
							</div> 
							<div class="interacciones">
							<br>
							<div class="contenttableresponsive">
							<table cellpadding="0" cellspacing="0" class='activityusers table-striped table-bordered'>
								<thead>
									<tr>
										<th>#</th>
										<th>Acción</th>
										<th>Hora</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; foreach ($datosLogDay as $value): ?>
										<tr>
											<td><?php echo $i ?></td>
											<?php if ($value['Log']['state_flujo'] == ''){ ?>
												<td>
													<?php echo $this->Utilities->find_action_log_user($value['Log']['action'],$value['Log']['model']).' '.$this->Utilities->find_model_log_user($value['Log']['model'],$value['Log']['action']).''.$this->Utilities->find_data_id_logs($value['Log']['id_colum'],$value['Log']['model'],$value['Log']['action']) ?>
												</td>
											<?php } else { ?>
												<td>
													<?php echo $this->Utilities->find_action_log_user($value['Log']['action'],$value['Log']['model']).' : '.$value['Log']['state_flujo'].' - Requerimiento: '.$this->Utilities->flowsTage_id_find_prospective($value['Log']['id_colum']) ?>
												</td>
											<?php } ?>
											<td><?php echo $this->Utilities->explode_date_time_db($value['Log']['created']); ?></td>
										</tr>
									<?php $i = $i + 1; endforeach ?>
								</tbody>	
								</table>
								</div>
							</div>	
						</div>

						<div class="tab-pane fade" id="ayer" role="tabpanel">
							<br>
							<div class="contenttableresponsive">
							<table cellpadding="0" cellspacing="0" class='activityusers table-striped table-bordered'>
								<thead>
									<tr>
										<th>#</th>
										<th>Acción</th>
										<th>Hora</th>
									</tr>
								</thead>
								<tbody>
								<?php $i = 1; foreach ($datosLogYesterday as $value): ?>
									<tr>
										<td><?php echo $i ?></td>
										<?php if ($value['Log']['state_flujo'] == ''){ ?>
											<td>
												<?php echo $this->Utilities->find_action_log_user($value['Log']['action'],$value['Log']['model']).' '.$this->Utilities->find_model_log_user($value['Log']['model'],$value['Log']['action']).''.$this->Utilities->find_data_id_logs($value['Log']['id_colum'],$value['Log']['model'],$value['Log']['action']) ?>
											</td>
										<?php } else { ?>
											<td>
												<?php echo $this->Utilities->find_action_log_user($value['Log']['action'],$value['Log']['model']).' : '.$value['Log']['state_flujo'].' - Requerimiento: '.$this->Utilities->flowsTage_id_find_prospective($value['Log']['id_colum']) ?>
											</td>
										<?php } ?>
										<td><?php echo $this->Utilities->explode_date_time_db($value['Log']['created']); ?></td>
									</tr>
								<?php $i = $i + 1; endforeach ?>
								</tbody>
							</table>
						</div>
						</div>
						<div class="tab-pane fade" id="antier" role="tabpanel" aria-labelledby="antier-tab">
							<br>
							<div class="contenttableresponsive">
							<table cellpadding="0" cellspacing="0" class='activityusers table-striped table-bordered'>
								<thead>
									<tr>
										<th>#</th>
										<th>Acción</th>
										<th>Hora</th>
									</tr>
								</thead>
								<tbody>
								<?php $i = 1; foreach ($datosLogBeforeDayYesterday as $value): ?>
									<tr>
										<td><?php echo $i ?></td>
										<?php if ($value['Log']['state_flujo'] == ''){ ?>
											<td>
												<?php echo $this->Utilities->find_action_log_user($value['Log']['action'],$value['Log']['model']).' '.$this->Utilities->find_model_log_user($value['Log']['model'],$value['Log']['action']).''.$this->Utilities->find_data_id_logs($value['Log']['id_colum'],$value['Log']['model'],$value['Log']['action']) ?>
											</td>
										<?php } else { ?>
											<td>
												<?php echo $this->Utilities->find_action_log_user($value['Log']['action'],$value['Log']['model']).' : '.$value['Log']['state_flujo'].' - Requerimiento: '.$this->Utilities->flowsTage_id_find_prospective($value['Log']['id_colum']) ?>
											</td>
										<?php } ?>
										<td><?php echo $this->Utilities->explode_date_time_db($value['Log']['created']); ?></td>
									</tr>
								<?php $i = $i + 1; endforeach ?>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cambiarContrasenaModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h2>
			</div>
			<div class="modal-body">
				<form>
					<?php 
					echo $this->Form->input('Contraseña actual',array('type' => 'password','placeholder'  => 'Ingrese la contraseña actual de su cuenta','id' => 'actual'));
					echo $this->Form->input('Contraseña nueva',array('type' => 'password','placeholder'  => 'Ingrese su nueva contraseña','id' => 'nueva'));
					echo $this->Form->input('r_nueva',array('label' => 'Confirmar contraseña nueva','type' => 'password','placeholder'  => 'Confirme su nueva contraseña'));
					?>
				</form>
				<p id="validacion_texto">Todos los campos son requeridos</p>
			</div>
			<div class="modal-footer">
				<a class="cancelmodal" data-dismiss="modal">Cancelar</a>
				<a class="savedata" id="btn_cambiar">Guardar</a>
			</div>
		</div>
	</div>
</div>

<?php 
	echo $this->Html->script("controller/users/profile.js?".rand(), 						array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),							array('block' => 'jqueryApp'));
?>