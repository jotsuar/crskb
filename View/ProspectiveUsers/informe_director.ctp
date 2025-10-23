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
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones')) ?>"><b>3-</b> Informe de Comisiones Director comercial</a>
					</li>					
				</ul>
			</div>
		</div>
	</div>
</div>



<div class="col-md-12">
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-5">
				<h1 class="nameview2">INFORME DE COMISIONES DIRECTOR COMERCIAL</h1>
				<span class="subname">Informe de comisiones que se deben liquidar al director comercial</span>
			</div>
			<div class="col-md-7">
				<div class="row">
					<?php echo $this->Form->create('ProspectiveUser',array('class' => 'form w-100')); ?>
						<div class="col-md-12">
							<div class="row">
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
											<?php echo $this->Form->input('excel',array("required" => true, "label" => "Generar Informe" ,"options" => Configure::read("IMPUESTOS"))); ?>
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
									<?php endif ?>
										<button type="submit" class="btn btn-primary" id="btn_find_adviser">Buscar</button>
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
			<table class="table table-hovered">
				<tbody>
					<tr>
						<td class="text-center" colspan="4">
							Datos relacionados a ventas
						</td>
					</tr>
					<tr>
						<th>
							Ventas totales
						</th>
						<th>
							Base comisión
						</th>
						<th>
							Porcentaje comisión
						</th>
						<th>
							Valor correspondiente
						</th>
					</tr>
					<tr>
						<td>
							$<?php echo number_format($total_ventas_empresa) ?>
						</td>
						<td>
							$<?php echo number_format($baseVentas) ?>
						</td>
						<td>
							<?php echo number_format($pocertajeVenta) ?>%
						</td>
						<td>
							$<?php echo number_format($totalXventas) ?>
						</td>
					</tr>
					<tr>
						<td class="text-center" colspan="4">
							Datos relacionados a cartera
						</td>
					</tr>
					<tr>
						<th>
							Cartera total
						</th>
						<th>
							Base comisión
						</th>
						<th>
							Porcentaje comisión
						</th>
						<th>
							Valor correspondiente
						</th>
					</tr>
					<tr>
						<td>
							$<?php echo number_format($total_cartera) ?>
						</td>
						<td>
							$<?php echo number_format($baseCartera) ?>
						</td>
						<td>
							<?php echo number_format($pocertajeCartera) ?>%
						</td>
						<td>
							$<?php echo number_format($totalXcartera) ?>
						</td>
					</tr>
					<tr>
						<td class="text-center" colspan="4">
							Datos relacionados a serviciós técnicos
						</td>
					</tr>
					<tr>
						<th>
							Total servicios mayores a 90 días
						</th>
						<th>
							Base comisión
						</th>
						<th>
							Porcentaje comisión
						</th>
						<th>
							Valor correspondiente
						</th>
					</tr>
					<tr>
						<td>
							<?php echo number_format($total_servicios) ?>
						</td>
						<td>
							$<?php echo number_format($baseServicio) ?>
						</td>
						<td>
							<?php echo number_format($pocertajeServicio) ?>%
						</td>
						<td>
							$<?php echo number_format($totalXservicio) ?>
						</td>
					</tr>
					<tr>
						<td class="text-center" colspan="4">
							Datos relacionados al margen general
						</td>
					</tr>
					<tr>
						<th>
							Total margen promedio general
						</th>
						<th>
							Base comisión
						</th>
						<th>
							Porcentaje comisión
						</th>
						<th>
							Valor correspondiente
						</th>
					</tr>
					<tr>
						<td>
							<?php echo number_format($total_margen) ?>%
						</td>
						<td>
							$<?php echo number_format($baseMargen) ?>
						</td>
						<td>
							<?php echo number_format($porcentajeMargen) ?>%
						</td>
						<td>
							$<?php echo number_format($totalXmargen) ?>
						</td>
					</tr>
					<tr>
						<td class="text-center" colspan="4">
							Datos relacionados a la venta de productos nuevos (12 meses)
						</td>
					</tr>
					<tr>
						<th>
							Total vendido en productos nuevos
						</th>
						<th>
							Base comisión
						</th>
						<th>
							Porcentaje comisión
						</th>
						<th>
							Valor correspondiente
						</th>
					</tr>
					<tr>
						<td>
							$<?php echo number_format($total_nuevos) ?>
						</td>
						<td>
							$<?php echo number_format($baseNuevos) ?>
						</td>
						<td>
							<?php echo number_format($porcentajeNuevos) ?>%
						</td>
						<td>
							$<?php echo number_format($totalXnuevos) ?>
						</td>
					</tr>
					<tr>
						<td class="text-center" colspan="4">
							Datos relacionados a la atención de flujos del CRM
						</td>
					</tr>
					<tr>
						<th>
							Total flujos en contactado
						</th>
						<th>
							Base comisión
						</th>
						<th>
							Porcentaje comisión
						</th>
						<th>
							Valor correspondiente
						</th>
					</tr>
					<tr>
						<td>
							<?php echo number_format($total_contactado) ?>
						</td>
						<td>
							$<?php echo number_format($baseContactado) ?>
						</td>
						<td>
							<?php echo number_format($porcentajeContactado) ?>%
						</td>
						<td>
							$<?php echo number_format($totalXContactado) ?>
						</td>
					</tr>
					<tr>
						<th>
							Total flujos en cotizado (mayores a 90 días)
						</th>
						<th>
							Base comisión
						</th>
						<th>
							Porcentaje comisión
						</th>
						<th>
							Valor correspondiente
						</th>
					</tr>
					<tr>
						<td>
							<?php echo number_format($total_cotizado) ?>
						</td>
						<td>
							$<?php echo number_format($baseCotizado) ?>
						</td>
						<td>
							<?php echo number_format($porcentajeCotizado) ?>%
						</td>
						<td>
							$<?php echo number_format($totalXCotizado) ?>
						</td>
					</tr>
					<tr>
						<td colspan="3" class="text-right">
							<h2><b>Total ganado:</b></h2>
						</td>
						<td>
							<h2><b><?php echo number_format($totalXventas+$totalXcartera+$totalXservicio+$totalXmargen+$totalXnuevos+$totalXContactado+$totalXCotizado,0) ?></b></h2>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php if ($muestraInfo): ?>
	
	<div class="blockwhite mt-4">
		<div class="row">
			<div class="col-md-12 text-center">
				<h2 class="my-4"> Detalles y listado </h2>
			</div>
			<div id="accordion" class="w-100">
				<!-- Inicio facturas -->

				<div class="card-header" id="headingFacturas">
		      <h5 class="mb-0">
		        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseFacturas" aria-expanded="true" aria-controls="collapseFacturas">
		          Facturas del mes y margen de cada factura
		        </button>
		      </h5>
		    </div>

		    <div id="collapseFacturas" class="collapse" aria-labelledby="headingFacturas" data-parent="#accordion">
		      <div class="card-body">
		        <div class="table-responsive">
		        	<table class="table-hovered table tblProcesoCotizacion w-100">
								<thead>
									<tr>
										<th class="p-0">
											Factura
										</th>
										<th class="p-0">
											Flujo
										</th>
										
										<th class="p-0" style="max-width: 350px !important">
											Cliente
										</th>
										<th class="p-0" style="max-width: 350px !important">
											Empleado
										</th>
										<th class="p-0">
											Valor
										</th>
										<th>
											Margen
										</th>
									</tr>
								</thead>
								<?php foreach ($facturas as $key => $value): ?>
									<tr>
										<?php $strPos = strpos($value["Factura"], "DMC"); ?>
										<th class="p-0">
											<?php echo $value["Factura"] ?>
										</th>

										<th class="p-0">
											<?php if ($strPos === false): ?>
												<?php echo is_null($value["Personalizado5"]) ? "No asignado" : $value["Personalizado5"] ?>
											<?php endif ?>
										</th>
										
										<td class="p-0" style="max-width: 350px !important">
											<?php echo $value["Nombre"] ?>
										</td>
										<td class="p-0" style="max-width: 350px !important">
											<?php echo $value["NombreVendedor"] ?>
										</td>
										<td class="p-0">
											<?php $strPos = strpos($value["Factura"], "DMC"); ?>
											<?php
												
												$totalVD = $strPos === false ? $value["Total_Venta"] : -1*$value["Total_Descuentos"]; ?>
											$ <?php echo number_format(intval($totalVD),"2",".",",") ?>
										</td>
										<td>
											<?php echo $value["Margen"] ?> %
										</td>
									</tr>
								<?php endforeach ?>
							</table>
		        </div>
		      </div>
		    </div>

				<!-- Fin facturas -->

				<!-- Inicio Cartera -->

				<div class="card-header" id="headingCartera">
		      <h5 class="mb-0">
		        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseCartera" aria-expanded="true" aria-controls="collapseCartera">
		          Cartera vencida a más de 15 días
		        </button>
		      </h5>
		    </div>

		    <div id="collapseCartera" class="collapse" aria-labelledby="headingCartera" data-parent="#accordion">
		      <div class="card-body">
		        <div class="table-responsive">
		        	<table class="table-bordered table tblProcesoCotizacion w-100" >
								<thead>
									<tr>
											<th class="p-1 empleado">
												Empleado
											</th>
										<th class="p-0">
											ID / NIT
										</th>
										<th class="p-0">
											Factura
										</th>
										<th class="p-0" style="max-width: 150px !important">
											Cliente
										</th>
										<th class="p-0 esconder">
											Valor
										</th>
										<th class="p-0 esconder">
											Fecha Límite
										</th>
										<th class="esconder">
											Días vencidos
										</th>
									</tr>
								</thead>
								<?php foreach ($carteras as $key => $value): ?>
									<tr>
											<td class="p-1 empleado <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
												<?php echo $value["Empleado"] ?>
											</td>
										<th class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
											<?php echo $value["Identificacion"] ?>
										</th>
										<td class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
											<?php echo $value["prefijo"]. " ".$value["DocumentoNúmero"] ?>
										</td>
										<td class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>" style="max-width: 150px !important">
											<?php echo $value["Nombres_terceros"] ?>
										</td>
										<td class="p-0 valorData <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>" data-value="<?php echo floatVal($value["Saldo"]) ?>">
											$ <?php echo number_format(intval($value["Saldo"]),"0",".",",") ?>
										</td>
										<td class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
											<?php echo str_replace("00:00:00.000", "", $value["Vencimiento"]) ?>
										</td>
										<td class="p-0 <?php echo $value["DIAS"] > 60 ? "text-danger" : "" ?>">
											<b><?php echo $value["DIAS"] ?></b>
										</td>
									</tr>
								<?php endforeach ?>
							</table>
		        </div>
		      </div>
		    </div>

				<!-- Fin Cartera -->

				<!-- Inicio Servicios tecnicos -->

				<div class="card-header" id="headingServicio">
		      <h5 class="mb-0">
		        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseServicio" aria-expanded="true" aria-controls="collapseServicio">
		          Servicios técnicos con más de 90 dias en proceso
		        </button>
		      </h5>
		    </div>

		    <div id="collapseServicio" class="collapse" aria-labelledby="headingServicio" data-parent="#accordion">
		      <div class="card-body">
		        <div class="table-responsive">
		        	<table id="serviciosTbl" class="table table-striped table-bordered tblProcesoCotizacion w-100">
								<thead>
									<tr>
										<th>Asesor</th>
										<th class="noShow">Código</th>
										<th>Cliente</th>
										<th class="noShow">F. de Ingreso</th>
										<th class="noShow">F. diagnóstico</th>
										<th class="noShow">Id Flujo</th>
										<th>Estado Flujo</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
		        </div>
		      </div>
		    </div>

				<!-- Fin Servicios tecnicos -->

				<!-- Inicio facturas -->

				<div class="card-header" id="headingNuevos">
		      <h5 class="mb-0">
		        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseNuevos" aria-expanded="true" aria-controls="collapseNuevos">
		          Productos nuevos vendidos (12 meses)
		        </button>
		      </h5>
		    </div>

		    <div id="collapseNuevos" class="collapse" aria-labelledby="headingNuevos" data-parent="#accordion">
		      <div class="card-body">
		        <div class="table-responsive">
		        	<table class="table-hovered table tblProcesoCotizacion w-100">
								<thead>
									<tr>
										<th class="p-0">
											Factura
										</th>
										<th class="p-0">
											N° de parte
										</th>
										
										<th class="p-0" style="max-width: 350px !important">
											Precio de venta
										</th>
										<th class="p-0" style="max-width: 350px !important">
											Fecha de creado el producto
										</th>
									</tr>
								</thead>
								<?php foreach ($nuevos as $key => $value): ?>
									<tr>
										<th>
											<?php echo $value["factura"] ?>
										</th>
										<td>
											<?php echo $value["part_number"] ?>
										</td>
										<td>
											$<?php echo number_format($value["precio"]) ?>
										</td>
										<td>
											<?php echo $value["fecha"] ?>
										</td>
									</tr>
								<?php endforeach ?>
							</table>
		        </div>
		      </div>
		    </div>

				<!-- Fin facturas -->

			</div>
		</div>
	</div>

	<?php endif ?>
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

<?php $this->start("AppScript"); ?>
	<script>
		 var postFilter = <?php echo $postConsulta ? 1 : 0 ?>;
	</script>
<?php
    $this->end();
 ?>




<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/prospectiveUsers/director.js?".rand(),			array('block' => 'AppScript')); 
?>


<?php echo $this->element("picker"); ?>
<?php echo $this->element("flujoModal"); ?>