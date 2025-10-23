<?php $partsNit = explode(" ", str_replace("-", " ", $clientsLegal["ClientsLegal"]["nit"]) ); ?>
<div class="prospectiveUsers view">
	<div class="container-fluid p-0">
			<div class=" widget-panel widget-style-2 bg-azulclaro big">
             <i class="fa fa-1x flaticon-growth"></i>
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>
		<div class="row">
			<div class="col-xl-8 col-lg-12">
				<div class="row">
					<div class="col-md-12 ">
						<div class="datajuridica blockwhite">
							<div class="row">
								<div class="col-md-5">
									<h2 class="d-inline">Persona Jurídica</h2>
									<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar cliente" class="btn_editar" data-uid="<?php echo $clientsLegal['ClientsLegal']['id'] ?>">
									 	<i class="fa fa-fw fa-pencil"></i>
									</a>

									<div class="datajuridico">
										<p>
											<b>Nombre: </b>
											<?php echo $this->Utilities->data_null(h($clientsLegal['ClientsLegal']['name'])); ?>
										</p>
										<p>
											<b>NIT: </b>
											<?php echo $this->Utilities->data_null(h($clientsLegal['ClientsLegal']['nit'])); ?>
										</p>

										<?php if (!empty($clientsLegal["ClientsLegal"]["parent_id"])): ?>
											<b>EMPRESA O GRUPO PRINCIPAL: </b>
											<?php echo $this->Utilities->data_null(h($clientsLegal['Parent']['nit'])); ?> | <?php echo $this->Utilities->data_null(h($clientsLegal['Parent']['name'])); ?>
											
										<?php endif ?>
										<p>
											<b>Fecha de registro: </b>
											<?php echo $this->Utilities->date_castellano($clientsLegal['ClientsLegal']['created']); ?>
										</p>
										<?php if ($clientsLegal['ClientsLegal']['user_receptor'] != 0): ?>
											<p>
												<b>Usuario que realizó el registro:</b>
												<?php echo $this->Utilities->find_name_lastname_adviser(h($clientsLegal['ClientsLegal']['user_receptor'])); ?>
											</p>
										<?php endif ?>
										<?php if (!empty($clientsLegal["ClientsLegal"]["document"])): ?>
											<p>
												<b>Documento asociado (PDF)</b>
												<a href="<?php echo $this->Html->url("/files/clientes_documentos/".$clientsLegal["ClientsLegal"]["document"]); ?>" target="_blank" class="btn btn-info">
													<i class="fa fa-file-pdf-o vtc"></i>
												</a>
											</p>
										<?php endif ?>
										<?php if (!empty($clientsLegal["ClientsLegal"]["document_2"])): ?>
											<p>
												<b>Imagen asociada</b>
												<a href="<?php echo $this->Html->url("/img/clientes_documentos/".$clientsLegal["ClientsLegal"]["document_2"]); ?>" target="_blank" class="btn btn-info">
													<i class="fa fa-file-image-o vtc"></i>
												</a>
											</p>
										<?php endif ?>

									</div>
								</div>
								<div class="col-md-7">
									<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística" || AuthComponent::user("email") == 'ventas2@almacendelpintor.com'): ?>
										<h2>
											Eliminar cliente duplicado <span><i class="fa fa-fw fa-trash"></i></span>
										</h2>

										<?php echo $this->Form->create('ClientsLegal',array('data-parsley-validate'=>true)); ?>
											<?php echo $this->Form->hidden("delete_id", ["value" => $clientsLegal["ClientsLegal"]["id"] ] ) ?>
											<?php echo $this->Form->input('other_id',array('empty'=>"Seleccionar","options" => $otherClients, "label" => "Seleccione el cliente con que será replazado", "required" )); ?>
										<?php echo $this->Form->end(__('Eliminar cliente')); ?>

									<?php endif ?>
								</div>
								<div class="col-md-12">
									<div class="table-responsive">
										<?php if (empty($dataCupo)): ?>
											<h2 class="text-center text-warning mt-4">
												No se encontró información sobre cupo de crédito

												<a href="" data-nit="<?php echo $partsNit[0] ?>" class="btn btn-warning verifyWo">
													Verificar cupo de credito en WO directamente
												</a>
											</h2>
										<?php else: ?>
											<table class="table table-hovered mt-4">
												<tr>
													<th>
														Cupo crédito: 
													</th>
													<td>
														<?php echo number_format(floatval($dataCupo->CupoCredito),2,",",".") ?>
													</td>
												</tr>
												<tr>
													<th>
														Saldo o deuda actual: 
													</th>
													<td>
														<?php echo number_format(floatval($dataCupo->Saldo),2,",",".") ?>
													</td>
												</tr>
												<tr>
													<th>
														Cupo final (Cupo crédito - Saldo)
													</th>
													<td>
														<?php echo number_format(floatval($dataCupo->CupoCredito-$dataCupo->Saldo),2,",",".") ?>
													</td>
												</tr>
											</table>
											<?php if (isset($dataCupo->details) && !empty($dataCupo->details)): ?>
												<h2 class="my-2 text-center text-info">
													Detalle de saldos
												</h2>
												<table class="table table hovered">
													<thead>
														<tr>
															<th>Documento</th>
															<th>Factura</th>
															<th>Fecha Factura</th>
															<th>Saldo</th>
															<th>Fecha de vencimiento</th>
														</tr>
													<tbody>
														<?php foreach ($dataCupo->details as $key => $value): ?>
															<tr>
																<td><?php echo $value->Documento ?></td>
																<td><?php echo $value->prefijo ?> <?php echo $value->DocumentoNúmero ?></td>
																<td><?php echo date("Y-m-d",strtotime($value->Fecha)) ?></td>
																<td><?php echo number_format(floatval($value->Saldo),2,",",".") ?></td>
																<td><?php echo date("Y-m-d",strtotime($value->Vencimiento)) ?></td>
															</tr>
														<?php endforeach ?>
													</tbody>
													</thead>
												</table>
											<?php endif ?>
										<?php endif ?>
									</div>
								</div>
							</div>
							<br>
						  	<div class="col-md-12">
						  		<div class="row dataclientsbar">
						  			<div class="col-md-3 requerimientosrecibidos">
						  				<span><?php echo $count_flujos_clients_juridico; ?></span>
						  				<h2>Requerimientos Recibidos </h2>
						  			</div>
						  			<div class="col-md-2 cotizacionesnumber">
						  				<span><?php echo $count_cotizaciones_enviadas ?></span>
						  				<h2>Cotizaciones enviadas </h2>
						  			</div>						  			
						  			<div class="col-md-2 negociosrealizados">
						  				<span><?php echo $count_negocios_realizados ?></span>
						  				<h2>Negocios realizados </h2>
						  			</div>
					  				<div class="bg-secondary col-md-2">
					  					<span class="text-white"><?php echo count($servicio_tecnico) ?></span>
						  				<h2 class="text-white">Servicios técnicos </h2>
						  			</div>
						  			<div class="col-md-3 ventastotales">
						  				<span>$<?php echo number_format((int)h($total_dinero_negocios),0,",","."); ?></span>
						  				<h2>Ventas totales </h2>
						  			</div>						  			
						  		</div>
						  	</div>
						</div>
					</div>

					<div class="col-md-12 ">	
						<div class="pdtb">				
							<div class="blockwhite">				
								<h2>Estado Comercial del Cliente</h2>
								<br>
								<ul class="nav nav-tabs" id="loguser" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="cotizacioness-tab" data-toggle="tab" href="#requerimientos" role="tab" aria-controls="cotizacioness" aria-selected="false">Requerimientos</a>
									</li>
									<li class="nav-item">
									    <a class="nav-link" id="cotizacioness-tab" data-toggle="tab" href="#cotizacioness" role="tab" aria-controls="cotizacioness" aria-selected="false">Cotizaciones</a>
									</li>
					  				<li class="nav-item">
										<a class="nav-link " id="serviciosTecnicos-tab" data-toggle="tab" href="#serviciosTecnicos" role="tab" aria-controls="serviciosTecnicos" aria-selected="false">Servicios técnicos</a>
									</li>
									<li class="nav-item">
									    <a class="nav-link" id="ventasgeneral" data-toggle="tab" href="#ventas" role="tab" aria-controls="ventas" aria-selected="true">Ventas</a>
									</li>
									<li class="nav-item">
									    <a class="nav-link" id="direccionesenvio" data-toggle="tab" href="#direcciones" role="tab" aria-controls="direcciones" aria-selected="true">Direcciones de envio</a>
									</li>
									<li class="nav-item">
									    <a class="nav-link" id="certificadosnav" data-toggle="tab" href="#certificados" role="tab" aria-controls="certificados" aria-selected="true">Certificados</a>
									</li>
								</ul>
								<div class="tab-content">
								 	<div class="tab-pane fade" id="ventas" role="tabpanel" aria-labelledby="ventasgeneral">
									  	<h2 class="text-center text-info">Cargando información...</h2>
								  	</div>
									<div class="tab-pane fade" id="cotizacioness" role="tabpanel" aria-labelledby="cotizacioness-tab">
									  	<h2 class="text-center text-info">Cargando información...</h2>	
									</div>
					  				<div class="tab-pane fade" id="serviciosTecnicos" role="tabpanel" aria-labelledby="serviciosTecnicos-tab">
										<h2 class="text-center text-info">Cargando información...</h2>
									</div>
									<div class="tab-pane fade show active" id="requerimientos" role="tabpanel" aria-labelledby="cotizacioness-tab">
										<h2 class="text-center text-info">Cargando información...</h2>
									</div>
									<div class="tab-pane fade" id="direcciones" role="tabpanel" aria-labelledby="direcciones-tab">
										<div class="databussiness contenttableresponsive">
											<table class="table table-striped table-bordered">
												<tr>
													<th>Recibe</th>
													<th>Direccion</th>
													<th>Detalle </th>
													<th>Ciudad </th>
													<th>Teléfonos</th>
													<th>Editar</th>
												</tr>
												<tbody>
													<?php foreach ($clientsLegal["Adress"] as $keyAdd => $valueAdd): ?>
														<tr>
															<td><?php echo $valueAdd["name"] ?></td>
															<td><?php echo $valueAdd["address"] ?></td>
															<td><?php echo $valueAdd["address_detail"] ?></td>
															<td><?php echo $valueAdd["city"] ?></td>
															<td><?php echo $valueAdd["phone"]; echo $valueAdd["phone_two"] != null ? " - ".$valueAdd["phone_two"] : "" ?></td>
															<td>
																<a href="javascript:void(0)" class="btn btn-warning btnAddressClient" data-id="<?php echo $valueAdd["id"] ?>" data-client='<?php echo $clientsLegal['ClientsLegal']['id'] ?>' data-type="legal" id='btnAddressClient'><i class="fa fa-pencil"></i></a>
															</td>
														</tr>
													<?php endforeach ?>
												</tbody>
											</table>

											<a href="javascript:void(0)" class="pull-right btn btn-primary btnAddressClient" data-id="0" data-client='<?php echo $clientsLegal['ClientsLegal']['id'] ?>' data-type="legal" id='btnAddressClient'>
												Crear otra dirección <i class="fa fa-plus-circle vtc"></i>
											</a>
											
										</div>
									</div>
									<div class="tab-pane fade" id="certificados" role="tabpanel" aria-labelledby="certificados-tab">
										<br>
										<div class="databussiness contenttableresponsive">
											<h3>
												Certificados emitidos al cliente
												<a href="<?php echo $this->Html->url(["controller"=>"certificados","action"=>"add","legal",$clientsLegal['ClientsLegal']['id']]) ?>" class="btn btn-warning btn-group-vertical" data-toggle="tooltip" data-placement="right" title="Certificado" data-id="0" data-client='<?php echo $clientsLegal['ClientsLegal']['id'] ?>' data-type="legal">
													<i class="fa fa-plus-circle"></i>
												</a>
											</h3>
											<table class="table table-striped">
												<thead>
												<tr>
													<th>Nombre recibe</th>
													<th>Identificación</th>
													<th>Curso realizado</th>
													<th>Ciudad y Fecha de emisión</th>
													<th>Archivo</th>
													<th>Acción</th>
												</tr>
												</thead>
												<tbody>
													<?php foreach ($clientsLegal["Certificado"] as $keyAdd => $valueAdd): ?>
														<tr>
															<td><?php echo $valueAdd["name"] ?></td>
															<td><?php echo $valueAdd["identification"] ?></td>
															<td><?php echo $valueAdd["course"] ?></td>
															<td><?php echo $valueAdd["city_date"] ?></td>
															<td>
																<a target="_blank" href="<?php echo $this->Html->url(["controller"=>"prospective_users","action"=>"diploma",str_replace([".png",".PNG",".jpg",".JPG"],'', $valueAdd["imagename"]) ]) ?>" class="btn btn-info">
																	Ver CERTIFICADO
																</a>
															</td>
															<td>
																<!-- <a href="<?php echo $this->Html->url(["controller"=>"certificados","action"=>"add",$clientsLegal['ClientsLegal']['id']]) ?>" class="btn btn-warning btn-group-vertical btnCertificateClient" data-toggle="tooltip" data-placement="right" title="Editar Certificado" data-id="<?php echo $valueAdd["id"] ?>" data-client='<?php echo $clientsLegal['ClientsLegal']['id'] ?>' data-type="legal" id='btnCertificateClient'>
																	<i class="fa fa-pencil"></i>
																</a> -->
															</td>
														</tr>
													<?php endforeach ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>								
							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="col-xl-4 col-lg-12">
				<div class="row">
					<div class="col-md-12">
						<div class="pdtb ">
							<div class="blockwhite w100 blockcontactsjuridica2">
								<div class="listarContactos"></div>
							</div>	
						</div> 
					</div>	
				</div>	

				<div class="row">
					<div class="col-md-12">
						<div class="pdtb specialresponsive">
							<div class="blockwhite w100">
								<h2 id="title_action">Añadir contacto</h2> <button id="btn_crear" class="btn-primary" type="button">A&nacutote;adir contacto</button>
								<p>Antes de crear o modificar un contacto para la empresa <b><?php echo h($clientsLegal['ClientsLegal']['name']); ?></b> por favor verifica que no esté registrado previamente.</p>
								<a id="btn_find_existencia_contacto_view" data-toggle="tooltip" data-placement="right" title="Comprobar que no esté creado"><i class="fa fa-refresh"></i>Validar existencia</a>
								<form>
									<?php echo $this->Form->hidden('empresa_id',array('value'=> $clientsLegal['ClientsLegal']['id'],"id"=>"empresa_id")); ?>
									<?php echo $this->Form->input('name',array('label' => "Nombre completo",'placeholder' => 'Nombre completo')); ?>
									<div class="form-row"> 
										<div class="col">
											<?php echo $this->Form->input('cell_phone',array('label' => "Celular",'placeholder' => 'Celular','type'=>'number')); ?>
										</div>
										<div class="col">
											<?php echo $this->Form->input('telephone',array('label' => "Teléfono",'placeholder' => 'Teléfono')); ?>
										</div>	
									</div>	
									<?php echo $this->Form->input('city',array('label' => "Ciudad",'autocomplete' => "off",'placeholder' => 'Ciudad')); ?>
									<?php echo $this->Form->input('email',array('label' => "Correo electrónico",'placeholder' => 'Correo electrónico')); ?>
									<?php 
										if(!empty($consesiones)){
											echo $this->Form->input('concession_id',array('label' => "Concesión a la que pertenece",'placeholder' => 'Seleccionar',"options"=>$consesiones, "empty"=>"Ninguna"));
										}else{
											echo $this->Form->hidden('concession_id',array('value'=> null));
										}
									 ?>
									<?php echo $this->Form->hidden('id_contact'); ?>
									
									<button id="btn_guardar" class="btn-primary" type="button">Guardar</button>
									<button id="btn_actualizar" class="btn-primary" type="button">Actualizar</button>
								</form>
							</div>	
						</div>	
					</div>	
				</div>					
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="modalClienteWo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle de credito </h2>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-12">
      			<div class="row">
      				<div class="col-md-6">
      					<div class="form-group">
      						<label for="nitCliente">Nit a consultar</label>
      						<input type="text" value="<?php  echo $partsNit[0] ?>" id="nitCliente" class="form-control" readonly>
      					</div>
      				</div>
      				<div class="col-md-6">
      					<div class="form-group">
      						<a href="" class="btn btn-info btnSearchCustomer mt-4">Consultar en WO</a>
      					</div>
      				</div>
      			</div>
      		</div>
      		<div class="col-md-12" id="bodyClienteWo">
      			
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>
<script>
	const IDS_CUSTOMER = "<?php echo $ids ?>";
</script>
<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/clientsLegal/view.js?".rand(),				array('block' => 'AppScript'));
	echo $this->Html->script("controller/clientsLegal/index.js?".rand(),			array('block' => 'AppScript'));
?>

<?php echo $this->element("address"); ?>