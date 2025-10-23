<div class="col-md-12 pdspecial">	
	<div class="status0 blockwhite">
		<h2>Notificaciones sin leer obligatorias</h2>
		<div class="contentnotificationnew">
			<?php echo $this->Utilities->data_null_notifications_new($datosNueva); ?>
			<?php foreach ($datos as $value): ?>
				<?php if ($value['Manage']['state'] == 0 && $value["Manage"]["type"] == 1): ?>
					<div class="item-notification">
						<div class="col-md-12 p-0">
							<div class="row">
								<div class="col-md-9">
									<span class="text-success">
										<?php echo $this->Utilities->validate_state_notifications($value['Manage']['state']); ?>
									</span>
									<span class="float-right"> | <b>Lectura obligatoria</b>
										
									</span>

									<div class="d-inline text-body">
										<?php echo $value['Manage']['description']; ?>
										<b><?php echo mb_strtoupper($this->Utilities->name_prospective_contact($value['Manage']['prospective_users_id'])); ?></b>
									</div>
								</div>
								<div class="col-md-1">
									<?php if (!empty($value['Manage']['prospective_users_id'])): ?>
										<a href="<?php echo $this->Html->url(["controller"=>"prospective_users","action"=>"index","?"=>["q"=>$value['Manage']['prospective_users_id']]]) ?>" class="btn btn-sm btn-primary btn-block">Ver flujo <i class="fa vtc fa-eye"></i></a>
									<?php endif ?>
								</div>
								<div class="col-md-2 text-center">
									<a href="<?php echo Router::url("/",true) ?>manages" data-state="1" data-uid="<?php echo $value['Manage']['id']; ?>" class="btn btn-block btn-info btn-sm stateNotificacion">
										Marcar como leido <i class="fa vtc fa-check"></i>
									</a>
								</div>
							</div>
						</div>						
					</div>
				<?php endif ?>
			<?php endforeach ?>
		</div>


		<!--  -->


		<h2>Notificaciones sin leer normales</h2>
		<div class="contentnotificationnew">
			<a class="dropdown-item small clearn" href="#" id="notificaciones_leidas">Marcar todas como leidas </a>
			<?php echo $this->Utilities->data_null_notifications_new($datosNueva); ?>
			<?php foreach ($datos as $value): ?>
				<?php if ($value['Manage']['state'] == 0 && $value["Manage"]["type"] == 0): ?>
					<div class="item-notification">
						<a class="stateNotificacion" data-uid="<?php echo $value['Manage']['id']; ?>" data-state="1" href="<?php echo $value['Manage']['url'] ?>">
							<span class="text-success">
								<?php echo $this->Utilities->validate_state_notifications($value['Manage']['state']); ?>
							</span>
							<span class="float-right"> | <b>Limite:</b>
								<?php echo $this->Utilities->date_castellano($value['Manage']['date']).' - '.$value['Manage']['time']; ?>
							</span>

							<div class="d-inline text-body">
								<?php echo $value['Manage']['description']; ?>
								<b><?php echo mb_strtoupper($this->Utilities->name_prospective_contact($value['Manage']['prospective_users_id'])); ?></b>
							</div>
						</a>						
					</div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
	</div>

	<div class="status1 blockwhite">
		<h2>Notificaciones Leídas</h2>
		<div class="contentnotification">
		<?php echo $this->Utilities->data_null_notifications_read($datosLeida); ?>
		<?php foreach ($datos as $value): ?>
			<?php if ($value['Manage']['state'] == 1): ?>
				<div class="item-notification">
					<a href="<?php echo $value['Manage']['url'] ?>">
						<span class="text-view">
							<?php echo $this->Utilities->validate_state_notifications($value['Manage']['state']); ?>
						</span>
						<span class="float-right"> | <b>Limite:</b>
							<?php echo $this->Utilities->date_castellano($value['Manage']['date']).' - '.$this->Utilities->format_time_pm_am($value['Manage']['time_end']); ?>
						</span>

						<div class="d-inline">
							<?php echo $value['Manage']['description']; ?>
							<b><?php echo mb_strtoupper($this->Utilities->name_prospective_contact($value['Manage']['prospective_users_id'])); ?></b>
						</div>
					</a>
				</div>
			<?php endif ?>
		<?php endforeach ?>
		</div>
	</div>
</div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>