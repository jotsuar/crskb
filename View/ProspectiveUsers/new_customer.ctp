	<div id="gestionCliente" class="pt-1 gestionCliente newDataCustom">
		<div class="col-md-12 margincenter">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-2">
							<div class="btn btn-secondary alignforlabel addNewCustomerProspectiveClienteEspecial" data-type="1" data-contacto="contact_<?php echo $datosP["ProspectiveUser"]["id"] ?>">	
								<i class="fa fa-x fa-plus-circle"></i> <span>Crear</span>
							</div>
						</div>
						<div class="col-md-10">
							<?php echo $this->Form->input('clients_natural_id',array('label' => 'Selecionar Cliente',"required", "options" => $options, "empty" => "Busca y selecciona un cliente", "id" => "flujoTiendaClienteEspecial","data-contacto" => "change_contact_".$datosP["ProspectiveUser"]["id"], "class" => "flujoTiendaClienteEspecial","value" => $actual,"default" => $actual)); ?>
							<input type="hidden" id="hideActual" value="<?php echo is_null($datosP["ProspectiveUser"]["clients_natural_id"]) ? "LEGAL" : "NATURAL" ?>" data-contacto="<?php echo is_null($datosP["ProspectiveUser"]["clients_natural_id"]) ? $datosP["ContacsUser"]["id"] : "" ?>" >
						</div>
					</div>
				</div>

				<div class="col-md-12">				
					<div class="selectContactEspecial" id="change_contact_<?php echo $datosP["ProspectiveUser"]["id"] ?>"></div>
				</div>
				<div class="col-md-12 mt-4">
					<a href="#" class="btn btn-success float-right gestionarCliente" id="gestionarCliente" data-flujo="<?php echo $datosP["ProspectiveUser"]["id"] ?>">Asignar cliente</a>
				</div>
			</div>
		</div>
	</div>