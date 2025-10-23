<?php echo $this->Utilities->data_null_notifications_new($datos); ?>
<div class="status0 sizedrop">
	<?php foreach ($datos as $value): ?>
		<div class="item-notification-drop">
			<a class="stateNotificacion" data-uid="<?php echo $value['Manage']['id']; ?>" data-state="1" herf="<?php echo $value['Manage']['url'] ?>">
				<span class="text-success">
					<?php echo $this->Utilities->validate_state_notifications($value['Manage']['state']); ?>
				</span>
				<div class="aligntext">
					<?php echo $value['Manage']['description']; ?>
					<b><?php echo mb_strtoupper($this->Utilities->name_prospective_contact($value['Manage']['prospective_users_id'])); ?></b>
				</div>
				<span class="aligntext">
					<?php if ($value["Manage"]["type"] == 1): ?>
						<b>Lectura obligatoria</b>
					<?php else: ?>
						<b>Limite:</b>
						<?php echo $this->Utilities->date_castellano($value['Manage']['date']).' - '.$value['Manage']['time']; ?>
					<?php endif ?>
				</span>						
			</a>
		</div>
		<div class="dropdown-divider"></div>
	<?php endforeach ?>
</div>
<?php if (count($datos) > 0): ?>
	<a class="dropdown-item small clearn" href="#" id="notificaciones_leidas">Marcar todas como leidas <i class="fa fa-times arrowicon"></i> </a>
<?php endif ?>
<a class="dropdown-item small" href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'index')) ?>">Ver todas mis notificaciones <i class="fa fa-chevron-right arrowicon"></i> </a>