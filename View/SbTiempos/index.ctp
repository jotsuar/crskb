<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
         <i class="fa fa-1x flaticon-growth"></i>
        <h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class=" blockwhite spacebtn20">
		<div class="row ">
			<div class="col-md-8">
				<h1 class="nameview">
					INFORME DE TIEMPOS DE CONEXIÓN
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
	<div class=" blockwhite spacebtn20">
		<div class="table-responsive">
			<table class="table table-hovered table-bordered">
				<thead>
					<tr>
						<th>Asesor</th>
						<th>tiempo activo</th>
						<th>Tiempo inactivo</th>
						<th>
							Acciones
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($users as $key => $value): ?>
						<tr>
							<td>
								<?php echo $value["name"] ?>
							</td>
							<td class="text-success">
								<?php echo $this->Utilities->calcularTiempoPasado($value["activo"]) ?>
							</td>
							<td class="text-danger">
								<?php echo $this->Utilities->calcularTiempoPasado($value["inactivo"]) ?>
							</td>
							<td>
								<a href="<?php echo $this->Html->url(["action"=>"view", $this->Utilities->encryptString($key) ,"?"=>["ini"=>$fechaInicioReporte, "end"=>$fechaFinReporte]]) ?>" class="btn btn-info">
									Ver detalle
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
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
