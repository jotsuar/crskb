<div class="col-md-12 spacebtn20">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite headerinformelineal">
		<div class="row">
			<div class="col-md-4">
				<h1 class="nameview">INFORME DE RECIBOS DE CAJA</h1>
			</div>
			<div class="col-md-8">
				<div class="rangofechas w-100">
					<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<span>Seleccionar rango de fechas:</span>
								</div>
								<div class="form-group">
									<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<?php echo $this->Form->input('excel',array("required" => true, "label" => "Generar Informe Excel" ,"options" => Configure::read("IMPUESTOS"))); ?>
								</div>
							</div>
							<div class="col-md-2 pt-4">
								<input type="date" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
								<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
								<button type="submit" class="btn btn-base pull-right" id="btn_find_adviser">Generar informe</button>
							</div>
						</div>
						
						
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="blockwhite mt-3">
		<div class="table-responsive">
			<h2 class="text-center">Datos de venta por flujo</h2>
			<table class="table table-bordered <?php echo empty($sales) ? "" : "datosPendientesDespacho" ?>  table-striped">
				<thead>
					<tr>						
						<th>Flujo</th>
						<th>Cliente</th>
						<th>Usuario</th>
						<th>Factura</th>
						<th>Fecha factura</th>
						<th>Recibo</th>
						<th>Fecha recibo</th>
						<th>Valor venta</th>
						<th>Valor pago</th>
						<th>RETEFUENTE</th>
						<th>RETEIVA</th>
						<th>Otras comis.</th>
						<th>Base comisión</th>
						<!-- <th>Factura</th> -->
					</tr>
				</thead>
				<tbody>						
					<?php if (empty($sales)): ?>
						<tr>
							<td colspan="13" class="text-center">
								<?php if ($filter): ?>
									<strong>No existen registros de facturación</strong>
								<?php else: ?>
									<h2 class="text-danger">! Para ver datos por favor realiza una búsqueda ¡</h2>									
								<?php endif ?>
							</td>
						</tr>
					<?php else: ?>
						<?php $totalPagar = 0; foreach ($sales as $key => $value): ?>
							<tr>
								<td>
									<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action' => 'index?q='.$value["ProspectiveUser"]["id"])) ?>" class="idflujotable m-1" target="_blank">
										<?php echo $value["ProspectiveUser"]["id"] ?>
									</a>
								</td>
								
								<td class="text-uppercase">
									<?php echo !empty($value["ClientsNatural"]["name"]) ? $value["ClientsNatural"]["name"] : $value["ContacsUser"]["ClientsLegal"]["name"] ?>
								</td>

								<td>
									<?php echo $this->Utilities->find_name_adviser($value['User']['id']); ?> 
								</td>


								<td class="text-uppercase"><?php echo $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_code") ?></td>

								<td><?php echo $this->Utilities->date_castellano2( $this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_date")); ?></td>

								<td class="text-uppercase"><?php echo $value["Receipt"]["code"] ?></td>

								<td><?php echo $this->Utilities->date_castellano2($value["Receipt"]["date_receipt"]) ?></td>

								<td> $ <?php echo number_format($this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_value"),0,".",",") ?></td>
								<td> $ <?php echo number_format($value["Receipt"]["total"],0,".",",") ?></td>
								<td><?php echo $value["Receipt"]["retefuente"] == 0 ? "No" : "Si" ?></td>
								<td><?php echo $value["Receipt"]["reteiva"] == 0 ? "No" : "Si" ?></td>
								<td><?php echo $value["Receipt"]["otras"] == 0 ? "No" : "Si" ?></td>
								<td> $ <?php echo number_format($value["Receipt"]["total_iva"],0,".",",") ?></td>
								<!-- <td>
									<a href="<?php echo $this->Html->url("/files/flujo/facturas/".$this->Utilities->getCodeBillField($value["ProspectiveUser"],$value["Receipt"]["salesinvoice_id"],"bill_file") ) ?>" target="blank" class="btn btn-info btn-secondary">
										Ver <i class="fa fa-file vtc"></i>
									</a>
								</td> -->
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
?>


<?php echo $this->element("picker"); ?>
