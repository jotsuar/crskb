<div class="col-md-12 p-0">
	<div class=" widget-panel widget-style-2 bg-cafe big">
         <i class="fa fa-1x flaticon-report-1"></i>
        <h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-6">
				<h1 class="nameview">INFORME DE LÍNEAS DE NEGOCIO POR CHAT</h1>
				<span class="subname">Informe general de Líneas de negocio para chats</span>
				
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
	<div class="blockwhite spacebtn20">
		<div class="row">
			<?php if (!empty($lineas)): ?>
				<div class="col-md-4 p-2 mt-2">
					<h3 class="text-center">
						Total chats por línea
					</h3>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tbody>
								<?php foreach ($lineas as $linea => $total): ?>
									<tr>
										<th class="py-0 px-1">
											<?php echo $linea ?>
										</th>
										<td class="py-0 px-1">
											<?php echo $total ?>
										</td>
									</tr>
								<?php endforeach ?>
								<tr>
									<th>Total:</th>
									<td>
										<?php echo array_sum($lineas) ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif ?>

			
			<?php if (!empty($fechas)): ?>
				<div class="col-md-8 p-2 mt-2">
					<h2 class="text-center">
						Datos por día
					</h2>
					<div class="row">
						<?php foreach ($fechas as $key => $lines): ?>
							<div class="col-md-4 mb-2">
								<div class="card">
								  <div class="card-header py-0 px-1 text-center">
								    <strong><?php echo $key ?> - Total: <?php echo array_sum($lines) ?></strong>
								  </div>
								  <ul class="list-group list-group-flush">
								  	<?php foreach ($lineas as $keyLine => $totalLine): ?>
								  		<?php if (!array_key_exists($keyLine, $lines)): ?>
								  			<li class="list-group-item py-0 px-1"><?php echo $keyLine ?>: 0</li>
								  		<?php else: ?>
								  			<li class="list-group-item py-0 px-1"><?php echo $keyLine ?>: <?php echo $lines[$keyLine] ?></li>
								  		<?php endif ?>
								  		
								  	<?php endforeach ?>
								  </ul>
								</div>
							</div>
						<?php endforeach ?>
					</div>
				</div>
			<?php endif ?>
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