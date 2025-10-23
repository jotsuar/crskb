<?php $roles = ["Asesor Técnico Comercial","Asesor Comercial","Gerente línea Productos Pelican","Servicio al Cliente","Asesor Técnico Comercial","Asesor Logístico Comercial","Asesor Externo"] ?>
<div class="col-md-12 spacebtn20">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite headerinformelineal mb-3">
		<div class="row">
			<div class="col-md-8">
				<h1 class="nameview">INFORME DE VENTAS REALES</h1>
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
	<div class="blockwhite col-md-12 blockinfome">
		<div class="row myChart p-4">
			<div class="col-md-12" id="efectivity">
				<div class="table-responsive">
					<h2 class="text-info text-center mb-2 font26">
						Informe de ventas y gestión para el periodo de <?php echo $this->request->query["ini"] ?> a <?php echo $this->request->query["end"] ?> y del mes actual
					</h2>
					<table class="table table-hovered table-bordered">
						<thead class="thead-dark">
							<tr class="text-center">
								<th class="bg-blue">
									Ventas del periodo
								</th>
								<th class="bg-blue">
									Ventas del mes
								</th>
							</tr>
						</thead>
						<tbody>
							<tr class="text-center font25">
								<td>
									$<?php echo number_format($totalVentasRango,2,",",".") ?>
								</td>
								<td>
									$<?php echo number_format($totalVentasMes,2,",",".") ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-12" id="ventas">
				<h1 class="text-info text-center mb-2 font26">
					Detalle por vendedor 
					<button id="descargaInfo" class="btn btn-success">
						<i class="fa fa-file vtc"></i> Descarga
					</button>
				</h1>
				<div class="table-responsive">
					<table class="table table-hovered table-bordered" id="naturalpersontable2">
						<thead class="thead-dark">
							<tr class="text-center">
								<th class="bg-blue">
									Asesor
								</th>
								<th class="bg-blue">
									Ventas periodo
								</th>
								<th class="bg-blue">
									Ventas mes
								</th>
								<th class="bg-blue">
									Meta mes
								</th>
								<th class="bg-blue">
									Cumplimiento Mes
								</th>
								<th class="bg-blue">
									F. Asignados
								</th>
								<th class="bg-blue">
									F. Cotizados
								</th>
								<th class="bg-blue">
									# ventas flujos asignados
								</th>
								<th class="bg-blue">
									Efect. Periodo
								</th>
								<th class="bg-blue">
									Efect. Mes
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($dataFinal as $key => $value): ?>
								<tr class="text-center">
									<td><?php echo ($value["name"]) ?></td>
									<td>$<?php echo number_format($value["total_periodo"]) ?></td>
									<td>$<?php echo number_format($value["total_mes"]) ?></td>
									<td>$<?php echo number_format($value["meta_mes"]) ?></td>
									<td><?php echo number_format($value["cumplimiento"]) ?>%</td>
									<td><?php echo ($value["asignados_periodo"]) ?></td>
									<td><?php echo ($value["cotizados_periodo"]) ?></td>
									<td><?php echo ($value["num_ventas"]) ?></td>
									<td><?php echo ($value["efectividad_periodo"]) ?>%</td>
									<td><?php echo ($value["efectividad_mes"]) ?>%</td>
								</tr>
							<?php endforeach ?>
							<tr class="text-center">
								<td class="bg-green text-white text-bold">
									Totales
								</td>
								<td>
									$<?php echo number_format($totalVentasRango,2,",",".") ?>
								</td>
								<td>
									$<?php echo number_format($totalVentasMes,2,",",".") ?>
								</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="col-md-12 mt-5" id="detalleVentas2">
				<h1 class="text-center">
					Detalle de ventas
				</h1>
				<div class="table-responsive">
					<table class="table table-hovered table-strpiped <?php echo !empty($ventas->listado) ? "datosPendientesDespacho" : ""  ?>">
						<thead>
							<tr>
								<th>NIT Cliente</th>
								<th>Cliente</th>
								<th>Factura</th>
								<th>Fecha</th>
								<th>Vendedor</th>
								<th>Total Vendido</th>
								<th>Total Descuento</th>
							</tr>
						</thead>
						<tbody>
							
							<?php if (!empty($ventas->listado)): ?>
								<?php foreach ($ventas->listado as $key => $value): ?>
									<?php if (in_array(AuthComponent::user("role"), $roles)): ?>
										<?php if ($value->IdVendedor != AuthComponent::user("identification")): ?>
											<?php continue; ?>
										<?php endif ?>
									<?php endif ?>
									<tr>
										<td>
											<?php echo $value->Identificacion ?>
										</td>
										<td>
											<?php echo $value->Nombre ?>
										</td>
										<td>
											<?php echo $value->Factura ?>
										</td>
										<td>
											<?php echo date("Y-m-d",strtotime($value->Fecha)) ?>
										</td>
										<td>
											<?php echo $value->NombreVendedor ?>
										</td>
										<th>
											<?php echo number_format($value->Total_Venta,2,",","."); ?>
										</th>
										<th>
											<?php echo number_format($value->Total_Descuentos,2,",","."); ?>
										</th>
									</tr>
								<?php endforeach ?>
							<?php else: ?>
								<tr>
									<td colspan="7" class="text-center">
										No hay datos para mostrar
									</td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
	
echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));

echo $this->Html->script(array('lib/jquery.table2excel.js?'.time()),	array('block' => 'AppScript'));

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

		$("#detalleVentasBtn").click(function(event) {
			$("#detalleVentas").toggle();
		});

		<?php if (!in_array(AuthComponent::user("role"), $roles)): ?>

			var datosMes = <?php echo json_encode($datosMes) ?>;
			var datosDia = <?php echo json_encode($datosDia) ?>;
			var datosMesDev = <?php echo json_encode($datosMesDev) ?>;
			var datosDiaDev = <?php echo json_encode($datosDiaDev) ?>;
			var labelsMeses = <?php echo json_encode($labelsMeses) ?>;
			var labelsDias = <?php echo json_encode($labelsDias) ?>;

		<?php endif ?>

		$("#descargaInfo").click(function(){

		  $("#naturalpersontable2").table2excel({

		    // exclude CSS class

		    exclude:".noExl",

		    name:"Datos cliente",

		    filename:"detalle_ventas_gestion_<?php echo str_replace("-","_",$this->request->query["ini"]) ?>@<?php echo str_replace("-","_",$this->request->query["end"]) ?>",//do not include extension

		    fileext:".xlsx" // file extension

		  });

		});

	</script>

<?php
	$this->end();
 ?>

<style>

	
	

	<?php if (!in_array(AuthComponent::user("role"), $roles)): ?>
		
		#detalleVentas{
			display: none;
		}

		svg{
			display: block !important;
		}

		text.highcharts-credits {
		    display: none;
		}
	<?php endif ?>
	.panel {
	  box-shadow: 0 2px 0 rgba(0,0,0,0.05);
	  border-radius: 0;
	  border: 0;
	  margin-bottom: 24px;
	}

	.panel-dark.panel-colorful {
	  background-color: #3b4146;
	  border-color: #3b4146;
	  color: #fff;
	}

	.panel-danger.panel-colorful {
	  background-color: #f76c51;
	  border-color: #f76c51;
	  color: #fff;
	}

	.panel-primary.panel-colorful {
	  background-color: #5fa2dd;
	  border-color: #5fa2dd;
	  color: #fff;
	}

	.panel-info.panel-colorful {
	  background-color: #4ebcda;
	  border-color: #4ebcda;
	  color: #fff;
	}

	.panel-body {
	  padding: 25px 20px;
	}

	.panel hr {
	  border-color: rgba(0,0,0,0.1);
	}

	.mar-btm {
	  margin-bottom: 15px;
	}

	h2, .h2 {
	  font-size: 28px;
	}

	.small, small {
	  font-size: 85%;
	}

	.text-sm {
	  font-size: .9em;
	}

	.text-thin {
	  font-weight: 300;
	}

	.text-semibold {
	  font-weight: 600;
	}
</style>