<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-verde big">
         <i class="fa fa-1x flaticon-money"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Tesorería</h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-12 text-center">
				<h1 class="nameview">INFORMES DE TESORERÍA</h1>
			</div>
		</div>
	</div>

	<div class=" blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12 mb-3">
				<h2 class="mb-2">TIPOS DE PAGOS</h2>
				<ul class="subpagos-box">
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment')) ?>">Verificar Pagos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_tienda')) ?>">Verificar pagos en tienda</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_credito')) ?>">Verificar créditos</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_true')) ?>">Pagos verificados</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_false')) ?>">Pagos rechazados</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payments_payments')) ?>">Verificación total de abonos</a>
					</li>

				</ul>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<h2 class="mb-2">INFORMES DE TESORERÍA</h2>
				<ul class="subpagos-box2">
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas')) ?>"><b>1-</b> Informe de ventas</a>
					</li>	
					<li class="activesub">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas_tienda')) ?>"><b>2-</b> Informe de ventas en tienda</a>
					</li>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones')) ?>"><b>3-</b> Informe de Comisiones</a>
					</li>					
				</ul>
			</div>
		</div>
	</div>

</div>

<div class="col-md-12 p-0">
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-8">
				<h1 class="text-info mt-3">INFORME DE VENTAS EN TIENDA</h1>
				<span class="subname">Informe de ventas realizadas en venta presencial</span>
			</div>
			<div class="col-md-4">
				<div class="row">
					<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<span>Seleccionar rango de fechas:</span>
									</div>
									<div class="form-group">
										<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
									</div>
								</div>
								
								<div class="col-md-3" style="display: none">
									<span>Desde</span>
									<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
								</div>

								<div class="col-md-3" style="display: none">
									<span>Hasta</span>
									<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
								</div>

								<div class="col-md-6 spacetop">
									<button type="submit" class="btn btn-base btn-primary" id="btn_find_adviser">Buscar</button>
								</div>

							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="blockwhite">
		<div class="table-responsive">
			<?php if (!empty($sales)): ?>				
				<h3 class="text-info text-center">
					<b>Total vendido: </b> <?php echo number_format($total,2,",",".") ?>
				</h3>
			<?php endif ?>
			<table class="table table-bordered <?php echo empty($sales) ? "" : "datosPendientesDespacho" ?>  table-hovered">
				<thead>
					<tr>						
						<th>Flujo</th>
						<th>Cliente</th>
						<th>Vendedor</th>
						<th>Nit o identificación</th>
						<th>Código de factura</th>
						<th>Valor factura</th>
						<th>Fecha de factura</th>
						<th>Archivo</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($sales)): ?>
						<tr>
							<td colspan="7" class="text-center">
								<?php if ($filter): ?>
									<p class="text-danger mb-0">No existen registros de facturación</p>
								<?php else: ?>
									<p class="text-danger mb-0">! Para ver datos por favor realiza una búsqueda ¡</p>									
								<?php endif ?>
							</td>
						</tr>
					<?php else: ?>
						<?php foreach ($sales as $key => $value): ?>
							<tr>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
																		<?php echo $value["ProspectiveUser"]["id"] ?>
																	</a>
								</td>
								<td class="text-uppercase"><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?></td>
								<td>
									<?php echo $this->Utilities->find_name_adviser($value["ProspectiveUser"]["bill_user"]); ?>
								</td>
								<td><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["identification"] : $value["ContacsUser"]["ClientsLegal"]["nit"] ?></td>
								<td class="text-uppercase"><?php echo $value["ProspectiveUser"]["bill_code"] ?></td>
								<td> $ <?php echo number_format($value["ProspectiveUser"]["bill_value"],0,".",",") ?></td>
								<td><?php echo $this->Utilities->date_castellano($value["ProspectiveUser"]["bill_date"]) ?></td>
								<td>
									<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$value["ProspectiveUser"]["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary">
										Ver factura <i class="fa fa-file"></i>
									</a>
								</td>
							</tr>
							<?php if (!empty($value["facturas"])): ?>

								<?php foreach ($value["facturas"] as $keyFactura => $valueFactura): ?>
									<tr>
										<td>
											<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1 flujoModal" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
												<?php echo $value["ProspectiveUser"]["id"] ?>
											</a>
										</td>
										<td class="text-uppercase"><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?></td>
										<td>
											<?php echo $this->Utilities->find_name_adviser($valueFactura["Salesinvoice"]["user_id"]); ?>
										</td>
										<td><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["identification"] : $value["ContacsUser"]["ClientsLegal"]["nit"] ?></td>
										<td class="text-uppercase"><?php echo $valueFactura["Salesinvoice"]["bill_code"] ?></td>
										<td> $ <?php echo number_format($valueFactura["Salesinvoice"]["bill_value"],0,".",",") ?></td>
										<td><?php echo $this->Utilities->date_castellano($valueFactura["Salesinvoice"]["bill_date"]) ?></td>
										<td>
											<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$valueFactura["Salesinvoice"]["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary">
												Ver factura <i class="fa fa-file"></i>
											</a>
										</td>
									</tr>
								<?php endforeach ?>
								
							<?php endif ?>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
			
		</div>
	</div>
	
</div>




<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript')); 
?>


<?php echo $this->element("picker"); ?>

<?php echo $this->element("flujoModal"); ?>