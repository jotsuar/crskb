<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="row">
		<div class="col-lg-12 allflujo">
			<div class="col-md-12 ">
				<div class="row">
					<div class="col-md-6 aligntitle">
						<div class="row">
							<h2>Flujos de Negocios</h2>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row numberpages">
							<?php
								echo $this->Paginator->first('<< ', array('class' => 'prev'), null);
								echo $this->Paginator->prev('< ', array(), null, array('class' => 'prev disabled'));
								echo $this->Paginator->counter(array('format' => '{:page} de {:pages}'));
								echo $this->Paginator->next(' >', array(), null, array('class' => 'next disabled'));
								echo $this->Paginator->last(' >>', array('class' => 'next'), null);
							?>
							<b> <?php echo $this->Paginator->counter(array('format' => '{:count} en total')); ?></b>
						</div>
					</div>
				</div>	
				<div class="row">			
					<nav class="navbar navbar-expand-lg navbar-light bg-filter p-1">
						<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>">Todos</a>
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'adviser')) ?>">Mis Flujos</a>
							<?php if (AuthComponent::user("role") == "Gerente General"): ?>
								<b><a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index_externals')) ?>">Flujos Externos</a></b>
							<?php endif ?>
						</div>

						<div class="linkstateflows inlinebar">
							<span class="validonot">
								<?php
									echo $this->Html->link('No válidos <i class="fa fa-window-close vtc"></i>', 
									array('?' => array('filter' => Configure::read('variables.control_flujo.flujo_no_valido'))),array('escape' => false));
								?>
							</span>

							<span class="cancelx">
								<?php
									echo $this->Html->link('Cancelados <i class="fa fa-lg fa-times"></i>', 
									array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_cancelado'))),array('escape' => false));
								?>
							</span>

							<span class="finalc">
								<?php
									echo $this->Html->link('Finalizados <i class="fa fa-lg fa-check"></i>', 
									array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_finalizado'))),array('escape' => false));
								?>
							</span>
						</div>

						<div class="dropdown text-right mr-2">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
								Filtrar etapas
							</button>
							<div class="dropdown-menu datastates">
								<?php
									$urlParams = array();
									$urlParams = $this->request->query;
									
									$label 			= 'Flujos en proceso '.$count_todo_habilitado;
									echo $this->Html->link($label, array(),array('class' => 'dropdown-item'));

									$label 						= 'Asignado '.$count_asignado;
									$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_asignado');
									echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item'));

									$label 						= 'Contactado '.$count_contactado;
									$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_contactado');
									echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item'));

									$label 						= 'Cotizado '.$count_cotizado;
									$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_cotizado');
									echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item'));

									$label 						= 'Negociado '.$count_negociado;
									$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_negociado');
									echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item'));

									$label 						= 'Pagado '.$count_pagado;
									$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_pagado');
									echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item'));

									$label 						= 'Pagado sin gestionar despacho '.$count_pagadoNoDes;
									$urlParams['filterEtapa'] 	= 56;
									echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item'));

									$label 						= 'Despachado '.$count_despachado;
									$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_despachado');
									echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item'));


								?>
								<div class="dropdown-divider"></div>
								<?php
									$cancelado 					= 'Cancelado '.$count_cancelado;
									$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_cancelado');
									echo $this->Html->link($cancelado, array('action'=>'index','?' => $urlParams),array('class' => 'dropdown-item'));

									$terminado 					= 'Terminados '.$count_terminados;
									$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_finalizado');
									echo $this->Html->link($terminado, array('action'=>'index','?' => $urlParams),array('class' => 'dropdown-item'));
								?>
							</div>
						</div>
						<div class="dropdown text-right">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
								Filtrar asesores
							</button>
							<div class="dropdown-menu datastates">
								<?php 
									$urlParams = array();
									$urlParams = $this->request->query;
								?>
								<?php foreach ($usuariosAsesores as $valueUsuarios){
									$urlParams["filterAsesores"] = $valueUsuarios['User']['id'];
									echo $this->Html->link($valueUsuarios['User']['name'], array('?' => $urlParams ),array('class' => 'dropdown-item'));
								} ?>
							</div>
						</div>
						<div class="d-inline ml-3">
							<label for="fechasInicioFin" class="d-inline">Fechas</label>
							<input type="text" id="fechasInicioFin" class="form-control d-inline fechaFiltroFlujos">
							<?php if (!empty($this->request->query)): ?>							
									<span class="btn btn-danger rounded deleteFilters" data-toggle="tooltip" data-placement="bottom" title="Eliminar filtros">
										<span class="solomobil">
											Remover filtro de fecha 
										</span>
										<i class="fa fa-trash vtc"></i>
									</span>
							<?php endif ?>
						</div>
					</nav>

				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
						<div class="linkstateflows blockbar">
							<span>
								Filtrar flujos <p class="soloweb">por estado</p>
							</span>
							<span class="validonot">
								<?php
									echo $this->Html->link('No válidos <i class="fa fa-window-close vtc"></i>', 
									array('?' => array('filter' => Configure::read('variables.control_flujo.flujo_no_valido'))),array('escape' => false));
								?>
							</span>

							<span class="cancelx">
								<?php
									echo $this->Html->link('Cancelados <i class="fa fa-lg fa-times"></i>', 
									array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_cancelado'))),array('escape' => false));
								?>
							</span>

							<span class="finalc">
								<?php
									echo $this->Html->link('Finalizados <i class="fa fa-lg fa-check"></i>', 
									array('?' => array('filterEtapa' => Configure::read('variables.control_flujo.flujo_finalizado'))),array('escape' => false));
								?>
							</span>
						</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="input-group stylish-input-group mb-2">
						<?php if (isset($this->request->query['q'])){ ?>
							<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador de flujos">
							<span class="input-group-addon btn_buscar">
		                        <i class="fa fa-search"></i>
		                    </span>
						<?php } else { ?>
							<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador de flujos">
							<span class="input-group-addon btn_buscar">
		                        <i class="fa fa-search"></i>
		                    </span>
						<?php } ?>
	                </div>
				</div>
			</div>


			<div class="prospectiveUsers index row">
				<div class="col-md-12">
					<?php if (empty($prospectiveUsers) && isset($this->request->query['q'])): ?>
						<h2 class="text-center">No existe ningún flujo de negocio que coincida con la búsqueda.</h2>
					<?php endif ?>
					<?php foreach ($prospectiveUsers as $prospectiveUser): ?>
						<?php
							$email 				= $this->Utilities->find_email_user($prospectiveUser['ProspectiveUser']['clients_natural_id'],$prospectiveUser['ProspectiveUser']['contacs_users_id']);
							$fechaFinValidate 	= $this->Utilities->find_date_state_finish($prospectiveUser['ProspectiveUser']['state_flow'],$prospectiveUser['ProspectiveUser']['modified']);
							$fechaFin 			= '';
							if ($fechaFinValidate != '') {
								$fechaFin = $fechaFinValidate;
							}
						?>
						<div class="registerprospective blockwhite control_flujo <?php echo $this->Utilities->validate_state_finish($prospectiveUser['ProspectiveUser']['state_flow']); ?> <?php echo count($prospectiveUsers) == 1 ? "activeflow" : ""  ?>" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-type="<?php echo $prospectiveUser['ProspectiveUser']['contacs_users_id'] ?>">
							
							<div class="row">
								<div class="col-md-7">
									<h3 class="opendetails" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-type="<?php echo $prospectiveUser['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $prospectiveUser['ProspectiveUser']['id']; ?> -
										<?php echo mb_strtoupper($this->Utilities->name_prospective_contact($prospectiveUser['ProspectiveUser']['id'])); ?>&nbsp;
										<span class="razonflow">
											- <?php echo $this->Utilities->find_reason_prospective($prospectiveUser['ProspectiveUser']['id']); ?>&nbsp; 

											<?php if ($prospectiveUser["ProspectiveUser"]["state_flow"] == 1 && is_null($prospectiveUser["ProspectiveUser"]["clients_natural_id"]) && $prospectiveUser["ProspectiveUser"]["contacs_users_id"] == 0 ): ?>
												<a href="" class="btn changeDescription btn-info" data-description="<?php echo addslashes($prospectiveUser["ProspectiveUser"]["description"]) ?>" data-flujo="<?php echo $prospectiveUser["ProspectiveUser"]["id"] ?>" data-toggle="tooltip" data-original-title="Modificar solicitud" style="padding: 2px !important;">
													<i class="fa fa-edit vtc"></i>
												</a>
											<?php endif ?>

										</span>
										<?php echo $this->Utilities->validate_existence_flow_notes($prospectiveUser['ProspectiveUser']['id']); ?>
										<?php if (!is_null($prospectiveUser["ProspectiveUser"]["page"]) && !empty($prospectiveUser["ProspectiveUser"]["page"])): ?>
											<br>
											<span>
												<?php echo $prospectiveUser["ProspectiveUser"]["page"] ?>
											</span>
										<?php endif ?>
									</h3>
								</div>
								<div class="col-md-5 text-right dataunique">
									<?php if ($prospectiveUser['ProspectiveUser']['user_receptor'] != 0): ?>
					
						<?php if ($prospectiveUser["ProspectiveUser"]["country"] !== "Colombia" && !is_null($prospectiveUser["ProspectiveUser"]["country"])): ?>
							<span class="internacional-tag">INTERNACIONAL /<small><?php echo $prospectiveUser["ProspectiveUser"]["country"] ?></small></span>

						<?php  else : ?>
							
						<?php endif; ?>


										<span class="dateasesor2">Asignó: <?php echo $this->Utilities->find_name_adviser($prospectiveUser['ProspectiveUser']['user_receptor']); ?> -> </span> 
									<?php endif ?>
									<span class="dateasesor">
										Gestiona: <?php echo $this->Utilities->find_name_adviser($prospectiveUser['ProspectiveUser']['user_id']); ?> - 
									</span>
									<span class="datecreated">
										<?php echo $this->Utilities->date_castellano($prospectiveUser['ProspectiveUser']['created']); ?>		
									</span>
									<div class="btnemail">
										<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'correo?correo='.$email.'&fechaInicial='.$prospectiveUser["ProspectiveUser"]["created"].'&fechaFin='.$fechaFin)) ?>">
											<img src="<?php echo $this->Html->url('/img/assets/gmailicon.jpg'); ?>" class="img-gmailicon">
										</a>
									</div>
									<?php if( AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Contabilidad') || AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Gerente General')  ): ?>
										<div class="linealign">
										    <i class="fa fa-ellipsis-v" id="assignedbtn" aria-hidden="true"></i>
										  	<div class="assigned">
										   		<?php echo $this->Form->input('asignado',array('label' => false, 'options' => $usuarios_asesores,'empty' => 'Asignar un nuevo asesor',"id" => "user_asignado_".$prospectiveUser['ProspectiveUser']['id'])); ?>
												<button class="btn_update_user" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>">Cambiar</button>
										 	 </div>
										</div>
									<?php endif ?>
									
								</div>
							</div>
							
							<div class="row bs-wizard" >
								<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_asignado($prospectiveUser['ProspectiveUser']['state_flow']); ?>">
									<div class="progress"><div class="progress-bar"></div></div>
									<span class="bs-wizard-dot state_asignado"></span>
									<div class="bs-wizard-info text-center">Asignado</div>
								</div>
								<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_contactado($prospectiveUser['ProspectiveUser']['state_flow']); ?>">
									<div class="progress"><div class="progress-bar"></div></div>
									<span class="bs-wizard-dot state_contactado" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>"></span>
									<div class="bs-wizard-info text-center">Contactado</div>
								</div>

								<?php if (!is_null($prospectiveUser["ProspectiveUser"]["clients_natural_id"]) || $prospectiveUser["ProspectiveUser"]["contacs_users_id"] > 0): ?>

									

									<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_cotizado($prospectiveUser['ProspectiveUser']['id'],$prospectiveUser['ProspectiveUser']['state_flow']); ?>">
										<div class="progress"><div class="progress-bar"></div></div>
										<span class="bs-wizard-dot state_cotizado" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>"></span>
										<div class="bs-wizard-info text-center"> Cotizado</div>
									</div>
									<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_negociado($prospectiveUser['ProspectiveUser']['id'],$prospectiveUser['ProspectiveUser']['state_flow']); ?>">
										<div class="progress"><div class="progress-bar"></div></div>
										<span class="bs-wizard-dot state_negociado" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>"></span>
										<div class="bs-wizard-info text-center">Negociado</div>
									</div>                              
									<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_pagado($prospectiveUser['ProspectiveUser']['id'],$prospectiveUser['ProspectiveUser']['state_flow']); ?>">
										<div class="progress"><div class="progress-bar"></div></div>
										<span class="bs-wizard-dot state_pagado" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>"></span>
										<div class="bs-wizard-info text-center">Pagado</div>    
									</div>

									<?php if (!empty($prospectiveUser['ProspectiveUser']['import_id'])): ?>
										<div class="col-md-1 bs-wizard-step ">
											<div class="progress"><div class="progress-bar"></div></div>
											<span class="bs-wizard-dot state_import" data-uid="<?php echo $this->Utilities->encryptString($prospectiveUser['ProspectiveUser']['import_id']) ?>" style="background-color: #004990"></span>
											<div class="bs-wizard-info text-center">
												<span class="soloweb"> Importación</span>
											</div>
										</div>
									<?php endif ?>


									<div class="<?php echo !empty($prospectiveUser['ProspectiveUser']['import_id']) ? "col-md-1" : "col-md-2" ?>  bs-wizard-step <?php echo $this->Utilities->validate_state_despachado($prospectiveUser['ProspectiveUser']['id'],$prospectiveUser['ProspectiveUser']['state_flow']); ?>">
										<div class="progress"><div class="progress-bar"></div></div>
										<span class="bs-wizard-dot state_despachado" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-stateFlow="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state'] ?>"></span>
										<div class="bs-wizard-info text-center">
											Despachado
											<?php echo $this->Utilities->check_state_prospective_despacho($prospectiveUser['ProspectiveUser']['state_flow']); ?>
										</div>
									</div>
								<?php endif ?>
							</div>
							<div class="row">
								<div class="col-lg-12 allnovedad_<?php echo $prospectiveUser['ProspectiveUser']['id'] ?> allnovedad dnone">
									<div class="row">
										<div class="col-md-8">
											<div class="novedadescontent ">
													<div class="resultadoNovedades_<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>"><img src="<?php echo $this->Html->url('/img/preload.gif'); ?>" id="loadajax"></div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="resultadoscontent ">												
												<div class="resultadoDatos_<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>">
													<img src="<?php echo $this->Html->url('/img/preload.gif'); ?>" id="loadajax">
												</div>
												<?php if (!empty($prospectiveUser["ProspectiveUser"]["image"])): ?>
													<h3 class="text-info text-center mt-2">
														Imagen de contacto con el cliente
													</h3>
													<img dataimg="<?php echo $this->Html->url('/img/flujo/imagenes/'.$prospectiveUser["ProspectiveUser"]["image"]); ?>" dataname="<?php echo h($prospectiveUser['ProspectiveUser']['description']); ?>" src="<?php echo $this->Html->url('/img/flujo/imagenes/'.$prospectiveUser["ProspectiveUser"]["image"]); ?>" class="img-fluid imgmin-product">
												<?php endif ?>
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
	</div>	
</div>
<div class="popup" style="width: 60%;">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript')); 
?>

<div class="modal fade " id="modalBillInformation" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Datos de factura de venta</h5>
      </div>
      <div class="modal-body" id="cuerpoBill">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="despachoDeProductos" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Gestión de despacho de productos</h5>
      </div>
      <div class="modal-body" id="cuerpoDespacho">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="cambiarDesc" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Cambiar descripción al flujo</h5>
      </div>
      <div class="modal-body" id="cuerpoDesc">
        <?php echo $this->Form->create('ProspectiveUser',array('data-parsley-validate')); ?>
        	<?php echo $this->Form->input('id',array('type' => 'hidden','placeholder' => 'Nombre completo','required' => true,"id" => "idToChange"));?>
        	<?php echo $this->Form->input('description',array('type' => 'textarea','label' => 'Descripción','required' => true, "id" => "descToChange"));?>
        	<input type="submit" value="Cambiar" class="btn btn-success btn-block mt-2">
   	 	</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade " id="recibodeCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Ingreso de información de recibo de caja</h5>
      </div>
      <div class="modal-body" id="cuerpoRecibo">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
	        Cerrar
	    </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalNewCustomerEspecial" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Gestionar nuevo cliente</h5>
      </div>
      <div class="modal-body" id="cuerpoCustomerEspecial">
        <div class="cuerpoContactoClienteModalEspecial"></div>
        <div id="ingresoClienteModalEspecial"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade " id="modalNewCustomerEspecialAsingacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Gestionar nuevo cliente para el flujo</h5>
      </div>
      <div class="modal-body" id="cuerpoReasigna">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade " id="modalChangeNatural" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalScrollableTitle">Cambiar cliente natural a jurídico</h5>
      </div>
      <div class="modal-body" id="cuerpoCambia">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<?php echo $this->element("address"); ?>
<?php echo $this->Html->script("controller/prospectiveUsers/despacho.js?".rand(),			array('block' => 'AppScript'));  ?>
<?php echo $this->Html->script("controller/prospectiveUsers/recibos.js?".rand(),			array('block' => 'AppScript'));  ?>
<?php echo $this->Html->script("controller/prospectiveUsers/especial_customer.js?".rand(),			array('block' => 'AppScript'));  ?>

<?php echo $this->element("picker"); ?>
 <?php echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); ?>
