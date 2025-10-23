<div class="col-md-12">
	<div class="row">
		<div class="col-xl-3 col-lg-12">
			<div class="pdtb">
				<div class="users view">
						<div class="img-responsive avatarprofileview" style="background-image: url('<?php echo $this->Html->url('/img/users/'.$user['User']['img']); ?>')"></div>
						<div class="main-user">
							<h2 class="text-center"><?php echo $user['User']['name']; ?></h2>
							<h2 class="text-center2"><?php echo $user['User']['role'] ?></h2>
						</div>
						<?php if ($user["User"]["role"] == "Asesor Externo"): ?>
							<div class="actionsasesors text-center">
								
								<div class="d-block mx-auto">
									<?php echo $this->Utilities->paint_edit_model_modal($user['User']['id'],'asesor') ?>
									<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($user['User']['id']))) ?>" class="btn btn-primary border-0 p-0" data-toggle="tooltip" title="Ver usuario"><i class="fa fa-fw vtc fa-eye"></i>
						            </a>
					            
									<?php $rolesPayments = array(Configure::read('variables.roles_usuarios.Contabilidad'),Configure::read('variables.roles_usuarios.Gerente General') );?>
					            	<?php if(in_array(AuthComponent::user('role'), $rolesPayments)): ?>
					            		<a href="#" data-uid="<?php echo $user['User']['id'] ?>" class="commisionUser btn btn-primary border-0 p-0" data-toggle="tooltip" title="Configurar comisiones"><i class="fa vtc fa-fw fa-money text-warning"></i>
					            		</a>
					            	<?php endif ?>
						            <?php if ($user['User']['state'] == 2): ?>
						            	<a href="" class="btn btn-success aprobarUser" data-id="<?php echo $user["User"]["id"] ?>" data-toggle="tooltip" title="Aprobar usuario">
						            		<i class="fa fa-check vtc"></i>
						            	</a>
						            	<a href="" class="btn btn-danger rechazarUser" data-toggle="tooltip" title="Rechazar usuario" data-id="<?php echo $user["User"]["id"] ?>">
						            		<i class="fa fa-times vtc"></i>
						            	</a>
						            <?php endif ?>
						             <a href="<?php echo $this->Html->url(array('action' => 'categories', $this->Utilities->encryptString($user['User']['id']))) ?>" class="btn btn-warning btnConfigureCats" data-toggle="tooltip" data-user="<?php echo $user['User']['id'] ?>" title="Configurar categorias de productos"><i class="fa vtc fa fa-shopping-bag"></i>
					            	</a>
						            	<a href="<?php echo $this->Html->url(array('action' => 'clientes', $this->Utilities->encryptString($user['User']['id']))) ?>" class="btn btn-warning btnConfigCustomer" data-toggle="tooltip" data-user="<?php echo $user['User']['id'] ?>" title="Configurar clientes existentes"><i class="fa fa-users vtc"></i>
						            </a>
								</div>
								
							</div>
						<?php endif ?>
						<hr>
					<div class="dataprofileview">
						<p><b>Identificación: </b><?php echo $user['User']['identification'] ?></p>
						<p><b>Teléfono: </b><?php echo $user['User']['telephone'] ?></p>
						<p><b>Celular: </b><?php echo $user['User']['cell_phone'] ?></p>
						<p><b>Ciudad: </b><?php echo $user['User']['city'] ?></p>
						<p><b>Correo electrónico: </b><?php echo $user['User']['email'] ?></p>
						<?php if ($user["User"]["role"] == "Asesor Externo"): ?>
							<p>
								<b>Dirección: </b> <?php echo h($user['User']['address']); ?>&nbsp;
							</p>
							<p>
								<b>Estado actual: </b>
								<span class="text-warning">
									<?php 

									switch ($user['User']['state']) {
										case '0':
											echo "Inactivo";
											break;

										case '1':
											echo "Activo";
											break;

										case '2':
											echo "Por aprobar";
											break;

										case '3':
											echo "Rechazado";
											break;
										
										default:
											# code...
											break;
									}

								 ?></span>
							</p>
							<p>
								<b>Fecha de registro:</b> <?php echo $user["User"]["created"] ?>
							</p>
							<p>
								<b>Empresa donde trabaja: </b> <?php echo h($user["User"]["company"]) ?>
							</p>
							<p>
								<b>Cargo en la empresa donde trabaja: </b> <?php echo h($user["User"]["company_role"]) ?>
							</p>
							<p class="mb-3">
								<b>Foto de la cédula frontal:</b>
								<img dataimg="<?php echo $this->Html->url('/img/users/'.$user['User']['img_identification_up']) ?>" dataname="Cédula frontal <?php echo h($user['User']['name']); ?>" src="<?php echo $this->Html->url('/img/users/'.$user['User']['img_identification_up']) ?>" width="50px" height="45px" class="imgmin-product">
							</p>
							<p class="mb-3">
								<b>Foto de la cédula trasera:</b>
								<img dataimg="<?php echo $this->Html->url('/img/users/'.$user['User']['img_identification_down']) ?>" dataname="Cédula trasera <?php echo h($user['User']['name']); ?>" src="<?php echo $this->Html->url('/img/users/'.$user['User']['img_identification_down']) ?>" width="50px" height="45px" class="imgmin-product">
							</p>
							<p class="mb 3">
								<b>RUT:</b>
								<a href="<?php echo $this->Html->url('/files/users/'.$user['User']['rut']) ?>" target="_blank" class="btn btn-primary">
									Ver RUT
								</a>
							</p>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-9 col-lg-12">
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
										<h2>Estado actual - <span> <?php echo $this->Utilities->find_state_conection($user['User']['id']); ?></span></h2>
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
				<?php $rolesPayments = array(Configure::read('variables.roles_usuarios.Contabilidad'),Configure::read('variables.roles_usuarios.Gerente General') );?>
				<?php if (!is_null($comision) && !empty($comision) && in_array(AuthComponent::user('role'), $rolesPayments)): ?>
					
					<div class="blockwhite sizeheight mt-3">
						<h2 class="spacebottom">Información de comisiones <a href="#" data-uid="<?php echo $user['User']['id'] ?>" class="commisionUser btn-info btn" data-toggle="tooltip" title="Configurar comisiones"> Gestionar comisiones<i class="fa fa-fw fa-money text-warning"></i></a></h2>
					

						<div class="contenttableresponsive">
							<table cellpadding="0" cellspacing="0" class='activityusers table-striped table-bordered text-center'>
								<thead>
									<tr>
										<th>Rango inicial</th>
										<th>Rango final</th>
										<th>Comision %</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $comision["Commision"]["range_one_init"] ?></td>
										<td><?php echo $comision["Commision"]["range_one_end"] ?></td>
										<td><?php echo $comision["Commision"]["range_one_percentage"] ?></td>
									</tr>
									<tr>
										<td><?php echo $comision["Commision"]["range_two_init"] ?></td>
										<td><?php echo $comision["Commision"]["range_two_end"] ?></td>
										<td><?php echo $comision["Commision"]["range_two_percentage"] ?></td>
									</tr>
									<tr>
										<td><?php echo $comision["Commision"]["range_three_init"] ?></td>
										<td><?php echo $comision["Commision"]["range_three_end"] ?></td>
										<td><?php echo $comision["Commision"]["range_three_percentage"] ?></td>
									</tr>
									<tr>
										<td><?php echo $comision["Commision"]["range_four_init"] ?></td>
										<td><?php echo $comision["Commision"]["range_four_end"] ?></td>
										<td><?php echo $comision["Commision"]["range_four_percentage"] ?></td>
									</tr>
								</tbody>	
							</table>
						</div>	
					</div>	
							

				<?php endif ?>

			</div>
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->css('comboTreePlugin.css?');
	echo $this->Html->script(array('lib/comboTreePlugin.js?'.rand()),					array('block' => 'AppScript'));
	echo $this->Html->script("controller/users/index.js?".rand(),					array('block' => 'AppScript'));
?>

<!-- Modal -->
<div class="modal fade " id="modalCommisions" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Configuración de comisiones</h5>
      </div>
      <div class="modal-body" id="cuerpoComision">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalCategorias" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Configuración de categorías de productos</h5>
      </div>
      <div class="modal-body" id="cuerpoCategorias" style="    min-height: 320px;">
        <?php echo $this->Form->create('User',array('enctype'=>"multipart/form-data",'datas-parsleys-validates'=>true,"autocomplete"=>"off","id"=>"FormCategoriesUser")); ?>
        	<?php echo $this->Form->hidden('user_id',array('value' => "", "required" => false)); ?>
        	<?php echo $this->Form->input('categories',array('value' => "", "required" => false,"class"=>"form-control","label"=>"Categorías asignadas","div"=>false)); ?>
       	<?php echo $this->Form->end(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btnGuardaCategoria">Guardar Información</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalCategorias" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Configuración de categorías de productos</h5>
      </div>
      <div class="modal-body" id="cuerpoCategorias" style="    min-height: 320px;">
        <?php echo $this->Form->create('User',array('enctype'=>"multipart/form-data",'datas-parsleys-validates'=>true,"autocomplete"=>"off","id"=>"FormCategoriesUser")); ?>
        	<?php echo $this->Form->hidden('user_id',array('value' => "", "required" => false)); ?>
        	<?php echo $this->Form->input('categories',array('value' => "", "required" => false,"class"=>"form-control","label"=>"Categorías asignadas","div"=>false)); ?>
       	<?php echo $this->Form->end(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btnGuardaCategoria">Guardar Información</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>