<div class="clientsLegals form">
	<?php if (AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Gerente General')){ ?>
		<?php echo $this->Form->create('ManagementNotice',array('enctype'=>"multipart/form-data","id" => 'formuploadajax')); ?>
			<?php echo $this->Form->input('title',array('label' => false,'placeholder' => 'Título'));?>
			<?php echo $this->Form->input('description',array('label' => false,'placeholder' => 'Descripción'));?>
			<div class="form-row">
				<div class="col">
					<label>Fecha inicio</label>
					<?php echo $this->Form->date('fecha_ini',array('class' => 'form-control','value' => date("Y-m-d")));?>
				</div>
				<div class="col">
					<label>Fecha límite</label>
					<?php echo $this->Form->date('fecha_fin',array('class' => 'form-control','value' => date("Y-m-d")));?>
				</div>
			</div>
			<?php
				echo $this->Form->input('imge',array('type' => 'file','label' => 'Imagen'));
				echo $this->Form->input('price',array('label' => false,'placeholder' => 'Precio'));
			?>
		</form>
	<?php } else { ?>
		<h2>No cuentas con los suficientes permisos para crear una nota de gerencia</h2>
	<?php } ?>
</div>