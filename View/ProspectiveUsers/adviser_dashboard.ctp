<?php if (isset($prospectos[0])) { ?>
	<?php $total = 1; ?>
	<?php foreach ($prospectos as $prospectiveUser): ?>
		<div class="registerprospective flujo_url blockwhite control_flujo <?php echo $this->Utilities->validate_state_finish($prospectiveUser['state_flow']); ?>" data-uid="<?php echo $prospectiveUser['id'] ?>" data-type="<?php echo $prospectiveUser['contacs_users_id'] ?>" <?php echo $total <= 5 ? 'style="
    background: #5bd82233;"' : '' ?> <?php echo $total <= 10 && $total > 5 ? 'style="
    background: #ffa50029;"' : '' ?> <?php echo $total > 10 ? 'style="
    background: #0000ff0f;"' : '' ?>  >
    		<?php $total++; ?>
			<div class="row">
				<div class="col-xl-9 col-lg-8 col-sm-7">
					<h3>

						<?php echo $prospectiveUser['id']; ?> -
						<?php echo mb_strtoupper($this->Utilities->name_prospective_contact($prospectiveUser['id'])); ?>&nbsp; <span class="razonflow">- <?php echo $this->Utilities->find_reason_prospective($prospectiveUser['id']); ?>&nbsp; </span>
						<div class="dropdown d-inline-block styledrop" style="width: 10% !important;">
						  <a class="btn btn-outline-primary dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($prospectiveUser['id']) ?>" data-toggle="dropdown" aria-expanded="false">
							Ver 								  
						  </a>

						  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($prospectiveUser['id']) ?>">
						    
						    <a class="dropdown-item" href="<?php echo $this->Html->url(array('quotations','action' => 'view', $this->Utilities->encryptString($prospectiveUser['cotizacion']))) ?>">
							    Ver cotización
							</a>

						    <a class="dropdown-item" href="<?php echo $this->Html->url(array("controller" => "prospective_users",'action' => 'index', "?" => ["q" => $prospectiveUser['id'] ])) ?>">
						    	Detalle de flujo
						    </a>
							
							<?php if (!empty($prospectiveUser["clients_natural_id"])): ?>								
							    <a class="dropdown-item" href="<?php echo $this->Html->url(array("controller" => "ClientsNaturals",'action' => 'view', $this->Utilities->encryptString($prospectiveUser['clients_natural_id']) )) ?>">
							    	Ver cliente
							    </a>

							<?php endif ?>

							<?php if (!empty($prospectiveUser["contacs_users_id"])): ?>								
							    <a class="dropdown-item" href="<?php echo $this->Html->url(array("controller" => "ClientsLegals",'action' => 'view', $this->Utilities->encryptString( $this->Utilities->id_empresa($prospectiveUser['contacs_users_id'])) )) ?>">
							    	Ver cliente
							    </a>
							    
							<?php endif ?>

						  </div>
					</div>
					</h3>
					<h1 class="text-success" style="font-size: 26px;">
						$<?php echo number_format($prospectiveUser["total"],2,".",",") ?> 
					</h1>
				</div>
				<div class="col-xl-3 col-lg-4 col-sm-5 text-right">
					<?php if ($prospectiveUser['user_receptor'] != 0): ?>
						<span class="datecreated">Asignó: <?php echo $this->Utilities->find_name_adviser($prospectiveUser['user_receptor']); ?> - </span>
					<?php endif ?>
					<span class="datecreated">
						<?php echo $this->Utilities->date_castellano($prospectiveUser['created']); ?>
					</span>
				</div>
			</div>
			<div class="row bs-wizard" >
				<div class="col-md-2 col-sm-2 bs-wizard-step <?php echo $this->Utilities->validate_state_asignado($prospectiveUser['state_flow']); ?>">
					<div class="progress"><div class="progress-bar"></div></div>
					<span class="bs-wizard-dot state_asignado"></span>
					<div class="bs-wizard-info text-center">Asignado</div>
				</div>
				<div class="col-md-2 col-sm-2 bs-wizard-step <?php echo $this->Utilities->validate_state_contactado($prospectiveUser['state_flow']); ?>">
					<div class="progress"><div class="progress-bar"></div></div>
					<span class="bs-wizard-dot state_contactado" data-uid="<?php echo $prospectiveUser['id'] ?>" data-state="<?php echo $prospectiveUser['state_flow'] ?>"></span>
					<div class="bs-wizard-info text-center">Contactado</div>
				</div>
				<div class="col-md-2 col-sm-2 bs-wizard-step <?php echo $this->Utilities->validate_state_cotizado($prospectiveUser['id'],$prospectiveUser['state_flow']); ?>">
					<div class="progress"><div class="progress-bar"></div></div>
					<span class="bs-wizard-dot state_cotizado" data-uid="<?php echo $prospectiveUser['id'] ?>" data-state="<?php echo $prospectiveUser['state_flow'] ?>"></span>
					<div class="bs-wizard-info text-center"> Cotizado</div>
				</div>
				<div class="col-md-2 col-sm-2 bs-wizard-step <?php echo $this->Utilities->validate_state_negociado($prospectiveUser['id'],$prospectiveUser['state_flow']); ?>">
					<div class="progress"><div class="progress-bar"></div></div>
					<span class="bs-wizard-dot state_negociado" data-uid="<?php echo $prospectiveUser['id'] ?>" data-state="<?php echo $prospectiveUser['state_flow'] ?>"></span>
					<div class="bs-wizard-info text-center">Negociado</div>
				</div>                              
				<div class="col-md-2 col-sm-2 bs-wizard-step <?php echo $this->Utilities->validate_state_pagado($prospectiveUser['id'],$prospectiveUser['state_flow']); ?>">
					<div class="progress"><div class="progress-bar"></div></div>
					<span class="bs-wizard-dot state_pagado" data-uid="<?php echo $prospectiveUser['id'] ?>" data-state="<?php echo $prospectiveUser['state_flow'] ?>"></span>
					<div class="bs-wizard-info text-center">Pagado</div>    
				</div>
				<?php if (!empty($prospectiveUser['import_id'])): ?>
					<div class="col-md-1 bs-wizard-step ">
						<div class="progress"><div class="progress-bar"></div></div>
						<span class="bs-wizard-dot state_import" data-uid="<?php echo $this->Utilities->encryptString($prospectiveUser['import_id']) ?>" style="background-color: #004990"></span>
						<div class="bs-wizard-info text-center">
							Importación
						</div>
					</div>
				<?php endif ?>
				<div class="<?php echo !empty($prospectiveUser['import_id']) ? "col-md-1 col-sm-1" : "col-md-2 col-sm-2" ?> bs-wizard-step <?php echo $this->Utilities->validate_state_despachado($prospectiveUser['id'],$prospectiveUser['state_flow']); ?>">
					<div class="progress"><div class="progress-bar"></div></div>
					<span class="bs-wizard-dot state_despachado" data-uid="<?php echo $prospectiveUser['id'] ?>" data-state="<?php echo $prospectiveUser['state_flow'] ?>"></span>
					<div class="bs-wizard-info text-center">
						Despachado
						<?php echo $this->Utilities->check_state_prospective_despacho($prospectiveUser['state_flow']); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-8"></div>
				<div class="col-lg-4"></div>
			</div>
		</div>
	<?php endforeach; ?>
<?php } else { ?>
	No tienes flujo en ningún proceso
<?php } ?>
			
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),								array('block' => 'jqueryApp'));
    echo $this->Html->script(array('bootstrap.bundle.js'),			array('block' => 'AppScript'));
    echo $this->Html->script(array('controller/prospectiveUsers/adviser_dashboard.js'),			array('block' => 'AppScript'));
?>

<?php echo $this->Html->css(array('media.css','bootstrap.css'), array('block' => 'AppCss'));?>