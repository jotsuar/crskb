<div class="col-md-12">
	<div class="contenttableresponsive">
		<h1 class="text-white bg-success text-center">
			Total facturado: <?php echo number_format($totalFacturado,2,",",".") ?>
		</h1>
		<br>
		<div class="row mb-3">
			<div class="border col-md-6 py-1">
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-center mb-2"> 
							Estados de los flujos actuales
						</h2>
					</div>
					<?php foreach ($totalesEstado as $key => $value): ?>
						<div class="col-xl-3 col-md-3 mb-1">
		                    <div class="card border-left-primary shadow border-blue">
		                        <div class="card-body">
		                            <div class="row no-gutters align-items-center">
		                                <div class="col mr-2">
		                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
		                                        <?php echo $this->Utilities->finde_state_flujo($key) ?></div>
		                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $value ?></div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					<?php endforeach ?>		
				</div>
			</div>
			<div class="border col-md-6 py-1">
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-center mb-2"> 
							Estados de los flujos actuales por usuario
						</h2>
					</div>
					<?php foreach ($flujosAsesor as $key => $value): ?>
						<div class="col-xl-3 col-md-3 mb-1">
		                    <div class="card border-left-primary shadow border-blue">
		                        <div class="card-body">
		                            <div class="row no-gutters align-items-center">
		                                <div class="col mr-2">
		                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
		                                        <?php echo $this->Utilities->find_name_adviser($key) ?></div>
		                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
		                                    	<ul class="list-unstyled">
		                                    		<?php foreach ($value as $estado => $total): ?>
		                                    			<li>
		                                    				<b>
		                                    					<?php echo $this->Utilities->finde_state_flujo($estado) ?>: 
		                                    				</b>
		                                    				<?php echo $total; ?>
		                                    			</li>
		                                    		<?php endforeach ?> 
		                                    	</ul>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					<?php endforeach ?>		
				</div>
			</div>
				
		</div>
		<h2 class="text-center mb-2"> 
			Datos por medio de llegada y asesor
		</h2>
		<table cellpadding="0" cellspacing="0" class='table_resultados_numero'>
			<thead>
				<tr class="namestable">
					<th class="columna30 noborder"></th>
					<?php if (in_array(Configure::read('variables.origenContact.Chat'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Chat') ?></span><span class="minicon"><i class="fa fa-comments-o"></i></span></th>
					<?php endif ?>
					<?php if (in_array("Servicio técnico", $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo "Servicio técnico" ?></span><span class="minicon"><i class="fa fa-comments-o"></i></span></th>
					<?php endif ?>
					<?php if (in_array("Tienda", $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo __("Tienda") ?></span><span class="minicon"><i class="fa fa-store"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Whatsapp'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Whatsapp') ?></span><span class="minicon"><i class="fa fa-whatsapp"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Email'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Email') ?></span><span class="minicon"><i class="fa fa-envelope"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Llamada'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Llamada') ?></span><span class="minicon"><i class="fa fa-phone"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Presencial'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Presencial') ?></span><span class="minicon"><i class="fa fa-user-circle-o"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Redes sociales'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Redes sociales') ?></span><span class="minicon"><i class="fa fa-facebook"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Referido'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Referido') ?></span><span class="minicon"><i class="fa fa-facebook"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Chat Pelican'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Chat Pelican') ?></span><span class="minicon"><i class="fa fa-facebook"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Chat Kebco USA'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Chat Kebco USA') ?></span><span class="minicon"><i class="fa fa-facebook"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Whatsapp Kebco USA'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Whatsapp Kebco USA') ?></span><span class="minicon"><i class="fa fa-facebook"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Email Kebco USA'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Email Kebco USA') ?></span><span class="minicon"><i class="fa fa-facebook"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Landing'), $origin) || in_array('Landing', $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Landing') ?></span><span class="minicon"><i class="fa fa-facebook"></i></span></th>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Marketing'), $origin)): ?>
						<th class="columna10"><span class="maxname"><?php echo Configure::read('variables.origenContact.Marketing') ?></span><span class="minicon"><i class="fa fa-facebook"></i></span></th>
					<?php endif ?>
					
					<th class="">TOTAL</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 1; foreach ($users as $user_id): ?>
					<tr>
						<td class="columna30 horizontal_user_<?php echo $i ?>"><?php echo $this->Utilities->find_name_adviser($user_id); ?></td>

						<?php if (in_array(Configure::read('variables.origenContact.Chat'), $origin)): ?>
							<td class="columna10 chatSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Chat')) ?></td>
						<?php endif ?>
						<?php if (in_array("Servicio técnico", $origin)): ?>
							<td class="columna10 stSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,"Servicio técnico") ?></td>
						<?php endif ?>
						<?php if (in_array("Tienda", $origin)): ?>
							<td class="columna10 tiendaSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,"Tienda") ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Whatsapp'), $origin)): ?>
							<td class="columna10 whatsappSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Whatsapp')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Email'), $origin)): ?>
							<td class="columna10 emailSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Email')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Llamada'), $origin)): ?>
							<td class="columna10 llamadaSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Llamada')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Presencial'), $origin)): ?>
							<td class="columna10 presencialSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Presencial')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Redes sociales'), $origin)): ?>
							<td class="columna10 redesSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,'Redes Sociales') ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Referido'), $origin)): ?>
							<td class="columna10 referidosSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Referido')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Chat Pelican'), $origin)): ?>
							<td class="columna10 pelicanSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Chat Pelican')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Chat Kebco USA'), $origin)): ?>
							<td class="columna10 chatUsaSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Chat Kebco USA')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Whatsapp Kebco USA'), $origin)): ?>
							<td class="columna10 wppUsaSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Whatsapp Kebco USA')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Email Kebco USA'), $origin)): ?>
							<td class="columna10 emailUsaSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Email Kebco USA')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Landing'), $origin) || in_array('Landing', $origin) ): ?>
							<td class="columna10 landingSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Landing')) ?></td>
						<?php endif ?>
						<?php if (in_array(Configure::read('variables.origenContact.Marketing'), $origin)): ?>
							<td class="columna10 marketingSuma horizontal_<?php echo $i ?>"><?php echo $this->Utilities->countFlujoOrigen($user_id,$datos,Configure::read('variables.origenContact.Marketing')) ?></td>
						<?php endif ?>
						<td class="usuarioTotal_<?php echo $i ?>"></td>
					</tr>
				<?php $i++; endforeach ?>
				<tr>
					<td class="nametotal">TOTAL</td>

					<?php if (in_array(Configure::read('variables.origenContact.Chat'), $origin)): ?>
						<td class="chatTotal"></td>
					<?php endif ?>
					<?php if (in_array("Servicio técnico", $origin)): ?>
						<td class="stTotal"></td>
					<?php endif ?>
					<?php if (in_array("Tienda", $origin)): ?>
						<td class="tiendaTotal" style="font-size: 23px !important;"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Whatsapp'), $origin)): ?>
						<td class="whatsappTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Email'), $origin)): ?>
						<td class="emailTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Llamada'), $origin)): ?>
						<td class="llamadaTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Presencial'), $origin)): ?>
						<td class="presencialTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Redes sociales'), $origin)): ?>
						<td class="redesTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Referido'), $origin)): ?>
						<td class="referidosTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Chat Pelican'), $origin)): ?>
						<td class="chatpelicanTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Chat Kebco USA'), $origin)): ?>
						<td class="chatUsaTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Whatsapp Kebco USA'), $origin)): ?>
						<td class="wppUsaTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Email Kebco USA'), $origin)): ?>
						<td class="emailUsaTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Landing'), $origin) || in_array('Landing', $origin)): ?>
						<td class="landingTotal"></td>
					<?php endif ?>
					<?php if (in_array(Configure::read('variables.origenContact.Marketing'), $origin)): ?>
						<td class="marketingTotal"></td>
					<?php endif ?>
					<td class="totalTotal"></td>
				</tr>
			</tbody>
		</table>
		<div class="maxbtn">
			<b class="horizontal_user_1">Flujos no válidos: <?php echo $countFlujosNoValidos ?></b> <br>
			<b class="horizontal_user_1">Flujos válidos: <?php echo $countFlujosValidos ?></b> <br>

			<?php $totalFlujos = $countFlujosValidos + $countFlujosNoValidos; $faltantes = count($datos) - $totalNaturalesNuevos - count($clientesNaturalesViejos) - $totalContactosNuevos - count($contactosViejos) ; ?>
			
			<b class="horizontal_user_1">Total clientes naturales nuevos: <?php echo $totalNaturalesNuevos ?> equivalente al <?php echo empty($datos) ? 0 : number_format(($totalNaturalesNuevos/count($datos)) * 100,2) ?> % </b> <br>

			<b class="horizontal_user_1">Total clientes naturales antiguos: <?php echo count($clientesNaturalesViejos) ?> equivalente al <?php echo empty($datos) ? 0 :  number_format((count($clientesNaturalesViejos)/count($datos)) * 100,2) ?> % </b><br>

			<b class="horizontal_user_1">Total clientes juridicos (contactos) nuevos: <?php echo $totalContactosNuevos ?> equivalente al <?php echo empty($datos) ? 0 :  number_format(($totalContactosNuevos/count($datos)) * 100,2) ?> % </b><br>	

			<b class="horizontal_user_1">Total clientes juridicos (contactos) antiguos: <?php echo count($contactosViejos) ?> equivalente al <?php echo empty($datos) ? 0 : number_format((count($contactosViejos)/count($datos)) * 100,2) ?> % </b> <br>

			<b class="horizontal_user_1">Total clientes sin gestión: <?php echo $faltantes ?> equivalente al <?php echo empty($datos) ? 0 : number_format(($faltantes/count($datos)) * 100,2) ?> % </b>
		</div>
		<br>
	</div>
</div>
<?php if (!empty($landing_prospective_total)): ?>
	
<div class="col-md-12">
	<h3 class="text-center">
		Prospectos por página.
	</h3>

	<div class="table-responsive">
		<table class="table table-bordered table-hovered">
			<?php foreach ($landing_prospective_total as $key => $value): ?>
				<tr>
					<th>
						<?php echo $value["ProspectiveUser"]["page"] ?>
					</th>
					<td>
						<b><?php echo $value["0"]["total"] ?></b>
					</td>
				</tr>
			<?php endforeach ?>
		</table>
	</div>

</div>
<?php endif ?>