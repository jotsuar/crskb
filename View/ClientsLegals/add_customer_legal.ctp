<div class="clientsLegals form">
	<a href="#" class="regresarFormClient">
		<i class="fa fa-arrow-left"></i> Regresar	
	</a>
	<?php echo $this->Form->create('ClientsLegal', array("id" => !isset($this->request->data["flujo"]) ? "formCrearCustomerLegal" : "formFlujoCrearCustomerLegal")); ?>
		<div class="row">
			<div class="col-md-12">
				<legend class="text-center">
					Datos empresa
					<?php if (isset($dataWo->Nombre)): ?>
						<br>
						<small class="text-danger">
							Datos traidos desde W.O.
						</small>
					<?php endif ?>
				</legend>
				<?php echo $this->Form->input('nit',array('label' => "NIT",'placeholder' => 'NIT', 'required' ,"value" => $nit, "readonly")); ?>
				<?php echo $this->Form->input('name',array('label' => "Nombre",'placeholder' => 'Nombre','required','value' => isset($dataWo->Nombre) ? $dataWo->Nombre : "")); ?>
				<?php echo $this->Form->input('parent_id',array('label' => "Grupo o empresa principal","empty"=>"Seleccionar","options"=>$legals)); ?>
			</div>
			<hr>
			<div class="col-md-12">
				<legend>Datos contacto</legend>
				<?php echo $this->Form->input('ContacsUser.name',array('label' => "Nombre completo",'placeholder' => 'Nombre completo', 'required','value' => isset($dataWo->Nombre) ? "Contacto -".$dataWo->Nombre : "")); ?>
				<div class="form-row"> 
					<div class="col">
						<?php echo $this->Form->input('ContacsUser.cell_phone',array('label' => "Celular",'placeholder' => 'Celular','type'=>'number','value' => isset($dataWo->Teléfonos) ? $dataWo->Teléfonos : "")); ?>
					</div>
					<div class="col">
						<?php echo $this->Form->input('ContacsUser.telephone',array('label' => "Teléfono",'placeholder' => 'Teléfono','value' => isset($dataWo->Movil1) ? $dataWo->Movil1 : "")); ?>
					</div>
				</div>
				<div class="form-row"> 
					<div class="col">
						<?php echo $this->Form->input('ContacsUser.city',array('label' => "Ciudad",'autocomplete' => "off",'placeholder' => 'Ciudad', "required",'value' => isset($dataWo->Ciudad) ? $dataWo->Ciudad.",".$dataWo->Departamento : "")); ?>
						<?php echo $this->Form->input('nacional',array('label' => false,'type' => 'hidden','value' => isset($this->request->data["nacional"]) ? $this->request->data["nacional"] : 1)); ?>
					</div>
					<div class="col">
						<?php echo $this->Form->input('ContacsUser.email',array('label' => "Correo electrónico",'placeholder' => 'Correo electrónico', "required",'value' => isset($dataWo->EMail) && !empty($dataWo->EMail) ? $dataWo->EMail : "",)); ?>
					</div>	
				</div>	
				<div class="form-row">
					<div class="col">
						<?php echo $this->Form->input('document', array("label" => "RUT", "type" => "file", "data-allowed-file-extensions" => "pdf", "data-max-file-size" => "5M", "class" => "dropify", "id" => "documentoForm1","required" => AuthComponent::user("role") == "Asesor Externo" ? true: false  )) ?>
					</div>
				</div>
				<div class="form-row">
					<div class="col">
						<?php echo $this->Form->input('document_2', array("label" => "Selecciona o arrastra documento adicional informativo del cliente imagen", "type" => "file", "data-allowed-file-extensions" => "png jpg jpeg gif pdf docx doc", "data-max-file-size" => "5M", "class" => "dropify", "id" => "documentoForm2" )) ?>
					</div>
				</div>
				<input type="submit" value="Guardar" class="btn btn-success float-right">
				
				
			</div>
		</div>
	</form>
</div>