<div class="clientsNaturals view">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12 ">
						<div class="datajuridica blockwhite">
							<div class="row">
								<div class="col-md-5">
									<h2 class="d-inline">Persona Natural</h2>
				            		<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Agregar requerimiento" data-uid="<?php echo $clientsNaturals['ClientsNatural']['id'] ?>" id="btn_agregar">
										<i class="fa fa-plus-circle"></i>
									</a>
									<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Editar cliente" class="btn_editar" data-uid="<?php echo $clientsNaturals['ClientsNatural']['id'] ?>">
									 	<i class="fa fa-fw fa-pencil"></i>
									</a>
									<div class="datajuridico">
										<p>
											<b>Identificación:</b>
											<?php echo $this->Utilities->data_null(h($clientsNaturals['ClientsNatural']['identification'])); ?>
											<?php if ($clientsNaturals["ClientsNatural"]["validate"] == 1): ?>
												<span class="text-success"> 
													- Validada
												</span>	
											<?php else: ?>
												<span class="text-danger"> 
													- Sin validar
												</span>	
											<?php endif ?>
										</p>
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
													<i class="fa fa-file-pdf-o vtc"></i>
												</a>
											</p>
										<?php endif ?>
										<?php if (!empty($clientsNaturals["ClientsNatural"]["document_2"])): ?>
											<p>
												<b>Imagen asociada</b>
												<a href="<?php echo $this->Html->url("/img/clientes_documentos/".$clientsNaturals["ClientsNatural"]["document_2"]); ?>" target="_blank" class="btn btn-info">
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

										<?php echo $this->Form->create('ClientsNatural',array('data-parsley-validate'=>true)); ?>
											<?php echo $this->Form->hidden("delete_id", ["value" => $clientsNaturals["ClientsNatural"]["id"] ] ) ?>
											<?php echo $this->Form->input('other_id',array('empty'=>"Seleccionar","options" => $otherClients, "label" => "Seleccione el cliente con que será replazado", "required" )); ?>
										<?php echo $this->Form->end(__('Eliminar cliente')); ?>

									<?php endif ?>
								</div>
								<div class="col-md-12">
									
									<!--  -->

									<div class="table-responsive">
										<?php if (empty($dataCupo)): ?>
											<h2 class="text-center text-warning mt-4">
												No se encontró información sobre cupo de crédito
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

									<!--  -->

								</div>
							
								
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
									<li class="nav-item">
									    <a class="nav-link" id="certificadosnav" data-toggle="tab" href="#certificados" role="tab" aria-controls="certificados" aria-selected="true">Certificados</a>
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
															<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$value['FlowStage']['document']) ?>">
																<i class="fa fa-file-text fa-x"></i>
															</a>
														<?php } else { ?>
															<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($value['FlowStage']['document']))) ?>">
																<i class="fa fa-file-text fa-x"></i>
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
																<a target="_blank" href="<?php echo Router::url('/', true).'TechnicalServices/flujos?q='.$value['ProspectiveUser']['id'] ?>">
																	<i class="fa fa-file-text fa-x"></i>
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
														<a href="<?php echo Router::url('/', true).'prospectiveUsers/index?q='.$value['ProspectiveUser']['id'] ?>">
															<i class="fa fa-file-text fa-x"></i>
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
												<a href="javascript:void(0)" class="btn btn-warning btn-group-vertical btnAddressClient" data-toggle="tooltip" data-placement="right" title="Agregar dirección" data-id="0" data-client='<?php echo $clientsNaturals['ClientsNatural']['id'] ?>' data-type="natural" id='btnAddressClient'>
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
													<th>Acción</th>
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
															<td>
																<a href="javascript:void(0)" class="btn btn-warning btn-group-vertical btnAddressClient" data-toggle="tooltip" data-placement="right" title="Editar dirección" data-id="<?php echo $valueAdd["id"] ?>" data-client='<?php echo $clientsNaturals['ClientsNatural']['id'] ?>' data-type="legal" id='btnAddressClient'>
																	<i class="fa fa-pencil"></i>
																</a>
															</td>
														</tr>
													<?php endforeach ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="certificados" role="tabpanel" aria-labelledby="certificados-tab">
										<br>
										<div class="databussiness contenttableresponsive">
											<h3>
												Certificados emitidos al cliente
												<a href="<?php echo $this->Html->url(["controller"=>"certificados","action"=>"add","natural",$clientsNaturals['ClientsNatural']['id']]) ?>" class="btn btn-warning btn-group-vertical" data-toggle="tooltip" data-placement="right" title="Certificado" data-id="0" data-client='<?php echo $clientsNaturals['ClientsNatural']['id'] ?>' data-type="natural">
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
													<?php foreach ($clientsNaturals["Certificado"] as $keyAdd => $valueAdd): ?>
														<tr>
															<td><?php echo $valueAdd["name"] ?></td>
															<td><?php echo $valueAdd["identification"] ?></td>
															<td><?php echo $valueAdd["course"] ?></td>
															<td><?php echo $valueAdd["city_date"] ?></td>
															<td>
																<a target="_blank" href="<?php echo $this->Html->url(["controller"=>"prospective_users","action"=>"diploma",str_replace([".png",".PNG",".jpg",".JPG"],'', $valueAdd["imagename"]).".pdf" ]) ?>" class="btn btn-info">
																	Ver CERTIFICADO
																</a>
															</td>
															<td>
																<!-- <a href="<?php echo $this->Html->url(["controller"=>"certificados","action"=>"add",$clientsNaturals['ClientsNatural']['id']]) ?>" class="btn btn-warning btn-group-vertical btnCertificateClient" data-toggle="tooltip" data-placement="right" title="Editar Certificado" data-id="<?php echo $valueAdd["id"] ?>" data-client='<?php echo $clientsNaturals['ClientsNatural']['id'] ?>' data-type="legal" id='btnCertificateClient'>
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
		</div>
	</div>
</div>

<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/clientsNatural/index.js?".rand(),				array('block' => 'AppScript'));
?>

<?php echo $this->element("address"); ?>