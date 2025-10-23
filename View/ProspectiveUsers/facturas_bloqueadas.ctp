<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titleviewer">Panel de aprobación de facturas</h2>
			</div>
			<div class="col-md-6 text-right">
				<?php if (!empty($datosFinales) && AuthComponent::user("role") == "Gerente General"  ): ?>
				<a href="<?php echo $this->Html->url(array('controller'=>'garantias','action'=>'index')) ?>" class="btn btn-success btnApproveAll"><i class="fa fa-1x fa-check vtc"></i> <span>Aprobar todas</span></a>
				<a href="<?php echo $this->Html->url(array('controller'=>'brands','action'=>'add')) ?>" class="btn btn-danger btnRejectAll"><i class="fa fa-1x fa-times vtc"></i> <span>Rechazar todas</span></a>
				<?php endif ?>
			</div>	
		</div>	
	</div>

	<div class="blockwhite">
		<div class="contenttableresponsive">
			<?php if (empty($datosFinales)  ): ?>
				<h1 class="text-center">
					No hay facturas por aprobar
				</h1>
			<?php endif ?>

	
			<table class="table-hovered tableApprove table table-bordered datosPendientesDespachoDn">
				<thead>
					<tr>						
						<th>Flujo(s)</th>
						<th>Cliente /ID CLIENTE</th>
						<th>Asesor</th>
						<th>Factura</th>
						<th style="width: 100px">Valor <br> Factura </th>
						<th>Valor <br> cotización</th>
						<th>Diferecia</th>
						<th style="width: 130.25px;" class="text-center">#Productos <br> Factura/Cotizazion</th>
						<th style="width: 120px">Fecha factura</th>
						<th style="width: 150px">Fecha bloqueo</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($datosFinales)): ?>
						<tr>
							<td colspan="9" class="text-center">
								<p class="text-danger mb-0">No existen registros de facturación</p>							
							</td>
						</tr>
					<?php else: ?>
						<?php if (!empty($datosFinales)): ?>
							<?php foreach ($datosFinales as $codigoFactura => $flujos): ?>
								<tr>
									<td>

										<?php foreach ($flujos as $key => $value): ?>
											<div class="dropdown d-inline styledrop">
												<a class="btn btn-success dropdown-toggle p-1 rounded btn-sm" href="#" role="button" id="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['ProspectiveUser']['id']) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<?php echo $value['ProspectiveUser']['id'] ?>
												</a>

												<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['ProspectiveUser']['id']) ?>">
													<a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value['ProspectiveUser']['id']); ?>">Ver flujo</a>
													<?php if (in_array($value['ProspectiveUser']['state_flow'], [3,4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>														
														<a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($value['ProspectiveUser']['id']) ?>" href="#">Ver cotización</a>
													<?php endif ?>
													<?php if (in_array($value['ProspectiveUser']['state_flow'], [4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
														<a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $value['ProspectiveUser']['id'] ?>">Ver órden de compra</a>
													<?php endif ?>
													<?php if (in_array($value['ProspectiveUser']['state_flow'], [5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
														<a class="dropdown-item getPagos" href="#" data-flujo="<?php echo $value['ProspectiveUser']['id'] ?>">Ver comprobante(s) de pago</a>
													<?php endif ?>
												</div>
											</div>
											<?php if (!empty($value["OTROS"])): ?>
												<div>
													
													<b>FACT. ASOCIADAS: </b>


													<?php foreach ($value["OTROS"] as $keyOTRO => $valueOTRO): ?>
														<a href="javascript:void(0)" target="blank" class="btn btn-info btn-secondary btn-sm mostradDatos" data-id="KEB_<?php echo md5($keyOTRO) ?>">
															Ver factura <i class="fa fa-file"></i>
														</a>

														<div id="KEB_<?php echo md5($keyOTRO) ?>" class="table-responsive" style="display: none;">
															<?php $factValue = (array) json_decode($valueOTRO); ?>
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

													<?php endforeach ?>
												</div>

											<?php endif ?>
											<hr>
										<?php endforeach ?>
										
									</td>
									<td>
										<?php echo $this->Utilities->name_prospective($flujos[0]['ProspectiveUser']["id"],true) ?>
									</td>
									<td>
										<?php echo $this->Utilities->find_name_adviser($flujos[0]["User"]["id"])  ?>
									</td>
									<td class="text-uppercase align-middle">
										<span class="d-flex">
											<b><?php echo $codigoFactura ?></b>
										</span>
									</td>
									<td> 
										<?php foreach ($flujos as $key => $value): ?>
											<?php if (!empty($value["Salesinvoice"])): ?>
												<?php 

													$factValue = (array) json_decode($value["Salesinvoice"]["bill_text"]);
													$productos = $factValue["productos_factura"];
													$totalFac  = 0;
													foreach ($productos as $kProd => $vProd) {
														$totalFac+= $vProd->Cantidad * $vProd->Precio;
													}

												 ?>
												$ <?php echo number_format($totalFac,0,".",",") ?>
											<?php else: ?>
												<?php 

													$factValue = (array) json_decode($value["ProspectiveUser"]["bill_text"]);
													$productos = $factValue["productos_factura"];
													$totalFac  = 0;
													foreach ($productos as $kProd => $vProd) {
														$totalFac+= $vProd->Cantidad * $vProd->Precio;
													}

												 ?>
												$ <?php echo number_format($totalFac,0,".",",") ?>
											<?php endif ?>
										<?php endforeach ?>
									</td>
									<td>
										<?php $totalFacFlows = 0; ?>
										<?php foreach ($flujos as $key => $value): ?>
											<?php if (!empty($value["Salesinvoice"])): ?>
												$ <?php echo number_format($value["ProspectiveUser"]["valor"],0,".",","); $totalFacFlows+=$value["ProspectiveUser"]["valor"]; ?>
											<?php else: ?>
												$ <?php echo number_format($value["ProspectiveUser"]["valor"],0,".",","); $totalFacFlows+=$value["ProspectiveUser"]["valor"]; ?>
											<?php endif ?>
										<?php endforeach ?>
									</td>
									<td>
										$ <?php echo number_format(abs($totalFac-$totalFacFlows),4,".",",") ?>
									</td>
									<td class="text-center">
										<?php foreach ($flujos as $key => $value): ?>
											<?php if (!empty($value["Salesinvoice"])): ?>
												<?php 
													$factValue = (array) json_decode($value["Salesinvoice"]["bill_text"]);
													$productos = $factValue["productos_factura"];
													$total 	   = 0;
													foreach ($productos as $kProd => $vProd) {
														$total+= $vProd->Cantidad;
													}
												 ?>
												<?php echo $total ?> / <?php echo $value["ProspectiveUser"]["totalProds"] ?>
											<?php else: ?>
												<?php 
													$factValue = (array) json_decode($value["ProspectiveUser"]["bill_text"]);
													$productos = $factValue["productos_factura"];
													$total 	   = 0;
													foreach ($productos as $kProd => $vProd) {
														$total+= $vProd->Cantidad;
													}
												?>
												<?php echo $total ?> / <?php echo $value["ProspectiveUser"]["totalProds"] ?>
											<?php endif ?>
										<?php endforeach ?>
										
									</td>
									<td>
										<small>
											
										<?php foreach ($flujos as $key => $value): ?>
											<?php if (!empty($value["Salesinvoice"])): ?>
												<?php echo $this->Utilities->date_castellano($value["Salesinvoice"]["bill_date"]) ?>
											<?php else: ?>
												<?php echo $this->Utilities->date_castellano($value["ProspectiveUser"]["bill_date"]) ?>
											<?php endif ?>
										<?php endforeach ?>
										
										</small>
									</td>
									<td>
										<small>
											
										<?php foreach ($flujos as $key => $value): ?>
											<?php if (!empty($value["Salesinvoice"])): ?>
												<?php echo $this->Utilities->date_castellano($value["Salesinvoice"]["date_locked"]) ?>
											<?php else: ?>
												<?php echo $this->Utilities->date_castellano($value["ProspectiveUser"]["date_locked"]) ?>
											<?php endif ?>
										<?php endforeach ?>
										
										</small>
									</td>
									<td class=" align-middle">
										<?php $flujosData = []; $others = []; $quotations = []; ?>
										<?php foreach ($flujos as $key => $value) {
											if (!empty($value["Salesinvoice"])) {
												$others[] = $value["Salesinvoice"]["id"];
											}else{
												$flujosData[] = $value["ProspectiveUser"]["id"];
											}
											$quotations[] = $this->Utilities->getQuotationId($value['ProspectiveUser']['id']);
										} 
										?>
										<span class="d-flex">
										<a href="javascript:void(0)" class="btn btn-info mostradDatosFact" data-toggle="tooltip" title="Ver detalle" data-id="KEB_<?php echo md5($codigoFactura) ?>" data-invoice="<?php echo implode(",", $others) ?>" data-uid="<?php echo implode(",", $flujosData) ?>" data-quotation="<?php echo implode(",", $quotations) ?>">
											<i class="fa fa-eye vtc"></i>
										</a>
										<!-- <a href="" class="btn btn-warning actionBill" data-type="2" data-invoice="<?php echo implode(",", $others) ?>" data-id="<?php echo implode(",", $flujosData) ?>"><i class="fa fa-check vtc"></i>Enviar mensaje</a> -->
										<a href="" class="btn btn-success actionBill approbeBtn" data-toggle="tooltip" title="Aprobar" data-type="1" data-invoice="<?php echo implode(",", $others) ?>" data-id="<?php echo implode(",", $flujosData) ?>"><i class="fa fa-check vtc"></i></a>
										
										<a href="" class="btn btn-danger actionBill rejectBtn" data-toggle="tooltip" title="Rechazar" data-type="3" data-invoice="<?php echo implode(",", $others) ?>" data-id="<?php echo implode(",", $flujosData) ?>"><i class="fa fa-times vtc"></i></a>
									</span>
									</td>
								</tr>
								
							<?php endforeach ?>
						<?php endif ?>
					<?php endif ?>
				</tbody>
			</table>

			
		</div>
	</div>
</div>


<?php if (!empty($datosFinales)): ?>
	<?php foreach ($datosFinales as $codigoFactura => $flujos): ?>
		<?php foreach ($flujos as $key => $value): ?>
			<div id="KEB_<?php echo md5($codigoFactura) ?>" class="table-responsive" style="display: none;">
				<?php if (!empty($value["Salesinvoice"])): ?>
					<?php $factValue = (array) json_decode($value["Salesinvoice"]["bill_text"]); ?>
				<?php else: ?>
					<?php $factValue = (array) json_decode($value["ProspectiveUser"]["bill_text"]); ?>
				<?php endif ?>
				<?php echo $this->element("vistaFacturaWo", ["factValue" => $factValue, "valores" => $value["Valores"]]); ?>
			</div>
		<?php endforeach; ?>
	<?php endforeach; ?>
	
<?php endif ?>


<?php 
	$whitelist = array(
            '127.0.0.1',
            '::1'
        ); 
?>

<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/facturas/actions.js?".rand(),			array('block' => 'AppScript')); 
?>

<script>
	var roleGerente = "<?php echo AuthComponent::user("role") == "Gerente General" ? 1 : 0 ?>";
	var actual_uri2 = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url2 = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";
</script>


<?php echo $this->element("flujoModal",["aprobar" => true]); ?>

<div class="modal fade" id="modalFacturaDetalle" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg4" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle factura </h2>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-6" id="detalleCotizacionDiv" style="max-height: 500px;overflow-y: auto;">
      			
      		</div>
      		<div class="col-md-6" id="bodyDetalleFactura">
      			
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
      	<div class="w-75">
      		
	      	<div class="row">
	      		<div class="col-md-4">
	      			<a href="" class="btn btn-success btn-sms actionBill aprobeModal" data-type="1" data-invoice="0" data-id="">Aprobar</a>
	      		</div>
	      		<div class="col-md-4">
	      			<a href="" class="btn btn-warning btn-sms actionBill approbeMesageModal" data-type="2" data-invoice="0" data-id="">Aprobar y enviar mensaje</a>
	      		</div>
	      		<div class="col-md-4">
	      			<a href="" class="btn btn-danger btn-sms actionBill RejectMessageModal" data-type="3" data-invoice="" data-id="">Rechazar</a>
	      		</div>
	      	</div>
      	</div>
        <a class="btn btn-outline-dark cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="modalFactura" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle factura </h2>
      </div>
      <div class="modal-body" id="bodyDetalleFacturaBloqueada">

      </div>
      <div class="modal-footer">
        <a class="btn btn-outline-dark cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="popup" style="width: 60%;">
	<span class="cierra"> <i class="fa fa-remove"></i> </span>
		<img src="" id="img-product" alt="">
		<p id="contenido"></p>
	</div>
<div class="fondo"></div>


<script>
	
	
</script>


<style>	

		.btnSm{
			padding: 0.25rem 0.5rem !important;
		    font-size: 0.875rem !important;
		    line-height: 1.5 !important;
		    border-radius: 0.2rem !important;
		}

		.bgInfo {
		    background-color: #3f5dcb1f !important;
		}

</style>