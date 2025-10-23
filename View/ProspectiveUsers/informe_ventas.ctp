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
		<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
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
		<?php endif?>
		<div class="row">
			<div class="col-md-12">
				<h2 class="mb-2">INFORMES DE TESORERÍA</h2>
				<ul class="subpagos-box2">
					<li class="activesub">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas')) ?>"><b>1-</b> Informe de ventas</a>
					</li>	
					<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
						<li>
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas_tienda')) ?>"><b>2-</b> Informe de ventas en tienda</a>
						</li>
					<?php endif?>
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
			<div class="col-md-5">
				<h1 class="nameview2 mt-3">INFORME DE VENTAS</h1>
				<span class="subname">Informe de ventas por usuario teniendo en cuenta las facturas de compra</span>
			</div>
			<div class="col-md-7">
				<div class="row">
					<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									<span>Asesor</span>
									<?php if (!in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
										<?php $usuarios = [AuthComponent::user("id") => AuthComponent::user("name")] ?>
									<?php endif ?>
									<?php echo $this->Form->input('user',array('label' => false, 'id' => 'usuario', 'options' => $usuarios, 'class' => 'form-control'));
									?>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<span>Seleccionar rango de fechas:</span>
									</div>
									<div class="form-group">
										<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
									</div>
								</div>
								
								<div style="display: none">
									<span>Desde</span>
									<input type="date" value="<?php echo $fechaInicioReporte ?>" class="form-control" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
		
									<span>Hasta</span>
									<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
								</div>

								<div class="col-md-2 spacetop">
									<button type="submit" class="btn btn-base" id="btn_find_adviser">Buscar</button>
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
			<table class="table table-bordered <?php echo empty($sales) ? "" : "datosPendientesDespacho" ?>  table-hovered">
				<thead>
					<tr>						
						<th>Flujo</th>
						<th>Cliente</th>
						<th>ID</th>
						<th>Factura</th>
						<th>Valor</th>
						<th>Fecha</th>
						<th>Archivo</th>
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($sales)): ?>
						<tr>
							<td colspan="6" class="text-center">
								<?php if ($filter): ?>
									<p class="text-danger mb-0">No existen registros de facturación</p>
								<?php else: ?>
									<p class="text-danger mb-0">Por favor realiza una búsqueda</p>									
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
								<td><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["identification"] : $value["ContacsUser"]["ClientsLegal"]["nit"] ?></td>
								<td class="text-uppercase"><?php echo $value["ProspectiveUser"]["bill_code"] ?></td>
								<td> $ <?php echo number_format($value["ProspectiveUser"]["bill_value"],0,".",",") ?></td>
								<td><?php echo $this->Utilities->date_castellano($value["ProspectiveUser"]["bill_date"]) ?></td>
								<td>
									<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$value["ProspectiveUser"]["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary <?php echo is_null($value["ProspectiveUser"]["bill_file"]) ? "mostradDatosFact" : "" ?>" data-id="KEB_<?php echo md5($value["ProspectiveUser"]["bill_code"]) ?>">
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
										<td><?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["identification"] : $value["ContacsUser"]["ClientsLegal"]["nit"] ?></td>
										<td class="text-uppercase"><?php echo $valueFactura["Salesinvoice"]["bill_code"] ?></td>
										<td> $ <?php echo number_format($valueFactura["Salesinvoice"]["bill_value"],0,".",",") ?></td>
										<td><?php echo $this->Utilities->date_castellano($valueFactura["Salesinvoice"]["bill_date"]) ?></td>
										<td>
											<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$valueFactura["Salesinvoice"]["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary <?php echo is_null($valueFactura["Salesinvoice"]["bill_file"]) ? "mostradDatosFact" : "" ?>" data-id="KEB_<?php echo md5($valueFactura["Salesinvoice"]["bill_code"]) ?>">
												Ver factura <i class="fa fa-file vtc"></i>
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

<?php if (!empty($sales)): ?>

	<?php foreach ($sales as $key => $sale): ?>

		<div id="KEB_<?php echo md5($sale["ProspectiveUser"]["bill_code"]) ?>" class="table-responsive" style="display: none;">
			<?php $factValue = (array) json_decode($sale["ProspectiveUser"]["bill_text"]); ?>
			<?php echo $this->element("vistaFacturaWo", ["factValue" => $factValue]); ?>
		</div>

		<?php if (!empty($sale["facturas"])): ?>
			<?php foreach ($sale["facturas"] as $keyFactura => $valueFactura): ?>
				<div id="KEB_<?php echo md5($valueFactura["Salesinvoice"]["bill_code"]) ?>" class="table-responsive" style="display: none;">
					<?php $factValue = (array) json_decode($valueFactura["Salesinvoice"]["bill_text"]); ?>
					<?php echo $this->element("vistaFacturaWo", ["factValue" => $factValue]); ?>
				</div>
			<?php endforeach ?>								
		<?php endif ?>

	<?php endforeach; ?>
	
<?php endif ?>



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


<?php echo $this->element("modals_prospective") ?>



<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/index.js?".rand(),			array('block' => 'AppScript')); 
?>


<?php echo $this->element("picker"); ?>

<?php echo $this->element("flujoModal"); ?>