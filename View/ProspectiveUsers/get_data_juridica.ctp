<h4><?php echo mb_strtoupper($datosEmpresa['ClientsLegal']['name']); ?></h4>
<div class="row">
	<div class="col-md-12 linedata">
		<b>NIT :</b> <?php echo $this->Utilities->data_null($datosEmpresa['ClientsLegal']['nit']); ?><br>
		<b>Contacto: </b> <?php echo $this->Utilities->name_client_contact($datos['ProspectiveUser']['contacs_users_id']); ?><br>
		<b>Origen:' </b><?php echo $this->Utilities->data_null(h($datos['ProspectiveUser']['origin'])); ?><br>

		<?php if (in_array($datos["ProspectiveUser"]["state_flow"], [2,3,4,5,6,8]) && in_array(AuthComponent::user("role"),["Logística","Gerente General"])): ?>
		
			<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar" class="btn-sm btn-info btn-xs btn_reasignar_cliente" data-uid="<?php echo $datosEmpresa['ClientsLegal']['id'] ?>" data-flujo="<?php echo $flujo_id ?>">
				Cambiar cliente <i class="fa fa-fw fa-recycle vtc"></i>
			</a>
			<br>
			<br>
		<?php endif ?>

		<?php if (!empty($datosEmpresa["ClientsLegal"]["document"])): ?>
			<p>
				<b>Documento asociado (PDF)</b>
				<a href="<?php echo $this->Html->url("/files/clientes_documentos/".$datosEmpresa["ClientsLegal"]["document"]); ?>" target="_blank" class="btn btn-info">
					<i class="fa fa-file-pdf-o vtc"></i>
				</a>
			</p>
		<?php endif ?>
		<?php if (!empty($datosEmpresa["ClientsLegal"]["document_2"])): ?>
			<p>
				<b>Imagen asociada</b>
				<a href="<?php echo $this->Html->url("/img/clientes_documentos/".$datosEmpresa["ClientsLegal"]["document_2"]); ?>" target="_blank" class="btn btn-info">
					<i class="fa fa-file-image-o vtc"></i>
				</a>
			</p>
		<?php endif ?>
		
	</div>
</div>
<div class="row">
<div class="col-md-12 linedata">
	<h3 class="colorazul">Contactos registrados</h3>
	<div id="accordion" class="usercontacts">
		<?php foreach ($datosEmpresa['ContacsUser'] as $value): ?>
			<?php if ($value["state"] == 0): ?>
				<?php continue; ?>
			<?php endif ?>
			<div class="card-contact">
				<div id="head<?php echo $value['id'] ?>">
					<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#qq<?php echo $value['id'] ?>" aria-expanded="false" aria-controls="<?php echo $value['id'] ?>">
						<span class="nametitle <?php echo $this->Utilities->identify_contact2($datos['ProspectiveUser']['contacs_users_id'],$value['id'],$value['name']); ?>">
							<?php echo $this->Utilities->identify_contact($datos['ProspectiveUser']['contacs_users_id'],$value['id'],$value['name']); ?>
						</span>
					</button>

					<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"): ?>
						<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar" class="btn_editar_contacto" data-uid="<?php echo $value['id'] ?>" data-flujo="<?php echo $flujo_id ?>">
							Editar <i class="fa fa-fw fa-pencil"></i>
						</a>
					<?php endif ?>
				</div>

				<div id="qq<?php echo $value['id'] ?>" class="collapse" aria-labelledby="head<?php echo $value['id'] ?>" data-parent="#accordion">
					<div class="card-body">
						<b>Teléfono: </b> <?php echo $this->Utilities->data_null(h($value['telephone'])) ?><br>
						<b>Celular: </b> <?php echo $this->Utilities->data_null(h($value['cell_phone'])) ?>
						<?php if ($value['cell_phone'] != ''): ?>
							<a href="<?php echo 'https://api.whatsapp.com/send?phone='.$this->Utilities->codigoPaisWhatsapp($value['city']).$value["cell_phone"]?>" target="_blank" class="wp"> 
								<i class="fa fa-whatsapp"></i>
							</a>
						<?php endif ?>
						<br>
						<b>Correo electrónico: </b> <?php echo $this->Utilities->data_null(h($value['email'])) ?>
						<a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=<?php echo $value['email'] ?>" target="_blank">
							<i class="fa fa-envelope-open"></i>
						</a>
						<br>
						<b>Ciudad: </b> <?php echo $this->Utilities->data_null(h($value['city'])) ?>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
</div>

<script>
	if ($(".nametitle").hasClass("show")) {
		$("#qq<?php echo $datos['ProspectiveUser']['contacs_users_id'] ?>").addClass("show");
    }
</script>