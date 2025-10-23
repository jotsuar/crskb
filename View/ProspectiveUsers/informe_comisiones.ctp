<div class="col-md-12">
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
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas')) ?>"><b>1-</b> Informe de ventas</a>
					</li>	
					<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
					<li>
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas_tienda')) ?>"><b>2-</b> Informe de ventas en tienda</a>
					</li>
					<?php endif?>
					<li class="activesub">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones')) ?>"><b>3-</b> Informe de Comisiones</a>
					</li>					
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12">
	<?php if (!in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])) {
		$usuarios = [AuthComponent::user("id") => AuthComponent::user("name")];
	} ?>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-4">
				<h1 class="nameview2">INFORME DE COMISIONES</h1>
				<span class="subname">Informe de comisiones teniendo en cuenta las facturas y los recibos de caja</span>
			</div>
			<div class="col-md-8">
				<div class="row">
					<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-2">
									<span>Estado del pago</span>
									<?php echo $this->Form->input('state',array('label' => false, 'id' => 'state_receipt', 'options' => ["1" => "Pago", "0" => "Sin pagar" ],"empty"=> "Pagados y sin pagar", 'class' => 'form-control',"value"=>$state));
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
								<div class="col-md-2 resetlabel">
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
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="blockwhite">
		<div class="table-responsive">
			<a href="" class="btn btn-success btnChangePayment">
									Marcar como pagado <i class="fa fa-check vtc"></i>
								</a>
			<table class="table table-bordered <?php echo empty($sales) ? "" : "datosPendientesDespacho" ?>  table-hovered">
				<thead>
					<tr>	
						<th>
							
							<label class="containercheck mb-3 mb-4 ml-4 mr-2">
									<input type="checkbox" class="checkAll check_all">
									<span class="checkmark"></span>
								</label>
								<br>
								<span class="ml-4">
									Marcar como pagado
								</span>

						</th>					
						<th>Flujo</th>
						<th>Cliente</th>
						<th>Factura</th>
						<th>Fecha factura</th>
						<th>Recibo</th>
						<th>Fecha recibo</th>
						<th>Valor venta</th>
						<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
						<th>Utilidad</th>
							<?php endif ?>
						<th>Valor recaudo</th>
						<th>Base</th>
						<th>Días pago</th>
						<th>% </th>
						<th>Comisión <br> sin calcular % de margen</th>
						<th>Notas</th>
						<th>Factura</th>
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
						<?php $totalPagar = 0; foreach ($sales as $key => $value): ?>
							<tr>
								<td>
									<?php if ($value["Receipt"]["state"] == 0): ?>
										
									<label class="containercheck position-absolute" style="margin-top: -10px;">
										<input type="checkbox" class="checkB check_prod_<?php echo md5($value["Receipt"]["id"]) ?>"  value="<?php echo $value["Receipt"]["id"] ?>" data-check="check_all">
										<span class="checkmark"></span>
									</label>
									<?php else: ?>
										Pagado
									<?php endif ?>
								</td>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable flujoModal m-1" target="_blank" data-uid="<?php echo $value['ProspectiveUser']['id'] ?>"  data-type="<?php echo $value['ProspectiveUser']['contacs_users_id'] ?>">
																		<?php echo $value["ProspectiveUser"]["id"] ?>
																	</a>
								</td>

								<td class="text-uppercase">
									<?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?>
								</td>
								<td class="text-uppercase"><?php echo $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code") ?></td>
								<td><?php echo $this->Utilities->date_castellano( $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date")); ?></td>
								<td class="text-uppercase"><?php echo $value["Receipt"]["code"] ?></td>
								<td><?php echo $this->Utilities->date_castellano($value["Receipt"]["date_receipt"]) ?></td>
								<td> $ <?php echo number_format($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value"),0,".",",") ?></td>
								<?php if (in_array(AuthComponent::user("role"), ["Gerente General","Contabilidad"])): ?>
									<td>
										
										<b>Valor factura: </b> <?php echo number_format($value["Valores"]["valor_factura"],2,",","."); ?>$ <br>
										<?php if ($value["Valores"]["valor_factura"] != $value["Valores"]["valorReal"] && $value["Valores"]["valorReal"] != 0): ?>
											<small>
												<b>Valor factura sin ST: </b> <?php echo number_format($value["Valores"]["valorReal"],2,",",".") ?>
											</small><br>
										<?php endif ?>
										<b>Costo factura: </b> <?php echo number_format($value["Valores"]["costo_factura"],2,",","."); ?>$ <br>
										<?php if ($value["Valores"]["costo_factura"] != $value["Valores"]["costoReal"] && $value["Valores"]["costoReal"] != 0): ?>
											<small>
												<b>Costo factura sin ST: </b> <?php echo number_format($value["Valores"]["costoReal"],2,",",".") ?>
											</small><br>
										<?php endif ?>
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
								<td>
									<?php 
										$percentaje =  !is_null($value['Receipt']['percent_value']) ? $value['Receipt']['percent_value'] : $this->Utilities->getComissionPercentaje($dias,$comision); 
										if (!is_null($value["Valores"]["percentFinalData"])){
											$percentajeNuevo = $percentaje * ($value["Valores"]["percentFinalData"]/100);
											echo $percentajeNuevo;
										}else{
											echo $percentaje; 
										}
									?>
								</td>
								<td>$ <?php
										if ($value["Valores"]["totalProductos"] == 1 && $value["Valores"]["totalSt"] > 0) {
											$value["Receipt"]["total_iva"] = 0;
										}

										$pagar 		= ($percentaje / 100) *  floatval($value["Receipt"]["total_iva"] - $value["Valores"]["valorSt"])  ; 

										if (!is_null($value["Valores"]["percentFinalData"])){
											$pagar =( ($pagar)  * ($value["Valores"]["percentFinalData"]/100));
										}

										$totalPagar += $pagar + ($value["Valores"]["valorBySt"]) ; echo number_format($pagar + ($value["Valores"]["valorBySt"]),0,".",",") 
										?>
								</td>
								<td>
									<?php if ($value["Valores"]["totalSt"] > 0): ?>
										<?php echo $value["Valores"]["totalSt"] ?> servicio(s) técnico(s), para un total de: <?php echo number_format($value["Valores"]["valorBySt"]) ?>
									<?php endif ?>
								</td>
								<td>
									<?php $dataBill = $this->Utilities->getDataBill($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"]); ?>

									<?php if (empty($dataBill)): ?>
										<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$dataBill["bill_file"] ) ?>" target="blank" class="btn btn-info btn-secondary">
											Ver factura <i class="fa fa-file vtc"></i>
										</a>
									<?php else: ?>
										<a href="#" target="blank" class="btn btn-info btn-secondary mostradDatosFact" data-id="KEB_<?php echo md5($dataBill["bill_code"]) ?>">
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
						<tr>
							<th>
								Efectividad periodo
							</th>
							<td>
								<?php echo isset($dataEfectivity["efectividad"]) ? $dataEfectivity["efectividad"] : 0 ?>%
							</td>
							<th>
								Meta de ventas:
							</th>
							<td>
								$<?php echo number_format($metaVentas) ?>
							</td>
							<th colspan="2" class="text-right">Meta de recaudo</th>
							<td>$<?php echo number_format($meta) ?></td>
							<th colspan="2"> Total recaudado</th>
							<td>$<?php echo number_format($saldos) ?></td>
							<th colspan="3" class="text-right">Total a pagar antes de calculos</th>
							<td colspan="3">
								<h2>
									<?php if ($saldos < $meta): ?>
										No cumple con el recaudo mínimo
									<?php else: ?>
										$<?php echo number_format($totalPagar,0,".",",") ?>	
									<?php endif ?>
								</h2>									
							</td>
							<td>
								<a href="" class="btn btn-success btnChangePayment">
									Marcar como pagado <i class="fa fa-check vtc"></i>
								</a>
							</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
	
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
	echo $this->Html->script("controller/prospectiveUsers/comisiones.js?".rand(),			array('block' => 'AppScript')); 
?>


<?php echo $this->element("picker"); ?>
<?php echo $this->element("flujoModal"); ?>