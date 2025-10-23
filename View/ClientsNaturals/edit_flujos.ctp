<div class="clientsNaturals form">
	<?php echo $this->Form->create('ClientsNatural',array('data-parsley-validate'=>true)); ?>
		<?php
			echo $this->Form->input('id',array('value' => $clientsNaturals['ClientsNatural']['id']));
			echo $this->Form->hidden('flujo_id',array('value' => $flujo_id));
			echo $this->Form->hidden('action',array('value' => 'edit'));
			echo $this->Form->input('name',array('label' => 'Nombre','placeholder' => 'Ingresa el nombre del cliente','value' => isset($dataWo->Nombre) ? $dataWo->Nombre : $clientsNaturals['ClientsNatural']['name']));
			echo $this->Form->input('identification',array('label' => 'Identiciación', 'readonly' => in_array(AuthComponent::user("role"),["Logística","Gerente General"]) ,'placeholder' => 'Ingresa la identificación del cliente','value' => isset($dataWo->Identificacion) ? $dataWo->Identificacion : $clientsNaturals['ClientsNatural']['identification']));

			echo $this->Form->input('validate',array('label' => 'La Identificación es válida','placeholder' => 'Ingresa la Identificación del cliente.', "options" => ["0"=>"NO","1"=>"SI"], "default" => 0, "value" => $clientsNaturals["ClientsNatural"]["validate"] ));
			echo $this->Form->input('telephone',array('label' => 'Teléfono','placeholder' => 'Ingresa el teléfono del cliente','value' => isset($dataWo->Teléfonos) ? $dataWo->Teléfonos : $clientsNaturals['ClientsNatural']['telephone']));
			echo $this->Form->input('cell_phone',array('label' => 'Celular','placeholder' => 'Ingresa el celular del cliente','value' => isset($dataWo->Movil1) ? $dataWo->Movil1 : $clientsNaturals['ClientsNatural']['cell_phone'],'type' => 'number'));
			echo $this->Form->input('email',array('label' => 'Correo electrónico','placeholder' => 'Ingresa el correo electrónico del cliente','value' => isset($dataWo->EMail) && !empty($dataWo->EMail) ? $dataWo->EMail : $clientsNaturals['ClientsNatural']['email'],"type" => "email"));
		?>
		<?php echo $this->Form->input('document', array("label" => "Selecciona o arrastra documento adicional informativo del cliente PDF", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify", "id" => "documentoForm1" )) ?>
		<?php echo $this->Form->input('document_2', array("label" => "Selecciona o arrastra documento adicional informativo del cliente imagen", "type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif pdf docx doc", "data-max-file-size" => "5M", "class" => "dropify", "id" => "documentoForm2" )) ?>

		<div class="form-row">
			<div class="form-group col-md-11">
				<?php echo $this->Form->input('city',array('label' => 'Ciudad','placeholder' => 'Ciudad','readonly' => true,'value' => isset($dataWo->Ciudad) ? $dataWo->Ciudad.",".$dataWo->Departamento : $clientsNaturals['ClientsNatural']['city'] )); ?>
			</div>
			<div class="form-group col-md-1 text-center">
				<a class="btn_editar_ciudad" title="Editar ciudad"><i class="fa fa-1x fa-pencil"></i></a>
			</div>
		</div>
	</form>
</div>