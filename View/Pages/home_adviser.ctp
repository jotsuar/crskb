<?php $roles = ["Asesor Técnico Comercial","Asesor Comercial","Gerente línea Productos Pelican","Servicio al Cliente","Asesor Técnico Comercial","Asesor Logístico Comercial","Asesor Externo"] ?>
<div class="d-block mx-auto w-90">
	

<div class="col-xl-12 bloqueshome p-0">
	<div class="row">
		<?php if (AuthComponent::user("role") == "Gerente General"): ?>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" id="mi_mensaje">
				<div class="blockwhite mb15 p-2">
					<div class="form-group top_search">
		    			<?php echo $this->Form->create("ProspectiveUser", array('role' => 'form','type'=>'GET','class'=>'')); ?>
		    			<div class="float-md-right float-xl-right input-group w-25">
			        		<?php echo $this->Form->input('uid', array('options'=>$usuarios_names,"empty"=>"Seleccionar vista de usuario", 'class'=>'form-control','label'=>false,'div'=>false,'value'=>$uid)) ?>
			        		<span class="input-group-btn">
					          <button class="btn btn-success text-white" type="submit">
					          	<?php echo __('Buscar'); ?>
					          </button>
					          <?php if (!empty($uid)): ?>
					          	<a href="<?php echo $this->Html->url(["action"=>"home_adviser"]) ?>" class="btn btn-warning">
					          		Eliminar filtro <i class="fa fa-times vtc"></i>
					          	</a>
					          <?php endif ?>
					        </span>
					     </div>
				      <?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		<?php endif ?>
		<?php $classTop = AuthComponent::user("role") == "Publicidad" ? "col-xl-12 col-lg-12 col-md-12 col-sm-12" : "col-xl-6 col-lg-6 col-md-6 col-sm-12" ?>
		<div class="<?php echo $classTop ?>" id="mi_mensaje" style="display: <?php echo AuthComponent::user("role") == "Publicidad" ? "none":"block"; ?>" >
			<div class="blockwhite mb15">

				<!--  -->

				<div class="card border-0 overflow-hidden bg-gris text-azul">

					<div class="card-body">

						<div class="row">

							<div class="col-xl-8 col-lg-8">

								<div class="mb-3 text-azul">
									<h2 class="text-center">Ventas totales <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
										<b>(empresa)</b>
									<?php endif ?></h2>
									<span class="ms-2">
										<!-- <i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Total sales" data-bs-placement="top" data-bs-content="Net sales (gross sales minus discounts and returns) plus taxes and shipping. Includes orders from all sales channels." data-bs-original-title="" title=""></i> -->
									</span>
								</div>


								<div class="d-flex mb-1">
									<a class="text-azul" href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'ventas_report',"?"=>array("ini" => date('Y-m-01'), "end" => date("Y-m-t")  ))) ?>">
										<h1 class="mb-0">$<span data-animation="number" data-value="64559.25"><?php echo number_format((int)h($total_ventas_empresa),0,",","."); ?></span></h1>
									</a>
								</div>

								<?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
									<div class="mb-3 text-azul">
										<?php 

											if (isset($metaMesActual) && $metaMesActual > 0) {
												$meta_mes_total_empresa = $metaMesActual;
												$porcentaje_meta_mes = ($total_ventas_empresa / $meta_mes_total_empresa ) * 100;
												$porcentaje_meta_mes = round($porcentaje_meta_mes,2);
											}

										 ?>
										<span data-animation="number" data-value="33.21"><?php echo $porcentaje_meta_mes ?></span>% de la meta del mes ($ <?php echo number_format((int)h($meta_mes_total_empresa),0,",","."); ?>)
										<div class="progress" style="height: 25px;">

											<?php if ($porcentaje_meta_mes <= 50): ?>
												<div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: <?php echo $porcentaje_meta_mes ?>%; font-size: 17px;" aria-valuenow="<?php echo $porcentaje_meta_mes ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $porcentaje_meta_mes ?>%</div>
											<?php else: ?>
												<div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>

												<?php if ($porcentaje_meta_mes <= 80): ?>
													<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: <?php echo $porcentaje_meta_mes-50 ?>%; font-size: 17px;" aria-valuenow="<?php echo $porcentaje_meta_mes-50 ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $porcentaje_meta_mes ?> %</div>
												<?php else: ?>
													<div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>

													<?php if ($porcentaje_meta_mes <= 100): ?>
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: <?php echo $porcentaje_meta_mes-80 ?>%; font-size: 17px;" aria-valuenow="<?php echo $porcentaje_meta_mes-80 ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $porcentaje_meta_mes ?> %</div>
													<?php else: ?>
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"><?php echo $porcentaje_meta_mes ?> %</div>
													<?php endif ?>

												<?php endif ?>

											<?php endif ?>
										</div>
									</div>

									<hr class="bg-white-transparent-5">
								<?php endif ?>
								<div class="row text-truncate">
									<?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
										
									
										<div class="col-6">
											<div class="fs-12px text-azul">Total ventas ayer <b>(empresa)</b></div>
											<div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="1568">
												<h2 class="text-azul">$<?php echo number_format($total_sales_business_yesterday,2,",",".") ?></h2>
											</div>
										</div>


										<div class="col-6">
											<div class="fs-12px text-azul">Total ventas hoy <b>(empresa)</b></div>
											<div class="fs-18px mb-5px fw-bold">
												<h2 data-animation="number" data-value="41.20" class="text-azul">
													<?php if ($total_descuentos_day > 0): ?>
														<i class="fa fa-plus vtc"></i>
													<?php endif ?>
													$<?php echo number_format($total_sales_business_day,2,",",".") ?>
													<?php if ($total_descuentos_day > 0): ?>
														<br>
														<i class="fa fa-minus vtc"></i> $<?php echo number_format($total_descuentos_day,2,",",".") ?> DCTOS
													<?php endif ?>
												</h2>
											</div>
										</div>
										<div class="col-12 mt-2">
											<hr>
										</div>
									<?php endif ?>
									<div class="<?php echo !is_null($totalComisiones) ? "col-md-6" : "col-12" ?> mt-2">
										<div class="fs-12px text-azul text-center"><strong>Mis ventas hoy:</strong></div>
										<div class="fs-18px mb-5px fw-bold text-center"><h2 data-animation="number" data-value="41.20" class="text-azul">$<?php echo number_format($total_ventas_session, 2, ".",",") ?></h2></div>
									</div>
									<?php if (!is_null($totalComisiones)): ?>
										<div class="col-6 mt-2">
											<div class="fs-12px text-azul text-center"><b>Total comisiones del mes:</b></div>
											<div class="fs-18px mb-5px fw-bold text-center">
												<h2 data-animation="number" data-value="41.20" class="text-azul">$<?php echo number_format($totalComisiones, 2, ".",",") ?> 

												</h2>
											</div>
										</div>
									<?php endif ?>
								</div>

							</div>


							<div class="col-xl-4 col-lg-4 align-items-center d-flex justify-content-center">
								<img src="https://seantheme.com/color-admin/admin/assets/img/svg/img-1.svg" height="150px" class="d-none d-lg-block">
							</div>
						</div>

					</div>

				</div>



				<!--  -->
				
			</div>
		</div>

		<div class="<?php echo $classTop ?> <?php echo AuthComponent::user("role") == "Asesor Externo" ? "d-nones" : "" ?>">
			<div class="blockwhite mb-1 p-2">
				<div class="card bg-gris border-0">
					<div class="row" id="block-three" style="height: 280px !important; max-height: 280px !important;">
						<div class="col-md-12 titleorigen mb-2">
							<h2>Origen de flujos <span class="sizedataorigin"><?php echo $this->Utilities->date_castellano(date("d-m-Y")); ?></span></h2>
						</div>
						<div class="col-md-12 titleorigen mb-1">
							<div class="col-md-12 pull-right text-right">
								<div class="rangofechas">
									<span>Seleccionar rango de fechas:</span>
									<div class="form-group">
										<input type="text" value="<?php echo date("Y-m-d"); ?>" id="fechasInicioFin2" class="mb-2">
										<a id="btn_find_adviser_2" data-id="totales" class="btn-primary btn">Buscar</a>
									</div>

									<div style="display: none">
										<div class="form-group">
										  	<span>Desde</span>
										</div>
										<div class="form-group">
											<input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="input_date_inicio2" style="display: none">
										</div>
									</div>
									<div style="display: none">
										<div class="form-group">
										  	<span>Hasta</span>
										  	<input type="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin2" placeholder="Desde" style="display: none">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row" id="dataCountChange">
								<div class="col-md-6 col-sm-12 boxright">
									<h2><?php echo Configure::read('variables.origenContact.Chat') ?><span class=""><?php echo $count_origen_chat ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Whatsapp') ?><span class=""><?php echo $count_origen_whatsapp ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Email') ?><span class=""><?php echo $count_origen_email ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Referido') ?><span class=""><?php echo $count_origen_referido ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Whatsapp Kebco USA') ?><span class=""><?php echo $count_origen_wpp_usa ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Email Kebco USA') ?><span class=""><?php echo $count_origen_email_usa ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Marketing') ?><span class=""><?php echo $count_origen_marketing ?></span></h2>
								</div>
								<div class="col-md-6 col-sm-12 boxleft">
									<h2><?php echo Configure::read('variables.origenContact.Llamada') ?><span class=""><?php echo $count_origen_llamada ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Presencial') ?><span class=""><?php echo $count_origen_presencial ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Redes sociales') ?><span class=""><?php echo $count_origen_redes ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Chat Pelican') ?><span class=""><?php echo $count_origen_pelican ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Chat Kebco USA') ?><span class=""><?php echo $count_origen_chat_usa ?></span></h2>
									<h2><?php echo Configure::read('variables.origenContact.Landing') ?><span class=""><?php echo $count_landing ?></span></h2>
									<h2 class="border-top" style="font-size: 20px;"><strong style="font-weight: 900;">Total de prospectos</strong><span style="font-size: 20px;" class=""><?php echo array_sum([$count_origen_chat,$count_origen_whatsapp,$count_origen_email,$count_origen_referido,$count_origen_wpp_usa,$count_origen_email_usa,$count_origen_marketing,$count_origen_llamada,$count_origen_presencial,$count_origen_redes,$count_origen_pelican,$count_origen_chat_usa,$count_landing]) ?></span></h2>
								</div>
							</div>
						</div>						
					</div>
				</div>
			</div>
			<div class="blockwhite p-3">
				<?php if (!is_null($totalComisiones)): ?>
					<a href="" class="btn btn-sm btn-info btn-block detalleComisionUsuario" data-user="<?php echo $id_user_busca ?>">
					Panel de comisiones - Ver detalle <i class="fa fa-eye vtc"></i></a> 
				<?php endif ?>
			</div>
		</div>

		<?php if (AuthComponent::user("role") != "Publicidad"): ?>
			
			<div class="col-xl-12 col-lg-12 col-md-12">
				<div class="blockwhite p-2 mb-3">
					<div class="card bg-gris border-0 p-3">
						<div class="row">
							<div class="col-md-12">
								<h2 class="text-center mb-2">Estadísticas generales</h2>						
							</div>
							<div class="col-md-12 pull-right text-right">
								<div class="rangofechas">
									<span>Seleccionar rango de fechas:</span>
									<div class="form-group">
										<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="mb-3">
										<a id="btn_buscar_datos_empresa" class="btn-primary btn">Buscar</a>
										<?php if ($fechaFinReporte != date("Y-12-31") && $fechaInicioReporte != date("2018-01-01") ): ?>
												<a href="<?php echo $this->Html->url(["action"=>"home_adviser"]) ?>" class="btn btn-warning">Borrar filtro</a>
											<?php endif ?>
									</div>

									<div style="display: none">
										<div class="form-group">
										  	<span>Desde</span>
										</div>
										<div class="form-group">
											<input type="date" value="<?php echo $fechaInicioReporte; ?>" class="form-control" id="input_date_inicio" style="display: none">
										</div>
									</div>
									<div style="display: none">
										<div class="form-group">
										  	<span>Hasta</span>
										  	<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
										</div>
										<div class="form-group">
											<input type="button" class="form-control" id="btn_find_adviser" style="display: none">
											
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<a href="<?php echo $this->Html->url(["controller"=>"ClientsNaturals","action"=>"index"]) ?>">
									
			                        <div class="border-left border-primary card h-100 shadow">
			                            <div class="card-body" style="    padding: 12px !important;">
			                                <div class="row no-gutters align-items-center">
			                                    <div class="col mr-2">
			                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
			                                        	Clientes Naturales
			                                        </div>
			                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->Utilities->getCountData("ClientsNatural",$fechaInicioReporte,$fechaFinReporte,$id_user_busca); ?></div>
			                                    </div>
			                                    <div class="col-auto">
			                                        <i class="fa fa-users fa-2x text-gray-300"></i>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
								</a>
		                    </div>
		                    <div class="col-xl-3 col-md-3">
		                    	<a href="<?php echo $this->Html->url(["controller"=>"ClientsLegals","action"=>"index"]) ?>">
			                        <div class="border-left border-warning card h-100 shadow">
			                            <div class="card-body" style="    padding: 12px !important;">
			                                <div class="row no-gutters align-items-center">
			                                    <div class="col mr-2">
			                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
			                                        	Clientes Jurídicos / Contactos 
			                                        </div>
			                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->Utilities->getCountData("ClientsLegal",$fechaInicioReporte,$fechaFinReporte,$id_user_busca); ?> / <?php echo $this->Utilities->getCountData("ContacsUser",$fechaInicioReporte,$fechaFinReporte,$id_user_busca); ?></div>
			                                    </div>
			                                    <div class="col-auto">
			                                        <i class="fa fa-users fa-2x text-gray-300"></i>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
		                        </a>
		                    </div>
		                    <div class="col-xl-3 col-md-3">
		                    	<a href="<?php echo $this->Html->url(["controller"=>"ClientsLegals","action"=>"index"]) ?>">
			                        <div class="border-left border-warning card h-100 shadow">
			                            <div class="card-body" style="    padding: 12px !important;">
			                                <div class="row no-gutters align-items-center">
			                                    <div class="col mr-2">
			                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
			                                        	Clientes totales
			                                        </div>
			                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->Utilities->getCountData("ClientsLegal",$fechaInicioReporte,$fechaFinReporte,$id_user_busca) + $this->Utilities->getCountData("ClientsNatural",$fechaInicioReporte,$fechaFinReporte,$id_user_busca); ?></div>
			                                    </div>
			                                    <div class="col-auto">
			                                        <i class="fa fa-users fa-2x text-gray-300"></i>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
		                        </a>
		                    </div>
		                    
		                    <div class="col-xl-3 col-md-3 mt-2">
		                    	<a href="<?php echo $this->Html->url(["controller"=>"ProspectiveUsers","action"=>"index"]) ?>">
		                        <div class="border-left border-info card h-100 shadow">
		                            <div class="card-body" style="    padding: 12px !important;">
		                                <div class="row no-gutters align-items-center">
		                                    <div class="col mr-2">
		                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
		                                        	Flujos totales
		                                        </div>
		                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->Utilities->getCountData("ProspectiveUser",$fechaInicioReporte,$fechaFinReporte,$id_user_busca); ?> </div>
		                                    </div>
		                                    <div class="col-auto">
		                                        <i class="fa fa-dollar fa-2x text-info"></i>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        </a>
		                    </div>
		                    <div class="col-xl-3 col-md-3 mt-2">
		                    	<a href="<?php echo $this->Html->url(["controller"=>"ProspectiveUsers","action"=>"index"]) ?>">
		                        <div class="border-left border-info card h-100 shadow">
		                            <div class="card-body" style="    padding: 12px !important;">
		                                <div class="row no-gutters align-items-center">
		                                    <div class="col mr-2">
		                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
		                                        	Flujos Pagados $
		                                        </div>
		                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pagados; ?> </div>
		                                    </div>
		                                    <div class="col-auto">
		                                        <i class="fa fa-dollar fa-2x text-info"></i>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        </a>
		                    </div>
		                     <div class="col-xl-3 col-md-3 mt-2">
		                    	<a href="<?php echo $this->Html->url(["controller"=>"ProspectiveUsers","action"=>"index"]) ?>">
		                        <div class="border-left border-info card h-100 shadow">
		                            <div class="card-body" style="    padding: 12px !important;">
		                                <div class="row no-gutters align-items-center">
		                                    <div class="col mr-2">
		                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
		                                        	Efectividad: Negocios con Pago VS Flujos de negocios creado
		                                        </div>
		                                        <div class="h6 mb-0 font-weight-bold text-gray-800"> <?php echo $pagados; ?>/<?php echo $this->Utilities->getCountData("ProspectiveUser",$fechaInicioReporte,$fechaFinReporte,$id_user_busca); ?>: 
		                                        	<?php $totalDi = $this->Utilities->getCountData("ProspectiveUser",$fechaInicioReporte,$fechaFinReporte,$id_user_busca); 
		                                        		$totalDi = $totalDi == 0 ? 1 : $totalDi;
		                                        	 ?>
		                                        	<?php echo round(($pagados/ $totalDi)*100,2) ?>%  </div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        </a>
		                    </div>
		                    <div class="col-xl-3 col-md-3 mt-2">
		                    	<a href="<?php echo $this->Html->url(["controller"=>"ProspectiveUsers","action"=>"quotes_sent"]) ?>">
		                        <div class="border-left border-danger card h-100 shadow">
		                            <div class="card-body" style="    padding: 12px !important;">
		                                <div class="row no-gutters align-items-center">
		                                    <div class="col mr-2">
		                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
		                                        	Cotizaciones Enviadas <br>
		                                        	<?php //$dates = $this->Utilities->getIniEndQuotation(); ?>
		                                        	<small><?php echo $fechaInicioReporte ?> / <?php echo $fechaFinReporte ?></small>
		                                        </div>
		                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->Utilities->getCountData("Quotation",$fechaInicioReporte,$fechaFinReporte,$id_user_busca); ?> </div>
		                                    </div>
		                                    <div class="col-auto">
		                                        <i class="fa fa-file fa-2x text-gray-300"></i>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        </a>
		                    </div>
		                    <div class="col-xl-3 col-md-3 mt-2">
		                    	<a href="<?php echo $this->Html->url(["controller"=>"Products","action"=>"index"]) ?>">
		                        <div class="border-left border-success card h-100 shadow">
		                            <div class="card-body" style="    padding: 12px !important;">
		                                <div class="row no-gutters align-items-center">
		                                    <div class="col mr-2">
		                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
		                                        	Productos 
		                                        </div>
		                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->Utilities->getCountData("Product",$fechaInicioReporte,$fechaFinReporte,$id_user_busca); ?> </div>
		                                    </div>
		                                    <div class="col-auto">
		                                        <i class="fa fa-barcode fa-2x text-gray-300"></i>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        </a>
		                    </div>
						</div>
					</div>
				</div>			
			</div>

		
			
		
			<div class="col-xl-12 col-lg-12 col-md-12 mt-3">
				<div class="blockwhite p-2">
					<div class="card bg-gris border-0 p-2">
						<div class="blockwhite spacebtn20 p-2 ?>">
							<div class="row">
								<div class="col-md-8">
									<h1 class="nameview">STATUS DE ATENCIÓN DE FLUJOS GENERAL DE LA EMPRESA</h1>
									<span class="subname">Mostrar flujos con retraso y el numero de horas aproximadas.</span>
									
								</div>
								<div class="col-md-4 pull-right text-right">
									<div class="rangofechas">
										<span>Seleccionar rango de fechas:</span>
										<div class="form-group">
											<input type="text" value="<?php echo date("Y-m-d"); ?>" id="fechasInicioFin25" class="w-50">
											<a id="btn_buscar_datos_empresa_home_report" class="btn-primary btn">Buscar</a>
										</div>

										<div style="display: none">
											<div class="form-group">
											  	<span>Desde</span>
											</div>
											<div class="form-group">
												<input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="input_date_inicio_empresa_adviser_report" style="display: none">
											</div>
										</div>
										<div style="display: none">
											<div class="form-group">
											  	<span>Hasta</span>
											</div>
											<div class="form-group">
												<input type="date" value="<?php echo date("Y-m-d") ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin_empresa_adviser_report" style="display: none">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 spacebtn20 p-0">
							<div class="blockwhite div_info_empresa p-0"></div>
						</div>

					</div>
				</div>
			</div>

			<?php endif ?>

			<!--    -->

			<div class="col-xl-12 col-lg-12 col-md-12 mt-3">
				<div class="blockwhite p-2 mb-3">
					<div class="card bg-gris border-0 p-3">
						<h2 class="text-center mb-2">Estadísticas por mes del año <?php echo date("Y") ?></h2>
					</div>
				</div>
			</div>

			<div class="col-xl-12 col-lg-12 col-md-12">
				<div class="blockwhite p-2 mb-3">
					<div class="card bg-gris border-0 p-3">
						<div id="clientesPagos"></div>
					</div>
				</div>
			</div>
			<?php if (AuthComponent::user("role") != "Publicidad"): ?>
				
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="blockwhite p-2 mb-3">
						<div class='bg-gris border-0 card d-inline-block my-2 p-3 w-100 text-center'>
							<?php echo $this->Form->input("anio",["label" => "Seleccione año", "div" => "col-md-4 d-block form-group mx-auto","options" => array_combine($anios,$anios) , "class" => "form-control mb-3", "value" => date("Y") ]) ?>
							<?php $varOp = 0; ?>
							<?php foreach ($labelsBtn as $key => $value): ?>
								<button id='<?php echo $value ?>' class="buttonsData btn btn-outline-primary <?php echo $varOp == 0 ? 'btn-primary' : '';  ?>">
								    <?php echo str_replace("_", " ", $value) ?>
								    <?php $varOp++; ?>
								</button>
							<?php endforeach ?>
						</div>
						<div class="card bg-gris border-0 p-3">
							<div id="containerMetas"></div>
						</div>
					</div>
				</div>
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="col-md-12 titleorigen mb-1">
						<div class="col-md-12 pull-right text-right">
							<div class="rangofechas">
								<span>Seleccionar rango de fechas:</span>
								<div class="form-group">
									<input type="text" value="<?php echo date("Y-m-d"); ?>" id="fechasInicioFin4" class="mb-2">
									<a  data-id="flujos" class="btn-primary btn" id="btn_find_adviser_4">Buscar</a>
								</div>

								<div style="display: none">
									<div class="form-group">
									  	<span>Desde</span>
									</div>
									<div class="form-group">
										<input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="input_date_inicio4" style="display: none">
									</div>
								</div>
								<div style="display: none">
									<div class="form-group">
									  	<span>Hasta</span>
									  	<input type="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-12-31"); ?>" id="input_date_fin4" placeholder="Desde" style="display: none">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="blockwhite p-2 mb-3">
						<div class="card bg-gris border-0 p-3">
							<div id="datosGeograficos"></div>
						</div>
					</div>
					
					<div class="blockwhite p-2 mb-3">
						<div class="card bg-gris border-0 p-3">
							<a href="<?php echo $this->Html->url(["action"=>"datos_productos"]) ?>" class="btn btn-block btn-info">Ver informe de venta de línea de productos</a>
						</div>
					</div>

				</div>
			<?php endif ?>

			<div class="col-xl-6 col-lg-6 col-md-6">
				<div class="blockwhite p-2 mb-3">
					<div class="card bg-gris border-0 p-3">
						<div id="mesesCliente"></div>
					</div>
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6">
				<div class="blockwhite p-2 mb-3">
					<div class="card bg-gris border-0 p-3">
						<div id="quoatationsTotalDiv"></div>					
					</div>
				</div>
			</div>

			<div class="col-xl-12 col-lg-12 col-md-12">
				<div class="blockwhite p-2 mb-3">
					<div class="card bg-gris border-0 p-3">
						<div id="flujosTotalDiv"></div>
					</div>
				</div>
			</div>

		<!--    -->

	</div>
</div>

<div class="row">
	
	<?php if (AuthComponent::user("role") != "Publicidad"): ?>
		
		<div class="col-md-12">
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'ventas_report',"?"=>array("end" => date('Y-m-d'), "ini" => date("Y-m-d",strtotime("-5 day"))  ))) ?>">
				<div class="blockwhite mb-3 p-2">
					<div class="card bg-gris border-0 p-3">
						<h2 class="text-center">Facturas de hoy 
							<?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
								$<?php echo number_format(array_sum( !empty($lastDocuments) ? Set::extract($lastDocuments, "{n}.Total_Venta") : [] ), "2",",",".") ?>
							<?php endif ?>
						</h2>
						<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
							<table class="table-hovered table">
								<thead>
									<tr>
										<th class="p-0">
											Factura
										</th>
										<th class="p-0">
											Flujo
										</th>
										
										<th class="p-0" style="max-width: 350px !important">
											Cliente
										</th>
										<th class="p-0" style="max-width: 350px !important">
											Empleado
										</th>
										<th class="p-0">
											Valor
										</th>
									</tr>
								</thead>
								<?php foreach ($lastDocuments as $key => $value): ?>
									<?php if (in_array(AuthComponent::user("role"), $roles) || isset($filtroADM) ): ?>
										<?php if ($value["IdVendedor"] != $id_busca): ?>
											<?php continue; ?>
										<?php endif ?>
									<?php endif ?>
									<tr>
										<?php $strPos = strpos($value["Factura"], "DMC"); ?>
										<th class="p-0">
											<?php echo $value["Factura"] ?>
										</th>

										<th class="p-0">
											<?php if ($strPos === false): ?>
												<?php echo is_null($value["Personalizado5"]) ? "No asignado" : $value["Personalizado5"] ?>
											<?php endif ?>
										</th>
										
										<td class="p-0" style="max-width: 350px !important">
											<?php echo $value["Nombre"] ?>
										</td>
										<td class="p-0" style="max-width: 350px !important">
											<?php echo $value["NombreVendedor"] ?>
										</td>
										<td class="p-0">
											<?php $strPos = strpos($value["Factura"], "DMC"); ?>
											<?php if ($strPos === false): ?>
												<i class="fa fa-plus vtc"></i>
											<?php else: ?>
												<i class="fa fa-minus vtc"></i>
											<?php endif ?>
											<?php
												
												$totalVD = $strPos === false ? $value["Total_Venta"] : $value["Total_Descuentos"]; ?>
											$ <?php echo number_format(intval($totalVD),"2",".",",") ?>
										</td>
									</tr>
								<?php endforeach ?>
							</table>
						</div>
					</div>
				</div>
			</a>
		</div>

	<?php endif ?>

	<div class="col-md-12">
		<div class="row">				
			<div class="col-md-12">
				<div class="blockwhite mb-3 p-2">
					<div class="card bg-gris border-0 p-3">
						<div class="col-md-12 titleorigen mb-2">
							<h2 class="text-center">Flujos recientes
						</div>
						
						<?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Publicidad"): ?>
							<div class="col-md-12 titleorigen mb-1">
								<div class="col-md-12 pull-right text-right">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="typeFlujoFilter">Tipo de flujo</label>
												<select name="typeFlujoFilter" id="typeFlujoFilter" class="form-control">
													<option value="all">Todos los flujos</option>
													<option value="robot">Flujos del robot</option>
												</select>
											</div>
										</div>
										<div class="col-md-9">
											<div class="rangofechas">
												<span>Seleccionar rango de fechas:</span>
												<div class="form-group">
													<input type="text" value="<?php echo date("Y-m-d"); ?>" id="fechasInicioFin3" class="mb-2">
													<a  data-id="flujos" class="btn-primary btn" id="btn_find_adviser_3">Buscar</a>
												</div>

												<div style="display: none">
													<div class="form-group">
													  	<span>Desde</span>
													</div>
													<div class="form-group">
														<input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="input_date_inicio3" style="display: none">
													</div>
												</div>
												<div style="display: none">
													<div class="form-group">
													  	<span>Hasta</span>
													  	<input type="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin3" placeholder="Desde" style="display: none">
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
							</div>
						<?php endif ?>
						
						<div class="table-responsive" id="dataFlujoCS">
							<div style="max-height: 400px; overflow-y: auto;">
								<table class="table-bordered table" id="flujosData" style="max-width: 100% !important;">
									<thead>
										<tr>
											<th class="noMostrar p-1" style="width: 80px !important;">
												FLUJO
											</th>
											<th class="noMostrar p-1">
												Cliente
											</th>
											<th class="p-1">Origen</th>
											<th class="noMostrar p-1">
												Requerimiento
											</th>										
											<th class="p-1">
												Estado actual
											</th>
											<th class="noMostrar p-1">
												Asesor
											</th>
											<th class="noMostrar p-1">
												Valor
											</th>
											<th class="noMostrar p-1">
												Fecha
											</th>
										</tr>
									</thead>
									<?php foreach ($flujos_cs as $key => $value): ?>
										<tr>
											<td class="p-1" style="width: 80px !important;">

												<?php if (AuthComponent::user("role") == "Publicidad"): ?>
													<?php echo $value['ProspectiveUser']['id'] ?>
												<?php else: ?>
													<div class="dropdown d-inline styledrop">
														<a class="btn btn-success dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['ProspectiveUser']['id']) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															<?php echo $value['ProspectiveUser']['id'] ?>
														</a>

														<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_<?php echo md5($key) ?>_<?php echo md5($value['ProspectiveUser']['id']) ?>">
															<a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>" data-type="<?php echo $this->Utilities->getTypeProspective($value['ProspectiveUser']['id']); ?>">Ver flujo</a>
															<?php if (AuthComponent::user("role") != "Publicidad"): ?>
																<?php if (in_array($value['ProspectiveUser']['state_flow'], [3,4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>														
																	<a class="dropdown-item getQuotationId" data-quotation="<?php echo $this->Utilities->getQuotationId($value['ProspectiveUser']['id']) ?>" href="#">Ver cotización</a>
																<?php endif ?>
																<?php if (in_array($value['ProspectiveUser']['state_flow'], [4,5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
																	<a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $value['ProspectiveUser']['id'] ?>">Ver órden de compra</a>
																<?php endif ?>
																<?php if (in_array($value['ProspectiveUser']['state_flow'], [5,6,8]) || ($value["ProspectiveUser"]["valid"] > 0 && $value['ProspectiveUser']['state_flow'] == 2) ): ?>
																	<a class="dropdown-item getPagos" href="#" data-flujo="<?php echo $value['ProspectiveUser']['id'] ?>">Ver comprobante(s) de pago</a>
																<?php endif ?>
															<?php endif ?>
														</div>
													</div>
												<?php endif ?>

												
											</td>
											<th class="p-0">
												 <?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value['ProspectiveUser']['id']), 40,array('ellipsis' => '...','exact' => false)); ?>
											</th>
											<td class="p-0"><?php echo $value['ProspectiveUser']['origin'] ?></td>
											<td class="p-0"><?php echo $this->Text->truncate($this->Utilities->find_reason_prospective($value['ProspectiveUser']['id']), 50,array('ellipsis' => '...','exact' => false)); ?></td>
											<td class="p-0"><?php echo $this->Utilities->finde_state_flujo($value['ProspectiveUser']['state_flow']); ?></td>
											<td class="p-0"><?php echo $this->Text->truncate($this->Utilities->find_name_adviser($value['ProspectiveUser']['user_id']), 50,array('ellipsis' => '...','exact' => false)); ?></td>
											<td class="p-0">$<?php echo number_format($value['ProspectiveUser']['total'],2,",",".") ?></td>
											<td class="p-0"><?php echo $value['ProspectiveUser']['created'] ?></td>
										</tr>
									<?php endforeach ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if (AuthComponent::user("role") != "Publicidad"): ?>

		<div class="col-md-12">
			<div class="row">				
				<div class="col-md-12">
					<div class="blockwhite mb-3 p-2">
						<div class="card bg-gris border-0 p-3">
							<div class="col-md-12 titleorigen mb-2">
								<h2 class="text-center">
									Productos que se encuentan en el panel de importaciones de logística
								</h2>
							</div>
							
							<?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
								<div class="col-md-12 titleorigen mb-1">
									<div class="col-md-12 pull-right text-right">
										<div class="rangofechas">
											<span>Seleccionar rango de fechas de solicitud:</span>
											<div class="form-group">
												<input type="text" value="<?php echo date("Y-m-d"); ?>" id="fechasInicioFin5" class="mb-2">
												<a  data-id="flujos" class="btn-primary btn" id="btn_find_adviser_5">Buscar</a>
											</div>

											<div style="display: none">
												<div class="form-group">
												  	<span>Desde</span>
												</div>
												<div class="form-group">
													<input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" id="input_date_inicio5" style="display: none">
												</div>
											</div>
											<div style="display: none">
												<div class="form-group">
												  	<span>Hasta</span>
												  	<input type="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin5" placeholder="Desde" style="display: none">
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endif ?>
							
							<div class="table-responsive" id="dataProductsCS">
								<div style="max-height: 400px; overflow-y: auto;">
									<table class="table-bordered table" id="productosData" style="max-width: 100% !important;">
										<thead>
											<tr>
												<th class="noMostrar2 p-1">
													Img
												</th>
												<th>
													Producto
												</th>
												<th>
													Cantidad
												</th>
												<th>
													Tipo solicitud
												</th>
												<th>
													Flujo
												</th>
												<th >
													Cliente
												</th>
												<th class="noMostrar2 p-1">
													Motivo
												</th>		
												<th>
													Asesor
												</th>								
												<th class="p-1">
													Fecha solicitud
												</th>
												<th class=" p-1">
													Fecha probable de entrega
												</th>
											</tr>
										</thead>
										<?php foreach ($productosImport as $key => $value): ?>
											<tr>
												<td class="p-1" style="width: 80px !important;">

													<?php $ruta = $this->Utilities->validate_image_products($value["Product"]['img']); ?>
													<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value["Product"]['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="55px" height="55px" class="imgmin-product">
												</td>
												<td>
													<?php echo $value["Product"]['part_number'] ?> /
													<?php echo $value["Product"]['name'] ?>
												</td>
												<td>
													<?php echo $value["ImportRequestsDetailsProduct"]["quantity"] ?>
												</td>
												<td>
													<?php echo Configure::read("TYPE_REQUEST_IMPORT_DATA.".$value["ImportRequestsDetail"]["type_request"]) ?>
												</td>
												<td>
													<?php echo $value["ImportRequestsDetail"]["prospective_user_id"] ?>
												</td>
												<th class="p-0">
													 <?php echo $this->Text->truncate($this->Utilities->name_prospective_contact($value["ImportRequestsDetail"]["prospective_user_id"]), 40,array('ellipsis' => '...','exact' => false)); ?>
												</th>
												<td class="p-0"><?php echo $value['ImportRequestsDetail']['description'] ?></td>
												<td class="p-0"><?php echo $this->Text->truncate($this->Utilities->find_name_adviser($value['ImportRequestsDetail']['user_id']), 50,array('ellipsis' => '...','exact' => false)); ?></td>
												<td class="p-0"><?php echo $value['ImportRequestsDetail']['created'] ?></td>
												<td class="p-0">
													
													<?php if ($value["ImportRequestsDetail"]["type_request"] == Configure::read("TYPE_REQUEST_IMPORT.SALES_NO_INVENTORY")): ?>
														<?php 
															$fecha = !is_null($value["ImportRequestsDetail"]["deadline"]) ? $value["ImportRequestsDetail"]["deadline"] : $this->Utilities->calculateFechaFinalEntrega($value["ImportRequestsDetail"]["created"],Configure::read("variables.entregaProductValues.".$value["ImportRequestsDetailsProduct"]["delivery"]));
															$dataDay = $this->Utilities->getClassDate($fecha);
														?>
														<span class="">
															<?php echo $this->Utilities->date_castellano($fecha); ?>
														</span>
														<br>
														<?php if ($dataDay == 0): ?>
															<span class="bg-danger text-white" style="font-size: 10px !important;">¡Para entrega hoy!</span>
														<?php elseif($dataDay > 0): ?>
															<span class="bg-danger text-white" style="font-size: 10px !important;">¡Retraso de <?php echo $dataDay ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
														<?php elseif($dataDay <= -5): ?>
															<span class="bg-success text-white" style="font-size: 10px !important;">¡Para entrega en  <?php echo abs($dataDay) ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
														<?php else: ?>
															<span class="bg-warning" style="font-size: 10px !important;">¡Para entrega en  <?php echo abs($dataDay) ?> día(s)! - <?php echo date("Y-m-d", strtotime("+".$dataDay." day")) ?></span>
														<?php endif ?>
													<?php endif;?>

												</td>
											</tr>
										<?php endforeach ?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="row">				
				<div class="col-md-12">
					<div class="blockwhite mb-3 p-2">
						<div class="card bg-gris border-0 p-3">
							<div class="col-md-12 titleorigen mb-2">
								<h2 class="text-center">
									Productos que se encuentan en transito
								</h2>
							</div>
						
							
							<div class="table-responsive" id="dataProductsTransito">
								<div style="max-height: 400px; overflow-y: auto;">
									<table class="table-bordered table datosPendientesDespacho" id="productosDataTransito" style="max-width: 100% !important;">
										<thead>
											<tr>
												<th class="p-1">
													Img
												</th>											
												<th>
													N° de parte
												</th>
												<th>
													Producto
												</th>
												<th>
													Cantidad
												</th>
												<th>
													Inventario actual
												</th>
											</tr>
										</thead>
										<?php foreach ($datosTransito as $key => $value): ?>
											<tr>
												<td class="p-1" style="width: 80px !important;">

													<?php $ruta = $this->Utilities->validate_image_products($value["Product"]['img']); ?>
													<img dataimg="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" dataname="<?php echo h($value["Product"]['name']); ?>" src="<?php echo $this->Html->url('/img/products/'.$ruta) ?>" width="55px" height="55px" class="imgmin-product">
												</td>
												<td>
													<?php echo $value["Product"]['part_number'] ?>
												</td>
												<td>
													
													<?php echo $value["Product"]['name'] ?>
												</td>
												<td>
													<?php echo $value["0"]["totalTransito"] ?>
												</td>
												<td>
													<?php echo $this->element("products_block",["producto" => $value["Product"],"inventario_wo" => $partsData[$value["Product"]["part_number"]] ,"reserva" => isset($partsData["Reserva"][$value["Product"]["part_number"]]) ? $partsData["Reserva"][$value["Product"]["part_number"]] : null ]) ?>
												</td>
											</tr>
										<?php endforeach ?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php endif; ?>
	
	<div class="col-md-12">
		<div class="blockwhite mb-3 p-2">
			<div class="card bg-gris border-0 p-3">
				<h2 class="text-center">Clientes hoy CRM (<?php echo count($naturals_cs)+count($legals_cs) ?>)</h2>
				<hr>
				<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
					<table class="table-bordered table">
						<thead>
							<tr>
								<th class="p-0">
									Tipo
								</th>
								<th class="p-0">
									Nombre
								</th>
								<th class="p-0">
									ID / NIT
								</th>
								<th class="p-0">
									Nac / Int
								</th>
								<th class="p-0">
									Datos adicionales
								</th>
							</tr>
						</thead>
						<?php foreach ($naturals_cs as $key => $value): ?>
							<tr>
								<th class="p-0">
									Natural
								</th>
								<th class="p-0">
									<?php echo $value["ClientsNatural"]["name"] ?>
								</th>
								<td class="p-0"><?php echo $value["ClientsNatural"]["identification"] ?></td>
								<td class="p-0">
									<?php echo $value["ClientsNatural"]["nacional"] == 1 ? "Nacional" : "Internacional" ?>
								</td>
								<td class="p-0">
									<ul class="list-unstyled">
										<li><b>Correo:</b> <?php echo $value["ClientsNatural"]["email"] ?></li>
										<li><b>Telefono/Celular:</b> <?php echo $value["ClientsNatural"]["telephone"] ?> / <?php echo $value["ClientsNatural"]["cell_phone"] ?></li>
										<li><b>Ciudad:</b> <?php echo $value["ClientsNatural"]["city"] ?></li>
									</ul>
								</td>
							</tr>
						<?php endforeach ?>
						<?php foreach ($legals_cs as $key => $value): ?>
							<tr>
								<th class="p-0">
									Empresa
								</th>
								<th class="p-0">
									<?php echo $value["ClientsLegal"]["name"] ?>
								</th>
								<td class="p-0"><?php echo $value["ClientsLegal"]["nit"] ?></td>
								<td class="p-0">
									<?php echo $value["ClientsLegal"]["nacional"] == 1 ? "Nacional" : "Internacional" ?>
								</td>
								<td class="p-0">
									<?php if (!empty($value["ContacsUser"])): ?>
										<?php foreach ($value["ContacsUser"] as $keyCont => $valueCont): ?>
											<?php if ($valueCont["created"] == date("Y-m-d")): ?>
												<ul class="list-unstyled">
													<li><b>Contacto:</b> <?php echo $valueCont["name"] ?></li>
													<li><b>Correo:</b> <?php echo $valueCont["email"] ?></li>
													<li><b>Telefono/Celular:</b> <?php echo $valueCont["telephone"] ?> / <?php echo $valueCont["cell_phone"] ?></li>
													<li><b>Ciudad:</b> <?php echo $valueCont["city"] ?></li>
												</ul>
											<?php endif ?>
										<?php endforeach ?>
									<?php endif ?>
									
								</td>
							</tr>
						<?php endforeach ?>
					</table>
				</div>
			</div>
		</div>
	</div>
		
	<?php if (AuthComponent::user("role") != "Publicidad"): ?>
		<?php echo $this->element("cartera",["roles"=>$roles]) ?>
		<div class="col-md-12">
			<div class="blockwhite p-2">
				<div class="card bg-gris border-0 p-2">
					<h2 class="text-center">Flujos y cotizaciones más costosas activas</h2>
					<hr>
					<div id="flujodashboard">
						<iframe width="100%" height="100%" src="<?php echo $this->Html->url(array('controller'=>'prospectiveUsers','action'=>'adviser_dashboard',$id_user_busca)) ?>"></iframe>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>
</div>

</div>


<div class="modal fade" id="modalFlujosEstado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg3" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title">
        	Detalle de flujos por estado
        </h2>
      </div>
      <div class="modal-body" id="bodyFlujosEstado">
      </div>
      <div class="modal-footer">
        <a class="cancelmodal btn btn-primary" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>


<div class="modal fade" id="modalDetalleComisionesUsuario" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg3" role="document" style="max-width: 98% !important">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title">
        	Detalle de comisiones por usuario        

		</h2>
		<div class="rangofechas pull-right text-right">
				<?php if (AuthComponent::user("role") == "Gerente General" ): ?>
					<?php echo $this->Form->input('user_id_simulates', array('options'=>$usuarios_names, 'class'=>'d-inline-block form-control w-auto','label'=>false,'div'=>false,'value'=>$id_user_busca)) ?>
				<?php else: ?>
					<?php echo $this->Form->input('user_id_simulates', array('type' => 'hidden','value'=>$id_user_busca)) ?>
				<?php endif ?>
				<span>Seleccionar rango de fechas:</span>
				<div class="d-inline-block form-group">
					<input type="text" value="<?php echo date("Y-m-d"); ?>" id="fechasInicioFinComisiones" class="mb-2">
					<a id="btn_find_adviser_comisiones" class="btn-primary btn">Buscar</a>
				</div>

				<div style="display: none">
					<div class="form-group">
					  	<span>Desde</span>
					</div>
					<div class="form-group">
						<input type="date" value="<?php echo date("Y-m-01"); ?>" class="form-control" id="input_date_inicio_comision" style="display: none">
					</div>
				</div>
				<div style="display: none">
					<div class="form-group">
					  	<span>Hasta</span>
					  	<input type="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin_comision" placeholder="Desde" style="display: none">
					</div>
				</div>
			</div>
      </div>
      <div class="modal-body pt-0">
      	<div class="row">
      		<div class="col-md-12 titleorigen mb-1">
				<div class="col-md-12 ">
					
				</div>
			</div>
			<div class="col-md-12" id="datosComisiones" style="min-height: 70vh  max-height: 70vh;  overflow: auto;">
				
			</div>
      	</div>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal btn btn-primary" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<?php 
	echo $this->element("flujoModal");
	if (!isset($catsClientes) || is_null($catsClientes)) {
		$catsClientes = [];
	}
	if (!isset($catsClientesPayments) || is_null($catsClientesPayments)) {
		$catsClientesPayments = [];
	}
 ?>

<?php 
    $this->start('AppScript'); ?>
	<script>

		<?php if (isset($goals_user) && !empty($goals_user)): ?>
			var GOALS = <?php echo json_encode($goals_user) ?>;
		<?php endif ?>

		<?php if (isset($porcentajes_comisiones) && !empty($porcentajes_comisiones)): ?>
			var PERCENTAJES = <?php echo json_encode($porcentajes_comisiones) ?>;
		<?php endif ?>

		<?php if (isset($recaudos_user) && !empty($recaudos_user)): ?>
			var RECAUDOS = <?php echo json_encode($recaudos_user) ?>;
		<?php endif ?>

		<?php if (isset($effectivity_user) && !empty($effectivity_user)): ?>
			var EFFECTIVITY = <?php echo json_encode($effectivity_user) ?>;
		<?php endif ?>

		const catsClientes = <?php echo json_encode($catsClientes); ?>;
		var datosClientes = <?php echo json_encode(array_values($datosClientes)); ?>;
		var totalClientes = <?php echo $totalClientes; ?>;
		var totalClientesGrafo = <?php echo $totalClientesGrafo; ?>;

		var catsFlujo 		= <?php echo json_encode($catsFlujo); ?>;
		var dataAllFlujo 	= <?php echo json_encode($dataAllFlujo); ?>;
		var dataAllPayment  = <?php echo json_encode($dataAllPayment); ?>;

		var catsQuotations = <?php echo json_encode($catsQuotations); ?>;
		var datosQuoatiations = <?php echo json_encode(array_values($datosQuoatiations)); ?>;
		var totalQuoatations = <?php echo $totalQuoatations; ?>;

		var catsClientesPayments = <?php echo json_encode($catsClientesPayments); ?>;
		var totalClients = <?php echo json_encode(array_values($totalClients)); ?>;
		var totalClientsPayment = <?php echo json_encode(array_values($totalClientsPayment)); ?>;

		var dataClientes = <?php echo json_encode($datosClientesByOwner); ?>;

		var flujosPagadosClientesAll = <?php echo json_encode($flujosPagadosClientesAll); ?>;
		var pagadosViejosClientes = <?php echo empty($pagadosViejosClientes)  ? [] :  json_encode($pagadosViejosClientes); ?>;

		var mesesCats = <?php echo json_encode(array_values($mesesCats)); ?>;
		var dataPrev = <?php echo json_encode($dataPrev); ?>;
		var dataBtnDt = <?php echo json_encode($labelsBtn); ?>;
		var totalByCompany = <?php echo json_encode($totalByCompany); ?>;
		var metasAnio = <?php echo json_encode($metasAnio); ?>;
		var cumplimientoAnual = <?php echo json_encode($cumplimientoAnual); ?>;

		var datosTodos = <?php echo json_encode($totalByCompany); ?>;
		var totalAnio = <?php echo json_encode($totalAnio); ?>;
		var metaAnual = <?php echo json_encode($metaAnual); ?>;
		var cumplimientoTodos = <?php echo json_encode($cumplimientoTodos); ?>;
		
	</script>
<?php
    $this->end();
 ?>

 <?php echo $this->element("picker"); ?>



<?php 
	$this->start('AppScript'); ?>

	<script>
		$("#btn_find_adviser").click(function(event) {
			var actual_query        =  URLToArray(actual_uri);

			actual_query["ini"] = $("#input_date_inicio").val();
			actual_query["end"] = $("#input_date_fin").val();

			location.href = actual_url+$.param(actual_query);
			console.log(actual_query)
		});
	</script>

<?php
	$this->end();
 ?>


<?php
	echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));
	echo $this->Html->script(array('https://code.highcharts.com/highcharts.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('https://code.highcharts.com/modules/data.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('https://code.highcharts.com/modules/drilldown.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/exporting.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/offline-exporting.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('lib/export-data.js'),	array('block' => 'AppScript'));
	echo $this->Html->script(array('controller/config/dashboard.js?'.time()),	array('block' => 'AppScript'));
	echo $this->Html->script("controller/prospectiveUsers/report_adviser.js",									array('block' => 'AppScript'));
?>
<style>
	svg{
		display: block !important;
	}
	.highcharts-data-table{
		display: none !important;
	}
	footer{
		display: none;
	}
	.table-responsive #flujosData_wrapper>.row, #debtsData_wrapper>.row{
		margin-right: 0px;
    	margin-left: 0px;
	}
	table, th, td, tbody, thead{
		font-weight: 550 !important;
	}
</style>