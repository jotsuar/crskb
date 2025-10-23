<?php echo $this->html->css("multi-select.css"); ?>
<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azul big">
		<i class="fa fa-1x flaticon-settings"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Configuraciones </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-5">
				<h2 class="titleviewer">Usuarios de KEBCO S.A.S. creados en el CRM</h2>
			</div>
			<div class="col-md-7 text-md-right text-lg-right">
				<ul class="subpagos">
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>">Mi Agenda</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ManagementNotices','action'=>'index')) ?>">Avisos Públicos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>">Banners de Cotizaciones</a>
					</li>
					<li class="activesub">
						<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'index')) ?>">Gestión de Usuarios</a>
					</li>
				</ul>			
				<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'add')) ?>" class="registernewuser btn" style="vertical-align: top;"><i class="fa fa-1x fa-plus-square"></i> <span>Registrar Usuario</span></a>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12">
	<div class="users index">
		<div class="row">
			<?php if (empty($users)): ?>
				<div class="col-xl-12 col-lg-12 col-md-12 mbbox">
					<div class="blockwhite text-center dataasesor">
						<div class="row">
							<h2 class="text-info text-center">
								No hay usuaros externos registrados
							</h2>
						</div>
					</div>
				</div>
			<?php endif ?>
			<?php foreach ($users as $user): ?>
			<div class="col-xl-12 col-lg-12 col-md-12 mbbox">
				<div class="blockwhite text-center dataasesor">
					<div class="row">
						<div class="col-md-5">
							<div class="avatarasesor <?php echo $this->Utilities->state_conection($user['User']['state_conection']) ?>" style="background-image: url(<?php echo $this->Html->url('/img/users/'.$user['User']['img']); ?>)"> </div>
							<div class="actionsasesors">
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
						<div class="col-md-7">
							<h5 class="text-center"><?php echo h($user['User']['name']); ?></h5>
							<hr>
							<div class="row">
								<div class="col-md-6">
									<ul class="list-unstyled">
										<li>
											<b>Identificación: </b><?php echo $user['User']['identification'] ?>
										</li>
										<li>
											<b>Fecha de nacimiento:</b> <?php echo $user["User"]["date_born"] ?>
										</li>
										<li>
											<b>Ciudad: </b> <?php echo h($user['User']['city']); ?>&nbsp;
										</li>
										<li>
											<b>Dirección: </b> <?php echo h($user['User']['address']); ?>&nbsp;
										</li>
										
										<li>
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
										</li>
										<li>
											<b>Fecha de registro:</b> <?php echo $user["User"]["created"] ?>
										</li>
										<li>
											<b>Correo electrónico:</b> <?php echo h($user['User']['email']); ?>&nbsp;
										</li>
									</ul>
								</div>
								<div class="col-md-6">
									<ul class="list-unstyled">
										<li>
											<b>Empresa donde trabaja: </b> <?php echo h($user["User"]["company"]) ?>
										</li>
										<li>
											<b>Cargo en la empresa donde trabaja: </b> <?php echo h($user["User"]["company_role"]) ?>
										</li>
										<li>
											<b>Teléfono: </b> <?php echo h($user["User"]["telephone"]) ?>
										</li>
										<li>
											<b>Celular: </b> <?php echo h($user["User"]["cell_phone"]) ?>
										</li>
										<li class="mb-3">
											<b>Foto de la cédula frontal:</b>
											<img dataimg="<?php echo $this->Html->url('/img/users/'.$user['User']['img_identification_up']) ?>" dataname="Cédula frontal <?php echo h($user['User']['name']); ?>" src="<?php echo $this->Html->url('/img/users/'.$user['User']['img_identification_up']) ?>" width="50px" height="45px" class="imgmin-product">
										</li>
										<li class="mb-3">
											<b>Foto de la cédula trasera:</b>
											<img dataimg="<?php echo $this->Html->url('/img/users/'.$user['User']['img_identification_down']) ?>" dataname="Cédula trasera <?php echo h($user['User']['name']); ?>" src="<?php echo $this->Html->url('/img/users/'.$user['User']['img_identification_down']) ?>" width="50px" height="45px" class="imgmin-product">
										</li>
										<li class="mb-3">
											<b>RUT:</b>
											<a href="<?php echo $this->Html->url('/files/users/'.$user['User']['rut']) ?>" target="_blank" class="btn btn-primary">
												Ver RUT
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
			</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script(array('jquery.multi-select.js?'.rand()),					array('block' => 'AppScript'));
	echo $this->Html->css('https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css');
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
<div class="modal fade " id="modalClientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Configuración de clientes existentes</h5>
      </div>
      <div class="modal-body" id="cuerpoClientes">
        
      </div>
      <div class="modal-footer">
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

<!-- Agregar contacto, editar asesor y registrar la informacion de la entrega del pedido-->
<div class="modal fade" id="modal_form_editUser" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_form_label">
        	Editar usuario
        </h2>
      </div>
      <div class="modal-body" id="modalBodyEdit">
      </div>
      <br>
      <br>
      <br>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<style>
	.card-body {
    	padding: 10px 5px !important;
	}
	form .input {
    	margin-bottom: 1rem; 
	}
</style>

<div class="popup">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>