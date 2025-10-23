<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-cafe big">
		<i class="fa fa-1x flaticon-report-1"></i>
		<h2 class="m-0 text-white bannerbig" >Módulo de Informes</h2>
	</div>
	<div class="blockwhite spacebtn20">
		<div class="row">
			<div class="col-md-12">
				<h1 class="nameview">DETALLE DE CLIENTES QUE COMPRARON EN EL/LOS AÑO(S) <?php echo $anio ?> </h1>
				<span class="subname">Informe y detalle de todos los clientes que realizaron una compra en uno o varios años, información para remarketing</span>
			</div>			
		</div>
	</div>
	<div class="row mt-4">
		<div class="col-xl-12 col-lg-12 col-md-12">
			<div class="blockwhite p-2 mb-3">
				<div class='bg-gris border-0 card d-inline-block my-2 p-3 w-100 text-center'>
					<div class="table-responsive">
						<button type="button" id="descargaInfo" class="btn btn-success float-left">
							Descargar informe <i class="fa fa-file vtc"></i>
						</button>
						<button type="button" id="asignaFlujos" style="display:none" class="btn btn-primary float-left">
							Crear flujo <i class="fa fa-plus vtc"></i>
						</button>
						<table class="table table-bordered table-hovered" id="naturalpersontable">
							<thead class="thead-dark">
								<tr class="text-center">
									<th class="bg-blue">
										Seleccionar
									</th>
									<th class="bg-blue">
										Flujos creados
									</th>
									<th class="bg-blue">
										NIT o ID
									</th>
									<th class="bg-blue">
										Nombre cliente
									</th>
									<th class="bg-blue">
										Teléfono cliente
									</th>
									<th class="bg-blue">
										Email cliente
									</th>
									<th class="bg-blue">
										Ciudad cliente
									</th>
									<th class="bg-blue noExl">Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($listado as $key => $value): ?>
									<tr class="text-center">
										<td class="text-center">
											<label class="containercheck position-absolute" style="margin-top: -10px;">
												<input type="checkbox" class="checkB" data-class="check_prod_<?php echo md5($key) ?>" data-id="<?php echo $value->Identificacion ?>" value="<?php echo $value->Identificacion ?>" >
												<span class="checkmark"></span>
											</label>

										</td>
										<td>
											<?php $flujos = $this->Utilities->getFlowsFactsAnios($value->Identificacion."|".$anio); ?>
											<?php if (empty($flujos)): ?>
												Sin asignar
											<?php else: ?>
												<?php foreach ($flujos as $keyPt => $valuePU): ?>
													<div class="dropdown d-inline">
													  <a class="btn btn-success btn-sm dropdown-toggle p-1 rounded" href="#" role="button" id="dropdownMenuLink_<?php echo md5($valuePU) ?>_<?php echo md5($key) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													   <?php echo $valuePU ?>
													  </a>

													  <div class="dropdown-menu styledrop" aria-labelledby="dropdownMenuLink_<?php echo md5($valuePU) ?>_<?php echo md5($key) ?>">
													    <a class="dropdown-item idflujotable flujoModal" href="#" data-uid="<?php echo $valuePU ?>" data-type="<?php echo $this->Utilities->getTypeProspective($valuePU); ?>">Ver flujo</a>
													   	<?php $qtr = $this->Utilities->getQuotationId($valuePU) ?>
													   	<?php if (!is_null($qtr)): ?>
													   		
													    <a class="dropdown-item getQuotationId" data-quotation="<?php echo $qtr ?>" href="#">Ver cotización</a>
													    <a class="dropdown-item getOrderCompra" href="#" data-flujo="<?php echo $valuePU ?>">Ver órden de compra</a>
													   	<?php endif ?>
													  </div>
													</div>
												<?php endforeach ?>
											<?php endif ?>
										</td>
										<td class="p-0">
											<?php echo $value->Identificacion ?>
										</td>
										<td class="p-0">
											<?php echo $value->Nombre. " ".$value->Apellidos ?>
										</td>
										<td class="p-0">
											<?php echo $value->Telefonos ?>
										</td>
										<td class="p-0">
											<?php echo $value->EMail ?>
										</td>
										<td class="p-0">
											<?php echo $value->Ciudad ?>
										</td>
										<td class="p-0 noExl">
											<a href="javascript:void()" class="btn btn-primary btn-sm createFlow" data-id="<?php echo $value->Identificacion ?>" data-toggle="tooltip" title="Crear flujo" data-anio="<?php echo $anio ?>">
												<i class="fa fa-plus vtc"></i>
											</a>
											<a href="javascript:void()" class="btn btn-info btn-sm detailCliente" data-id="<?php echo $value->Identificacion ?>" data-toggle="tooltip" title="Ver detalle de ventas" data-anio="<?php echo $anio ?>">
												<i class="fa fa-eye vtc"></i>
											</a>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
						<div style="display:none">
							
							<table class="table table-bordered table-hovered" id="naturalpersontable2">
								<thead class="thead-dark">
									<tr class="text-center">
										<th class="bg-blue">
											NIT o ID
										</th>
										<th class="bg-blue">
											Nombre cliente
										</th>
										<th class="bg-blue">
											Teléfono cliente
										</th>
										<th class="bg-blue">
											Email cliente
										</th>
										<th class="bg-blue">
											Ciudad cliente
										</th>
										<th class="bg-blue noExl">Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($listado as $key => $value): ?>
										<tr class="text-center">
											<td class="p-0">
												<?php echo $value->Identificacion ?>
											</td>
											<td class="p-0">
												<?php echo $value->Nombre. " ".$value->Apellidos ?>
											</td>
											<td class="p-0">
												<?php echo $value->Telefonos ?>
											</td>
											<td class="p-0">
												<?php echo $value->EMail ?>
											</td>
											<td class="p-0">
												<?php echo $value->Ciudad ?>
											</td>
											<td class="p-0 noExl">
												<a href="javascript:void()" class="btn btn-primary btn-sm createFlow" data-id="<?php echo $value->Identificacion ?>" data-toggle="tooltip" title="Crear flujo" data-anio="<?php echo $anio ?>">
													<i class="fa fa-plus vtc"></i>
												</a>
												<a href="javascript:void()" class="btn btn-info btn-sm detailCliente" data-id="<?php echo $value->Identificacion ?>" data-toggle="tooltip" title="Ver detalle de ventas" data-anio="<?php echo $anio ?>">
													<i class="fa fa-eye vtc"></i>
												</a>
											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>

<?php 
    $this->start('AppScript'); ?>

    <script>
    	 const DATA_CLIENTS  = <?php echo json_encode($listado) ?>;
    	 const ANIO_CONSULTA = "<?php echo $anio ?>";
    </script>	

<?php
    $this->end();
 ?>
<?php
	echo $this->Html->script(array('//code.jquery.com/jquery-1.9.1.js'),array('block' => 'jqueryApp'));
	echo $this->Html->script(array('lib/jquery.table2excel.js?'.time()),	array('block' => 'AppScript'));
	echo $this->Html->script(array('controller/config/clientes_detail.js?'.time()),	array('block' => 'AppScript'));
?>

<div class="modal fade" id="modalFacturaDetalle" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Detalle ventas </h2>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-12" id="detalleVentas" style="max-height: 500px;overflow-y: auto;">
      			
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <a class="btn btn-outline-dark cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="modalCrearFlujoCliente" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg3" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Crear flujo para cliente </h2>
      </div>
      <div class="modal-body">
      	<div class="cuerpoFlujoAddWO" id="bodyCrearFlujoCliente"></div>
        <div class="cuerpoContactoClienteWO"></div>
        <div id="ingresoClienteWO"></div>
      </div>
      <div class="modal-footer">
        <a class="btn btn-outline-dark cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="modalAsignarFlujos" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title"> Asignar flujos para clientes seleccionados </h2>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-12">
      			<?php echo $this->Form->input("user_id_flow", ["options" => $usuarios, "label" => "Asesor asignado", "empty" => "Seleccionar"] ) ?>
      		</div> 
      		<div class="col-md-12 mt-2 text-center">
      			<button class="btn btn-success" id="creaFlujo">Crear flujos</button>
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <a class="btn btn-outline-dark cancelmodal" data-dismiss="modal">Cancelar</a>
      </div>      
    </div>
  </div>
</div>



<style>
	svg{
		display: block !important;
	}
	.highcharts-data-table{
		display: none !important;
	}
	footer{
		display: none;
	}
	.table-responsive #flujosData_wrapper>.row, #debtsData_wrapper>.row{
		margin-right: 0px;
    	margin-left: 0px;
	}
</style>

<?php echo $this->element("flujoModal");
echo $this->Html->script("controller/quotations/view.js?".rand(),			array('block' => 'AppScript')); 
 ?>