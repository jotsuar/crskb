<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-rojo big">
         <i class="fa fa-1x flaticon-settings-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Servicio Técnico</h2>
	</div>
	<div class="row">
		<div class="col-lg-12 allflujo">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<h2>Flujo de Negocios - Servicio técnico 
								<a href="<?php echo $this->Html->url(["action"=>"process"]) ?>" class="btn btn-info ml-2">
									Listar servicios en proceso
								</a>
							</h2>
						</div>
					</div>

					<div class="col-md-6">
						<div class="row numberpages">
			                <?php if (!isset($this->request->query['filter'])) { ?>
								<span class="finalc">
									<?php
										echo $this->Html->link('Finalizados <i class="fa fa-lg fa-check"></i>', 
										array('?' => array('filter' => Configure::read('variables.control_flujo.flujo_finalizado'))),array('escape' => false));
									?>
								</span>
							<?php } else { ?>
								<span class="cancelx">
									<a href="javascript:void(0)" id="btn_filtrosCero">Sin filtros <i class="fa fa-lg fa-times"></i></a>
								</span>
		                	<?php } ?>
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
			</div>

			<div class="row mt-2">
				<div class="col-md-4">

					<div class="input-group stylish-input-group p-1">
	                    <?php if (isset($this->request->query['q'])){ ?>
							<input type="text" id="txt_buscador" value="<?php echo $this->request->query['q']; ?>" class="form-control" placeholder="Buscador de flujos">
						<?php } else { ?>
							<input type="text" id="txt_buscador" class="form-control" placeholder="Buscador por número de flujo o código de servicio">
						<?php } ?>
		                <?php if (isset($this->request->query['q'])) { ?>
		                	<span class="input-group-addon btn_buscar border-right">
		                        <i class="fa fa-search"></i>
		                    </span>
		                <?php } else { ?>
		                	<span class="input-group-addon btn_buscar border-right">
		                        <i class="fa fa-search"></i>
		                    </span>
		                <?php } ?>
		                
						
	                </div>
				</div>
				<div class="col-md-2">
					<div class="dropdown text-right mr-2">
						<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
							Filtrar etapas
						</button>
						<div class="dropdown-menu datastates2">
							<?php
								$urlParams = array();
								$urlParams = $this->request->query;
								$etapa 	   = null;

								if (isset($urlParams["filterEtapa"]) && !empty($urlParams["filterEtapa"])) {
									echo $this->Form->hidden("filterEtapa",["value" => $urlParams["filterEtapa"], "id" => "filterEtapa" ]);
									$etapa = $urlParams["filterEtapa"];
								}
								
								$label 			= 'Flujos en proceso ';
								echo $this->Html->link($label, array(),array('class' => 'dropdown-item'));

								$classAdd 				= !empty($etapa) && $etapa == Configure::read('variables.control_flujo.flujo_asignado') ? "active" : "";
								$label 						= 'Asignado ';
								$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_asignado');
								echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item '.$classAdd));

								$classAdd 				= !empty($etapa) && $etapa == Configure::read('variables.control_flujo.flujo_contactado') ? "active" : "";
								$label 						= 'Contactado ';
								$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_contactado');
								echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item '.$classAdd));

								$classAdd 				= !empty($etapa) && $etapa == Configure::read('variables.control_flujo.flujo_cotizado') ? "active" : "";
								$label 						= 'Cotizado ';
								$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_cotizado');
								echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item '.$classAdd));

								$classAdd 				= !empty($etapa) && $etapa == Configure::read('variables.control_flujo.flujo_negociado') ? "active" : "";
								$label 						= 'Negociado ';
								$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_negociado');
								echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item '.$classAdd));

								$classAdd 				= !empty($etapa) && $etapa == Configure::read('variables.control_flujo.flujo_pagado') ? "active" : "";
								$label 						= 'Pagado ';
								$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_pagado');
								echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item '.$classAdd));

								$classAdd 				= !empty($etapa) && $etapa == 56 ? "active" : "";
								$label 						= 'Pagado sin gestionar despacho ';
								$urlParams['filterEtapa'] 	= 56;
								echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item '.$classAdd));

								$classAdd 				= !empty($etapa) && $etapa == Configure::read('variables.control_flujo.flujo_despachado') ? "active" : "";
								$label 						= 'Despachado ';
								$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_despachado');
								echo $this->Html->link($label, array('?' => $urlParams),array('class' => 'dropdown-item '.$classAdd));


							?>
							<div class="dropdown-divider"></div>
							<?php
								$classAdd 				  = !empty($etapa) && $etapa == Configure::read('variables.control_flujo.flujo_cancelado') ? "active" : "";
								$cancelado 					= 'Cancelado ';
								$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_cancelado');
								echo $this->Html->link($cancelado, array('action'=>'flujos','?' => $urlParams),array('class' => 'dropdown-item'));

								$classAdd 				  = !empty($etapa) && $etapa == Configure::read('variables.control_flujo.flujo_finalizado') ? "active" : "";
								$terminado 					= 'Terminados ';
								$urlParams['filterEtapa'] 	= Configure::read('variables.control_flujo.flujo_finalizado');
								echo $this->Html->link($terminado, array('action'=>'flujos','?' => $urlParams),array('class' => 'dropdown-item'));
							?>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="dropdown text-right">
						<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
							Filtrar asesores
						</button>
						<div class="dropdown-menu datastates2">
							<?php 
								$urlParams = array();
								$urlParams = $this->request->query;

								$usuario   = null;

								if (isset($urlParams["filterAsesores"]) && !empty($urlParams["filterAsesores"])) {
									echo $this->Form->hidden("filterAsesores",["value" => $urlParams["filterAsesores"], "id" => "filterAsesores" ]);
									$usuario = $urlParams["filterAsesores"];
								}

							?>
							<?php foreach ($usuarios_asesores as $key => $valueUsuarios){
								$urlParams["filterAsesores"] = $key;
								$classAdd 	= !empty($usuario) && $usuario == $key ? "active" : "";
								echo $this->Html->link($valueUsuarios, array('?' => $urlParams ),array('class' => 'dropdown-item '.$classAdd));
							} ?>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="d-inline ml-3">
						<label for="fechasInicioFin" class="d-inline">Fechas</label>
						<input type="text" id="fechasInicioFin" class="form-control d-inline fechaFiltroFlujos w-100">
					</div>
				</div>
				<div class="col-md-1">
					<?php if (!empty($this->request->query)): ?>							
						<div class="d-inline ml-1">
							<span class="btn btn-danger rounded deleteFilters mt-2" data-toggle="tooltip" data-placement="bottom" title="Eliminar filtros">
								<i class="fa fa-trash vtc"></i>
							</span>
						</div>
					<?php endif ?>
				</div>
			</div>

			<div class="prospectiveUsers index row">
				<div class="col-md-12">
					<?php if (empty($prospectiveUsers) && isset($this->request->query['q'])): ?>
						<h2 class="text-center">
							No existe ningún flujo de negocio que coincida con el parámetro de búsqueda
						</h2>
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
						<div class="registerprospective blockwhite control_flujo <?php echo $this->Utilities->validate_state_finish($prospectiveUser['ProspectiveUser']['state_flow']); ?>" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-type="<?php echo $prospectiveUser['ProspectiveUser']['contacs_users_id'] ?>">
							<div class="row">
								<div class="col-md-6">
									<h3 class="opendetails" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-type="<?php echo $prospectiveUser['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $prospectiveUser['ProspectiveUser']['id']; ?> -
										<?php echo mb_strtoupper($this->Utilities->name_prospective_contact($prospectiveUser['ProspectiveUser']['id'])); ?>&nbsp;
										<?php if (!is_null($prospectiveUser["ProspectiveUser"]["page"]) && !empty($prospectiveUser["ProspectiveUser"]["page"])): ?>
											<br>
											<span>
												<?php echo $prospectiveUser["ProspectiveUser"]["page"] ?>
											</span>
										<?php endif ?>
									</h3>
								</div>
								<div class="col-md-6 text-center text-lg-right text-md-right text-sm-center">
									<span class="dateasesor"><?php echo $this->Utilities->find_name_adviser($prospectiveUser['ProspectiveUser']['user_id']); ?> - </span>
									<span class="datecreated mr-2"><?php echo $this->Utilities->date_castellano($prospectiveUser['ProspectiveUser']['created']); ?></span>
									<span class="col-lg-5 col-md-5 col-sm-12 d-block float-lg-right float-md-right orderst text-center">ÓRDEN DE SERVICIO <b><?php echo $this->Utilities->consult_cod_service($prospectiveUser['ProspectiveUser']['type']) ?></b></span>
									<div class="btnemail">
										<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=>'correo?correo='.$email.'&fechaInicial='.$prospectiveUser["ProspectiveUser"]["created"].'&fechaFin='.$fechaFin)) ?>">
											<img src="<?php echo $this->Html->url('/img/assets/gmailicon.jpg'); ?>" class="img-gmailicon">
										</a>
									</div>
								</div>
							</div>
							
							<div class="row bs-wizard" >

								<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_contactado($prospectiveUser['ProspectiveUser']['state_flow']); ?>">
									<div class="progress"><div class="progress-bar"></div></div>
									<span class="bs-wizard-dot state_contactado" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>"></span>
									<div class="bs-wizard-info text-center">Contactado</div>
								</div>

								<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_cotizado($prospectiveUser['ProspectiveUser']['id'],$prospectiveUser['ProspectiveUser']['state_flow']); ?>">
									<div class="progress"><div class="progress-bar"></div></div>
									<span class="bs-wizard-dot state_cotizado" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>"></span>
									<div class="bs-wizard-info text-center"> Cotizado</div>
								</div>
								<div class="col-md-2 bs-wizard-step <?php echo $this->Utilities->validate_state_negociado($prospectiveUser['ProspectiveUser']['id'],$prospectiveUser['ProspectiveUser']['state_flow']); ?>">
									<div class="progress"><div class="progress-bar"></div></div>
									<span class="bs-wizard-dot state_negociado" data-id="<?php echo $this->Utilities->encryptString($prospectiveUser['ProspectiveUser']['id']) ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>"></span>
									<div class="bs-wizard-info text-center">Negociado</div>
								</div>                              
								<div class="col-md-3 bs-wizard-step <?php echo $this->Utilities->validate_state_pagado($prospectiveUser['ProspectiveUser']['id'],$prospectiveUser['ProspectiveUser']['state_flow']); ?>">
									<div class="progress"><div class="progress-bar"></div></div>
									<span class="bs-wizard-dot state_pagado" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>"></span>
									<div class="bs-wizard-info text-center">Pagado</div>    
								</div>
								<div class="col-md-3 bs-wizard-step <?php echo $this->Utilities->validate_state_despachado($prospectiveUser['ProspectiveUser']['id'],$prospectiveUser['ProspectiveUser']['state_flow']); ?>">
									<div class="progress"><div class="progress-bar"></div></div>
									<span class="bs-wizard-dot state_despachado" data-uid="<?php echo $prospectiveUser['ProspectiveUser']['id'] ?>" data-stateFlow="<?php echo $prospectiveUser['ProspectiveUser']['state_flow'] ?>" data-state="<?php echo $prospectiveUser['ProspectiveUser']['state'] ?>"></span>
									<div class="bs-wizard-info text-center">
										Despachado
										<?php echo $this->Utilities->check_state_prospective_despacho($prospectiveUser['ProspectiveUser']['state_flow']); ?>
									</div>
								</div>

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

		<div class="col-lg-5 allnovedad dnone">
			<div class="row pdtb novedadescontent">
				<div class="blockwhite w100">
					<div class="resultadoNovedades"><img src="<?php echo $this->Html->url('/img/preload.gif'); ?>" id="loadajax"></div>
				</div>
			</div>
			<div class="row pdtb resultadoscontent">
				<div class="blockwhite w100">
					<div class="resultadoDatos">
					<img src="<?php echo $this->Html->url('/img/preload.gif'); ?>" id="loadajax">
					</div>
				</div>
			</div>
		</div>

	</div>	
</div>
<div class="popup2">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
	<div class="contentpopup">
		<img src="" class="img-product" alt="">
	</div>
</div>
<div class="fondo"></div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript')); 
?>

<!-- Modal -->
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


<?php echo $this->element("modals_prospective") ?>

<?php echo $this->element("address"); ?>
<?php echo $this->Html->script("controller/prospectiveUsers/despacho.js?".rand(),			array('block' => 'AppScript'));  ?>

<?php echo $this->Html->script("controller/prospectiveUsers/recibos.js?".rand(),			array('block' => 'AppScript'));  ?>

<?php echo $this->element("picker"); ?>
 <?php echo $this->Html->script("controller/inventories/detail.js?".rand(),    array('block' => 'AppScript')); ?>
