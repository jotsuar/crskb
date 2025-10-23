<?php $roles = ["Asesor Técnico Comercial","Asesor Comercial","Gerente línea Productos Pelican","Servicio al Cliente","Asesor Técnico Comercial","Asesor Logístico Comercial","Asesor Externo"] ?>
<div class="col-md-12 spacebtn20">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite headerinformelineal mb-3">
		<div class="row">
			<div class="col-md-8">
				<h1 class="nameview">
					INFORME DE FLUJOS CANCELADOS POR ASESOR
				</h1>
			</div>
			<div class=" col-md-4 pull-right text-right">
				<div class="rangofechas">
					<input type="date" value="<?php echo $fechaInicioReporte; ?>" id="input_date_inicio" placeholder="Desde" style="display: none">
					<input type="text" value="<?php echo $fechaInicioReporte; ?>" id="fechasInicioFin" class="">
					<input type="date" value="<?php echo $fechaFinReporte ?>" max="<?php echo date("Y-m-d") ?>" id="input_date_fin" placeholder="Desde" style="display: none">
					<a class="btn-primary btn" id="btn_find_adviser">Filtrar Fechas</a>
				</div>
				
			</div>
		</div>
	</div>
	<div class="blockwhite col-md-12 blockinfome pb-5">
			<div class="col-md-12" id="ventas">
				<h1 class="text-info text-center mb-2 font26">
					Detalle por vendedor para el periodo de <?php echo $this->request->query["ini"] ?> a <?php echo $this->request->query["end"] ?>
				<!-- 	<button id="descargaInfo" class="btn btn-success">
						<i class="fa fa-file vtc"></i> Descarga
					</button> -->
				</h1>
				<div class="table-responsive">
					<table class="table table-hovered table-bordered" id="naturalpersontable2">
						<thead class="thead-dark">
							<tr class="text-center">
								<th class="bg-blue">
									Asesor
								</th>
								<th class="bg-blue">
									F asignados
								</th>
								<th class="bg-blue">
									Cancelados
								</th>
								<th>
									% de cancelación
								</th>
								<th class="bg-red">
									Reasignados
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 

								$totalAsigned 		= 0; 
								$totalCancelados	= 0;
								$totalReasignados	= 0;
								$totalCanceladosPercent = 0;

							?>
							<?php foreach ($usuarios as $user_id => $nombre): ?>
								<tr class="text-center">
									<td><?php echo $nombre ?></td>
									<td>
										<?php //var_dump($finalData[$user_id]); ?>
										<?php $totalAsignados = ($finalData[$user_id][7]+$finalData[$user_id][9]+$finalData[$user_id][10]+$finalData[$user_id][0]); $totalAsigned+=($finalData[$user_id][7]+$finalData[$user_id][9]+$finalData[$user_id][10]+$finalData[$user_id][0]);

											echo $totalAsignados;
										 ?></td>
									<td>
										<?php echo ($finalData[$user_id][7]+$finalData[$user_id][9]); $totalCancelados+=$finalData[$user_id][7]+$finalData[$user_id][9] ?>
									</td>
									<td>
										<?php echo round((($finalData[$user_id][7]+$finalData[$user_id][9]) / $totalAsignados) * 100,2);

											$totalCanceladosPercent += round((($finalData[$user_id][7]+$finalData[$user_id][9]) / $totalAsignados) * 100,2);
										 ?> %
									</td>
									<td>
										<?php echo $finalData[$user_id][10]; $totalReasignados+=$finalData[$user_id][10]; ?>
									</td>
								</tr>
							<?php endforeach ?>
							<tr class="text-center">
								<th class="bg-green text-white text-bold">
									TOTALES
								</th>
								<td>
									<?php echo number_format($totalAsigned,0,",",".") ?>
								</td>
								<td>
									<?php echo $totalCancelados ?>
								</td>
								<td>
									<?php echo round($totalCanceladosPercent/count($usuarios),2); ?> %
								</td>
								<td>
									<?php echo $totalReasignados ?>
								</td>
							</tr>
						</tbody>
					</table>
					
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
	
echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));

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

		$("#guardaInforme").click(function(event) {
			$("#ManagementContent").val($("#ventas").html());
			$("#ManagementAddForm").trigger('submit')
		});

	</script>

<?php
	$this->end();
 ?>
