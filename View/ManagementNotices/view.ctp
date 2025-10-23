<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<div class="managementNotices view blockwhite">
				<small class="themename">Aviso de gerencia</small>

				<p><b>Título: </b><?php echo $this->Utilities->data_null(h($managementNotice['ManagementNotice']['title'])); ?>&nbsp;</p>

				<p><b>Descripción: </b><?php echo $this->Utilities->data_null($managementNotice['ManagementNotice']['description']); ?>&nbsp;</p>

				<p><b>Precio: </b><?php echo $this->Utilities->data_null_numeros(number_format((int)h($managementNotice['ManagementNotice']['price']),0,",",".")); ?>&nbsp;</p>

				<p><b>Imagen: </b>
					<?php if ($managementNotice['ManagementNotice']['img'] != ''){ ?>
						<img src="<?php echo $this->Html->url('/img/managementNotices/'.$managementNotice['ManagementNotice']['img']) ?>" width="30px" height="22px" class="imgmin-product">
					<?php } else { ?>
						<?php echo $this->Utilities->data_null($managementNotice['ManagementNotice']['img']); ?>&nbsp;
					<?php } ?>
				</p>

				<p><b>Fecha inicio: </b><?php echo $this->Utilities->data_null($managementNotice['ManagementNotice']['fecha_ini']); ?>&nbsp;</p>

				<p><b>Fecha fin (Límite): </b><?php echo $this->Utilities->data_null($managementNotice['ManagementNotice']['fecha_fin']); ?>&nbsp;</p>

				<?php if ($datos['ManagementNotice']['state'] 	= Configure::read('variables.state_enabled')): ?>
					<button class="centerbtn btn btnSendReminder" data-date="<?php echo $managementNotice['ManagementNotice']['fecha_ini'] ?>" data-state="<?php echo $managementNotice['ManagementNotice']['state'] ?>">Enviar recordatorio</button>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/managementNotices/index.js?".rand(),			array('block' => 'AppScript'));
?>