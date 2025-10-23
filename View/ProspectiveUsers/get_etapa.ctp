<?php if (is_null($datosProspecto["ProspectiveUser"]["clients_natural_id"]) && $datosProspecto["ProspectiveUser"]["contacs_users_id"] == 0): ?>
<style type="text/css">
	.novedadescontent {
    height: auto;
    min-height: initial;
    overflow: initial;
    max-height: initial;
}
.resultadoscontent {
    min-height: initial;
    height: auto;
    max-height: initial;
    overflow: initial;
}
.novedadescontent, .resultadoscontent {
    padding: 0px;
    border-radius: 0px;
    background: transparent;
}
.allnovedad .col-md-8, .allnovedad .col-md-4 {
    width: 100% !important;
    flex: 0 0 100%;
    max-width: 100%;
    text-align: center;
}
.allnovedad{
    padding: 5px 25px 25px 25px;
    border-radius: 10px;
    background: #fff;	
}
</style>
<div class="flujoxpress">
	<div class="glyph-icon flaticon-report-1"></div>
	<h2 class="text-danger text-center mb-3">
		Este flujo debe ser gestionado, por favor contacta al prospecto para continuar
	</h2>
	<h2>INFORMACIÓN DEL PROSPECTO</h2>
	<hr>
	<?php if (!empty($datosProspecto["ProspectiveUser"]["phone_customer"])): ?>		
		<h3>
			Celular: <span><?php echo $datosProspecto["ProspectiveUser"]["phone_customer"] ?></span>
		</h3>
	<?php endif ?>
	<?php if (!empty($datosProspecto["ProspectiveUser"]["email_customer"])): ?>
		<h3>
			Correo: <span><?php echo $datosProspecto["ProspectiveUser"]["email_customer"] ?></span>
		</h3>
	<?php endif ?>
	<?php if (!empty($datosProspecto["ProspectiveUser"]["country"])): ?>
		<h3>
			País: <span><?php echo $datosProspecto["ProspectiveUser"]["country"] ?></span>
		</h3>
	<?php endif ?>
</div>
	
<?php else: ?>

<h2 class="titleflujost">
	<?php if ($datosProspecto['ProspectiveUser']['type'] > 0): ?>
		<span class="orderst">
			ÓRDEN DE SERVICIO <b><?php echo $this->Utilities->consult_cod_service($datosProspecto['ProspectiveUser']['type']) ?></b>
		</span>
	<?php endif ?>
	<a href="javascript:void(0)" class="btn_notas_flujo" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-toggle="tooltip" title="Ver notas">
		<i class="fa fa-comments"></i> Gestión
	</a>
</h2>
<?php if (AuthComponent::user("role") == "Gerente General" && $datosProspecto["ProspectiveUser"]["state"] == 3): ?>
<h2 class="titleflujost">
	<a href="javascript:void(0)" class="activar_flujo p-1 text-white" data-id="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-toggle="tooltip" title="Reactivar flujo">
		<i class="fa fa-check"></i> Activar flujo
	</a>
</h2>
<?php endif ?>
<div class="linedata">
	<div id="accordion1" class="useretapa">
		<?php if (true): ?>
			<div class="card-etapa">
				<div id="datosFacturas<?php echo $datosProspecto['ProspectiveUser']['id'] ?>">
					<h5>
						<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#datosFacturasData<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" aria-expanded="false" aria-controls="recibos<?php echo $datosProspecto['ProspectiveUser']['id'] ?>">
							Facturas
						</button>
					</h5>
				</div>
				<div id="datosFacturasData<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" class="collapse pdetapa" aria-labelledby="datosFacturas<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-parent="#accordion1">
					<h4 class="text-center m-3">Facturas registradas al flujo</h4>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Código</th>
									<th>Total sin iva</th>
									<th>Total con iva</th>
									<th>Fecha factura</th>
									<th>Usuario</th>
									<th>Archivo</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($datosProspecto["ProspectiveUser"]["bill_user"]) && $datosProspecto["ProspectiveUser"]["locked"] != -1): ?>
									<tr>
										<td><?php echo $datosProspecto["ProspectiveUser"]["bill_code"] ?></td>
										<td><?php echo number_format($datosProspecto["ProspectiveUser"]["bill_value"], 2, ",",".") ?></td>
										<td><?php echo number_format($datosProspecto["ProspectiveUser"]["bill_value_iva"], 2, ",",".") ?></td>
										<td><?php echo $datosProspecto["ProspectiveUser"]["bill_date"] ?></td>
										<td><?php echo $this->Utilities->find_name_adviser($datosProspecto["ProspectiveUser"]["bill_user"]) ?></td>
										<td>
											<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$datosProspecto["ProspectiveUser"]["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary <?php echo is_null($datosProspecto["ProspectiveUser"]["bill_file"]) ? "mostradDatos" : "" ?>" data-id="KEB_<?php echo md5($datosProspecto["ProspectiveUser"]["bill_code"]) ?><?php echo $modal ? "_modal" : "" ?>">
												Ver factura <i class="fa fa-file vtc"></i>
											</a>	
											<?php if (AuthComponent::user("role") == "Logística" || AuthComponent::user("role") == "Gerente General"): ?>
												<a href="" class="btn btn-warning editEnvoice" data-type="0" data-id="<?php echo $datosProspecto["ProspectiveUser"]["id"] ?>" title="Editar factura" data-toggole="tooltip">
													<i class="fa fa-pencil vtc"></i>
												</a>
												<a href="" class="btn btn-danger bntEliminaFactura" data-type="0" data-id="<?php echo $datosProspecto["ProspectiveUser"]["id"] ?>" title="Eliminar factura" data-toggole="tooltip">
													<i class="fa fa-trash vtc"></i>
												</a>										
											<?php endif ?>									
										</td>
									</tr>
								<?php else: ?>
									<tr>
										<td colspan="6">
											<a href="" class="btn btn-info btn-block btnAutomaticFacture" data-id="<?php echo $datosProspecto["ProspectiveUser"]["id"] ?>">
												Cargar factura automática desde wo
											</a>
										</td>
									</tr>
								<?php endif ?>
								<?php foreach ($otrasFacturas as $key => $value): ?>
									<tr>
										<td><?php echo $value["Salesinvoice"]["bill_code"] ?></td>
										<td><?php echo number_format($value["Salesinvoice"]["bill_value"], 2, ",",".") ?></td>
										<td><?php echo number_format($value["Salesinvoice"]["bill_value_iva"], 2, ",",".") ?></td>
										<td><?php echo $value["Salesinvoice"]["bill_date"] ?></td>
										<td><?php echo $this->Utilities->find_name_adviser($value["Salesinvoice"]["user_id"]) ?></td>
										<td>
											<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$value["Salesinvoice"]["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary <?php echo is_null($value["Salesinvoice"]["bill_file"]) ? "mostradDatos" : "" ?>" data-id="KEB_<?php echo md5($value["Salesinvoice"]["bill_code"]) ?><?php echo $modal ? "_modal" : "" ?>">
												Ver factura <i class="fa fa-file"></i>
											</a>
											<?php if (AuthComponent::user("role") == "Logística" || AuthComponent::user("role") == "Gerente General"): ?>
												<a href="" class="btn btn-warning editEnvoice" data-type="<?php echo $value["Salesinvoice"]["id"] ?>" data-id="<?php echo $datosProspecto["ProspectiveUser"]["id"] ?>" title="Editar factura" data-toggole="tooltip">
													<i class="fa fa-pencil vtc"></i>
												</a>
												<a href="" class="btn btn-danger bntEliminaFactura" data-type="<?php echo $value["Salesinvoice"]["id"] ?>" data-id="<?php echo $datosProspecto["ProspectiveUser"]["id"] ?>" title="Eliminar factura" data-toggole="tooltip">

													<i class="fa fa-trash vtc"></i>
												</a>
											<?php endif ?>											
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					
					<?php if (is_null($datosProspecto["ProspectiveUser"]["bill_file"]) && !is_null($datosProspecto["ProspectiveUser"]["bill_text"]) ): ?>

						<div id="KEB_<?php echo md5($datosProspecto["ProspectiveUser"]["bill_code"]) ?><?php echo $modal ? "_modal" : "" ?>" class="table-responsive" style="display: none;">
							<?php $factValue = (array) json_decode($datosProspecto["ProspectiveUser"]["bill_text"]); ?>
							<table class="table table-bordered">
								<tr>
									<th colspan="2">
										<h3 class="text-center">
											Datos de la factura <?php echo $factValue["datos_factura"]->Fecha ?>
										</h3>
									</th>
								</tr>
								<tr>
									<td>
										<b>Código: </b> <?php echo $factValue["datos_factura"]->Id ?>
									</td>
									<td>
										<b>Prefijo: </b> <?php echo $factValue["datos_factura"]->prefijo ?>
									</td>
								</tr>	
								<?php if (!empty($factValue["valores_factura"])): ?>
									<tr>
										<th colspan="2">
											<h3 class="text-center">
												Valores de la factura
											</h3>
										</th>
									</tr>
									<?php foreach ($factValue["valores_factura"] as $key => $value): ?>
										<tr>
											<?php if (!is_null($value->IdClasificación)): ?>
												<td><b>Valor factura sin IVA: </b> </td> <td><?php echo number_format(round($value->Crédito,2),2,",",".")  ?></td>
											<?php else: ?>
												<td><b>Valor factura con IVA: </b> </td> <td> <?php echo number_format(round($value->Débito,2),2,",",".") ?></td>
											<?php endif ?>
										</tr>
									<?php endforeach ?>
								<?php endif ?>
								<?php if (!empty($factValue["productos_factura"])): ?>
									<tr>
										<th colspan="2">
											<h3 class="text-center">
												Productos de la factura
											</h3>
										</th>
									</tr>	
									<?php foreach ($factValue["productos_factura"] as $key => $value): ?>
										<tr>
											<td>
												<b>Referencia: </b> <?php echo $value->CódigoInventario ?> <br>
												<b>Producto: </b> <?php echo $value->Descripción ?> <br>
												<b>Cantidad: </b> <?php echo $value->Cantidad ?> <br>
											</td>
											<td>
												<b>Precio: </b> <?php echo number_format(round($value->Precio,2),2,",",".") ?> <br>
												<b>Bodega: </b> <?php echo $value->Bodega ?> <br>
											</td>
										</tr>
									<?php endforeach ?>	
								<?php endif ?>	
							</table>
						</div>

					<?php endif ?>

					<?php foreach ($otrasFacturas as $key => $value): ?>
						<?php if (is_null($value["Salesinvoice"]["bill_file"])): ?>

							<div id="KEB_<?php echo md5($value["Salesinvoice"]["bill_code"]) ?>" class="table-responsive" style="display: none;">
								<?php $factValue = (array) json_decode($value["Salesinvoice"]["bill_text"]); ?>
								<table class="table table-bordered">
									<tr>
										<th colspan="2">
											<h3 class="text-center">
												Datos de la factura <?php echo $factValue["datos_factura"]->Fecha ?>
											</h3>
										</th>
									</tr>
									<tr>
										<td>
											<b>Código: </b> <?php echo $factValue["datos_factura"]->Id ?>
										</td>
										<td>
											<b>Prefijo: </b> <?php echo $factValue["datos_factura"]->prefijo ?>
										</td>
									</tr>	
									<?php if (!empty($factValue["valores_factura"])): ?>
										<tr>
											<th colspan="2">
												<h3 class="text-center">
													Valores de la factura
												</h3>
											</th>
										</tr>
										<?php foreach ($factValue["valores_factura"] as $key => $value): ?>
											<tr>
												<?php if (!is_null($value->IdClasificación)): ?>
													<td><b>Valor factura sin IVA: </b> </td> <td><?php echo number_format(round($value->Crédito,2),2,",",".")  ?></td>
												<?php else: ?>
													<td><b>Valor factura con IVA: </b> </td> <td> <?php echo number_format(round($value->Débito,2),2,",",".") ?></td>
												<?php endif ?>
											</tr>
										<?php endforeach ?>
									<?php endif ?>
									<?php if (!empty($factValue["productos_factura"])): ?>
										<tr>
											<th colspan="2">
												<h3 class="text-center">
													Productos de la factura
												</h3>
											</th>
										</tr>	
										<?php foreach ($factValue["productos_factura"] as $key => $value): ?>
											<tr>
												<td>
													<b>Referencia: </b> <?php echo $value->CódigoInventario ?> <br>
													<b>Producto: </b> <?php echo $value->Descripción ?> <br>
													<b>Cantidad: </b> <?php echo $value->Cantidad ?> <br>
												</td>
												<td>
													<b>Precio: </b> <?php echo number_format(round($value->Precio,2),2,",",".") ?> <br>
													<b>Bodega: </b> <?php echo $value->Bodega ?> <br>
												</td>
											</tr>
										<?php endforeach ?>	
									<?php endif ?>	
								</table>
							</div>

						<?php endif ?>
					<?php endforeach ?>
					</div>
				</div>
			</div>
		<?php endif ?>
		<?php $rolesPayments = array(Configure::read('variables.roles_usuarios.Contabilidad'),Configure::read('variables.roles_usuarios.Gerente General') );?>
        <?php if(in_array(AuthComponent::user('role'), $rolesPayments) || AuthComponent::user("id") == 2 || AuthComponent::user("role") == "Logística" ): ?>        	
			<div class="card-etapa">
				<div id="datosRecibos<?php echo $datosProspecto['ProspectiveUser']['id'] ?>">
					<h5>
						<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#datosRecibosData<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" aria-expanded="false" aria-controls="recibos<?php echo $datosProspecto['ProspectiveUser']['id'] ?>">
							Recibos de caja
						</button>
					</h5>
				</div>
				<div id="datosRecibosData<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" class="collapse pdetapa" aria-labelledby="datosRecibos<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-parent="#accordion1">
					<div class="contentdataRecibos">
						<?php $totalNoIvaOtras = 0;	$totalIvaOtras = 0; ?>
						<?php if (!empty($valorQuotation)): ?>
							<p>Precio de cotización con iva: <b> $ <?php echo number_format(($valorQuotation * 1.19), 2, ".",",") ?> </b></p>
						<?php endif ?>
						<?php if (!empty($valorQuotation)): ?>
							<p>Precio de cotización sin iva: <b> $ <?php echo number_format($valorQuotation, 2, ".",",") ?></b></p>
						<?php endif ?>
						<?php if (!empty($datosProspecto["ProspectiveUser"]["bill_value"])): ?>
							<?php if(!empty($otrasFacturas)){
								
								foreach ($otrasFacturas as $key => $value) {
									$totalNoIvaOtras+=$value["Salesinvoice"]["bill_value"];
									$totalIvaOtras+=$value["Salesinvoice"]["bill_value_iva"];
								}
							} ?>
							<p>Precio de factura sin iva: <b> $ <?php echo number_format( ($datosProspecto["ProspectiveUser"]["bill_value"] + $totalNoIvaOtras), 2, ".",",") ?> </b></p>
						<?php endif ?>
						<?php if (!empty($datosProspecto["ProspectiveUser"]["bill_value_iva"])): ?>
							<p>Precio de factura con iva: <b> $ <?php echo number_format(($datosProspecto["ProspectiveUser"]["bill_value_iva"] + $totalIvaOtras), 2, ".",",") ?></b></p>
						<?php endif ?>

						<p>Total ingresado en recibos de caja <b>$ <?php echo number_format($totalActual, 2, ".",",") ?></b></p>
						<?php if ($totalActual >= 0): ?>
							<p>Saldo por ingresar en recibos de caja <b>$ <?php echo number_format( (($valorQuotation * 1.19) - $totalActual) , 2, ".",",") ?></b></p>
						<?php endif ?>
					</div>
					<?php if (!empty($recibosCaja)): ?>
						<h4 class="text-center titlespace">Recibos de caja registrados</h4>
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Código</th>
										<th>Factura </th>
										<th>Sin iva</th>
										<th>Con iva</th>
										<th>F. recibo</th>
										<th>Usuario</th>
										<th>RETEFUENTE</th>
										<th>RETEIVA</th>
										<th>Otras retenc.</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($recibosCaja as $key => $value): ?>
										<tr>
											<td><?php echo $value["Receipt"]["code"] ?></td>
											<td><?php echo $this->Utilities->getCodeBill($datosProspecto["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"]); ?></td>
											<td><?php echo number_format($value["Receipt"]["total_iva"],2,".",",") ?></td>
											<td><?php echo number_format($value["Receipt"]["total"],2,".",",") ?></td>
											<td><?php echo $value["Receipt"]["date_receipt"] ?></td>
											<td>
												<?php $texto = explode(" ", $value['User']['name']); if (isset($texto[1])) {$texto = $texto[0];}
													echo $texto;?>
											</td>
											<td><?php echo $value["Receipt"]["retefuente"] == 1 ? "Si" : "No" ?></td>
											<td><?php echo $value["Receipt"]["reteiva"] == 1 ? "Si" : "No"?></td>
											<td><?php echo $value["Receipt"]["otras"] == 1 ? "Si" : "No" ?></td>
											<td>
												<?php if (AuthComponent::user("role") == "Logística" || AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Contabilidad"): ?>													
													<a href="" class="btn btn-info btnEditRecipe" data-id="<?php echo $value["Receipt"]["id"] ?>">
														<i class="fa fa-edit vtc"></i>
													</a>
												<?php endif ?>
											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					<?php else: ?>
						<p class="text-center text-uppercase noRecibos">No se han ingresado recibos de caja</p>
					<?php endif ?>				
					<div class="text-center">
						<a href="#" data-recibo="<?php echo $datosProspecto["ProspectiveUser"]["id"] ?>" class="text-center nuevoReciboBtn"> 
							<i class="fa fa-plus vtc"></i> Ingresar nuevo recibo 
						</a>						
					</div>
				</div>
			</div>
        <?php endif ?>
        <?php if ($datosProspecto['ProspectiveUser']['state_flow'] != Configure::read('variables.control_flujo.flujo_cancelado')): ?>
			<?php if ($datosProspecto['ProspectiveUser']['state_flow'] > Configure::read('variables.control_flujo.flujo_asignado') && $datosProspecto['ProspectiveUser']['state_flow'] < Configure::read('variables.control_flujo.flujo_cotizado')): ?>
				<div class="card-etapa">
					<div id="headqCotizaciones">
						<h5>
							<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#etapaCotizacione" aria-expanded="false" aria-controls="Cotizacione">
								Cotizaciones guardadas
							</button>
						</h5>
					</div>
					<div id="etapaCotizacione" class="collapse show pdetapa" aria-labelledby="headqCotizacione" data-parent="#accordion1">
						<div class="spaceangle"></div>
						<div class="card-body">
							<div class="aa">
								<?php $value = $datos[0]; ?>
								<?php if ($datosProspecto["ProspectiveUser"]["valid"] == 0): ?>							
									<a class="alingicon" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'add',$value['FlowStage']['prospective_users_id'],$value['FlowStage']['id'])) ?>" data-toggle="tooltip" data-placement="right" title="Hacer cotización">
										HACER COTIZACIÓN &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								<?php endif ?>
								<?php if ($datosProspecto["ProspectiveUser"]["state_flow"] == 2 && $datosProspecto["ProspectiveUser"]["valid"] == 1 && AuthComponent::user("Gerente General") ): ?>
									<a class="btn btn-warning d-inline-block quotationWO" data-flujo="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-qt="<?php echo $value['FlowStage']['id'] ?>" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'from_wo',$datosProspecto['ProspectiveUser']['id'],$value['FlowStage']['id'])) ?>" data-toggle="tooltip" data-placement="right" title="Hacer cotización">
										CREAR COTIZACIÓN DESDE FACTURA WO <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								<?php endif ?>
								<p class="borrador">
									<span class="borradorspan"><?php echo $this->Utilities->validate_isset_borrador($datosProspecto['ProspectiveUser']['id']); ?></span> 
									<i class="fa-1x fa fa-exclamation-circle"></i>
								</p>
							</div>
							<?php if (isset($documentosList[0])){ ?>
								<ul class="cotizacioneslistas">
									<?php foreach ($documentosList as $valueDocumento): ?>
										<?php if ($valueDocumento["Quotation"]["state"] == 0): ?>
											<?php continue; ?>
										<?php endif ?>
										<li>
											<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($valueDocumento['Quotation']['id']))) ?>">
												<i class="fa fa-file-text fa-x"></i>
												<?php echo $valueDocumento['Quotation']['name'] ?> - <?php echo $valueDocumento['Quotation']['codigo'] ?>
												<span><?php echo $valueDocumento['Quotation']['created'] ?></span>
											</a>
											<?php if ($datosProspecto["ProspectiveUser"]["valid"] == 0): ?>
												<a class="btn_eliminar_cotizacion" data-uid="<?php echo $valueDocumento['Quotation']['id'] ?>" >
													<i class="fa fa-times fa-x"></i>
												</a>
											<?php endif ?>
										</li>
									<?php endforeach ?>
								</ul>
							<?php } else { ?>
								No existen cotizaciones guardadas 
							<?php } ?>
						</div>
					</div>
				</div>
			<?php endif ?>
		<?php endif ?>
		<?php foreach ($datos as $value): ?>
			<?php 
				$user_asigno 		= $this->Utilities->idUser_asigno_flujo($value['FlowStage']['prospective_users_id']);
			?>
			<div class="card-etapa">
				<div id="headq<?php echo $value['FlowStage']['id'] ?>">
					<h5>
						<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#etapa<?php echo $value['FlowStage']['id'] ?>" aria-expanded="false" aria-controls="<?php echo $value['FlowStage']['id'] ?>">
							<span class="maxbtn"><?php echo $value['FlowStage']['state_flow'] == "Despacho" ? "Datos despacho" : $value['FlowStage']['state_flow']; echo ' , '.$this->Utilities->date_castellano($value['FlowStage']['created']); ?></span>
							<span class="minbtn"><?php echo $value['FlowStage']['state_flow'].' , '.$value['FlowStage']['created']; ?></span>
						</button>
						<?php if (!isset($this->request->data["modal"])): ?>
							<span class="btnnovedadesetapa" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id']; ?>" data-etapa="<?php echo $value['FlowStage']['state_flow'] ?>" data-toggle="tooltip" data-placement="right" title="Añadir Gestión">
								<i class="fa fa-clone"></i>
							</span>
						<?php endif ?>
					</h5>
				</div>
				<div id="etapa<?php echo $value['FlowStage']['id'] ?>" class="collapse <?php echo $this->Utilities->validate_show_accordeon($value['FlowStage']['id'],$idLatestRegystri) ?> pdetapa" aria-labelledby="headq<?php echo $value['FlowStage']['id'] ?>" data-parent="#accordion1">
					<div class="spaceangle"></div>
					<div class="card-body">

						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_contactado')): ?>
							<b>Contacto: </b><?php echo $this->Utilities->flujo_contactado_user($value['FlowStage']['contact']); ?><br>
							<b>Origen: </b><?php echo $value['FlowStage']['origin'] ?><br>
						<?php endif ?>

						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_asignado') || $value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_contactado')): ?>
							<b> <?php echo $datosProspecto["ProspectiveUser"]["valid"] == 1 ? "Solicitud:" : "Razón:" ?>  </b><?php echo $value['FlowStage']['reason'] ?><br>
							<div class="imgdesc"><b>Descripción: </b><?php echo $value['FlowStage']['description'] ?></div>
							<?php if ($user_asigno != 0): ?>
								<b>Asignó: </b><?php echo $this->Utilities->find_name_lastname_adviser($user_asigno) ?>
							<?php endif ?>
						<?php endif ?>

						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_contactado')): ?>
							<?php if ($value['FlowStage']['cotizacion'] == "1"): ?>
								<b>Estado de cotización: </b><?php echo $this->Utilities->cotizacion_enviada($value['FlowStage']['send']); ?>
							<?php endif ?>
							<?php if (!empty($value["FlowStage"]["image"])): ?>
								<br>
								<div class="Comprobanteacep imgbuy">
									<img datacomprobante="<?php echo $this->Html->url('/img/flujo/contactado/'.$value["FlowStage"]["image"]) ?>" src="<?php echo $this->Html->url('/img/flujo/contactado/'.$value["FlowStage"]["image"]) ?>" class="comprobanteimg" width="0px">
									Prueba de contacto al cliente &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
								</div>
							<?php endif ?>
						<?php endif ?>

						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_cotizado')): ?>
							<div><b>Precio: </b> $<?php echo number_format( str_replace(",", "", $value['FlowStage']['priceQuotation']) ,2); ?></div>
							<div><b>Codigo de la cotización: </b><?php echo $value['FlowStage']['codigoQuotation'] ?></div>
							<b>Cotización: </b>
							<?php if ($this->Utilities->exist_file(WWW_ROOT.'/files/flujo/cotizado/'.$value['FlowStage']['document'])) { ?>
								<a target="_blank" href="<?php echo $this->Html->url('/files/flujo/cotizado/'.$value['FlowStage']['document']) ?>">
									<i class="fa fa-file-text fa-x"></i>
									<?php $documentoURL = $this->Html->url('/files/flujo/cotizado/'.$value['FlowStage']['document']) ?>
									<?php echo $this->Utilities->find_name_file_quotation($value['FlowStage']['nameDocument'],$value['FlowStage']['document']) ?>
								</a>
							<?php } else { ?>
								<?php $documentoURL = $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($value['FlowStage']['document']))) ?>
								<a target="_blank" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action' => 'view',$this->Utilities->encryptString($value['FlowStage']['document']))) ?>">
									<i class="fa fa-file-text fa-x"></i>
									<?php echo $this->Utilities->find_name_file_quotation($value['FlowStage']['nameDocument'],$value['FlowStage']['document']) ?>
								</a>
							<?php } ?>
							<br>

							<!-- <b>Url de la cotización en el servidor:</b>
							<div>
								<p id="copyurl">
								    crm.kebco.co<?php echo $documentoURL ?>
								</p>
							</div> -->
							
							<b>Copias enviadas a: </b><p class="copiasemailinput"><?php echo $this->Utilities->data_null(h($value['FlowStage']['copias_email'])); ?></p>
							
							<?php if ($datosProspecto['ProspectiveUser']['state_flow'] < Configure::read('variables.control_flujo.flujo_negociado')): ?>
								<?php if ($value['FlowStage']['id'] == $latsetCotizado): ?>
									<a class="alingicon" id="btn_cambiar_estado" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-flowstages="<?php echo $idEstadoContacdo; ?>" data-toggle="tooltip" data-placement="right" title="Crear una nueva cotización">
										HACER OTRA &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
									&nbsp; 
									&nbsp; 
									&nbsp; 
									&nbsp; 

									<a class="btn btn-warning d-inline-block d-table-cell ml-4 reenviarCot" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-flowstages="<?php echo $idEstadoContacdo; ?>" data-toggle="tooltip" data-placement="right" title="Reenviar cotización de nuevo al correo electrónico">
										Reenviar cotización &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								<?php endif ?>
							<?php endif ?>
						<?php endif ?>

						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_negociado')): ?>
							<div class="comentariosnegociado"><b>Comentarios: </b> <?php echo $value['FlowStage']['description'] ?></div>
							<?php if ($value['FlowStage']['document'] != ''): ?>

								<?php $isPdfFile = strpos(strtolower($value['FlowStage']['document']), ".pdf" ); ?>

								<?php if ($isPdfFile === false): ?>
									

								<a class="alingicon d-inline-block" target="_blank" href="<?php echo $this->Html->url('/img/flujo/negociado/'.$value['FlowStage']['document']) ?>">
									Ver aprobación &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
								</a>
								<?php else: ?>
									<a class="alingicon d-inline-block" target="_blank" href="<?php echo $this->Html->url('/files/flujo/negociado/'.$value['FlowStage']['document']) ?>">
									Ver aprobación &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
								</a>
								<?php endif ?>
							<?php endif ?>
							<?php if ($value["FlowStage"]["cotizacion"] != 0): ?>
								<a class="btn btn-success d-inline-block" target="_blank" href="<?php echo $this->Html->url(["controller"=>"orders","action"=>"view",$this->Utilities->encryptString($value["FlowStage"]["cotizacion"])]) ?>">
									Orden de pedido - Remisión &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
								</a>
							<?php endif ?>
						<?php endif ?>

						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_pagado') || $value['FlowStage']['state_flow'] == 6): ?>

							<p class="resetmargin">
								<b>Valor: </b>$<?php echo number_format((int)h($value['FlowStage']['valor']),0,",","."); ?> 
								<?php echo $this->Utilities->type_pay_quotation($value['FlowStage']['type_pay']); ?> 
								a través de 
								<?php echo $value['FlowStage']['payment']; ?>
							</p>

							<?php if ($value['FlowStage']['type_pay'] == 2): ?>
								<div class="stylebtn">
									<a href="javascript:void(0)" class="find_payments_flujo" data-toggle="tooltip" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" title="Pagos realizados">Ver más pagos<i class="fa fa-question" aria-hidden="true"></i></a>
								</div>
							<?php endif ?>
							<?php 
								$estados_credito = array('3','5');
								if (!in_array($value['FlowStage']['type_pay'],$estados_credito)){
							?>
								<div class="Comprobanteacep imgbuy">
									<img datacomprobante="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['FlowStage']['document']) ?>" src="<?php echo $this->Html->url('/img/flujo/pagado/'.$value['FlowStage']['document']) ?>" class="comprobanteimg" width="0px">
									Comprobante de pago &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
								</div>
							<?php } else { ?>
								<b>Número de días para el crédito: </b><?php echo $this->Utilities->number_day_payment_text($value['FlowStage']['payment_day']); ?><br>
							<?php } ?>

							<div class="statushop"><b>Estado del pago: </b><?php echo $this->Utilities->check_state_flow_stage($value['FlowStage']['state']) ?></div>
							<?php if ($value['FlowStage']['state'] == 4): ?>
								<b>Mótivo del rechazo: </b><?php echo $value['FlowStage']['payment_false_description']; ?>
							<?php endif ?>
							<?php if ($datosProspecto['ProspectiveUser']['state_flow'] < Configure::read('variables.control_flujo.flujo_cancelado')): ?>
									<?php if ($value['FlowStage']['type_pay'] != 2){ ?>
											<div class="statushopicon uno 2"><?php echo $this->Utilities->validate_active_pago_flujo($value['FlowStage']['state'],$datosProspecto['ProspectiveUser']['id'], $datosProspecto["ProspectiveUser"], true); ?></div>
								<?php } else { ?>

									<div class="statushopicon uno 3"><?php echo $this->Utilities->validate_active_pago_new_abono($value['FlowStage']['state'],$datosProspecto['ProspectiveUser']['id'],$idLatestRegystri,$value['FlowStage']['id']); ?></div>
									<div class="statushop"><?php echo $this->Utilities->verificate_payment_flujo($value['FlowStage']['state'],$idLatestRegystri,$value['FlowStage']['id'],$datosProspecto['ProspectiveUser']['id']); ?></div>

										<div class="statushopicon uno 4 "><?php echo $this->Utilities->validate_pedido_importacion_pago_abono($value['FlowStage']['state'],$datosProspecto['ProspectiveUser']['id'], $datosProspecto["ProspectiveUser"]); ?></div>
									
									<!-- <div class="statushop">

										<?php //if ($totalCop > 0): ?>
											<?php //$total90 = ($totalCop+$totalParaIva)*0.9; ?>
											<?php //if ($totalPagado >= $total90): ?>
												<?php //echo $this->Utilities->verificate_payment_flujo($value['FlowStage']['state'],$idLatestRegystri,$value['FlowStage']['id'],$datosProspecto['ProspectiveUser']['id']); ?>												
											<?php //endif ?>
										<?php //else: ?>
											<?php //echo $this->Utilities->verificate_payment_flujo($value['FlowStage']['state'],$idLatestRegystri,$value['FlowStage']['id'],$datosProspecto['ProspectiveUser']['id']); ?>
										<?php //endif ?>


									</div> -->
								<?php } ?>
								<?php if ($this->Utilities->validate_new_quotation($idLatestRegystri,$value['FlowStage']['id'],$value['FlowStage']['state'],$datosProspecto['ProspectiveUser']['id'])): ?>
									<a class="statushop state_pagado" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-state="4">
										Enviar un nuevo pago
									</a>
								<?php endif ?>
								<?php if (AuthComponent::user("role") != "Asesor Externo" && AuthComponent::user("Gerente General") ): ?>
									<a class="btn btn-warning d-inline-block quotationWO" data-flujo="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-qt="<?php echo $idFlowstage ?>" href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'from_wo',$datosProspecto['ProspectiveUser']['id'],$idFlowstage)) ?>" data-toggle="tooltip" data-placement="right" title="Hacer cotización">
										CREAR COTIZACIÓN DESDE FACTURA WO <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								<?php endif ?>
							<?php endif ?>
						<?php endif ?>


						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.datos_despacho')): ?>
							<b>Contacto de entrega: </b><?php echo $value['FlowStage']['contact']; ?><br>
							<b>Ciudad de entrega: </b><?php echo $value['FlowStage']['city']; ?><br>
							<b>Dirección de entrega: </b><?php echo $value['FlowStage']['address']; ?> (<?php echo $value['FlowStage']['additional_information']; ?>)<br>
							<b>Condición del Flete: </b><?php echo $value['FlowStage']['flete']; ?><br>
							<b>Copias del correo electrónico: </b><?php echo $this->Utilities->data_null(h($value['FlowStage']['copias_email'])); ?>
						<?php endif ?>

						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_despachado')): ?>
							<b>Numero de guía: </b><?php echo $value['FlowStage']['number'] ?><br>
							<b>Transportadora: </b><?php echo $value['FlowStage']['conveyor'] ?><br>
							<b>Número de equipos enviados: </b><?php echo $value['FlowStage']['products_send'] ?><br>
							<?php if ($value['FlowStage']['document'] != ''): ?>
								<?php $pos = strpos(strtolower($value['FlowStage']['document']), '.pdf'); ?>

								<?php if ($pos === false): ?>
									<div class="Comprobanteacep imgbuy">
										<img datacomprobante="<?php echo $this->Html->url('/img/flujo/despachado/'.$value['FlowStage']['document']) ?>" src="<?php echo $this->Html->url('/img/flujo/despachado/'.$value['FlowStage']['document']) ?>" class="comprobanteimg" width="0px">
										VER GUIA &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</div>
								<?php else: ?>
									<a class="alingicon d-inline-block" target="_blank" href="<?php echo $this->Html->url('/files/flujo/despachado/'.$value['FlowStage']['document']) ?>">
										Ver gúia &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
									</a>
								<?php endif ?>
							<?php endif ?>
						<?php endif ?>

						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.asignado_flujo_proceso')): ?>
							<b>Descripción: </b><?php echo $value['FlowStage']['description'] ?>
						<?php endif ?>

						<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_no_valido')): ?>
							<b>Origen: </b><?php echo $value['FlowStage']['origin'] ?><br>
							<b>Razón: </b><?php echo $value['FlowStage']['reason'] ?><br>
							<div class="imgdesc"><b>Descripción: </b><?php echo $value['FlowStage']['description'] ?></div>
						<?php else: ?>	
							<?php if ($value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.datos_despacho') || $value['FlowStage']['state_flow'] == Configure::read('variables.nombre_flujo.flujo_despachado') ): ?>
								<br>
								<a href="#" class="btn btn-danger bg-danger p-1 btnChargeFacture"  data-id="<?php echo $value['FlowStage']['prospective_users_id'] ?>">
					                <i class="fa fa-x fa-plus-circle"></i> 
					                Nueva factura
					            </a>
							<?php endif ?>
							
						<?php endif ?>

					</div>
				</div>
			</div>
		<?php endforeach ?>

		
		<div class="row">
			
		<?php if ($datosProspecto['ProspectiveUser']['state_flow'] > Configure::read('variables.control_flujo.flujo_asignado') && $datosProspecto['ProspectiveUser']['state_flow'] <= Configure::read('variables.control_flujo.flujo_pagado')): ?>
			<div class="col-md-<?php echo in_array($datosProspecto['ProspectiveUser']['state_flow'], [3,4,5,6]) && AuthComponent::user("role") == "Gerente General" ? "6" : "12"; ?>">
				<a class="btn_cancelar_flujo btn btn-outline-danger btn-block" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-toggle="tooltip" title="¡No podrás retomarlo después!">
					Dar por terminado el flujo &nbsp; <i class="fa-1x fa fa-times"></i>
				</a>
			</div>
			<div class="col-md-6">
			<?php if ($datosProspecto["ProspectiveUser"]["state"] != 4): ?>
				
				<?php if (in_array($datosProspecto['ProspectiveUser']['state_flow'], [3,4,5,6,8]) && (!isset($modal) || (isset($modal) && !$modal ) ) ): ?>
					<a class="btnRetornoFlujo mt-3 btn btn-outline-info btn-block" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-state="<?php echo $value['FlowStage']['state_flow'] ?>">
						Retornar flujo &nbsp; <i class="fa-1x fa fa-arrow-left"></i>
					</a>
				<?php endif ?>
			<?php else: ?>
				<p class="text-warning mt-4">
					Actualmente el flujo se encuentra en valídación de devolución.					
				</p>
			<?php endif ?>
			</div>
		<?php endif ?>
		</div>

		<?php if ($datosProspecto['ProspectiveUser']['state_flow'] == Configure::read('variables.control_flujo.flujo_cancelado')): ?>
			<b>Motivo de la cancelación del flujo: </b>
			<?php echo $this->Utilities->data_null(h($datosProspecto['ProspectiveUser']['description'])); ?>
			<br>
			<a class="alingicon reactivar_flujo" data-uid="<?php echo $datosProspecto['ProspectiveUser']['id'] ?>" data-toggle="tooltip" title="Volver a activar el flujo">
				Reactivar flujo &nbsp; <i class="fa-1x fa fa-arrow-circle-o-right"></i>
			</a>
		<?php endif ?>

		<?php if ($datosProspecto['ProspectiveUser']['state_flow'] == Configure::read('variables.control_flujo.flujo_finalizado')): ?>
			<div class="flujo_finalizado_text"><?php echo $this->Utilities->check_state_prospective($datosProspecto['ProspectiveUser']['state_flow']) ?></div>
		<?php endif ?>

		<?php if ($datosProspecto['ProspectiveUser']['state_flow'] == Configure::read('variables.control_flujo.flujo_no_valido')): ?>
			<b>Flujo no válido</b>
		<?php endif ?>

	

	</div>
</div>
<?php endif ?>