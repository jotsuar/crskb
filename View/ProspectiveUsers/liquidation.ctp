<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">LIQUIDACIÓN DE COMISIONES</h1>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12">
	<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
	<?php if (!in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])) {
		$usuarios = [AuthComponent::user("id") => AuthComponent::user("name")];
	} ?>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-4">
				<h1 class="nameview2">LIQUIDACIÓN DE COMISIONES</h1>
				<span class="subname">Loquidación de comisiones teniendo en cuenta las facturas y los recibos de caja</span>
			</div>
			<div class="col-md-8">
				<div class="row">
					
						<div class="col-md-12">
							<div class="row">
								
							</div>
							<div class="row">
								<div class="hidden" style="display: none;">
									<span>Estado del pago</span>
									<?php echo $this->Form->input('state',array('label' => false, 'id' => 'state_receipt', 'options' => ["1" => "Pago", "0" => "Sin pagar" ],"empty"=> "Pagados y sin pagar", 'class' => 'form-control','default'=> 0,"value"=>$state));
									?>
								</div> 
								<div class="col-md-2">
									<span>Asesor</span>
									<?php echo $this->Form->input('user',array('label' => false, 'id' => 'usuario', 'options' => $usuarios, 'class' => 'form-control'));
									?>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<span>Seleccionar rango de fechas:</span>
									</div>
									<div class="form-group">
										<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
									</div>

								</div>
								<div class="col-md-3 resetlabel">
									<div class="form-group">
										<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
											<?php echo $this->Form->input('excel',array("required" => true, "label" => "Generar Informe detallado" ,"options" => Configure::read("IMPUESTOS"))); ?>
										<?php else: ?>
											<?php echo $this->Form->input('excel',array("required" => true, "value" => 0, "label" => "Generar Informe" ,"type" => "hidden")); ?>
										<?php endif ?>
									</div>
								</div>								
								<div style="display: none">
									<span>Desde</span>
									<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
									<span>Hasta</span>
									<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
								</div>

								<div class="col-md-2 spacetop">
									<?php if (!empty($usuarios)): ?>
										<button type="submit" class="btn btn-primary" id="btn_find_adviser">Buscar</button>
									<?php endif ?>
								</div>

							</div>
						</div>
					
				</div>
			</div>
		</div>
	</div>

	<div class="blockwhite">
		<div class="table-responsive">
			<table class="table table-bordered <?php echo empty($sales) ? "" : "datosPendientesDespacho" ?>  table-hovered">
				<thead>
					<tr>	
											
						<th>Flujo</th>
						<th>Cliente</th>
						<th>Factura</th>
						<th>Fecha factura</th>
						<th>Recibo</th>
						<th>Fecha recibo</th>
						<th>Valor venta</th>
						<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
							<th>Utilidad</th>
							<th>Margen Factura</th>
						<?php endif ?>
						<th>Valor recaudo <br> con IVA</th>
						<th>Base</th>
						<th>Días pago</th>
						<th>% Com. <br> por recaudo </th>
						<th>Comisión <br> por recaudo</th>
						<th>% Com. <br> por utilidad </th>
						<th>Comisión <br> por utilidad</th>
						<th>Notas</th>
						<th>Factura</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($sales)): ?>
						<?php $totalUtilidad = 0; ?>
						<?php $arrUtils = []; ?>
						<tr>
							<td colspan="18" class="text-center">
								<?php if ($filter): ?>
									<p class="text-danger mb-0">No existen registros de facturación</p>
								<?php else: ?>
									<p class="text-danger mb-0">! Para ver datos por favor realiza una búsqueda ¡</p>									
								<?php endif ?>
							</td>
						</tr>
					<?php else: ?>
						<?php  foreach ($sales as $key => $value): ?>
							<tr>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable flujoModal m-1" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
																		<?php echo $value["ProspectiveUser"]["id"] ?>
																	</a>
								</td>

								<td class="text-uppercase">
									<?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?>
								</td>
								<td class="text-uppercase"><?php echo $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code") ?></td>
								<td><?php echo ( $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date")); ?></td>
								<td class="text-uppercase"><?php echo $value["Receipt"]["code"] ?></td>
								<td><?php echo ($value["Receipt"]["date_receipt"]) ?></td>
								<td> $ <?php echo number_format($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value"),0,".",",") ?></td>
								<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
									<td>
										
										 <?php echo number_format($value["Valores"]["utilidad_factura"],2,",","."); ?>$ 

										 <?php 
										 	if(!in_array($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code"), $arrUtils)){
										  		$totalUtilidad+=$value["Valores"]["utilidad_factura"]; 
										  		$arrUtils[] = $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code");
										 	}
										 ?>

									</td>
									<td>
										
										 <?php echo number_format( ($value["Valores"]["utilidad_factura"] / $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value")) * 100 ,2,",","."); ?> %

									</td>
								<?php endif ?>
								<td> $ <?php echo number_format($value["Receipt"]["total"],0,".",",") ?></td>
								<td> $ <?php echo number_format($value["Receipt"]["total_iva"],0,".",",") ?></td>
								<td><?php $dias = $value["Finals"]["dias"]; echo $dias; ?></td>
								<td class="border border-info p-1" style="border-color: #004990 !important;">
									<?php 
										$percentaje =  $value["Finals"]["percentaje"];
									?>

									<div class="pointer text-center price-purchase_price_usd <?php echo in_array(AuthComponent::user("role"), array("Gerente General")) ? "cambioCostoDataUsd" : "" ?>" data-id="<?php echo $value["Receipt"]["id"] ?>" data-type="purchase_price_usd" data-price="<?php echo is_null($value['Receipt']['percent_value']) ? $percentaje : $value['Receipt']['percent_value'] ?>" data-currency="USD">
											<b class="font16"><?php echo is_null($value['Receipt']['percent_value']) ? $percentaje : $value['Receipt']['percent_value'] ?></b>
										</div>
								</td>
								<td>$ <?php

										$pagar 		  =  $value["Finals"]["pagar"];

										echo number_format($pagar);
										?>
								</td>
								<td >
									<?php echo $value["Finals"]["percentUtility"]  ?>
								</td>
								<td>
									<?php echo number_format($value["Finals"]["pagarByPercent"]) ?> 
								</td>
								<td>
									<?php echo $value["Finals"]["nota"]  ?>
								</td>
								<td style="    width: 100px;">
									<?php $dataBill = $this->Utilities->getDataBill($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"]); ?>

									<?php if (empty($dataBill)): ?>
										<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$dataBill["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary">
											Ver factura <i class="fa fa-file vtc"></i>
										</a>
									<?php else: ?>
										<a href="#" target="blank" class="btn btn-info btn-secondary mostradDatosFact btn-sm" data-id="KEB_<?php echo md5($dataBill["bill_code"]) ?>">
											Ver factura <i class="fa fa-file vtc"></i>
										</a>

										<div id="KEB_<?php echo md5($dataBill["bill_code"]) ?>" class="table-responsive" style="display: none;">
											<?php $factValue = (array) json_decode($dataBill["bill_text"]); ?>
											<?php echo $this->element("vistaFacturaWo", ["factValue" => $factValue]); ?>
										</div>

									<?php endif ?>

									
								</td>
							</tr>
						<?php endforeach ?>
						<tr class="font22 text-center">
							<td colspan="18">
								<div class="row">
									<div class="col-md-5">

										<div class="card">
										  <div class="bg-blue card-header font-weight-bold">
										    Metas y valores
										  </div>
										  <ul class="list-group list-group-flush">
										    <li class="list-group-item font20 border-top mb-5 mt-5">
										    	<strong>
										    		Demora en ingreso total:
										    	</strong>
										    	<?php if ($demora["dias"] > 0): ?>
												Días: <?php echo $demora["dias"] ?>
											<?php endif ?>
											<?php if ($demora["horas"] > 0): ?>
												Horas: <?php echo $demora["horas"] ?>
											<?php endif ?>
											<?php if ($demora["minutos"] >= 0): ?>
												Minutos: <?php echo $demora["minutos"] ?>
											<?php endif ?>
										    </li>
										    <li class="list-group-item font20 border-top mb-5">
										    	<strong>
										    		Meta de ventas:
										    	</strong>
										    	$<?php echo number_format($metaVentas) ?> / <strong>
										    		Total ventas:
										    	</strong>
										    	$<?php echo number_format($totalVentas) ?>
										    </li>
										    <li class="list-group-item font20 border-top mb-5">
										    	<strong>
										    		Utilidad en las ventas del mes:
										    	</strong>
										    	$<?php echo number_format($totalUtilidadVentas) ?> / <strong>
										    		Margen:
										    	</strong>
										    	$<?php echo number_format($totalMargen) ?> %
										    </li>
										    <li class="list-group-item font20 mb-5">
										    	<strong>
										    		Meta de recaudo:
										    	</strong>
										    	$<?php echo number_format($meta) ?> / <strong>
										    		Total recaudado:
										    	</strong>
										    	$<?php echo number_format($saldos) ?>
										    </li>
										    <li class="list-group-item font20 mb-5 border-bottom">
										    	<strong>
										    		Efectividad periodo:
										    	</strong>
										    	<?php echo isset($dataEfectivity["efectividad"]) ? $dataEfectivity["efectividad"] : 0 ?>% / <strong>
										    		% a pagar según efectividad de <span class="text-success"><?php echo isset($dataEfectivity["numVentas"]) ? $dataEfectivity["numVentas"] : 0 ?></span> ventas de <?php echo $dataEfectivity["totalAsignados"] ?> flujos:
										    	</strong>
										    	<?php echo $percentajeDb["Effectivity"]["value"] ?> %	
										    </li>
										  </ul>
										</div>										
									</div>
									<div class="col-md-7">
										<div class="card">
										  <div class="bg-blue card-header font-weight-bold">
										    Valores a pagar
										  </div>
										  <ul class="list-group list-group-flush">
										  	<li class="list-group-item">
										  		<div class="row">
										  			
										  			<div class="col-md-6">
										  				<!--  -->

										  				<div class="border-left border-info card h-10000 shadow">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
								                                        <div class="text-xs text-azul text-uppercase d-inline">
								                                        	Comisión por recaudo:
								                                        </div>
								                                        <div class="h5 mb-0 font-weight-bold text-gray-800 d-inline">
								                                        	<?php if ($saldos < $meta): ?>
																														No cumple con el recaudo mínimo
																													<?php else: ?>
																														$<?php echo number_format($totalPagar,0,".",",") ?>	
																													<?php endif ?>
								                                        </div>
								                                    </div>
								                                    <div class="col-auto">
								                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
								                                    </div>
								                                </div>
								                            </div>
				                        				</div>

										  				<!--  -->
										  			</div>
										  			<div class="col-md-6">
										  				<!--  -->

										  				<div class="border-left border-info card h-10000 shadow">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
								                                        <div class="text-xs text-azul text-uppercase d-inline">
								                                        	Comisión por margen:
								                                        </div>
								                                        <div class="h5 mb-0 font-weight-bold text-gray-800 d-inline">
								                                        	<?php if ($saldos < $meta): ?>
																				No cumple con el recaudo mínimo
																			<?php else: ?>
																				$<?php echo number_format($totalPagarFinal,0,".",",") ?>	
																			<?php endif ?>
								                                        </div>
								                                    </div>
								                                    <div class="col-auto">
								                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
								                                    </div>
								                                </div>
								                            </div>
				                       					 </div>

										  				<!--  -->
										  			</div>
										  			<div class="col-md-12">
										  				<!--  -->

										  				<div class="border-left <?php echo $totalPagarFinal > $totalPagar ? 'border-success' : 'border-danger' ?> card h-10000 shadow mt-2">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
								                                        <div class="text-xs text-azul text-uppercase d-inline">
								                                        	Variación por margen:
								                                        </div>
								                                        <div class="h5 mb-0 font-weight-bold text-gray-800 d-inline">
								                                        	<?php if ($saldos < $meta): ?>
																				No cumple con el recaudo mínimo
																			<?php else: ?>
																				<span class="<?php echo $totalPagarFinal > $totalPagar ? 'text-success' : 'text-danger' ?>">
																					<?php echo $totalPagarFinal > $totalPagar ? '+' : '-' ?> $<?php echo number_format(abs(($totalPagar)-($totalPagarFinal))) ?>	
																				</span>
																			<?php endif ?>
								                                        </div>
								                                    </div>
								                                    <div class="col-auto">
								                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
								                                    </div>
								                                </div>
								                            </div>
				                        				</div>

										  				<!--  -->
										  			</div>
										  			<div class="col-md-12">
										  				<hr>
										  			</div>
										  			<div class="col-md-6">
										  				<!--  -->

										  				<div class="border-left border-info card h-10000 shadow">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
								                                        <div class="text-xs text-azul text-uppercase d-inline">
								                                        	% de pago de efectividad: 
								                                        </div>
								                                        <div class="h5 mb-0 font-weight-bold text-gray-800 d-inline">
								                                        	<?php if ($saldos < $meta): ?>
																				No cumple con el recaudo mínimo
																			<?php else: ?>
																				<?php echo $percentajeDb["Effectivity"]["value"] ?> %	
																			<?php endif ?>
								                                        </div>
								                                    </div>
								                                    <div class="col-auto">
								                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
								                                    </div>
								                                </div>
								                            </div>
				                        				</div>

										  				<!--  -->
										  			</div>
										  			<div class="col-md-6">
										  				<!--  -->

										  				<div class="border-left border-info card h-10000 shadow">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
								                                        <div class="text-xs text-azul text-uppercase d-inline">
								                                        	Comisión por efectividad: 
								                                        </div>
								                                        <div class="h5 mb-0 font-weight-bold text-gray-800 d-inline">
								                                        	<?php if ($saldos < $meta): ?>
																				No cumple con el recaudo mínimo
																			<?php else: ?>
																				$<?php echo number_format($totalFinalEfectivity,0,".",",") ?>	
																			<?php endif ?>
								                                        </div>
								                                    </div>
								                                    <div class="col-auto">
								                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
								                                    </div>
								                                </div>
								                            </div>
				                        				</div>

										  				<!--  -->
										  			</div>
										  			<div class="col-md-12">
										  				<!--  -->

									  					<div class="border-left <?php echo $totalFinalEfectivity > $totalPagarFinal ? 'border-success' : 'border-danger' ?> card h-10000 shadow mt-2">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
								                                        <div class="text-xs text-azul text-uppercase d-inline">
								                                        	Variación por efectividad:
								                                        </div>
								                                        <div class="h5 mb-0 font-weight-bold text-gray-800 d-inline">
								                                        	<?php if ($saldos < $meta): ?>
																				No cumple con el recaudo mínimo
																			<?php else: ?>
																				<span class="<?php echo $totalFinalEfectivity > $totalPagarFinal ? 'text-success' : 'text-danger' ?>">
																					<?php echo $totalFinalEfectivity > $totalPagarFinal ? '+' : '-' ?> $<?php echo number_format(abs(($totalFinalEfectivity)-($totalPagarFinal))) ?>	
																				</span>
																			<?php endif ?>
								                                        </div>
								                                    </div>
								                                    <div class="col-auto">
								                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
								                                    </div>
								                                </div>
								                            </div>
				                       					 </div>

										  				<!--  -->
										  			</div>
										  			<div class="col-md-12">
										  				<hr>
										  			</div>
										  			<div class="col-md-12">
										  				<!--  -->

									  					<div class="border-left <?php echo $totalVentas >= $metaVentas ? 'border-success' : 'border-danger' ?> card h-10000 shadow mt-2">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
								                                        <div class="text-xs text-azul text-uppercase d-inline">
								                                        	Total adicional por cumplir meta de ventas:
								                                        </div>
								                                        <div class="h5 mb-0 font-weight-bold text-gray-800 d-inline">
								                                        	<?php if ($saldos < $meta): ?>
																				No cumple con el recaudo mínimo
																			<?php else: ?>
																				<?php echo $totalVentas >= $metaVentas ?  '$'.number_format($totalEfectivity*0.3,0,".",",") : 'No cumplió la meta de ventas' ?>
																			<?php endif ?>
								                                        </div>
								                                    </div>
								                                    <div class="col-auto">
								                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
								                                    </div>
								                                </div>
								                            </div>
			                       						</div>

										  				<!--  -->
						  							</div>
										  			<div class="col-md-12">
										  				<hr>
										  			</div>
													<?php  if ($totalServicios > 0): ?>
														<div class="col-md-12">
															  				<!--  -->

															<div class="border-left border-success card h-10000 shadow mt-2">
									                            <div class="card-body" style="    padding: 5px !important;">
									                                <div class="row no-gutters align-items-center">
									                                    <div class="col mr-2">
									                                        <div class="text-xs text-azul text-uppercase d-inline">
									                                        	Total servicios técnicos:
									                                        </div>
									                                        <div class="h5 mb-0 font-weight-bold text-gray-800 d-inline">
									                                        	$ <?php echo number_format($totalServicios) ?> 
									                                        </div>
									                                    </div>
									                                    <div class="col-auto">
									                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
									                                    </div>
									                                </div>
									                            </div>
									                        </div>

															  				<!--  -->
											  			</div>
											  		<?php endif ?>
											  		
										  			<div class="col-md-12">
										  				<hr>
										  			</div>
										  			<div class="col-md-12">
														  				<!--  -->

														<div class="border-left border-success bg-success card h-10000 shadow mt-2">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
							                                    		<div class="row">
							                                    			<div class="col">
							                                    				<div class="text-xs font-weight-bold text-white text-uppercase alignforlabel">
									                                        	<span class="pt-2">
									                                        		Total a pagar final: $<?php echo number_format(($meta <= $saldos ? $totalEfectivity  : 0) + $totalServicios - $totalAdescontar,0,".",",") ?>
									                                        	</span>
									                                        </div>
							                                    			</div>
							                                    			<div class="col">
							                                    				<div class="h5 mb-0 font-weight-bold text-gray-800 font14 d-inline ">
										                                        	<table class="table table-hovered alignforlabel" style="color: #ffffffc4">
										                                        		<tr>
										                                        			<th class="p-0" style="border:none !important;">
										                                        				Total comisiones: 
										                                        			</th>
										                                        			<td class="p-0" style="border:none !important;">
										                                        				+ $<?php echo number_format($totalFinalEfectivity) ?>
										                                        			</td>
										                                        		</tr>
										                                        		<tr>
										                                        			<th class="p-0" style="border:none !important;">
										                                        				Total por cumplir meta: 
										                                        			</th>
										                                        			<td class="p-0" style="border:none !important;">
										                                        				+ $<?php echo $totalVentas >= $metaVentas ? number_format($totalEfectivity*0.3,0,".",",") : '0' ?>
										                                        			</td>
										                                        		</tr>
										                                        		<?php  if ($totalServicios > 0): ?>
										                                        		<tr>
										                                        			<th class="p-0" style="border:none !important;">
										                                        				Total servicios técnicos:
										                                        			</th>
										                                        			<td class="p-0" style="border:none !important;">
										                                        				+ $<?php echo number_format($totalServicios) ?>
										                                        			</td>
										                                        		</tr>
										                                        		<tr>
										                                        			<td class="p-0" style="border:none !important;"></td>
										                                        			<td class="p-0" style="border:none !important;     border-top: 1px solid black !important;">
										                                        				$<?php echo number_format(($meta <= $saldos ? $totalEfectivity  : 0) + $totalServicios,0,".",",") ?>
										                                        			</td>
										                                        			
										                                        		</tr>
										                                        		<?php endif ?>
										                                        		<tr>
										                                        			<th class="p-0" style="border:none !important;">
										                                        				Descuento por no cobro de comisión bancaria:
										                                        			</th>
										                                        			<td class="p-0" style="border:none !important;">
										                                        				- $<?php echo number_format($totalAdescontar) ?>
										                                        			</td>
										                                        		</tr>
										                                        		<tr>
										                                        			<td colspan="2" style="display: none !important;">
										                                        				<input type="hidden" name="total_sin_gerencia" id="total_sin_gerencia" class="form-control" min="0" step="10000" value="<?php echo ($meta <= $saldos ? $totalEfectivity  : 0) + $totalServicios - $totalAdescontar ?>">
										                                        			</td>
										                                        		</tr>
										                                        	</table>
							                                        			</div>
							                                    			</div>
							                                    		</div>
								                                        
								                                        
								                                    </div>
								                                    <div class="col-auto">
								                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
								                                    </div>
								                                </div>
								                            </div>
									                    </div>

										  				<!--  -->
										  			</div>
										  			<div class="col-md-12">
															  				<!--  -->

														<div class="border-left border-success card h-10000 shadow mt-2">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
								                                        <div class="text-xs text-azul text-uppercase d-inline">
								                                        	Total Bono gerencia:
								                                        </div>
								                                        <div class="h5 mb-0 font-weight-bold text-gray-800 d-inline">
								                                        	<input type="number" name="total_bono_gerencia" id="total_bono_gerencia" class="form-control" step="1000" value="0">
								                                        </div>
								                                    </div>
								                                    <div class="col-auto">
								                                        <i class="fa fa-dollar fa-2x font28 text-azul"></i>
								                                    </div>
								                                </div>
								                            </div>
								                        </div>

														  				<!--  -->
										  			</div>
										  			<div class="col-md-12">
														  				<!--  -->

														<div class="border-left border-success bg-success card h-10000 shadow mt-2">
								                            <div class="card-body" style="    padding: 5px !important;">
								                                <div class="row no-gutters align-items-center">
								                                    <div class="col mr-2">
							                                    		<div class="row">
							                                    			<div class="col">
							                                    				<div class="text-xs font-weight-bold text-white text-uppercase alignforlabel">
									                                        	<span class="pt-2">
									                                        		Total a pagar final + bono de gerencia: $ <span id="totalizado"><?php echo number_format(($meta <= $saldos ? $totalEfectivity  : 0) + $totalServicios,0,".",",") ?></span>
									                                        	</span>
									                                        </div>
									                                    </div>
									                                </div>
									                            </div>
									                        </div>
									                    </div>
									                </div>
									  			</div>
										  	</li>
										  </ul>									
										</div>	
									</div>
								</div>
								
							</td>
						</tr>
						<tr>
							<td colspan="18">
								<input type="submit" name="guardar_liquidación" class="btn btn-success btn-block" value="Guardar liquidación">
							</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>

	</form>
	
</div>



<div class="modal fade" id="modalFacturaDetalle" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle factura </h2>
      </div>
      <div class="modal-body" id="bodyDetalleFactura">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>



<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript')); 
	echo $this->Html->script(array('lib/jquery.jeditable.min.js?'.rand()),						array('block' => 'AppScript'));
	echo $this->Html->script("controller/prospectiveUsers/comisiones_panel.js?".time(),			array('block' => 'AppScript')); 
?>


<?php echo $this->element("picker"); ?>
<?php echo $this->element("flujoModal"); ?>

<style>
	#formularioQueCambia{
		z-index: 1000000;
	}
</style>