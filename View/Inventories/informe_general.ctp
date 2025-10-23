<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-cafe big">
         <i class="fa fa-1x flaticon-report-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h1 class="nameview">INFORME DE INVENTARIO</h1>
				<span class="subname">Informe general de stock de inventario.</span>
				
			</div>
			<div class="col-md-6 pull-right text-right">
				<?php echo $this->Form->create('', array('role' => 'form','type'=>'GET','class'=>'')); ?>	     
					<div class="rangofechas">
						<span>Seleccionar rango de fechas:</span>
						<div class="form-group">
							<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="">
							<input type="submit" class="btn-primary btn" value="Filtrar fechas">
						</div>
					</div>

					<div style="display: none">
						<div class="form-group">
							<span>Desde</span>
						</div>
						<div class="form-group">
							<input type="date" value="<?php echo $fechaInicioReporte ?>" max="<?php echo date("Y-m-d") ?>" name="ini" class="form-control" id="input_date_inicio" style="display: none">
						</div>
					</div>
					<div style="display: none">
						<div class="form-group">
							<span>Hasta</span>
						</div>
						<div class="form-group">
							<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" name="end" class="form-control" id="input_date_fin" style="display: none">
						</div>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-6">
			<h2>Costo total usd: <b><?php echo number_format($avgGeneral["0"]["total_usd"],2,",","."); ?></b></h2>
		</div>
		<div class="col-md-6">
			<h2>Costo total cop: <b><?php echo number_format($avgGeneral["0"]["total_cop"],2,",","."); ?></b></h2>
		</div>
		<div class="col-md-12 mt-3">
			<h2>Detalle por producto</h2>
			<div class="table-responsive">
				<table class="table table-bordered <?php echo empty($inventarios) ? "" : "datosPendientesDespacho" ?> ">
					<thead>
						<tr>
							<th>Producto</th>
							<th>Referencia</th>
							<th>Inventario promedio</th>
							<th>Costo promedio usd</th>
							<th>Costo promedio cop</th>
							<th>
								Total costo inventario
							</th>
							<th>
								Total costo inventario
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($inventarios as $key => $value): ?>
							<tr>
								<td>
									<?php echo $value["Product"]["name"] ?>
								</td>
								<td>
									<?php echo $value["Product"]["part_number"] ?>
								</td>
								<td>
									<?php echo number_format($value["0"]["inventario"],0,",",".") ?>
								</td>
								<td>
									<?php echo number_format($value["0"]["total_usd"],2,",",".") ?>
								</td>
								<td>
									<?php echo number_format($value["0"]["total_cop"],2,",",".") ?>
								</td>
								<td>
									<?php echo number_format($value["0"]["inventario"]*$value["0"]["total_usd"],2,",",".") ?>
								</td>
								<td>
									<?php echo number_format($value["0"]["inventario"]*$value["0"]["total_cop"],2,",",".") ?>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>





<?php echo $this->element("picker"); ?>




<?php 

echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
echo $this->Html->script(array('controller/inventories/reports_promedios.js'),	array('block' => 'AppScript'));

 ?>


<style>
svg{
	display: block !important;
}
.highcharts-data-table{
	display: none !important;
}
</style>