<div class="clientsNaturals form">
	<a href="#" class="regresarFormClient">
		<i class="fa fa-arrow-left"></i> Regresar	
	</a>
	<?php echo $this->Form->create('ClientsNatural', array("id" => !isset($this->request->data["flujo"]) ? "formCustomerNatural" : "formFlujoCustomerNatural")); ?>
		<legend class="text-center">
			Datos cliente natural 
			<?php if (isset($dataWo->Nombre)): ?>
				<br>
				<small class="text-danger">
					Datos traidos desde W.O.
				</small>
			<?php endif ?>
		</legend>
		<?php
			echo $this->Form->input('name',array('label' => 'Nombre *','placeholder' => 'Ingresa el nombre del cliente','required','value' => isset($dataWo->Nombre) ? $dataWo->Nombre : ""));
			echo $this->Form->input('identification',array('label' => 'Identificación','placeholder' => 'Ingresa la Identificación del cliente.', "value" => $idClient, !empty($idClient) ? "readonly" : "" ));
			echo $this->Form->input('validate',array('label' => 'La Identificación es válida','placeholder' => 'Ingresa la Identificación del cliente.', "options" => ["0"=>"NO","1"=>"SI"], "default" => 0 ));
			echo $this->Form->input('telephone',array('label' => 'Teléfono','placeholder' => 'Ingresa el teléfono del cliente','value' => isset($dataWo->Teléfonos) ? $dataWo->Teléfonos : ""));
			echo $this->Form->input('cell_phone',array('label' => 'Celular','placeholder' => 'Ingresa el celular del cliente','type' => 'number','value' => isset($dataWo->Movil1) ? $dataWo->Movil1 : ""));
			echo $this->Form->input('nacional',array('label' => false,'type' => 'hidden','value' => isset($this->request->data["nacional"]) ? $this->request->data["nacional"]: "1"));
			echo $this->Form->input('email',array('label' => 'Correo electrónico *','placeholder' => 'Ingresa el correo electrónico del cliente',"type" => "email",'required','value' => isset($dataWo->EMail) && !empty($dataWo->EMail) ? $dataWo->EMail : $emailClient, !empty($emailClient) || (isset($dataWo->EMail) && !empty($dataWo->EMail)) ? "readonly" : "" ));
			echo $this->Form->input('city',array('label' => 'Ciudad *','placeholder' => 'Ingresa la ciudad del cliente',"type" => "text",'required',"autocomplete"=>"off",'value' => isset($dataWo->Ciudad) ? $dataWo->Ciudad.",".$dataWo->Departamento : ""));
		?>
		<?php echo $this->Form->input('document', array("label" => "Ingresa el RUT", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify", "id" => "documentoForm1" )) ?>
		<?php echo $this->Form->input('document_2', array("label" => "Selecciona o arrastra documento adicional informativo del cliente imagen", "type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif pdf docx doc", "data-max-file-size" => "5M", "class" => "dropify", "id" => "documentoForm2" )) ?>
		<input type="submit" value="Guardar" class="btn btn-success float-right">
	</form>
</div>
