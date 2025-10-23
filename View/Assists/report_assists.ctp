<?php $roles = ["Asesor Técnico Comercial","Asesor Comercial","Gerente línea Productos Pelican","Servicio al Cliente","Asesor Técnico Comercial","Asesor Logístico Comercial","Asesor Externo"] ?>
<div class="col-md-12 spacebtn20">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite headerinformelineal mb-3">
		<div class="row">
			<div class="col-md-6">
				<h2>
					Total demora de la empresa: <?php echo $totalEmpresa ?> Horas
				</h2>
			</div>
			<div class=" col-md-6 pull-right text-right">
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
				
				</h1>
				<div class="table-responsive">
					<table class="table table-hovered table-bordered" id="naturalpersontable2">
						<thead class="thead-dark">
							<tr class="text-center">
								<th class="bg-blue">
									Usuario
								</th>
								
								<th class="bg-green">
									Demora
								</th>
								<th>
									Ver detalle
								</th>
							</tr>
						</thead>
						<tbody>
							
							<?php foreach ($usuarios as $user_id => $value): ?>
								<tr class="text-center">
									<td><?php echo $value["name"] ?></td>
									<td><?php echo $value["demora"] ?> Horas</td>
									<td>
										<a href="<?php echo $this->Html->url(["action"=>"index","?"=>["ini"=>$this->request->query["ini"], "end"=>$this->request->query["end"], "user_id" => $user_id ]]) ?>" class="btn btn-info" target="_blank">Ver detalle</a>
									</td>
									
								</tr>
							<?php endforeach ?>

						</tbody>
					</table>
					
				</div>
			</div>
		<!-- 	<button type="button" class="btn btn-success float-right" id="guardaInforme">
				Guardar informe
			</button> -->
			
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


	</script>

<?php
	$this->end();
 ?>
