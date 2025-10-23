<?php if ($type == "legal"): ?>

<!-- Legal ini -->

<div class="prospectiveUsers view">
	<div class="container-fluid p-0">
			<div class=" widget-panel widget-style-2 bg-azulclaro big">
            <h2 class="m-0 text-white bannerbig" >Módulo de Gestión de CRM </h2>
		</div>
		<div class="row">
			<div class="col-xl-8 col-lg-12">
				<div class="row">
					<div class="col-md-12 ">
						<div class="datajuridica blockwhite">
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
											Ver documento
										</a>
									</p>
								<?php endif ?>
								<?php if (!empty($clientsLegal["ClientsLegal"]["document_2"])): ?>
									<p>
										<b>Imagen asociada</b>
										<a href="<?php echo $this->Html->url("/img/clientes_documentos/".$clientsLegal["ClientsLegal"]["document_2"]); ?>" target="_blank" class="btn btn-info">
											Ver imagen
										</a>
									</p>
								<?php endif ?>
							</div>
							<br>
						  	<div class="col-md-12">
						  		<div class="row dataclientsbar">
						  			<div class="col-md-3 requerimientosrecibidos">
						  				<span><?php echo $count_flujos_clients_juridico; ?></span>
						  				<h2>Requerimientos Recibidos </h2>
						  			</div>
						  			<div class="<?php echo !empty($servicio_tecnico) ? "col-md-2" : "col-md-3" ?> cotizacionesnumber">
						  				<span><?php echo $count_cotizaciones_enviadas ?></span>
						  				<h2>Cotizaciones enviadas </h2>
						  			</div>						  			
						  			<div class="<?php echo !empty($servicio_tecnico) ? "col-md-2" : "col-md-3" ?> negociosrealizados">
						  				<span><?php echo $count_negocios_realizados ?></span>
						  				<h2>Negocios realizados </h2>
						  			</div>
						  			<?php if (!empty($servicio_tecnico)): ?>
						  				<div class="bg-secondary col-md-2">
						  					<span class="text-white"><?php echo count($servicio_tecnico) ?></span>
							  				<h2 class="text-white">Servicios técnicos </h2>
							  			</div>
						  			<?php endif ?>
						  			<div class="col-md-3 ventastotales">
						  				<span>$<?php echo number_format((int)h($total_dinero_negocios),0,",","."); ?></span>
						  				<h2>Ventas totales </h2>
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
							<div class="blockwhite w100 blockcontactsjuridica">
								<div class="listarContactos">
									
									<h2>Contactos registrados</h2>
									<br>
									<ul class="listcontacts">
									<?php if (count($clientsLegal["ContacsUser"]) > 0){ ?>
										<?php  foreach ($clientsLegal["ContacsUser"] as $value) { ?>
											<li>	
												<span><?php echo $value['name'] ?> </span>
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
									        </li>
										<?php } ?>
									</ul>
									<?php } else {?>
										<p>Aún no se registran contactos para el cliente juridico</p>
									<?php } ?>

								</div>
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
									<?php if (!empty($servicio_tecnico)): ?>
						  				<li class="nav-item">
											<a class="nav-link " id="serviciosTecnicos-tab" data-toggle="tab" href="#serviciosTecnicos" role="tab" aria-controls="serviciosTecnicos" aria-selected="false">Servicios técnicos</a>
										</li>
						  			<?php endif ?>
									<li class="nav-item">
									    <a class="nav-link" id="ventasgeneral" data-toggle="tab" href="#ventas" role="tab" aria-controls="ventas" aria-selected="true">Ventas</a>
									</li>
									<li class="nav-item">
									    <a class="nav-link" id="direccionesenvio" data-toggle="tab" href="#direcciones" role="tab" aria-controls="direcciones" aria-selected="true">Direcciones de envio</a>
									</li>
								</ul>
								<div class="tab-content">
								 	<div class="tab-pane fade" id="ventas" role="tabpanel" aria-labelledby="ventasgeneral">
									  	<div class="databussiness">
											<table class="table table-striped table-bordered">
												<tr>
													<th>Valor</th>
													<th>Negocio</th>
													<th>Asesor</th>
													<th>Fecha</th>
												</tr>
												<?php foreach ($negocios_realizados as $value): ?>
													<tr>
														<td>
															$<?php echo number_format((int)h($value['FlowStage']['valor']),0,",","."); ?>&nbsp;
														</td>
														<td>
															<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id']))) { ?>
																<a target="_blank" class="nameuppercase" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id'])) ?>">
																	<?php echo $this->Utilities->find_name_document_quotation_send($value['FlowStage']['prospective_users_id']) ?>
																</a>
															<?php } else { ?>
																<a target="_blank" class="nameuppercase" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id'])))) ?>">
																	<?php echo $this->Utilities->find_name_document_quotation_send($value['FlowStage']['prospective_users_id']) ?>
																</a>
															<?php } ?>
														</td>
														<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']); ?></td>
														<td><?php echo $this->Utilities->date_castellano(h($value['FlowStage']['created'])); ?></td>
													</tr>
												<?php endforeach ?>
											</table>
									  	</div>
								  	</div>
									<div class="tab-pane fade" id="cotizacioness" role="tabpanel" aria-labelledby="cotizacioness-tab">
									  	<div class="databussiness contenttableresponsive">
											<table class="table table-striped table-bordered">
												<tr>
												    <th>Nombre</th>
												    <th>Valor</th>
												    <th>Asesor</th>
												    <th>Fecha</th>
												    <th>Ver</th>
												</tr>
											  <?php foreach ($cotizaciones_enviadas as $value): ?>
											  	<?php if (empty($value["ProspectiveUser"]["user_id"])): ?>
											  		
												  	<?php else: ?>
												  	
														<tr>
															<td><?php echo $this->Utilities->find_name_file_quotation($value['FlowStage']['nameDocument'],$value['FlowStage']['document']) ?>&nbsp; </td>
															<td>$<?php echo $this->Utilities->find_valor_quotation_flujo_id($value['FlowStage']['prospective_users_id']) ?></td> 
															<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
															<td><?php echo $this->Utilities->date_castellano(h($value['FlowStage']['created'])); ?></td>
															<td>
																<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$value['FlowStage']['document'])) { ?>
																	<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$value['FlowStage']['document']) ?>">
																		Ver
																	</a>
																<?php } else { ?>
																	<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($value['FlowStage']['document']))) ?>">
																		Ver
																	</a>
																<?php } ?>
															</td>
														</tr>
													<?php endif ?>
												<?php endforeach ?>
											</table>
									  	</div>	
									</div>
									<?php if (!empty($servicio_tecnico)): ?>
						  				<div class="tab-pane fade" id="serviciosTecnicos" role="tabpanel" aria-labelledby="serviciosTecnicos-tab">
											<div class="databussiness contenttableresponsive">
												<table class="table table-striped table-bordered">
													<tr>
														<th>Flujo</th>
														<th>Código</th>
														<th>Asesor</th>
														<th>Fecha de inicio</th>
														<th>Fecha de fin</th>
														<th>Ver</th>
													</tr>
													<tbody>
													<?php foreach ($servicio_tecnico as $value): ?>
													<tr>
														<td><?php echo $value['ProspectiveUser']['id'] == 0 ? "Sin flujo" : $value['ProspectiveUser']['id'] ?></td>
														<td><?php echo $value["TechnicalService"]["code"] ?></td>
														<td><?php echo $value['User']['name'] ?></td>
														<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['created']) ?></td>
														<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['date_end']) ?></td>
														<td>
															<?php if ($value['ProspectiveUser']['id'] == 0): ?>
																N/A
															<?php else: ?>
																<a class="btn btn-primary" target="_blank" href="<?php echo Router::url('/', true).'TechnicalServices/flujos?q='.$value['ProspectiveUser']['id'] ?>">
																	Ver servicio
																</a>
															<?php endif ?>
															
														</td>
													</tr>
													<?php endforeach ?>
													</tbody>
												</table>
											</div>
										</div>
						  			<?php endif ?>
									<div class="tab-pane fade show active" id="requerimientos" role="tabpanel" aria-labelledby="cotizacioness-tab">
										<div class="databussiness contenttableresponsive">
											<table class="table table-striped table-bordered">
												<tr>
													<th>Origen</th>
													<th>Requerimiento</th>
													<th>Asesor</th>
													<th>Fecha</th>
													<th>Ver</th>
												</tr>
												<tbody>
												<?php foreach ($requerimientos_cliente as $value): ?>
												<tr>
													<td><?php echo $value['ProspectiveUser']['origin'] ?></td>
													<td><?php echo $this->Utilities->find_reason_prospective($value['ProspectiveUser']['id']) ?></td>
													<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
													<td><?php echo $this->Utilities->date_castellano($value['ProspectiveUser']['created']) ?></td>
													<td>
														<a class="btn btn-primary" target="_blank" href="<?php echo Router::url('/', true).'prospectiveUsers/index?q='.$value['ProspectiveUser']['id'] ?>">
															Ver flujo
														</a>
													</td>
												</tr>
												<?php endforeach ?>
												</tbody>
											</table>
										</div>
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
												</tr>
												<tbody>
													<?php foreach ($clientsLegal["Adress"] as $keyAdd => $valueAdd): ?>
														<tr>
															<td><?php echo $valueAdd["name"] ?></td>
															<td><?php echo $valueAdd["address"] ?></td>
															<td><?php echo $valueAdd["address_detail"] ?></td>
															<td><?php echo $valueAdd["city"] ?></td>
															<td><?php echo $valueAdd["phone"]; echo $valueAdd["phone_two"] != null ? " - ".$valueAdd["phone_two"] : "" ?></td>
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
</div>

<?php
	// echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	// echo $this->Html->script("controller/clientsLegal/view.js?".rand(),				array('block' => 'AppScript'));
	// echo $this->Html->script("controller/clientsLegal/index.js?".rand(),			array('block' => 'AppScript'));
?>

<?php echo $this->element("address"); ?>

<!-- Legal fin -->


<?php else: ?>

<!-- Natural ini -->

<div class="clientsNaturals view">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12 ">
						<div class="datajuridica blockwhite">
							<h2 class="d-inline">Persona Natural</h2>
		            		<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Agregar requerimiento" data-uid="<?php echo $clientsNaturals['ClientsNatural']['id'] ?>" id="btn_agregar">
								<i class="fa fa-plus-circle"></i>
							</a>
							<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar cliente" class="btn_editar" data-uid="<?php echo $clientsNaturals['ClientsNatural']['id'] ?>">
							 	<i class="fa fa-fw fa-pencil"></i>
							</a>
							<div class="datajuridico">
								<p>
									<b>Nombre:</b>
									<?php echo $this->Utilities->data_null(h($clientsNaturals['ClientsNatural']['name'])); ?>
								</p>
								<p>
									<b>Teléfono:</b>
									<?php echo $this->Utilities->data_null(h($clientsNaturals['ClientsNatural']['telephone'])); ?>
								</p>
								<p>
									<b>Celular:</b>
									<?php echo $this->Utilities->data_null(h($clientsNaturals['ClientsNatural']['cell_phone'])); ?>
								</p>
								<p>
									<b>Correo electrónico:</b>
									<?php echo $this->Utilities->data_null(h($clientsNaturals['ClientsNatural']['email'])); ?>
								</p>
								<p>
									<b>Ciudad:</b>
									<?php echo $this->Utilities->data_null(h($clientsNaturals['ClientsNatural']['city'])); ?>
								</p>
								<p>
									<b>Fecha de registro:</b>
									<?php echo $this->Utilities->date_castellano(h($clientsNaturals['ClientsNatural']['created'])); ?>
								</p>
								<?php if ($clientsNaturals['ClientsNatural']['user_receptor'] != 0): ?>
									<p>
										<b>Usuario que realizó el registro:</b>
										<?php echo $this->Utilities->find_name_lastname_adviser(h($clientsNaturals['ClientsNatural']['user_receptor'])); ?>
									</p>
								<?php endif ?>
								<?php if (!empty($clientsNaturals["ClientsNatural"]["document"])): ?>
									<p>
										<b>Documento asociado (PDF)</b>
										<a href="<?php echo $this->Html->url("/files/clientes_documentos/".$clientsNaturals["ClientsNatural"]["document"]); ?>" target="_blank" class="btn btn-info">
											Ver archivo
										</a>
									</p>
								<?php endif ?>
								<?php if (!empty($clientsNaturals["ClientsNatural"]["document_2"])): ?>
									<p>
										<b>Imagen asociada</b>
										<a href="<?php echo $this->Html->url("/img/clientes_documentos/".$clientsNaturals["ClientsNatural"]["document_2"]); ?>" target="_blank" class="btn btn-info">
											Ver imagen
										</a>
									</p>
								<?php endif ?>
							</div>
							<br>
						  	<div class="col-md-12">
						  		<div class="row dataclientsbar">
						  			<div class="col-md-3 requerimientosrecibidos">
						  				<h2>Requerimientos Recibidos <span><?php echo $count_flujos ?></span></h2>
						  			</div>
						  			<div class="<?php echo !empty($servicio_tecnico) ? "col-md-2" : "col-md-3" ?> cotizacionesnumber">
						  				<h2>Cotizaciones enviadas <span><?php echo $count_cotizaciones_enviadas ?></span></h2>
						  			</div>						  			
						  			<div class="<?php echo !empty($servicio_tecnico) ? "col-md-2" : "col-md-3" ?> negociosrealizados">
						  				<h2>Negocios realizados <span><?php echo $count_negocios_realizados ?></span></h2>
						  			</div>
						  			<?php if (!empty($servicio_tecnico)): ?>
						  				<div class="bg-secondary col-md-2">
							  				<h2 class="text-white">Servicios técnicos <span><?php echo count($servicio_tecnico) ?></span></h2>
							  			</div>
						  			<?php endif ?>						  			
						  			<div class="col-md-3 ventastotales">
						  				<h2>Ventas totales 
						  					<span>$<?php echo number_format((int)h($total_dinero_negocios),0,",","."); ?></span>
						  				</h2>
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
										<a class="nav-link active" id="cotizacioness-tab" data-toggle="tab" href="#requerimientos" role="tab" aria-controls="requerimientos" aria-selected="false">Requerimientos</a>
									</li>
									<li class="nav-item">
										<a class="nav-link " id="cotizacioness-tab" data-toggle="tab" href="#cotizacioness" role="tab" aria-controls="cotizacioness" aria-selected="false">Cotizaciones</a>
									</li>
									<?php if (!empty($servicio_tecnico)): ?>
						  				<li class="nav-item">
											<a class="nav-link " id="serviciosTecnicos-tab" data-toggle="tab" href="#serviciosTecnicos" role="tab" aria-controls="serviciosTecnicos" aria-selected="false">Servicios técnicos</a>
										</li>
						  			<?php endif ?>
									<li class="nav-item">
										<a class="nav-link" id="ventasgeneral" data-toggle="tab" href="#ventas" role="tab" aria-controls="ventas" aria-selected="true">Ventas</a>
									</li>
									<li class="nav-item">
									    <a class="nav-link" id="direccionesenvio" data-toggle="tab" href="#direcciones" role="tab" aria-controls="direcciones" aria-selected="true">Direcciones de envio</a>
									</li>

								</ul>
								<div class="tab-content">
									<div class="tab-pane fade" id="ventas" role="tabpanel" aria-labelledby="ventasgeneral">
										<br>
										<div class="databussiness contenttableresponsive">
											<table class="table table-striped">
												<thead>
												<tr>
													<th>VALOR</th>
													<th>NEGOCIO</th>
													<th>ASESOR</th>
													<th>FECHA</th>
													<th>
														Ver flujo
													</th>
												</tr>
												</thead>
												<tbody>
												<?php foreach ($negocios_realizados as $value): ?>
												<tr>
													<td>
														$<?php echo number_format((int)h($value['FlowStage']['valor']),0,",","."); ?>&nbsp;
													</td>
													<td>
														<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id']))) { ?>
															<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id'])) ?>">
																<?php echo $this->Utilities->find_name_document_quotation_send($value['FlowStage']['prospective_users_id']) ?>
															</a>
														<?php } else { ?>
															<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($this->Utilities->find_id_document_quotation_send($value['FlowStage']['prospective_users_id'])))) ?>">
																<?php echo $this->Utilities->find_name_document_quotation_send($value['FlowStage']['prospective_users_id']) ?>
															</a>
														<?php } ?>
													</td>
													<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']); ?></td>
													<td><?php echo $this->Utilities->date_castellano(h($value['FlowStage']['created'])); ?></td>
													<td>
														<a class="btn btn-primary" target="_blank" href="<?php echo Router::url('/', true).'prospectiveUsers/index?q='.$value['FlowStage']['prospective_users_id'] ?>">
															Ver flujo
														</a>
													</td>
												</tr>
												<?php endforeach ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="cotizacioness" role="tabpanel" aria-labelledby="cotizacioness-tab">
										<br>
										<div class="databussiness contenttableresponsive">
											<table class="table table-striped">
												<thead>
												<tr>
													<th>NOMBRE</th>
													<th>VALOR</th>
													<th>ASESOR</th>
													<th>FECHA DE ENVÍO</th>
													<th>VER</th>
												</tr>
												</thead>
												<tbody>
												<?php foreach ($cotizaciones_enviadas as $value): ?>
												<tr>
													<td><?php echo $this->Utilities->find_name_file_quotation($value['FlowStage']['nameDocument'],$value['FlowStage']['document']) ?>&nbsp; </td>
													<td>$<?php echo $this->Utilities->find_valor_quotation_flujo_id($value['FlowStage']['prospective_users_id']) ?></td> 
													<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
													<td><?php echo $this->Utilities->date_castellano(h($value['FlowStage']['created'])); ?></td>
													<td>
														<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$value['FlowStage']['document'])) { ?>
															<a target="_blank" class="btn btn-info" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$value['FlowStage']['document']) ?>">
																Ver cotización
															</a>
														<?php } else { ?>
															<a target="_blank" class="btn btn-info" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($value['FlowStage']['document']))) ?>">
																Ver cotización
															</a>
														<?php } ?>
													</td>
												</tr>
												<?php endforeach ?>
												</tbody>
											</table>
										</div>	
									</div>
									<?php if (!empty($servicio_tecnico)): ?>
						  				<div class="tab-pane fade" id="serviciosTecnicos" role="tabpanel" aria-labelledby="serviciosTecnicos-tab">
											<br>
											<div class="databussiness contenttableresponsive">
												<table class="table table-striped">
													<thead>
													<tr>
														<th>Flujo</th>
														<th>Código</th>
														<th>Asesor</th>
														<th>Fecha de inicio</th>
														<th>Fecha de fin</th>
														<th>Ver</th>
													</tr>
													</thead>
													<tbody>
													<?php foreach ($servicio_tecnico as $value): ?>
													<tr>
														<td><?php echo $value['ProspectiveUser']['id'] == 0 ? "Sin flujo" : $value['ProspectiveUser']['id'] ?></td>
														<td><?php echo $value["TechnicalService"]["code"] ?></td>
														<td><?php echo $value['User']['name'] ?></td>
														<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['created']) ?></td>
														<td><?php echo $this->Utilities->date_castellano($value['TechnicalService']['date_end']) ?></td>
														<td>
															<?php if ($value['ProspectiveUser']['id'] == 0): ?>
																N/A
															<?php else: ?>
																<a target="_blank" class="btn btn-info" href="<?php echo Router::url('/', true).'TechnicalServices/flujos?q='.$value['ProspectiveUser']['id'] ?>">
																	Ver flujo
																</a>
															<?php endif ?>
															
														</td>
													</tr>
													<?php endforeach ?>
													</tbody>
												</table>
											</div>
										</div>
						  			<?php endif ?>
									<div class="tab-pane fade show active" id="requerimientos" role="tabpanel" aria-labelledby="requerimientos">
										<br>
										<div class="databussiness contenttableresponsive">
											<table class="table table-striped">
												<thead>
												<tr>
													<th>Origen</th>
													<th>Requerimiento</th>
													<th>Asesor</th>
													<th>Fecha</th>
													<th>Ver</th>
												</tr>
												</thead>
												<tbody>
												<?php foreach ($requerimientos_cliente as $value): ?>
												<tr>
													<td><?php echo $value['ProspectiveUser']['origin'] ?></td>
													<td><?php echo $this->Utilities->find_reason_prospective($value['ProspectiveUser']['id']) ?></td>
													<td><?php echo $this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']) ?></td>
													<td><?php echo $this->Utilities->date_castellano($value['ProspectiveUser']['created']) ?></td>
													<td>
														<a target="_blank" class="btn btn-info" href="<?php echo Router::url('/', true).'prospectiveUsers/index?q='.$value['ProspectiveUser']['id'] ?>">
															Ver flujo
														</a>
													</td>
												</tr>
												<?php endforeach ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="direcciones" role="tabpanel" aria-labelledby="direcciones-tab">
										<br>
										<div class="databussiness contenttableresponsive">
											<h3>
												Direcciones de envio registradas 
												<a href="javascript:void(0)" class="btn btn-warning btn-group-vertical btnAddressClient" data-toggle="tooltip" data-placement="right" title="Agregar dirección" data-id="0" data-client='<?php echo $clientsNaturals['ClientsNatural']['id'] ?>' data-type="legal" id='btnAddressClient'>
													<i class="fa fa-plus-circle"></i>
												</a>
											</h3>
											<table class="table table-striped">
												<thead>
												<tr>
													<th>Nombre recibe</th>
													<th>Direccion</th>
													<th>Detalle direccion</th>
													<th>Ciudad </th>
													<th>Telefonos</th>
												</tr>
												</thead>
												<tbody>
													<?php foreach ($clientsNaturals["Adress"] as $keyAdd => $valueAdd): ?>
														<tr>
															<td><?php echo $valueAdd["name"] ?></td>
															<td><?php echo $valueAdd["address"] ?></td>
															<td><?php echo $valueAdd["address_detail"] ?></td>
															<td><?php echo $valueAdd["city"] ?></td>
															<td><?php echo $valueAdd["phone"]; echo $valueAdd["phone_two"] != null ? " - ".$valueAdd["phone_two"] : "" ?></td>
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
		</div>
	</div>
</div>

<?php
	// echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	// echo $this->Html->script("controller/clientsNatural/index.js?".rand(),				array('block' => 'AppScript'));
?>

<?php echo $this->element("address"); ?>


<!-- Natural fin -->
	
<?php endif ?>