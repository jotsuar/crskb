<div class="<?php echo 'divButton_'.$equipos_creados?> row"></div>
<div id="<?php echo 'equipo_'.$equipos_creados ?>" class="collapse">
<div class="blockwhite spacebtn20">
	<div class="form-row">
	    <div class="form-group col-md-4">
	    	<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.equipment',array('label' => 'Equipo','placeholder' => 'Equipo','class' => 'form-control','required' => true)); ?>
	    </div>
	    <div class="form-group col-md-4">
	    	<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.reason',array('label' => 'Motivo de ingreso','placeholder' => 'Motivo de ingreso','class' => 'form-control','required' => true)); ?>
	    </div>
	    <div class="form-group col-md-4">
			<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.brand',array('class' => 'form-control','label' => 'Marca','options'=>$marca)); ?>
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-4">
			<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.part_number',array('label' => 'Número de parte','placeholder' => 'Número de parte','class' => 'form-control','required' => true)); ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.serial_number',array('label' => 'Número de serie','placeholder' => 'Número de serie','class' => 'form-control','required' => true)); ?>
		</div>
		<div class="form-group col-md-4">
			<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.serial_garantia',array('label' => 'Serial','placeholder' => 'Serial (Garantía)','class' => 'form-control')); ?>
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-12">
			<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.observations',array('type' => 'textarea','maxlength'=>'400','label' => "Observaciones",'placeholder' => 'Por favor escribe aquí como recibes el equipo','required' => true));?>
		</div>
	</div>

	<div class="form-row">	
		<div class="form-group col-md-12">
			<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.possible_failures',array('type' => 'textarea','maxlength'=>'400','label' => "Posibles fallas del equipo",'placeholder' => 'Diagnostico inicial según comentarios del cliente','required' => true));?>
		</div>
	</div>

	<label for="TechnicalServiceMaintenanceAccessories">Accesorios entregados con el equipo</label>
	<div class="accesorioslist">
		<?php echo $this->Form->select('TechnicalService.'.$equipos_creados.'.accessories',$accesorios_mantenimiento,array('multiple' => 'checkbox'));?>
	</div>

	<div id="<?php echo 'input_otros_div_'.$equipos_creados ?>">
		<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.otros_input',array('type' => 'text','class'=>'form-control','label' => 'Ingresa los accesorios','placeholder' => 'Ingresa los accesorios'));?>
	</div>

	<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.maintenance_status',array('label' => 'Estado general del equipo','options'=>$estados_mantenimiento));?>

	<div class="dataequipoimg">
		<div class="form-row aqthis">
			<div class="form-group col-md-12">
				<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.img1',array('type' => 'file','label' => 'Imagen del producto')); ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.img2',array('type' => 'file','label' => 'Segunda imagen del producto')); ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.img3',array('type' => 'file','label' => 'Tercera imagen del producto')); ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.img4',array('type' => 'file','label' => 'Cuarta imagen del producto')); ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $this->Form->input('TechnicalService.'.$equipos_creados.'.img5',array('type' => 'file','label' => 'Quinta imagen del producto')); ?>
			</div>
		</div>
	</div>
</div>
</div>