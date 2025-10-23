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
				<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'externos')) ?>" class="btn btn-info">Ver asesores externos</a>
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
			<?php foreach ($users as $user): ?>
			<div class="col-xl-4 col-lg-6 col-md-6 mbbox">
				<div class="blockwhite text-center dataasesor">
					<div class="row">
						<div class="col-md-5">
							<div class="avatarasesor <?php echo $this->Utilities->state_conection($user['User']['state_conection']) ?>" style="background-image: url(/img/users/<?php echo h($user['User']['img']); ?>)"> </div>
						</div>
						<div class="col-md-7">
							<h5><?php echo h($user['User']['name']); ?></h5>
							<hr>
							<div class="actionsasesors">
								<?php if (AuthComponent::user("role") == "Gerente General"): ?>
									<a href="#" data-uid="<?php echo $user['User']['id'] ?>" class="assignUsers btn btn-primary border-0 p-0" data-toggle="tooltip" title="Asignar usuarios para revisar cotizaciones">
										<i class="fa vtc fa-fw fa-users text-info"></i>
						            </a>
						            <a href="#" data-uid="<?php echo $user['User']['id'] ?>" class="copyUsers btn btn-primary border-0 p-0" data-toggle="tooltip" title="Asignar usuarios en copia para cotizaciones">
										<i class="fa vtc fa-fw fa-copy text-info"></i>
						            </a>
								<?php endif ?>
								<?php echo $this->Utilities->paint_edit_model_modal($user['User']['id'],'asesor') ?>
								<a href="<?php echo $this->Html->url(array('action' => 'view', $this->Utilities->encryptString($user['User']['id']))) ?>" class="btn btn-primary border-0 p-0" data-toggle="tooltip" title="Ver usuario"><i class="fa fa-fw vtc fa-eye"></i>
					            </a>

								<?php echo $this->Utilities->paint_state_model($user['User']['state'],$user['User']['id']) ?>
								<?php $rolesPayments = array(Configure::read('variables.roles_usuarios.Contabilidad'),Configure::read('variables.roles_usuarios.Gerente General') );?>
					            <?php if(in_array(AuthComponent::user('role'), $rolesPayments)): ?>
					            	<a href="#" data-uid="<?php echo $user['User']['id'] ?>" class="commisionUser btn btn-primary border-0 p-0" data-toggle="tooltip" title="Configurar comisiones"><i class="fa vtc fa-fw fa-money text-warning"></i>
					            </a>
					            <?php endif ?>
								
							</div>
							<h6><?php echo h($user['User']['city']); ?>&nbsp;</h6>
							<p><?php echo h($user['User']['role']) ?></p>
							<h3><?php echo h($user['User']['email']); ?>&nbsp;</h3>
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
	echo $this->Html->script("controller/users/index.js?".rand(),					array('block' => 'AppScript'));
?>

<!-- Modal -->
<div class="modal fade " id="modalCommisions" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
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

<!-- Modal -->
<div class="modal fade " id="modalPermisos" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Configuración de usuarios asignados para revisar cotizaciones</h5>
      </div>
      <div class="modal-body" id="cuerpoPermisos">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCopias" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Configuración de usuarios que deben ir en copia al cotizar.</h5>
      </div>
      <div class="modal-body" id="cuerpoCopias">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>