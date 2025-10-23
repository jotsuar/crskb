<?php if ($datos["ProspectiveUser"]["valid"] == 1): ?>
	<h2 class="text-center text-info">
		El flujo se encuentra en estado de validación para ser cancelado
	</h2>
<?php else: ?>
	

<div class="container">
	<div class="row bs-wizard modalbs">
		<div class="col-md-6 bs-wizard-step active">
			<div class="progress"><div class="progress-bar"></div></div>
			<span class="bs-wizard-dot state_asignado"></span>
			<div class="bs-wizard-info text-center">Asignado</div>
		</div>

		<div class="col-md-6 bs-wizard-step complete">
			<div class="progress"><div class="progress-bar"></div></div>
			<span class="bs-wizard-dot state_contactado" "></span>
			<div class="bs-wizard-info text-center">Contactado</div>
		</div>
	</div>

	<p>
		Estás actualizando el estado de atención del flujo del cliente <span class="nameclient"><?php echo mb_strtoupper($this->Utilities->name_prospective_contact($datos['ProspectiveUser']['id']))?></span>, por favor diligencia la siguiente información para continuar el proceso de venta
	</p>


	<?php echo $this->Form->create('FlowStage',array('id' => 'form_contactado',"type" => "file")); ?>
		<?php echo $this->Form->hidden('flujo_id',array('value' => $datos['ProspectiveUser']['id'])); ?>
		<div class="form-row">
		    <?php if ($datos['ProspectiveUser']['contacs_users_id'] > 0){ ?>
		    	<div class="form-group col-md-11">
			    	<?php echo $this->Form->input('name_users',array('label' => 'Nombre','options' => $users_contacs,'default' => $datos['ProspectiveUser']['contacs_users_id'])); ?>
			    </div>
			    <div class="form-group col-md-1 text-center plusadd">
			    	<i class="fa fa-lg fa-plus" id="icon_add" data-bussines="<?php echo $this->Utilities->id_empresa($datos['ProspectiveUser']['contacs_users_id']); ?>"></i>
			    </div>
		    <?php } else { ?>
		    	<div class="form-group col-md-12">
		    		<?php if (empty($datosC)): ?>
		    			<?php echo $this->Form->input('name',array('label' => 'Nombre','placeholder' => 'Nombre','value' => "Cliente no contactado", "readonly" )); ?>
		    		<?php else: ?>
			    		<?php echo $this->Form->input('name',array('label' => 'Nombre','placeholder' => 'Nombre','value' => $datosC['ClientsNatural']['name'])); ?>
		    		<?php endif ?>
			    </div>
		    <?php } ?>
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<?php echo $this->Form->input('reason',array('value' => $datosFlow['FlowStage']['reason'],'label' => 'Asunto o motivo del requerimiento','placeholder' => 'Breve descripción del requerimiento')); ?>
			</div>
			<div class="form-group col-md-6">
				<?php echo $this->Form->input('origin',array('label' => 'Medio de contacto:', 'options' => $origen)); ?>
			</div>
			<div class="form-group col-md-12">
				<?php echo $this->Form->input('image',array('label' => "Selecciona o arrastra la imagen de prueba del contacto con el cliente", 'type' => "file","class" => "form-control dropify", "data-allowed-file-extensions" => "png jpg jpeg gif", "data-max-file-size" => "5M")); ?>
			</div>
		</div>
		<div class="form-group controlheight">
			<?php echo $this->Form->input('description',array('value' => $datosFlow['FlowStage']['description'],'label' => 'Detalle de necesidad del cliente o razón de cancelación del flujo.','type' => 'textarea','rows'=>'3','placeholder' => 'Por favor detalla la solicitud y los requerimientos del cliente')); ?>
			<small class="text-danger">
				En caso de no enviar cotización detalla por favor muy bien la razón, ya que deberá ser aprobado por un administrador.
			</small>
		</div>
		
		<label class="cotiza">¿Enviarás cotización?</label>

		<div class="form-check-inline">
		  <label class="form-check-label">
		    <input class="form-check-input" <?php echo is_null($datos["ProspectiveUser"]["clients_natural_id"]) && $datos["ProspectiveUser"]["contacs_users_id"] == 0 ? "disabled title='No se ha gestionado el cliente'" : "" ?>  type="radio" name="data[FlowStage][inlineRadioOptions]" id="inlineRadio1" value="1"> SI
		  </label>
		</div>
		<div class="form-check-inline">
		  <label class="form-check-label">
		    <input class="form-check-input" <?php echo is_null($datos["ProspectiveUser"]["clients_natural_id"]) && $datos["ProspectiveUser"]["contacs_users_id"] == 0 ? "checked" : "" ?> type="radio" name="data[FlowStage][inlineRadioOptions]" id="inlineRadio2" value="0"> NO
		  </label>
		</div>	
		<div class="form-group controlheight mt-2">
			<?php echo $this->Form->input('description_no_contact',array('label' => '¿Porqué no continuarás el flujo?','type' => 'textarea','rows'=>'3','placeholder' => 'Por favor detalla el porqué no se seguirá el flujo y el contacto con el posible cliente')); ?>
		</div>
	</form>
</div>
<?php endif ?>