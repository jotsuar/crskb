<div class="ContacsUser view">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12 ">
						<div class="datajuridica blockwhite">
							<h2 class="d-inline">Contacto de la empresa <span class="uppercase"><?php echo $this->Utilities->name_bussines(h($contact['ContacsUser']['clients_legals_id'])); ?></span></h2>
							<a href="javascript:void(0)" data-toggle="tooltip" title="Editar" class="btn_editar_contacto" data-uid="<?php echo $contact['ContacsUser']['id'] ?>" data-flujo="0">
				            	<i class="fa fa-fw fa-pencil"></i>
				            </a>
							<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Agregar requerimiento" data-uid='<?php echo $contact['ContacsUser']['id'] ?>' id='btn_agregar'>
								<i class="fa fa-plus-circle"></i>
							</a>
							<div class="datajuridico">
								<p>
									<b>Nombre:</b>
									<?php echo $this->Utilities->data_null(h($contact['ContacsUser']['name'])); ?>
								</p>
								<p>
									<b>Teléfono:</b>
									<?php echo $this->Utilities->data_null(h($contact['ContacsUser']['telephone'])); ?>
								</p>
								<p>
									<b>Celular:</b>
									<?php echo $this->Utilities->data_null(h($contact['ContacsUser']['cell_phone'])); ?>
								</p>
								<p>
									<b>Correo electrónico:</b>
									<?php echo $this->Utilities->data_null(h($contact['ContacsUser']['email'])); ?>
								</p>
								<p>
									<b>Ciudad:</b>
									<?php echo $this->Utilities->data_null(h($contact['ContacsUser']['city'])); ?>
								</p>
								<p>
									<b>Fecha de registro:</b>
									<?php echo $this->Utilities->date_castellano(h($contact['ContacsUser']['created'])); ?>
								</p>
								<?php if ($contact['ContacsUser']['user_receptor'] != 0): ?>
									<p>
										<b>Usuario que realizó el registro:</b>
										<?php echo $this->Utilities->find_name_lastname_adviser(h($contact['ContacsUser']['user_receptor'])); ?>
									</p>
								<?php endif ?>
							</div>
							<br>
						  	<div class="col-md-12">
						  		<div class="row dataclientsbar">
						  			<div class="col-md-3 requerimientosrecibidos">
						  				<h2>Requerimientos Recibidos <span><?php echo $count_flujos_clients_juridico; ?></span></h2>
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
						  				<h2>Ventas totales <span>$<?php echo number_format((int)h($total_dinero_negocios),0,",","."); ?></span></h2>
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
										<a class="nav-link active" id="ventasgeneral" data-toggle="tab" href="#ventas" role="tab" aria-controls="ventas" aria-selected="true">Ventas</a>
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
										<a class="nav-link" id="cotizacioness-tab" data-toggle="tab" href="#requerimientos" role="tab" aria-controls="cotizacioness" aria-selected="false">Requerimientos</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade show active" id="ventas" role="tabpanel" aria-labelledby="ventasgeneral">
										<br>
										<div class="databussiness">
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
										<div class="databussiness">
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
									<div class="tab-pane fade" id="requerimientos" role="tabpanel" aria-labelledby="cotizacioness-tab">
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
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/clientsLegal/index.js?".rand(),				array('block' => 'AppScript'));
?>