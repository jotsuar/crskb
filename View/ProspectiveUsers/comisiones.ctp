<div class="blockwhite p-1">
	<div class="col-md-12">
		<h1 class="nameview2">INFORME DE COMISIONES</h1>
		<span class="subname">Informe de comisiones teniendo en cuenta las facturas y los recibos de caja</span>
	</div>
	<ul class="nav nav-tabs nav-pills border-0" id="myTab" role="tablist">
	  <li class="nav-item" role="presentation">
	    <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Datos comisiones</button>
	  </li>
	  <li class="nav-item" role="presentation">
	    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Datos simulador</button>
	  </li>
	</ul>
	<div class="tab-content" id="myTabContent">
	  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
	  	<div id="totalesYReferencia">
	  		
	  	</div>
	  	<div class="table-responsive" id="tablaDeValores">
	  		<h2 class="text-center my-3 detalleFinalComisionesUser">
	  			Detalle de comisiones 	  			
	  		</h2>
			<table id="detalleFinalComisionesUser" class="table detalleFinalComisionesUser table-bordered <?php echo empty($sales) ? "" : "datosPendientesDespacho" ?>  table-hovered" style="font-size: 11px;">
				<thead>
					<tr>						
						<th>Flujo</th>
						<th>Cliente</th>
						<th>Factura</th>
						<th>Recibo</th>
						<th>Valor venta</th>
						<?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] )): ?>
							
						<th>Utilidad</th>
						<?php endif ?>
						<th>Valor pago</th>
						<th>Base</th>
						<th>Días pago</th>
						<th>% </th>
						<th>Comisión</th>
						<th>Notas</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($sales)): ?>
						<tr>
							<td colspan="13" class="text-center">
								<?php if ($filter): ?>
									<p class="text-danger mb-0">No existen registros de facturación</p>
								<?php else: ?>
									<p class="text-danger mb-0">! Para ver datos por favor realiza una búsqueda ¡</p>									
								<?php endif ?>

							</td>
						</tr>
					<?php else: ?>
						<?php $totalNormal = 0; $totalPagar = 0; foreach ($sales as $key => $value): ?>
							<tr>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable flujoModal m-1" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
										<?php echo $value["ProspectiveUser"]["id"] ?>
									</a>

									<br>
									<b>Estado actual:</b> <?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?>
								</td>

								<td class="text-uppercase">
									<?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?>
								</td>
								<td class="text-uppercase">
									<b><?php echo $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code") ?></b>
									<br>
									<?php echo $this->Utilities->date_castellano( $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date")); ?>				
								</td>
								<td class="text-uppercase"><b><?php echo $value["Receipt"]["code"] ?></b> <br><?php echo $this->Utilities->date_castellano($value["Receipt"]["date_receipt"]) ?></td>
								<td> $ <?php echo number_format($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value"),0,".",",") ?></td>
								<?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] )): ?>
									<td>
										
										<b>Valor factura: </b> <?php echo number_format($value["Valores"]["valor_factura"],2,",","."); ?>$ <br>
										<b>Costo factura: </b> <?php echo number_format($value["Valores"]["costo_factura"],2,",","."); ?>$ <br>
										<b>Utilidad en pesos: </b> <?php echo number_format($value["Valores"]["utilidad_factura"],2,",","."); ?>$ <br>
										<b>Margen sobre la venta: </b> <?php echo number_format($value["Valores"]["utilidad_porcentual"],2,",","."); ?>% <br>
										<?php if (!is_null($value["Valores"]["percentFinalData"])): ?>
											<b>Comisión a pagar según % de margen: </b> <?php echo number_format($value["Valores"]["percentFinalData"],2,",","."); ?>% <br>
										<?php endif ?>

									</td>
								<?php endif ?>
								<td> $ <?php echo number_format($value["Receipt"]["total"],0,".",",") ?></td>
								<td> $ <?php echo number_format($value["Receipt"]["total_iva"],0,".",",") ?></td>
								<td><?php $dias = $this->Utilities->calculateDays($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date"),$value["Receipt"]["date_receipt"]); echo $dias; ?></td>
								<td><?php 
										$percentaje =  $this->Utilities->getComissionPercentaje($dias,$comision); 

										if (!is_null($value["Valores"]["percentFinalData"])){
											$percentajeNuevo = $percentaje * ($value["Valores"]["percentFinalData"]/100);
											echo $percentajeNuevo;
										}else{
											echo $percentaje; 
										}

										?></td>
								<td>$ <?php
										if ($value["Valores"]["totalProductos"] == 1 && $value["Valores"]["totalSt"] > 0) {
											$value["Receipt"]["total_iva"] = $value["Valores"]["valorSt"];
										}
										
										$pagar 		= ($percentaje / 100) * floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"]) + ($value["Valores"]["valorBySt"]);
										$totalNormal += $pagar;


										if (!is_null($value["Valores"]["percentFinalData"])){
											$pagar = $pagar * ($value["Valores"]["percentFinalData"]/100);
										}

										$totalPagar += $pagar; echo number_format($pagar,0,".",",") ?>


								</td>
								<td>
									<?php if ($value["Valores"]["totalSt"] > 0): ?>
										<?php echo $value["Valores"]["totalSt"] ?> servicio(s) técnico(s), para un total de: <?php echo number_format($value["Valores"]["valorBySt"]) ?>
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>
						<?php $totalEfectivity = $totalPagar; ?>
						
					<?php endif ?>
				</tbody>
			</table>
			<?php if (!empty($sales)): ?>
				<div class="row dataFinal mx-2" id="dataFinal">
					<div class="col-md-5 mr-3 text-center mt-1 border border-info p-1 bg-blue" style="border-radius: 20px 20px 0px 0px;">
						<h2 class="text-white">
							Tablas de valores de referencia
						</h2>

					</div>
					<div class="col-md-3 mr-3 text-center mt-1 border border-secondary p-1 bg-azul" style="border-radius: 20px 20px 0px 0px;">
						<h2 class="text-white">
							Datos para calcular comisiones
						</h2>

					</div>
					<div class="col-md-4 text-center mt-1 border border-success p-1 bg-success" style=" flex: 0 0 30.333333%; max-width: 30.333333%; border-radius: 20px 20px 0px 0px;">
						<h2 class="text-white">
							Resultados comisiones
						</h2>

					</div>
					<div class="col-md-5 mr-3 border border-info py-2" style="border-color: #004990 !important; border-radius: 0px 0px 20px 20px ;" >
						<div class="col-md-12 py-2 blockwhite ">
							<div class="row">
								<div class="col-md-12">
									<h2 class="text-center mb-1 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
										Tabla de edad de recaudo
									</h2>
									<table cellpadding="0" cellspacing="0" class='activityusers  table-bordered text-center'>
										<thead>
											<tr class="p-0">
												<th>Rango inicial</th>
												<th>Rango final</th>
												<th>Comision %</th>
											</tr>
										</thead>
										<tbody>
											<tr class="p-0">
												<td class="p-0"><?php echo $comision["Commision"]["range_one_init"] ?></td>
												<td class="p-0"><?php echo $comision["Commision"]["range_one_end"] ?></td>
												<td class="p-0"><?php echo $comision["Commision"]["range_one_percentage"] ?></td>
											</tr>
											<tr class="p-0">
												<td class="p-0"><?php echo $comision["Commision"]["range_two_init"] ?></td>
												<td class="p-0"><?php echo $comision["Commision"]["range_two_end"] ?></td>
												<td class="p-0"><?php echo $comision["Commision"]["range_two_percentage"] ?></td>
											</tr>
											<tr class="p-0">
												<td class="p-0"><?php echo $comision["Commision"]["range_three_init"] ?></td>
												<td class="p-0"><?php echo $comision["Commision"]["range_three_end"] ?></td>
												<td class="p-0"><?php echo $comision["Commision"]["range_three_percentage"] ?></td>
											</tr>
											<tr class="p-0">
												<td class="p-0"><?php echo $comision["Commision"]["range_four_init"] ?></td>
												<td class="p-0"><?php echo $comision["Commision"]["range_four_end"] ?></td>
												<td class="p-0"><?php echo $comision["Commision"]["range_four_percentage"] ?></td>
											</tr>
										</tbody>	
									</table>
								</div>
								
							</div>	
						</div>
						<div class="col-md-12 py-2 blockwhite mt-1">
							<div class="row">
								<?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] )): ?>									
								
									<div class="col-md-6 border-right">
										<h2 class="text-center mb-1 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">Tabla margen de utilidad</h2>
										<table class="table table-hovered  table-bordered">
											<thead style="display: table;
														  width: 100%;
														  table-layout: fixed;">
												<tr class="p-0">
													<th class="p-0">
														Margen min.
													</th>
													<th class="p-0">
														Margen max.
													</th>
													<th class="p-0">
														% Comisión
													</th>
												</tr>
											</thead>
											<tbody style="max-height: 3000px; overflow: auto; display: block;">
												<?php foreach ($porcentajes_comisiones as $key => $value): ?>
													<tr style="display: table;
														  width: 100%;
														  table-layout: fixed;">
														<td class="p-0 text-center"><?php echo $value["Percentage"]["min"] ?></td>
														<td class="p-0 text-center"><?php echo $value["Percentage"]["max"] ?></td>
														<td class="p-0 text-center"><?php echo $value["Percentage"]["value"] ?></td>
													</tr>
												<?php endforeach ?>
											</tbody>
										</table>
									</div>
								<?php endif ?>	
								<div class="col-md-12 border-left">
									<h2 class="text-center mb-1 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">Tabla efectividad</h2>
									<table class="table table-hovered  table-bordered">
										<thead style="display: table;
													  width: 100%;
													  table-layout: fixed;">
											<tr class="p-0 text-center">
												<th class="p-0 text-center">
													Efect. min.
												</th>
												<th class="p-0 text-center">
													Efect. max.
												</th>
												<th class="p-0 text-center">
													% Comisión
												</th>
											</tr>
										</thead>
										<tbody style="max-height: 3000px; overflow: auto; display: block;">
											<?php foreach ($efetivities_comisiones as $key => $value): ?>
												<tr class="p-0 text-center" style="display: table;
													  width: 100%;
													  table-layout: fixed;">
													<td class="p-0 text-center"><?php echo $value["Effectivity"]["min"] ?></td>
													<td class="p-0 text-center"><?php echo $value["Effectivity"]["max"] ?></td>
													<td class="p-0 text-center"><?php echo $value["Effectivity"]["value"] ?></td>
												</tr>
											<?php endforeach ?>
										</tbody>
									</table>
								</div>	
							</div>
						</div>
					</div>

					<div class="col-md-3 mr-3 border border-secondary py-2" style="border-radius: 0px 0px 20px 20px ;">
						<div class="col-md-12 py-2 mb-1 blockwhite ">
							<h2 class="text-center mb-3 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
								Metas 
							</h2>
							<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
								<tbody>
									<tr class="p-1">
										<th class="p-1" style="width: 240px !important;">Meta de recaudo:</th>
										<td class="p-1">$<?php echo number_format($meta,0,".",",") ?></td>
									</tr>
									<tr class="p-1">
										<th class="p-1" style="width: 240px !important;">Meta de ventas</th>
										<td class="p-1">$<?php echo number_format($metaVentas,0,".",",") ?></td>
									</tr>
									<?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] )): ?>										
										<tr class="p-1">
											<th class="p-1" style="width: 240px !important;">Meta de margen mínimo:</th>
											<td class="p-1"><?php echo number_format(35,0,".",",") ?>%</td>
										</tr>
									<?php endif ?>
									<tr class="p-1">
										<th class="p-1" style="width: 240px !important;">Meta de efectividad</th>
										<td class="p-1"><?php echo number_format(30,0,".",",") ?>%</td>
									</tr>
								</tbody>	
							</table>
						</div>
						<div class="col-md-12 py-2 mb-1 blockwhite ">
							<h2 class="text-center mb-1 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
								Datos para calcular efectividad
							</h2>
							<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
								<tbody>
									<tr class="p-1">
										<th class="p-1" style="width: 240px !important;">Flujos asignados</th>
										<td class="p-1"><?php echo $dataEfectivity["totalAsignados"] ?></td>
									</tr>
									<tr class="p-1">
										<th class="p-1" style="width: 240px !important;">Flujos cotizados</th>
										<td class="p-1"><?php echo $dataEfectivity["numCotizados"] ?></td>
									</tr>
									<tr class="p-1">
										<th class="p-1" style="width: 240px !important;">Flujos vendidos</th>
										<td class="p-1"><?php echo $dataEfectivity["numVentas"] ?></td>
									</tr>
									<tr class="p-1">
										<th class="p-1" style="width: 240px !important;">Flujos reasignados</th>
										<td class="p-1"><?php echo $dataEfectivity["totalLose"] ?></td>
									</tr>
									<tr class="p-1">
										<th class="p-1" style="width: 240px !important;">Formula efectividad</th>
										<td class="p-1">(Vend. / Asig.) <i class="fa vtc fa-times"></i> 100 </td>
									</tr>
								</tbody>	
							</table>
						</div>
					</div>
					

					<div class="col-md-4 border border-success py-2" style="    flex: 0 0 30.333333%; max-width: 30.333333%; border-radius: 0px 0px 20px 20px ;">
						<div class="col-md-12 py-2 mb-1 blockwhite " >
							<h2 class="text-center mb-1 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
								Total recaudado y ventas
							</h2>
							<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
								<tbody>
									<tr class="p-1">
										<th class="p-1" style="width: 280px !important;">Total recaudado</th>
										<td class="p-1 <?php echo $saldos < $meta ? 'text-danger' : 'text-success' ?>">
											$<?php echo number_format($saldos,0,".",",") ?>
										</td>
									</tr>
									<tr class="p-1">
										<th class="p-1" style="width: 280px !important;">Total ventas</th>
										<td class="p-1 <?php echo $totalVentas < $metaVentas ? 'text-danger' : 'text-success' ?>">$<?php echo number_format($totalVentas,0,".",",") ?></td>
									</tr>
								</tbody>	
							</table>
						</div>
						<div class="col-md-12 py-2 mb-1 blockwhite ">
							<h2 class="text-center mb-1 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
								Comisiones recaudo vs margen
							</h2>
							<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
								<tbody>
									<tr class="p-1">
										<th class="p-1" style="width: 280px !important;">Total comisiones por edad de recaudo:</th>
										<td class="p-1 <?php echo $saldos < $meta ? 'text-danger' : 'text-success' ?>">
											<?php if ($saldos >= $meta): ?>
												$<?php echo number_format($totalNormal,0,".",",") ?>
											<?php else: ?>
												No cumple la meta mínima de recaudo
											<?php endif ?>
										</td>
									</tr>
									<?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] )): ?>
										<tr class="p-1">
											<th class="p-1" style="width: 280px !important;">Total comisiones aplicando margen:</th>
											<td class="p-1 <?php echo $saldos < $meta ? 'text-danger' : 'text-success' ?>">
												<?php if ($saldos >= $meta): ?>
													$<?php echo number_format($totalPagar,0,".",",");  ?>
												<?php else: ?>
													No cumple la meta mínima de recaudo
												<?php endif ?>
											</td>
										</tr>
										<tr class="p-1">
											<th class="p-1" style="width: 280px !important;">Diferencia comisiones por edad y margen </th>
											<td class="p-1 <?php echo $totalPagar < $totalNormal ? 'text-danger' : 'text-success' ?>">
												<?php if ($saldos >= $meta): ?>
													$<?php echo number_format(abs($totalPagar-$totalNormal),0,".",",") ?>
												<?php else: ?>
													No cumple la meta mínima de recaudo
												<?php endif ?>
											</td>
										</tr>
									<?php endif ?>
									
								</tbody>	
							</table>
						</div>
						<?php if (!is_null($percentajeDb) && !empty($percentajeDb) ) : ?>
							<div class="col-md-12 py-2 mb-1 blockwhite ">
								<h2 class="text-center mb-1 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
									Comisiones por efectividad
								</h2>
								<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
									<tbody>
										<tr class="p-1">
											<th class="p-1" style="width: 280px !important;">% de efectividad obtenido:</th>
											<td class="p-1">
												<?php echo number_format(isset($dataEfectivity["efectividad"]) ? $dataEfectivity["efectividad"] : 0,2,".",",") ?> %
											</td>
										</tr>
										<tr class="p-1">
											<th class="p-1" style="width: 280px !important;">% a pagar según tabla de efectividad:</th>
											<td class="p-1 ">
												<?php $totalEfectivity = $percentajeDb["Effectivity"]["value"] == 0 ? 0 : $totalPagar * ($percentajeDb["Effectivity"]["value"] / 100 ); ?> 
												<?php echo number_format($percentajeDb["Effectivity"]["value"],2) ?>%

											</td>
										</tr>
										<tr class="p-1">
											<th class="p-1" style="width: 280px !important;">Total comisiones según efectividad: </th>
											<td class="p-1">
												<?php if ($saldos >= $meta): ?>
													$<?php echo number_format($totalEfectivity,0) ?>
												<?php else: ?>
													No cumple la meta mínima de recaudo
												<?php endif ?>
											</td>
										</tr>
										<tr class="p-1">
											<th class="p-1" style="width: 280px !important;">Diferencia com. margen y efectividad: </th>
											<td class="p-1 <?php echo $totalEfectivity < $totalPagar ? 'text-danger' : 'text-success' ?>">
												<?php if ($saldos >= $meta): ?>
													$<?php echo number_format(abs($totalEfectivity-$totalPagar),0,".",",") ?>
												<?php else: ?>
													No cumple la meta mínima de recaudo
												<?php endif ?>
											</td>
										</tr>
									</tbody>	
								</table>
							</div>

						<?php endif ?>
						<?php if ($metaVentas > 0 ): ?>
							<div class="col-md-12 py-2 mb-1 blockwhite ">
								<h2 class="text-center mb-1 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
									Total adicional por cumplir meta de ventas:
								</h2>
								<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
									<tbody>
										<tr class="p-1">
											<th class="p-1 <?php echo $totalVentas < $metaVentas ? 'text-danger' : 'text-success' ?>" >
												<?php echo $totalVentas >= $metaVentas ? '$ '.number_format($totalPagar *0.3,0,".",",") : 'No cumplió la meta de ventas' ?>
												<?php if ($totalVentas >= $metaVentas) {
								                $totalEfectivity += $totalPagar*0.3 ;
								            } ?>
											</th>
										</tr>
									</tbody>	
								</table>
							</div>
						<?php endif ?>
						<div class="col-md-12 py-2 mb-1 blockwhite ">
							<h2 class="text-center mb-1 bg-success text-white" style="border-radius: 20px 20px 0px 0px;">
								Total a pagar final:
							</h2>
							<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
								<tbody>
									<tr class="p-1">
										<th class="p-1 <?php echo $totalEfectivity <= 0 ? 'text-danger' : 'text-success' ?>">
											$<?php echo number_format($totalEfectivity); ?>
											
										</th>
									</tr>
								</tbody>	
							</table>
							<button class="btn btn-info text-white btn-block" id="btnIconR">
				  				Ver detalle de ventas y comisiones	<i id="detailIcon" class="fa fa-eye vtc"></i>
				  			</button>
						</div>

					</div>

					
				</div>	
			<?php endif ?>
		</div>
	  </div>
	  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
	  	
  		<div class="row mx-2">
  			<div class="col-md-4  ">
  				<div class="col-md-12 blockwhite p-0">
  					
	  				<h2 class="text-center mb-0 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
						Formulario para simular totales y porcentajes
					</h2>
	  				<form action="#" class="border p-4">
	  					<div class="form-group mb-3">
	  						<small>
	  							Por favor cambien los valores según corresponda y haga clic en simular
	  						</small>
	  					</div>
	  					<div class="form-group mt-2">
	  						<label for="ValorRecaudado">Valor recaudado</label>
	  						<input type="number" step="1000000" class="form-control" value="<?php echo $saldos ?>" id="ValorRecaudado">
	  					</div>
	  					<div class="form-group" style="display: <?php echo in_array(AuthComponent::user("role") ,["Logística","Gerente General"] ) ? 'block' : 'none' ?>">
	  						<label for="MargenPromedio">Margen promedio</label>
	  						<input type="number" min="1" max="50" step="0.5" class="form-control" id="MargenPromedio" value="35.00" >
	  					</div>
	  					<div class="form-group">
	  						<label for="EfectividadPromedio">Porcentaje de efectividad promedio</label>
	  						<input type="number" class="form-control" min="1" max="50" step="0.5" id="EfectividadPromedio" value="30.00">
	  					</div>
	  					<div class="form-group">
	  						<label for="TotalVentas">Total Ventas</label>
	  						<input type="number" step="1000000" value="<?php echo $totalVentas ?>" class="form-control" id="TotalVentas">
	  					</div>
	  					<div class="form-group">
	  						<button class="btn btn-block btn-success mt-3" id="simular">
	  							Simular
	  						</button>
	  					</div>
	  				</form>
  				</div>
  			</div>
  			<div class="col-md-3">
  				<div id="blockwhite" class="blockwhite col-md-12 p-0">
  					<h2 class="text-center mb-0 bg-azul text-white" style="border-radius: 20px 20px 0px 0px;">
						Datos para calcular comisiones
					</h2>
					<div class="border p-4">
						<h2 class="text-center mb-3 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
							Metas 
						</h2>
						<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
							<tbody>
								<tr class="p-1">
									<th class="p-1" style="width: 240px !important;">Meta de recaudo:</th>
									<td class="p-1">$<?php echo number_format($meta,0,".",",") ?></td>
								</tr>
								<tr class="p-1">
									<th class="p-1" style="width: 240px !important;">Meta de ventas</th>
									<td class="p-1">$<?php echo number_format($metaVentas,0,".",",") ?></td>
								</tr>
								<tr class="p-1" style="display: <?php echo in_array(AuthComponent::user("role") ,["Logística","Gerente General"] ) ? 'block' : 'none' ?>">
									<th class="p-1" style="width: 240px !important;">Meta de margen mínimo:</th>
									<td class="p-1"><?php echo number_format(35,0,".",",") ?>%</td>
								</tr>
								<tr class="p-1">
									<th class="p-1" style="width: 240px !important;">Meta de efectividad</th>
									<td class="p-1"><?php echo number_format(30,0,".",",") ?>%</td>
								</tr>
							</tbody>	
						</table>

					</div>
  				</div>
  			</div>
  			<div class="col-md-5">
  				<div class="col-md-12 blockwhite p-0">
  					<h2 class="text-center mb-0 bg-success text-white" style="border-radius: 20px 20px 0px 0px;">
						Resultados comisiones
					</h2>
					<div class="border p-4">
						<h2 class="text-center mb-1 bg-blue text-white" style="border-radius: 20px 20px 0px 0px;">
							Total recaudado y ventas
						</h2>
						<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
							<tbody>
								<tr class="p-1">
									<th class="p-1" style="width: 280px !important;">Total recaudado</th>
									<td class="p-1">
										<input type="hidden" id="metaRecaudoInput" value="<?php echo $meta ?>">
										<span id="totalRecaudado"></span>
									</td>
								</tr>
								<tr class="p-1">
									<th class="p-1" style="width: 280px !important;">Total ventas</th>
									<td class="p-1">
										<input type="hidden" id="metaVentasInput" value="<?php echo $metaVentas ?>">
										<span id="totalVentas"></span>
									</td>
								</tr>
							</tbody>	
						</table>

						<!--  -->

						<h2 class="text-center mb-1 bg-blue text-white mt-3" style="border-radius: 20px 20px 0px 0px;">
							Comisiones recaudo vs margen
						</h2>
						<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
							<tbody>
								<tr class="p-1">
									<th class="p-1" style="width: 280px !important;">Total comisiones por edad de recaudo:</th>
									<td class="p-1">
										<span id="totalComisionesRecaudo"></span>
									</td>
								</tr>
								<tr class="p-1" style="display: <?php echo in_array(AuthComponent::user("role") ,["Logística","Gerente General"] ) ? 'block' : 'none' ?>">
									<th class="p-1" style="width: 280px !important;">Total comisiones aplicando margen:</th>
									<td class="p-1">
										<span id="totalComisionesMargen"></span>
									</td>
								</tr>
								<tr class="p-1" style="display: <?php echo in_array(AuthComponent::user("role") ,["Logística","Gerente General"] ) ? 'block' : 'none' ?>">
									<th class="p-1" style="width: 280px !important;">Diferencia comisiones por edad y margen </th>
									<td class="p-1">
										<span id="diferenciaComisionesRecaudoMargen"></span>
									</td>
								</tr>
							</tbody>	
						</table>

						<!--  -->

						<h2 class="text-center mb-1 bg-blue text-white mt-3" style="border-radius: 20px 20px 0px 0px;">
							Comisiones por efectividad
						</h2>
						<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
							<tbody>
								<tr class="p-1">
									<th class="p-1" style="width: 280px !important;">% de efectividad obtenido:</th>
									<td class="p-1">
										<span id="porcentajeEfectividad"></span>
									</td>
								</tr>
								<tr class="p-1">
									<th class="p-1" style="width: 280px !important;">% a pagar según tabla de efectividad:</th>
									<td class="p-1 ">
										<span id="efectidadValor"></span>
									</td>
								</tr>
								<tr class="p-1">
									<th class="p-1" style="width: 280px !important;">Total comisiones según efectividad: </th>
									<td class="p-1">
										<span id="totalComisionesEfectividad"></span>
									</td>
								</tr>
								<tr class="p-1">
									<th class="p-1" style="width: 280px !important;">Diferencia com. margen y efectividad: </th>
									<td class="p-1">
										<span id="diferenciaComisionesEfectividadMargen"></span>
									</td>
								</tr>
							</tbody>	
						</table>

						<!--  -->

						<h2 class="text-center mb-1 bg-blue text-white mt-3" style="border-radius: 20px 20px 0px 0px;">
							Total adicional por cumplir meta de ventas:
						</h2>
						<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
							<tbody>
								<tr class="p-1">
									<th class="p-1" >
										<span id="adicionalPorVentas"></span>
									</th>
								</tr>
							</tbody>	
						</table>

						<!--  -->

						<h2 class="text-center mb-1 bg-success text-white mt-3" style="border-radius: 20px 20px 0px 0px;">
							Total a pagar final:
						</h2>
						<table cellpadding="0" cellspacing="0" class='activityusers table-bordered text-center'>
							<tbody>
								<tr class="p-1">
									<th class="p-1">
										<span id="totalPagoFinal"></span>
									</th>
								</tr>
							</tbody>	
						</table>
					</div>
  				</div>
  			</div>
  		</div>

	  		
	  </div>
	</div>

	
</div>