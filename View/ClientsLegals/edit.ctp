<div class="clientsLegals form">
	<?php echo $this->Form->create('ClientsLegal'); ?>
		<?php
			echo $this->Form->input('name',array('label' => "Nombre",'placeholder' => 'Nombre','value' => $datos['ClientsLegal']['name']));
			echo $this->Form->input('nit',array('label' => "NIT",'placeholder' => 'NIT','value' => $datos['ClientsLegal']['nit']));
			echo $this->Form->input('parent_id',array('label' => "Grupo o empresa principal","empty"=>"Seleccionar","options"=>$legals,"value" => $datos['ClientsLegal']['parent_id']));
			echo $this->Form->hidden('client_id',array('value' => $datos['ClientsLegal']['id']));
			echo $this->Form->hidden('id',array('value' => $datos['ClientsLegal']['id']));
		?>
		<?php echo $this->Form->input('document', array("label" => "Selecciona o arrastra documento adicional informativo del cliente PDF", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify", "id" => "documentoForm1" )) ?>
		<?php echo $this->Form->input('document_2', array("label" => "Selecciona o arrastra documento adicional informativo del cliente imagen", "type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif pdf docx doc", "data-max-file-size" => "5M", "class" => "dropify", "id" => "documentoForm2" )) ?>
	</form>
</div>