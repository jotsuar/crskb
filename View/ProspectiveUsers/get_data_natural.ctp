<?php if (!empty($datos) ): ?>
<div class="row">
<div class="col-md-12 linedata">
	<h4><?php echo mb_strtoupper($datos['ClientsNatural']['name']) ?></h4>
	<h3>
		Datos del cliente
		<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar" class="btn_editar_natural" data-uid="<?php echo $datos['ClientsNatural']['id'] ?>" data-flujo="<?php echo $flujo_id ?>">
			<i class="fa fa-fw fa-pencil"></i>
		</a>

	</h3>
		<?php if (in_array($datosP["ProspectiveUser"]["state_flow"], [2,3,4,5,6,8]) && in_array(AuthComponent::user("role"),["Logística","Gerente General"])): ?>
		
			<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar" class="btn-sm btn-info btn-xs btn_reasignar_cliente" data-uid="<?php echo $datos['ClientsNatural']['id'] ?>" data-flujo="<?php echo $flujo_id ?>">
				Cambiar cliente <i class="fa fa-fw fa-recycle vtc"></i>
			</a>
		<?php endif ?>
			
			<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar" class="btn-sm btn-dark ml-1 btn-xs btn_change_juridico_cliente" data-uid="<?php echo $datos['ClientsNatural']['id'] ?>" data-flujo="<?php echo $flujo_id ?>">
				Cambiar cliente a jurídico <i class="fa fa-fw fa-compass vtc"></i>
			</a>
		<br>
		<br>
	
	<b>Teléfono: </b><?php echo $this->Utilities->data_null(h($datos['ClientsNatural']['telephone'])) ?><br>
	<b>Identificación: </b><?php echo h($datos['ClientsNatural']['identification']) ?>
	<?php if ($datos["ClientsNatural"]["validate"] == 1): ?>
		<span class="text-success"> 
			- Validada
		</span>	
	<?php else: ?>
		<span class="text-danger"> 
			- Sin validar
		</span>	
	<?php endif ?>
	<br>
	<b>Celular: </b><?php echo $this->Utilities->data_null(h($datos['ClientsNatural']['cell_phone'])) ?>
	<?php if ($datos['ClientsNatural']['cell_phone'] != ''): ?>
		<a href="<?php echo 'https://api.whatsapp.com/send?phone='.$this->Utilities->codigoPaisWhatsapp($datos['ClientsNatural']['city']).$datos["ClientsNatural"]["cell_phone"]?>" target="_blank" class="wp">
			<i class="fa fa-whatsapp"></i>
		</a>
	<?php endif ?>
	<br>
	<b>Correo electrónico: </b><?php echo $this->Utilities->data_null(h($datos['ClientsNatural']['email'])) ?>
	<a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=<?php echo $datos['ClientsNatural']['email'] ?>" target="_blank">
		<i class="fa fa-envelope-open"></i>
	</a>
	<br>
	<b>Ciudad: </b><?php echo $this->Utilities->data_null(h($datos['ClientsNatural']['city'])) ?><br>
	<b>Origen del cliente: </b><?php echo $this->Utilities->data_null(h($datosP['ProspectiveUser']['origin'])) ?><br>

	<?php if (!empty($datos["ClientsNatural"]["document"])): ?>
		<p>
			<b>Documento asociado (PDF)</b>
			<a href="<?php echo $this->Html->url("/files/clientes_documentos/".$datos["ClientsNatural"]["document"]); ?>" target="_blank" class="btn btn-info">
				<i class="fa fa-file-pdf-o vtc"></i>
			</a>
		</p>
	<?php endif ?>
	<?php if (!empty($datos["ClientsNatural"]["document_2"])): ?>
		<p>
			<b>Imagen asociada</b>
			<a href="<?php echo $this->Html->url("/img/clientes_documentos/".$datos["ClientsNatural"]["document_2"]); ?>" target="_blank" class="btn btn-info">
				<i class="fa fa-file-image-o vtc"></i>
			</a>
		</p>
	<?php endif ?>
	
</div>
</div>
<?php else: ?>
	<div class="text-center">
		<a href="#" class="btn btn-primary gestionFlujoEspecial align-middle mt-2" data-id="<?php echo $flujo_id  ?>">
			Gestionar cliente
		</a>
	</div>
	<div id="gestionCliente" class="pt-1 gestionCliente" style="display: none">
		<div class="col-md-6 margincenter">
			<div class="row">
				<div class="col-md-12">
					<div class="btn btn-secondary alignforlabel addNewCustomerProspectiveClienteEspecial" data-type="1" data-contacto="contact_<?php echo $datosP["ProspectiveUser"]["id"] ?>">	
						<i class="fa fa-x fa-plus-circle"></i> <span>Crear</span>
					</div>
					<?php echo $this->Form->input('clients_natural_id',array('label' => 'Selecionar Cliente',"required", "options" => [], "empty" => "Busca y selecciona un cliente", "id" => "flujoTiendaClienteEspecial","data-contacto" => "contact_".$datosP["ProspectiveUser"]["id"], "class" => "flujoTiendaClienteEspecial")); ?>
				</div>

				<div class="col-md-12">				
					<div class="selectContactEspecial" id="contact_<?php echo $datosP["ProspectiveUser"]["id"] ?>"></div>
				</div>
				<div class="col-md-12 mt-4">
					<a href="#" class="btn btn-success float-right gestionarCliente" id="gestionarCliente" data-flujo="<?php echo $datosP["ProspectiveUser"]["id"] ?>">Asignar cliente</a>
				</div>
			</div>
		</div>
	</div>
	
<?php endif; ?>