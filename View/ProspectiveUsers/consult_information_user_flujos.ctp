<div class="col-md-12">
	<div class="blockwhite">
		<div class="row">
			<div class="col-md-12 text-center">
				<h2 class="titleviewer font28">Indicadores para el asesor: <?php echo $this->Text->truncate($this->Utilities->find_name_adviser($user_id), 50,array('ellipsis' => '...','exact' => false)); ?>, para las fechas <?php echo $fecha_inicio ?> / <?php echo $fecha_fin ?></h2>
			</div>	
			<div class="col-md-12 px-5 mt-5">
				<div class="row">
					<div class="col-md-12 blockwhite mb-3">
						<div class="row">
							<div class="col-md-6">
								<div class="table-responsive">
									<h2 class="text-info text-center mb-2 font26">
										Datos de flujos
									</h2>
									<table class="table table-hovered table-bordered">
										<thead class="thead-dark">
											<tr class="text-center">
												<th class="bg-blue">
													Asignados
												</th>
												<th class="bg-blue">
													Contactados / Sin contactar 
												</th>
												<th class="bg-blue">
													Cotizados / Sin cotizar
												</th>
												<th class="bg-blue">
													Total cancelados
												</th>
												<th class="bg-blue">
													Total reasignados
												</th>
											</tr>
										</thead>
										<tbody>
											<tr class="text-center font25">
												<td class="text-info">
													<?php echo $numAsignados ?>
												</td>
												<td>
													<span class="text-success"> <?php echo $numContactados ?> </span> / <span class="text-danger"> <?php echo $numAsignados-$numContactados ?></span>
												</td>
												<td>
													<span class="text-success" id="cotizadosNum" data-days="<?php echo $totalDays ?>" data-promedio="<?php echo round($numCotizados/$totalDays) ?>"> <?php echo $numCotizados ?></span> / <span class="text-danger"><?php echo $numContactados-$numCotizados ?></span>
												</td>
												<td class="text-danger">
													<?php echo $numCancelados ?>
												</td>
												<td class="text-danger">
													<?php echo $totalLose ?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>	
							<div class="col-md-6">
								<div class="table-responsive">
									<h2 class="text-info text-center mb-2 font26">
										Retrazos 
									</h2>
									<table class="table table-hovered table-bordered">
										<thead class="thead-dark">
											<tr class="text-center">
												<th class="bg-danger"> Retrazos en contactar </th>
												<th class="bg-danger"> Retrazos en cotizar </th>
											</tr>
										</thead>
										<tbody>
											<tr class="text-center">
												<td class="font18">
													<b><?php echo $numAsignados ?></b>  Flujos / <b><?php echo round($demoraContactado["total"]/$numAsignados,2) ?></b> horas promedio 
												</td>
												<td class="font18">
													<b><?php echo $numAsignados ?></b>  Flujos / <b><?php echo round($demoraCotizar["total"]/$numAsignados,2) ?></b> horas promedio 
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12" style="display: none;">
						<table class="table" id="countData">
							<thead class="thead-dark">
								<tr>
									<th></th>
									<th>
										Cotizados
									</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($arrDays as $keyDay => $day): ?>
									<tr>
										<th>
											<?php echo $day ?>
										</th>
										<td>
											<?php echo isset($detailDays[$day]) ? $detailDays[$day] : 0 ?>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
					<div class="col-md-12 blockwhite mb-3">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<h2 class="text-info text-center font26 mb-2">
										Información de efectividad sobre flujos asignados en el rango de fechas
									</h2>
									<table class="table table-bordered table-hovered mb-0">
										<thead class="thead-dark">
											<tr class="text-center">
												
												<th class="bg-blue">
													Total ventas en el rango de fechas seleccionados
												</th>
												<th class="bg-blue">
													Total ventas despues del rango seleccionado (40 días)
												</th>
												<th class="bg-blue">
													Total ventas concretadas
												</th>
												<th class="bg-blue">
													Total dinero recaudado
												</th>
												<?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] )): ?>													
													<th class="bg-blue">
														Total utilidad / Margen
													</th>
												<?php endif ?>
											</tr>
										</thead>
										<tbody>
											<tr class="font25 text-center">
																						
												<td>
													<?php echo number_format((int)$infoFinal["numberMesNow"],0,",","."); ?>
												</td>
												<td>
													<?php echo number_format((int)$infoFinal["numberMesFuture"],0,",","."); ?>
												</td>
												<td>
													<?php echo number_format((int)$infoFinal["numberVentas"],0,",","."); ?>
												</td>
												<td>
													$<?php echo number_format((int)$infoFinal["totalValorVentas"],0,",","."); ?>
												</td>
												<?php if (in_array(AuthComponent::user("role") ,["Logística","Gerente General"] )): ?>													
													<td>
														<?php 
															$utilidad = (int)$infoFinal["totalValorVentas"]-$infoFinal["totalCostoVentas"];
															$porcentaje = round($utilidad/$infoFinal["totalValorVentas"]*100,2);

														 ?>
														$<?php echo number_format($utilidad,0,",","."); ?> / <?php echo $porcentaje ?> % 
													</td>
												<?php endif ?>
											</tr>
										</tbody>
									</table>
									<h2 class="text-info text-center font26 mb-2 mt-3">
										Efectividad final
									</h2>
									<table class="table table-bordered table-hovered">
										<thead class="thead-dark">
											<tr class="text-center">
												<th class="bg-blue" style="width: 33.33%">
													No. de flujos válidos
												</th>
												<th class="bg-blue" style="width: 33.33%">
													No. de ventas concretadas
												</th>
												<th class="bg-blue" style="width: 33.33%">
													Efectividad
												</th>
											</tr>
										</thead>
										<tbody>
											<tr class="text-center font25">
												<td class="">
													<?php echo count($finalIdsAssined) ?> 
												</td>
												<td>
													<?php echo $infoFinal["numberVentas"] ?>
												</td>	
												<td>
													<?php echo $efectividad ?> %
												</td>
											</tr>
										</tbody>
									</table>
									
									
									<?php if (isset($datosComisiones) && isset($percentajeDb)): ?>
										<h2 class="text-info text-center font26 mb-2 mt-3">
											Comisión a pagar ganada por el asesor
										</h2>
										<table class="table table-bordered table-hovered">
											<thead class="thead-dark">
												<tr class="text-center">
													<th class="bg-blue" style="width: 33.33%">
														Comisión ganada (100%)
													</th>
												</tr>
											</thead>
											<tbody>
												<tr class="text-center font25">
													<td>$<?php echo number_format($datosComisiones["totalPagarFinal"]) ?></td>
												</tr>
											</tbody>
										</table>
										
										<div class="row ml-0 mr-0">
											<div class="col-md-4">
												<h2 class="text-info text-center font20 mb-2 mt-3">
													Rangos para calcular % de comisión según efectividad
												</h2>
												<table class="table table-bordered table-hovered">
													<thead class="thead-dark">
														<tr>
															<th class="bg-blue">
																Base inicial
															</th>
															<th class="bg-blue">
																Base final
															</th>
															<th class="bg-blue">
																% comisión
															</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($porcentajes as $key => $value): ?>
															<tr>
																<td><?php echo $value["Effectivity"]["min"] ?></td>
																<td><?php echo $value["Effectivity"]["max"] ?></td>
																<td><?php echo $value["Effectivity"]["value"] ?></td>
															</tr>
														<?php endforeach ?>
													</tbody>
												</table>
											</div>
											<div class="col-md-8">
												<h2 class="text-info text-center font22 mb-2 mt-3">
														Comisión a pagar ganada por el asesor afectada por la productividad
													</h2>
												<table class="table table-bordered table-hovered">
													<thead class="thead-dark">
														<tr class="text-center">
															<th class="bg-blue" style="width: 50%">
																% a pagar según efectividad 
															</th>
															<th class="bg-blue" style="width: 50%">
																 Valor a pagar final: / Diferencia: 
															</th>
														</tr>
													</thead>
													<tbody>
														<tr class="text-center font23">
															<td><?php echo $percentajeDb["Effectivity"]["value"] ?>%</td>
															<td>
																$<?php echo number_format( ($datosComisiones["totalPagarFinal"])*($percentajeDb["Effectivity"]["value"]/100) ) ?> 
																<hr> 
																<?php if ( ($datosComisiones["totalPagarFinal"])*($percentajeDb["Effectivity"]["value"]/100) > $datosComisiones["totalPagarFinal"] ): ?>
																	<i class="text-success fa fa-plus vtc"></i> 
																<?php else: ?>
																	<i class="text-danger fa fa-minus vtc"></i>
																<?php endif ?>

																$<?php echo number_format(abs( ($datosComisiones["totalPagarFinal"])*($percentajeDb["Effectivity"]["value"]/100) - $datosComisiones["totalPagarFinal"])) ?>

															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										
									<?php endif ?>
								</div>
							</div>
						</div>	
					</div>
					<div class="col-md-12 blockwhite bg-gris" id="graficoCountVentas">
						<hr>
					</div>
					<div class="col-md-12">
						<hr>
					</div>
					<div class="col-md-12 d-block m-auto">
						<div class="row">
						</div>
					</div>
					<div class="col-md-12">
						<hr>
					</div>
					<div class="col-md-12">
						<a class="btn btn-block btn-outline-success text-center" href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'ventas_report',"?"=>array("ini" =>$fecha_inicio, "end" => $fecha_fin  ))) ?>" >
				           Detalle de ventas
				        </a>
					</div>	
				</div>	
			</div>
		</div>	
	</div>
</div>


