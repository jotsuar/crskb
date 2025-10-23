<div class="col-md-12 spacebtn20">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite headerinformelineal">
		<div class="row">
			<div class="col-md-4">
				<h1 class="nameview">INFORME DE PAGOS PARA ASESORES EXTERNOS</h1>
			</div>
			<div class="col-md-8">
				<div class="rangofechas w-100">
					<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<span>Seleccionar rango de fechas:</span>
								</div>
								<div class="form-group">
									<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<?php echo $this->Form->input('user_id',array("required" => false, "label" => "Seleccionar usuario" ,"options" => $users, "empty" => "Todos los usuarios","value" => $user_id)); ?>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<?php echo $this->Form->input('excel',array("required" => true, "label" => "Generar Informe Excel" ,"options" => Configure::read("IMPUESTOS"))); ?>
								</div>
							</div>
							<div class="col-md-2 pt-4">
								<input type="date" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none" name="fechaIni">
								<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none" name="fechaEnd">
								<button type="submit" class="btn btn-success pull-right" id="btn_find_adviser">Generar informe</button>
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
						<th>Código Cuenta</th>
						<th>Usuario que solicita</th>
						<th>Fecha solicitud</th>
						<th>Valor Solicitud inicial</th>
						<th>Fecha pago</th>
						<th>Valor pagado</th>
						<th># Recibos</th>
						<th>Detalle</th>
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
									CBKEB #<?php echo $value["Account"]["id"] ?>
								</td>
								
								<td class="text-uppercase">
									<?php echo h($value['User']['name']); ?>
								</td>
								<td class="text-uppercase">
									<?php echo h($value['Account']['date_send']); ?>
								</td>
								<td>
									$<?php echo number_format($value['Account']['initial_value']); ?>&nbsp;
								</td>

								<td>
									<?php echo h($value['Account']['date_payment']); ?>
								</td>
								<td><?php echo count($value["Receipt"]) ?></td>
								<td>
									$<?php echo number_format($value['Account']['value_payment']); $totalPagar+=$value['Account']['value_payment']; ?>&nbsp;
								</td>
								<td>
									<a class="btn btn-outline-primary" href="<?php echo $this->Html->url(array('action' => 'informe_comisiones_externals_view', $this->Utilities->encryptString($value['Account']['id']))) ?>" data-placement="top" data-toggle="tooltip" title="Ver detalle">
					                  <i class="fa fa-fw fa-eye vtc"></i>
					                </a>
								</td>
							</tr>
							<tr>
								<td colspan="6" class="text-right">
									Total pagado: 
								</td>
								<th colspan="2">
									$<?php echo number_format($totalPagar) ?>
								</th>
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
